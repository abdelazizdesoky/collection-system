<?php

namespace App\Http\Controllers;

use App\Models\Cheque;
use App\Models\Customer;
use App\Exports\ChequesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use DB;

class ChequeController extends Controller
{
    /**
     * Display a listing of cheques.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $showTrashed = $request->input('trashed') === '1';

        $query = Cheque::with('customer');

        if ($showTrashed) {
            $query->onlyTrashed();
        }

        $cheques = $query
            ->when($search, function($query, $search) {
                return $query->where('cheque_no', 'like', "%{$search}%")
                    ->orWhere('bank_name', 'like', "%{$search}%")
                    ->orWhereHas('customer', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $trashedCount = Cheque::onlyTrashed()->count();
        $activeCount = Cheque::count();

        return view('cheques.index', compact('cheques', 'showTrashed', 'trashedCount', 'activeCount'));
    }

    /**
     * Restore a soft deleted cheque.
     */
    public function restore($id)
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403);
        }

        $cheque = Cheque::onlyTrashed()->findOrFail($id);
        $cheque->restore();

        return redirect()->route('cheques.index', ['trashed' => '1'])
            ->with('success', 'تم استعادة الشيك بنجاح.');
    }

    /**
     * Permanently delete a cheque.
     */
    public function forceDelete($id)
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $cheque = Cheque::onlyTrashed()->findOrFail($id);
        $cheque->forceDelete();

        return redirect()->route('cheques.index', ['trashed' => '1'])
            ->with('success', 'تم حذف الشيك نهائياً.');
    }

    /**
     * Show the form for creating a new cheque.
     */
    public function create(): View
    {
        $customers = Customer::all();
        $banks = \App\Models\Bank::orderBy('name')->get();

        return view('cheques.create', compact('customers', 'banks'));
    }

    /**
     * Store a newly created cheque in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'cheque_no' => 'required|string|max:50',
            'bank_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,cleared,bounced',
        ]);

        Cheque::create($validated);

        return redirect()->route('cheques.index')
            ->with('success', 'Cheque created successfully.');
    }

    /**
     * Display the specified cheque.
     */
    public function show(Cheque $cheque): View
    {
        $cheque->load('customer');

        return view('cheques.show', compact('cheque'));
    }

    /**
     * Show the form for editing the specified cheque.
     */
    public function edit(Cheque $cheque): View
    {
        $customers = Customer::all();
        $banks = \App\Models\Bank::orderBy('name')->get();

        return view('cheques.edit', compact('cheque', 'customers', 'banks'));
    }

    /**
     * Update the specified cheque in database.
     */
    public function update(Request $request, Cheque $cheque)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'cheque_no' => 'required|string|max:50',
            'bank_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,cleared,bounced',
        ]);

        $cheque->update($validated);

        return redirect()->route('cheques.show', $cheque)
            ->with('success', 'Cheque updated successfully.');
    }

    /**
     * Remove the specified cheque from database.
     */
    public function destroy(Cheque $cheque, \App\Services\AccountBalanceService $accountService)
    {
        DB::transaction(function () use ($cheque, $accountService) {
            // Cancel ledger entry if it was posted (Cheques might be posted on creation or clearing)
            // Assuming Cheque creation posted to ledger or will be posted:
            $accountService->cancelTransaction('Cheque', $cheque->id);
            
            $cheque->delete();
        });

        return redirect()->route('cheques.index')
            ->with('success', 'Cheque deleted and removed from account balance successfully.');
    }

    /**
     * Export cheques to Excel.
     */
    public function export()
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403);
        }

        return Excel::download(new ChequesExport, 'cheques_'.now()->format('Y-m-d_His').'.xlsx');
    }
}
