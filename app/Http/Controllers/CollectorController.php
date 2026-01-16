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
    /**
     * Display a listing of collectors.
     */
    public function index(): View
    {
        $collectors = Collector::latest()->with('user')->paginate(15);

        return view('collectors.index', compact('collectors'));
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
            'phone' => 'required|string|max:20',
            'area' => 'required|string|max:255',
        ]);

        $collector = Collector::create([
            'phone' => $validated['phone'],
            'area' => $validated['area'],
        ]);

        // Assign the collector profile to the selected user
        $user = \App\Models\User::findOrFail($validated['user_id']);
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
            'phone' => 'required|string|max:20',
            'area' => 'required|string|max:255',
        ]);

        $collector->update([
            'phone' => $validated['phone'],
            'area' => $validated['area'],
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
