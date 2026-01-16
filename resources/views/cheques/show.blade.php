@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Cheque #{{ $cheque->cheque_no }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('cheques.edit', $cheque) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Edit</a>
            <a href="{{ route('cheques.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Cheque Details</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Cheque No:</span>
                    <span>{{ $cheque->cheque_no }}</span>
                </div>
                <div>
                    <span class="font-semibold">Customer:</span>
                    <a href="{{ route('customers.show', $cheque->customer) }}" class="text-blue-600 hover:text-blue-900">{{ $cheque->customer->name }}</a>
                </div>
                <div>
                    <span class="font-semibold">Bank:</span>
                    <span>{{ $cheque->bank_name }}</span>
                </div>
                <div>
                    <span class="font-semibold">Due Date:</span>
                    <span>{{ $cheque->due_date->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Payment Information</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Amount:</span>
                    <span class="text-3xl font-bold text-blue-600">{{ number_format($cheque->amount, 2) }}</span>
                </div>
                <div>
                    <span class="font-semibold">Status:</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $cheque->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                           ($cheque->status == 'cleared' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($cheque->status) }}
                    </span>
                </div>
                @if ($cheque->due_date < now() && $cheque->status == 'pending')
                    <div class="mt-2 p-2 bg-red-100 border border-red-400 text-red-700 rounded text-xs">
                        ⚠️ This cheque is overdue
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($cheque->collection && $cheque->collection->attachment)
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4 text-right" dir="rtl">صورة المرفق (إثبات الدفع من التحصيل)</h2>
            <div class="rounded-xl overflow-hidden border-2 border-gray-100 flex justify-center bg-gray-50">
                <img src="{{ asset('storage/' . $cheque->collection->attachment) }}" alt="إثبات الدفع" class="max-w-full h-auto max-h-[500px] object-contain cursor-pointer" onclick="window.open(this.src)">
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('collections.show', $cheque->collection) }}" class="text-blue-600 hover:underline font-bold">عرض تفاصيل التحصيل المرتبط ←</a>
            </div>
        </div>
    @endif
</div>
@endsection
