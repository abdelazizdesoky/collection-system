@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">تعديل العميل</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">الكود (اختياري)</label>
                <input type="text" name="code" value="{{ old('code', $customer->code) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="كود مرجعي مميز">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">الاسم *</label>
                <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">الهاتف *</label>
                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">العنوان *</label>
                <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('address', $customer->address) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">الرصيد الافتتاحي *</label>
                <input type="number" name="opening_balance" step="0.01" value="{{ old('opening_balance', $customer->opening_balance) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">نوع الرصيد *</label>
                <select name="balance_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="debit" {{ old('balance_type', $customer->balance_type) == 'debit' ? 'selected' : '' }}> مدين (العميل يدين)</option>
                    <option value="credit" {{ old('balance_type', $customer->balance_type) == 'credit' ? 'selected' : '' }}>دائن (العميل يدأين)</option>
                </select>
            </div>

            <div class="mb-4 text-right">
                <label class="block text-gray-700 text-sm font-bold mb-2">المنطقة</label>
                <select name="area_id" class="w-full select2-search" data-placeholder="اختر المنطقة...">
                    <option value=""></option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ old('area_id', $customer->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6 text-right">
                <label class="block text-gray-700 text-sm font-bold mb-2">المندوب المسؤول</label>
                <select name="collector_id" class="w-full select2-search" data-placeholder="اختر المندوب المسؤول...">
                    <option value=""></option>
                    @foreach($collectors as $collector)
                        <option value="{{ $collector->id }}" {{ old('collector_id', $customer->collector_id) == $collector->id ? 'selected' : '' }}>{{ $collector->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
                <a href="{{ route('customers.show', $customer) }}" class="flex-1 bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-center">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
