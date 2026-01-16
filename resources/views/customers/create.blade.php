@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 text-right" dir="rtl">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">عميل جديد</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded text-right">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customers.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">الاسم *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">الهاتف *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">العنوان *</label>
                <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>{{ old('address') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">الرصيد الافتتاحي *</label>
                <input type="number" name="opening_balance" step="0.01" value="{{ old('opening_balance', 0) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">نوع الرصيد *</label>
                <select name="balance_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" dir="rtl" required>
                    <option value="">اختر النوع</option>
                    <option value="debit" {{ old('balance_type') == 'debit' ? 'selected' : '' }}>مدين</option>
                    <option value="credit" {{ old('balance_type') == 'credit' ? 'selected' : '' }}>دائن</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">إنشاء</button>
                <a href="{{ route('customers.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-center">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
