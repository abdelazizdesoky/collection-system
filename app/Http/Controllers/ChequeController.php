<?php

namespace App\Http\Controllers;

use App\Models\Cheque;
use App\Models\Customer;
use App\Exports\ChequesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChequeController extends Controller
{
    /**
     * Display a listing of cheques.
     */
    public function index(): View
    {
        $cheques = Cheque::with('customer')->latest()->paginate(15);

        return view('cheques.index', compact('cheques'));
    }

    /**
     * Show the form for creating a new cheque.
     */
    public function create(): View
    {
        $customers = Customer::all();

        return view('cheques.create', compact('customers'));
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

        return view('cheques.edit', compact('cheque', 'customers'));
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
    public function destroy(Cheque $cheque)
    {
        $cheque->delete();

        return redirect()->route('cheques.index')
            ->with('success', 'Cheque deleted successfully.');
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
