@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Ledger Entry</h1>
        <a href="{{ route('customer-accounts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</a>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Entry Information</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Customer:</span>
                    <a href="{{ route('customers.show', $customerAccount->customer) }}" class="text-blue-600 hover:text-blue-900">
                        {{ $customerAccount->customer->name }}
                    </a>
                </div>
                <div>
                    <span class="font-semibold">Date:</span>
                    <span>{{ $customerAccount->date->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="font-semibold">Description:</span>
                    <span>{{ $customerAccount->description }}</span>
                </div>
                <div>
                    <span class="font-semibold">Reference:</span>
                    <span>{{ $customerAccount->reference_type }} #{{ $customerAccount->reference_id }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Amount Details</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Debit (Amount Owed):</span>
                    <span class="text-2xl font-bold text-red-600">{{ number_format($customerAccount->debit, 2) }}</span>
                </div>
                <div>
                    <span class="font-semibold">Credit (Payment):</span>
                    <span class="text-2xl font-bold text-green-600">{{ number_format($customerAccount->credit, 2) }}</span>
                </div>
                <div class="border-t pt-3">
                    <span class="font-semibold">Running Balance:</span>
                    <span class="text-3xl font-bold text-blue-600">{{ number_format($customerAccount->balance, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">Customer Ledger</h2>
        <a href="{{ route('customer-accounts.ledger', $customerAccount->customer) }}" class="text-blue-600 hover:text-blue-900">
            View Full Ledger for {{ $customerAccount->customer->name }}
        </a>
    </div>
</div>
@endsection
