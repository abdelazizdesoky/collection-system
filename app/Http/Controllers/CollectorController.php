<?php

namespace App\Http\Controllers;

use App\Models\Collector;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectorController extends Controller
{
    /**
     * Display a listing of collectors.
     */
    public function index(): View
    {
        $collectors = Collector::with('user')->paginate(15);
        return view('collectors.index', compact('collectors'));
    }

    /**
     * Show the form for creating a new collector.
     */
    public function create(): View
    {
        if (!auth()->user()->hasAnyRole(['admin', 'user'])) {
            abort(403);
        }

        return view('collectors.create');
    }

    /**
     * Store a newly created collector in database.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'user'])) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'area' => 'required|string|max:255',
        ]);

        Collector::create($validated);

        return redirect()->route('collectors.index')
            ->with('success', 'Collector created successfully.');
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
        if (!auth()->user()->hasAnyRole(['admin', 'user'])) {
            abort(403);
        }

        return view('collectors.edit', compact('collector'));
    }

    /**
     * Update the specified collector in database.
     */
    public function update(Request $request, Collector $collector)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'user'])) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'area' => 'required|string|max:255',
        ]);

        $collector->update($validated);

        return redirect()->route('collectors.show', $collector)
            ->with('success', 'Collector updated successfully.');
    }

    /**
     * Remove the specified collector from database.
     */
    public function destroy(Collector $collector)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $collector->delete();

        return redirect()->route('collectors.index')
            ->with('success', 'Collector deleted successfully.');
    }
}
