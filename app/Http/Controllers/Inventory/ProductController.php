<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'unit'])->withSum('stockItems', 'quantity');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('is_for_sale')) {
            $query->where('is_for_sale', $request->is_for_sale);
        }

        $products = $query->latest()->paginate(20);
        $categories = ProductCategory::where('is_active', true)->get();

        return view('inventory.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = ProductCategory::where('is_active', true)->get();
        $units = Unit::all();
        $warehouses = \App\Models\Warehouse::where('is_active', true)->get();

        return view('inventory.products.create', compact('categories', 'units', 'warehouses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:products,code',
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:product_categories,id',
            'unit_id' => 'nullable|exists:units,id',
            'description' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
            'is_for_sale' => 'boolean',
            'is_active' => 'boolean',
            'opening_stock' => 'nullable|numeric|min:0',
            'warehouse_id' => 'required_with:opening_stock|nullable|exists:warehouses,id',
        ]);

        $validated['is_for_sale'] = $request->has('is_for_sale');
        $validated['is_active'] = $request->has('is_active');

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request) {
            $product = Product::create($validated);

            if ($request->filled('opening_stock') && (float) $request->opening_stock > 0) {
                $warehouseId = $request->warehouse_id;
                $quantity = (float) $request->opening_stock;

                // Create Stock Item
                \App\Models\StockItem::create([
                    'warehouse_id' => $warehouseId,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);

                // Record Movement
                \App\Models\StockMovement::create([
                    'warehouse_id' => $warehouseId,
                    'product_id' => $product->id,
                    'movement_type' => 'in',
                    'quantity' => $quantity,
                    'reference_type' => 'opening_stock',
                    'note' => 'رصيد افتتاحي عند تعريف المنتج',
                ]);
            }
        });

        return redirect()->route('products.index')
            ->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function show(Product $product): View
    {
        $product->load(['category', 'unit', 'stockItems.warehouse', 'stockMovements' => function ($q) {
            $q->with(['warehouse'])->latest()->limit(20);
        }]);

        return view('inventory.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::where('is_active', true)->get();
        $units = Unit::all();

        return view('inventory.products.edit', compact('product', 'categories', 'units'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:products,code,'.$product->id,
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:product_categories,id',
            'unit_id' => 'nullable|exists:units,id',
            'description' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
            'is_for_sale' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['is_for_sale'] = $request->has('is_for_sale');
        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }
}
