<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(Request $request): View
    {
        $warehouses = Warehouse::where('is_active', true)->get();

        $query = Product::with(['category', 'unit', 'stockItems.warehouse'])
            ->withSum('stockItems', 'quantity');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('low_stock')) {
            $query->whereHas('stockItems', function ($q) {
                $q->whereRaw('quantity <= (SELECT min_stock FROM products WHERE products.id = stock_items.product_id)');
            });
        }

        $products = $query->latest()->paginate(20);

        return view('inventory.stock.index', compact('products', 'warehouses'));
    }

    public function movements(Request $request): View
    {
        $query = StockMovement::with(['warehouse', 'product.unit'])->latest();

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        $movements = $query->paginate(30);
        $warehouses = Warehouse::where('is_active', true)->get();

        return view('inventory.stock.movements', compact('movements', 'warehouses'));
    }
}
