@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Customer Accounts Ledger</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Debit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($accounts as $account)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('customers.show', $account->customer) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $account->customer->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $account->date->format('M d, Y') }}</td>
                        <td class="px-6 py-4">{{ $account->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-red-600 font-semibold">
                            {{ $account->debit > 0 ? number_format($account->debit, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-green-600 font-semibold">
                            {{ $account->credit > 0 ? number_format($account->credit, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold">{{ number_format($account->balance, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $account->reference_type }}</td>
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
