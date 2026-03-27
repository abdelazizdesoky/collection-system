<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function index(): View
    {
        $units = Unit::withCount('products')->latest()->get();

        return view('inventory.units.index', compact('units'));
    }

    public function create(): View
    {
        return view('inventory.units.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:50',
        ]);

        Unit::create($validated);

        return redirect()->route('units.index')
            ->with('success', 'تم إضافة الوحدة بنجاح');
    }

    public function edit(Unit $unit): View
    {
        return view('inventory.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:50',
        ]);

        $unit->update($validated);

        return redirect()->route('units.index')
            ->with('success', 'تم تحديث الوحدة بنجاح');
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        if ($unit->products()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف الوحدة لأنها مرتبطة بمنتجات');
        }

        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'تم حذف الوحدة بنجاح');
    }
}
