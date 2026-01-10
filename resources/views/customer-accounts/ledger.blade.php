@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">{{ $customer->name }} - Ledger</h1>
        <a href="{{ route('customers.show', $customer) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to Customer</a>
    </div>

    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Customer Info</h2>
            <div class="space-y-2">
                <div>
                    <span class="font-semibold">Name:</span>
                    <span>{{ $customer->name }}</span>
                </div>
                <div>
                    <span class="font-semibold">Phone:</span>
                    <span>{{ $customer->phone }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Balance Type</h2>
            <span class="px-3 py-1 inline-flex text-lg leading-5 font-semibold rounded-full {{ $customer->balance_type == 'debit' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                {{ ucfirst($customer->balance_type) }}
            </span>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Current Balance</h2>
            <span class="text-3xl font-bold text-blue-600">{{ number_format($customer->getCurrentBalance(), 2) }}</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Debit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($accounts as $account)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $account->date->format('M d, Y') }}</td>
                        <td class="px-6 py-4">{{ $account->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $account->reference_type }} #{{ $account->reference_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-red-600 font-semibold">
                            {{ $account->debit > 0 ? number_format($account->debit, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-green-600 font-semibold">
                            {{ $account->credit > 0 ? number_format($account->credit, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-lg">{{ number_format($account->balance, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $accounts->links() }}
    </div>
</div>
@endsection
