<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::withCount('products')->latest()->get();

        return view('inventory.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('inventory.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        ProductCategory::create($validated);

        return redirect()->route('product-categories.index')
            ->with('success', 'تم إضافة القسم بنجاح');
    }

    public function edit(ProductCategory $productCategory): View
    {
        return view('inventory.categories.edit', compact('productCategory'));
    }

    public function update(Request $request, ProductCategory $productCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $productCategory->update($validated);

        return redirect()->route('product-categories.index')
            ->with('success', 'تم تحديث القسم بنجاح');
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        if ($productCategory->products()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف القسم لأنه يحتوي على منتجات');
        }

        $productCategory->delete();

        return redirect()->route('product-categories.index')
            ->with('success', 'تم حذف القسم بنجاح');
    }
}
