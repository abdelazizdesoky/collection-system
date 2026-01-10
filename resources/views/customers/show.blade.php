@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">{{ $customer->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('customers.edit', $customer) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
            <a href="{{ route('customers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Customer Information</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Name:</span>
                    <span>{{ $customer->name }}</span>
                </div>
                <div>
                    <span class="font-semibold">Phone:</span>
                    <span>{{ $customer->phone }}</span>
                </div>
                <div>
                    <span class="font-semibold">Address:</span>
                    <span>{{ $customer->address }}</span>
                </div>
                <div>
                    <span class="font-semibold">Opening Balance:</span>
                    <span>{{ number_format($customer->opening_balance, 2) }}</span>
                </div>
                <div>
                    <span class="font-semibold">Balance Type:</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $customer->balance_type == 'debit' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ ucfirst($customer->balance_type) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Account Summary</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Current Balance:</span>
                    <span class="text-2xl font-bold text-blue-600">{{ number_format($customer->getCurrentBalance(), 2) }}</span>
                </div>
                <div>
                    <span class="font-semibold">Total Collections:</span>
                    <span>{{ $customer->collections->count() }}</span>
                </div>
                <div>
                    <span class="font-semibold">Total Cheques:</span>
                    <span>{{ $customer->cheques->count() }}</span>
                </div>
                <div>
                    <span class="font-semibold">Ledger Entries:</span>
                    <span>{{ $customer->accounts->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-bold mb-4">Recent Collections</h2>
        @if ($customer->collections->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Receipt No</th>
                            <th class="px-4 py-2 text-left">Amount</th>
                            <th class="px-4 py-2 text-left">Type</th>
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Collector</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customer->collections->take(5) as $collection)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $collection->receipt_no }}</td>
                                <td class="px-4 py-2">{{ number_format($collection->amount, 2) }}</td>
                                <td class="px-4 py-2"><span class="px-2 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ ucfirst($collection->payment_type) }}</span></td>
                                <td class="px-4 py-2">{{ $collection->collection_date->format('M d, Y') }}</td>
                                <td class="px-4 py-2">{{ $collection->collector->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($customer->collections->count() > 5)
                <a href="{{ route('customer-accounts.ledger', $customer) }}" class="text-blue-600 hover:text-blue-900 mt-2 inline-block">View Full Ledger</a>
            @endif
        @else
            <p class="text-gray-500">No collections recorded yet.</p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">Recent Cheques</h2>
        @if ($customer->cheques->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Cheque No</th>
                            <th class="px-4 py-2 text-left">Amount</th>
                            <th class="px-4 py-2 text-left">Bank</th>
                            <th class="px-4 py-2 text-left">Due Date</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customer->cheques->take(5) as $cheque)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $cheque->cheque_no }}</td>
                                <td class="px-4 py-2">{{ number_format($cheque->amount, 2) }}</td>
                                <td class="px-4 py-2">{{ $cheque->bank_name }}</td>
                                <td class="px-4 py-2">{{ $cheque->due_date->format('M d, Y') }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 inline-flex text-xs font-semibold rounded-full
                                        {{ $cheque->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($cheque->status == 'cleared' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($cheque->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No cheques recorded yet.</p>
        @endif
    </div>
</div>
@endsection
