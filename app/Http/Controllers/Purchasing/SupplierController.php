<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->latest()->paginate(20);

        return view('purchasing.suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        return view('purchasing.suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:suppliers,code',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'opening_balance' => 'nullable|numeric|min:0',
            'balance_type' => 'required|in:debit,credit',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'تم إضافة المورد بنجاح');
    }

    public function show(Supplier $supplier): View
    {
        $supplier->load(['purchaseInvoices' => function ($q) {
            $q->latest()->limit(20);
        }, 'purchasePayments' => function ($q) {
            $q->latest()->limit(20);
        }]);

        return view('purchasing.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier): View
    {
        return view('purchasing.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:suppliers,code,'.$supplier->id,
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'opening_balance' => 'nullable|numeric|min:0',
            'balance_type' => 'required|in:debit,credit',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'تم تحديث المورد بنجاح');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        if ($supplier->purchaseInvoices()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف المورد لأنه مرتبط بفواتير');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'تم حذف المورد بنجاح');
    }
}
