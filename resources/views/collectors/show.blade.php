@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">{{ $collector->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('collectors.edit', $collector) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
            <a href="{{ route('collectors.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Collector Information</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Name:</span>
                    <span>{{ $collector->name }}</span>
                </div>
                <div>
                    <span class="font-semibold">Phone:</span>
                    <span>{{ $collector->phone }}</span>
                </div>
                <div>
                    <span class="font-semibold">Area:</span>
                    <span>{{ $collector->area }}</span>
                </div>
                <div>
                    <span class="font-semibold">User Account:</span>
                    <span>{{ $collector->user?->name ?? 'Not assigned' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Activity Summary</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Total Collections:</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $collector->collections->count() }}</span>
                </div>
                <div>
                    <span class="font-semibold">Total Collected:</span>
                    <span class="text-2xl font-bold text-green-600">{{ number_format($collector->collections->sum('amount'), 2) }}</span>
                </div>
                <div>
                    <span class="font-semibold">Collection Plans:</span>
                    <span>{{ $collector->collectionPlans->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-bold mb-4">Recent Collections</h2>
        @if ($collector->collections->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Receipt No</th>
                            <th class="px-4 py-2 text-left">Customer</th>
                            <th class="px-4 py-2 text-left">Amount</th>
                            <th class="px-4 py-2 text-left">Type</th>
                            <th class="px-4 py-2 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collector->collections->take(5) as $collection)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $collection->receipt_no }}</td>
                                <td class="px-4 py-2">{{ $collection->customer->name }}</td>
                                <td class="px-4 py-2">{{ number_format($collection->amount, 2) }}</td>
                                <td class="px-4 py-2"><span class="px-2 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ ucfirst($collection->payment_type) }}</span></td>
                                <td class="px-4 py-2">{{ $collection->collection_date->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No collections recorded yet.</p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">Collection Plans</h2>
        @if ($collector->collectionPlans->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Type</th>
                            <th class="px-4 py-2 text-left">Items</th>
                            <th class="px-4 py-2 text-left">Expected Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collector->collectionPlans->take(5) as $plan)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $plan->date->format('M d, Y') }}</td>
                                <td class="px-4 py-2">{{ $plan->type }}</td>
                                <td class="px-4 py-2">{{ $plan->items->count() }}</td>
                                <td class="px-4 py-2">{{ number_format($plan->getTotalExpectedAmount(), 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No collection plans assigned yet.</p>
        @endif
    </div>
</div>
@endsection
