<?php

namespace App\Http\Controllers;

use App\Models\Collector;
use App\Models\User;
use App\Exports\CollectorsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectorController extends Controller
{
    /**
     * Display a listing of collectors.
     */
    public function index(Request $request): View
    {
        $showTrashed = $request->input('trashed') === '1';

        $query = Collector::with('user');

        if ($showTrashed) {
            $query->onlyTrashed();
        }

        $collectors = $query->latest()->paginate(15);

        $trashedCount = Collector::onlyTrashed()->count();
        $activeCount = Collector::count();

        return view('collectors.index', compact('collectors', 'showTrashed', 'trashedCount', 'activeCount'));
    }

    /**
     * Restore a soft deleted collector.
     */
    public function restore($id)
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403);
        }

        $collector = Collector::onlyTrashed()->findOrFail($id);
        $collector->restore();

        return redirect()->route('collectors.index', ['trashed' => '1'])
            ->with('success', 'تم استعادة المحصل بنجاح.');
    }

    /**
     * Permanently delete a collector.
     */
    public function forceDelete($id)
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $collector = Collector::onlyTrashed()->findOrFail($id);
        $collector->forceDelete();

        return redirect()->route('collectors.index', ['trashed' => '1'])
            ->with('success', 'تم حذف المحصل نهائياً.');
    }

    /**
     * Show the form for creating a new collector.
     */
    public function create(): View
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'user'])) {
            abort(403);
        }

        // Get users with 'collector' role who are not yet assigned a collector profile
        $users = \App\Models\User::role('collector')
            ->whereNull('collector_id')
            ->get();

        return view('collectors.create', compact('users'));
    }

    /**
     * Store a newly created collector in database.
     */
    public function store(Request $request)
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'user'])) {
            abort(403);
        }
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255', // Enforce name validation from User or input if needed?
            // Actually Collector model has 'name' field too (shadowing user name or independent?)
            // Looking at previous file view, it seems collector has 'name' but the create logic didn't validate it?
            // Wait, previous file view store method:
            /*
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'phone' => 'required|string|max:20',
                'area' => 'required|string|max:255',
            ]);
            $collector = Collector::create([
                'phone' => $validated['phone'],
                'area' => $validated['area'],
            ]);
            */
            // Ideally we should add 'code' here.
            'code' => 'nullable|string|unique:collectors,code',
            'phone' => 'required|string|max:20',
            'area' => 'required|string|max:255',
        ]);

        // Get user name for collector name if not provided?
        // The Create View probably doesn't have name input if it selects a User.
        // But the Model has 'name'. 
        // Let's grab name from User.
        $user = \App\Models\User::findOrFail($validated['user_id']);
        
        $collector = Collector::create([
            'user_id' => $validated['user_id'],
            'code' => $validated['code'] ?? null,
            'name' => $user->name, // Sync name
            'phone' => $validated['phone'],
            'area' => $validated['area'],
        ]);

        $user->update(['collector_id' => $collector->id]);

        return redirect()->route('collectors.index')
            ->with('success', 'Collector profile created and assigned to user successfully.');
    }

    /**
     * Display the specified collector.
     */
    public function show(Collector $collector): View
    {
        $collector->load('user', 'collectionPlans', 'collections');

        return view('collectors.show', compact('collector'));
    }

    /**
     * Show the form for editing the specified collector.
     */
    public function edit(Collector $collector): View
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'user'])) {
            abort(403);
        }

        $currentUserId = $collector->user->id ?? null;

        // Get candidates: users with 'collector' role who are available OR the currently assigned user
        $users = \App\Models\User::role('collector')
            ->where(function ($query) use ($currentUserId) {
                $query->whereNull('collector_id');
                if ($currentUserId) {
                    $query->orWhere('id', $currentUserId);
                }
            })
            ->get();

        return view('collectors.edit', compact('collector', 'users'));
    }

    /**
     * Update the specified collector in database.
     */
    public function update(Request $request, Collector $collector)
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'user'])) {
            abort(403);
        }
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'code' => 'nullable|string|unique:collectors,code,' . $collector->id,
            'phone' => 'required|string|max:20',
            'area' => 'required|string|max:255',
        ]);

        $collector->update([
            'code' => $validated['code'] ?? null,
            'phone' => $validated['phone'],
            'area' => $validated['area'],
            // Updates to name should probably sync with User if we are strict, or just leave as is.
        ]);

        // Update user association if changed
        $currentUser = $collector->user;

        // If there was a user and it's different from the new one, or if there was no user
        if (! $currentUser || $currentUser->id != $validated['user_id']) {
            // Unlink old user
            if ($currentUser) {
                $currentUser->update(['collector_id' => null]);
            }
            // Link new user
            $newUser = \App\Models\User::findOrFail($validated['user_id']);
            $newUser->update(['collector_id' => $collector->id]);
        }

        return redirect()->route('collectors.show', $collector)
            ->with('success', 'Collector updated successfully.');
    }

    /**
     * Remove the specified collector from database.
     */
    public function destroy(Collector $collector)
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403);
        }

        // Unlink user before deletion
        if ($collector->user) {
            $collector->user->update(['collector_id' => null]);
        }

        $collector->delete();

        return redirect()->route('collectors.index')
            ->with('success', 'Collector deleted successfully.');
    }

    /**
     * Export collectors to Excel.
     */
    public function export()
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403);
        }

        return Excel::download(new CollectorsExport, 'collectors_'.now()->format('Y-m-d_His').'.xlsx');
    }
}
