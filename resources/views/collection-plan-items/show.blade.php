@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Plan Item - {{ $collectionPlanItem->customer->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('collection-plan-items.edit', $collectionPlanItem) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
            <a href="{{ route('collection-plan-items.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Item Details</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Plan:</span>
                    <a href="{{ route('collection-plans.show', $collectionPlanItem->collectionPlan) }}" class="text-blue-600 hover:text-blue-900">
                        {{ $collectionPlanItem->collectionPlan->date->format('M d, Y') }}
                    </a>
                </div>
                <div>
                    <span class="font-semibold">Collector:</span>
                    <a href="{{ route('collectors.show', $collectionPlanItem->collectionPlan->collector) }}" class="text-blue-600 hover:text-blue-900">
                        {{ $collectionPlanItem->collectionPlan->collector->name }}
                    </a>
                </div>
                <div>
                    <span class="font-semibold">Customer:</span>
                    <a href="{{ route('customers.show', $collectionPlanItem->customer) }}" class="text-blue-600 hover:text-blue-900">
                        {{ $collectionPlanItem->customer->name }}
                    </a>
                </div>
                <div>
                    <span class="font-semibold">Priority:</span>
                    <span class="bg-blue-100 px-2 py-1 rounded">{{ $collectionPlanItem->priority }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Amount Information</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Expected Amount:</span>
                    <span class="text-3xl font-bold text-green-600">{{ number_format($collectionPlanItem->expected_amount, 2) }}</span>
                </div>
                <div>
                    <span class="font-semibold">Current Balance:</span>
                    <span class="text-lg font-bold">{{ number_format($collectionPlanItem->customer->getCurrentBalance(), 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
