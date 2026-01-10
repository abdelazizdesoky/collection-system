@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Collection Plan - {{ $collectionPlan->date->format('M d, Y') }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('collection-plans.edit', $collectionPlan) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
            <a href="{{ route('collection-plan-items.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">+ Add Item</a>
            <a href="{{ route('collection-plans.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Plan Information</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Date:</span>
                    <span>{{ $collectionPlan->date->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="font-semibold">Type:</span>
                    <span>{{ $collectionPlan->type }}</span>
                </div>
                <div>
                    <span class="font-semibold">Collector:</span>
                    <a href="{{ route('collectors.show', $collectionPlan->collector) }}" class="text-blue-600 hover:text-blue-900">{{ $collectionPlan->collector->name }}</a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Summary</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Total Items:</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $collectionPlan->items->count() }}</span>
                </div>
                <div>
                    <span class="font-semibold">Expected Amount:</span>
                    <span class="text-2xl font-bold text-green-600">{{ number_format($collectionPlan->getTotalExpectedAmount(), 2) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Status</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Created:</span>
                    <span>{{ $collectionPlan->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div>
                    <span class="font-semibold">Status:</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        Active
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">Plan Items</h2>
        @if ($collectionPlan->items->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Priority</th>
                            <th class="px-4 py-2 text-left">Customer</th>
                            <th class="px-4 py-2 text-left">Expected Amount</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collectionPlan->items->sortBy('priority') as $item)
                            <tr class="border-b">
                                <td class="px-4 py-2 font-semibold">{{ $item->priority }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('customers.show', $item->customer) }}" class="text-blue-600 hover:text-blue-900">{{ $item->customer->name }}</a>
                                </td>
                                <td class="px-4 py-2">{{ number_format($item->expected_amount, 2) }}</td>
                                <td class="px-4 py-2 text-sm">
                                    <a href="{{ route('collection-plan-items.edit', $item) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</a>
                                    <form action="{{ route('collection-plan-items.destroy', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No items added to this plan yet.</p>
        @endif
    </div>
</div>
@endsection
