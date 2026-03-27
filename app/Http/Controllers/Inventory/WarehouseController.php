<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(): View
    {
        $warehouses = Warehouse::withCount('stockItems')->latest()->get();

        return view('inventory.warehouses.index', compact('warehouses'));
    }

    public function create(): View
    {
        return view('inventory.warehouses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'manager' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Warehouse::create($validated);

        return redirect()->route('warehouses.index')
            ->with('success', 'تم إضافة المخزن بنجاح');
    }

    public function show(Warehouse $warehouse): View
    {
        $warehouse->load(['stockItems.product.unit', 'stockItems.product.category']);

        return view('inventory.warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse): View
    {
        return view('inventory.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'manager' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $warehouse->update($validated);

        return redirect()->route('warehouses.index')
            ->with('success', 'تم تحديث المخزن بنجاح');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        if ($warehouse->stockItems()->where('quantity', '>', 0)->count() > 0) {
            return back()->with('error', 'لا يمكن حذف المخزن لأنه يحتوي على رصيد');
        }

        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'تم حذف المخزن بنجاح');
    }
}
