@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Collection #{{ $collection->receipt_no }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('collections.edit', $collection) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
            <a href="{{ route('collections.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Collection Details</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Receipt No:</span>
                    <span>{{ $collection->receipt_no }}</span>
                </div>
                <div>
                    <span class="font-semibold">Customer:</span>
                    <a href="{{ route('customers.show', $collection->customer) }}" class="text-blue-600 hover:text-blue-900">{{ $collection->customer->name }}</a>
                </div>
                <div>
                    <span class="font-semibold">Collector:</span>
                    <a href="{{ route('collectors.show', $collection->collector) }}" class="text-blue-600 hover:text-blue-900">{{ $collection->collector->name }}</a>
                </div>
                <div>
                    <span class="font-semibold">Collection Date:</span>
                    <span>{{ $collection->collection_date->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Payment Information</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Amount:</span>
                    <span class="text-3xl font-bold text-green-600">{{ number_format($collection->amount, 2) }}</span>
                </div>
                <div>
                    <span class="font-semibold">Payment Type:</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ ucfirst($collection->payment_type) }}
                    </span>
                </div>
                <div>
                    <span class="font-semibold">Status:</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Recorded
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if ($collection->notes || $collection->attachment)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            @if ($collection->notes)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-bold mb-4 text-right" dir="rtl">الملاحظات</h2>
                    <p class="text-right" dir="rtl">{{ $collection->notes }}</p>
                </div>
            @endif

            @if ($collection->attachment)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-bold mb-4 text-right" dir="rtl">صورة المرفق (إثبات الدفع)</h2>
                    <div class="rounded-xl overflow-hidden border-2 border-gray-100">
                        <img src="{{ asset('storage/' . $collection->attachment) }}" alt="إثبات الدفع" class="w-full h-auto max-h-96 object-contain cursor-pointer" onclick="window.open(this.src)">
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">Ledger Entry</h2>
        @if ($collection->accountEntry)
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Entry Date:</span>
                    <span>{{ $collection->accountEntry->date->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="font-semibold">Description:</span>
                    <span>{{ $collection->accountEntry->description }}</span>
                </div>
                <div>
                    <span class="font-semibold">Credit:</span>
                    <span class="text-green-600">{{ number_format($collection->accountEntry->credit, 2) }}</span>
                </div>
                <div>
                    <span class="font-semibold">Balance After:</span>
                    <span class="text-lg font-bold">{{ number_format($collection->accountEntry->balance, 2) }}</span>
                </div>
            </div>
        @else
            <p class="text-gray-500">No ledger entry found.</p>
        @endif
    </div>
</div>
@endsection
