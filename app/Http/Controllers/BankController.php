<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BankController extends Controller
{
    public function index(): View
    {
        $banks = Bank::latest()->paginate(15);
        return view('banks.index', compact('banks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|unique:banks,name|max:255',
            'type' => 'required|in:bank,wallet',
        ]);
        Bank::create($request->only('name', 'type'));
        return redirect()->route('banks.index')->with('success', 'Bank created successfully.');
    }

    public function update(Request $request, Bank $bank): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:banks,name,' . $bank->id,
            'type' => 'required|in:bank,wallet',
        ]);
        $bank->update($request->only('name', 'type'));
        return redirect()->route('banks.index')->with('success', 'Bank updated successfully.');
    }

    public function destroy(Bank $bank): RedirectResponse
    {
        $bank->delete();
        return redirect()->route('banks.index')->with('success', 'Bank deleted successfully.');
    }
}
