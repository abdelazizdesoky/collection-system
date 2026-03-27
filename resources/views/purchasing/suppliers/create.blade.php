@extends('layouts.app')
@section('title', 'إضافة مورد')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('suppliers.index') }}" class="bg-gray-100 dark:bg-dark-card p-3 rounded-2xl hover:bg-gray-200 transition-colors"><svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg></a>
        <h1 class="text-2xl font-black text-gray-900 dark:text-white">إضافة مورد جديد</h1>
    </div>
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border p-8">
        <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">كود المورد <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">
                    @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror</div>
                <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">اسم المورد <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror</div>
                <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white"></div>
                <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">الرصيد الافتتاحي</label>
                    <input type="number" step="0.01" name="opening_balance" value="{{ old('opening_balance', 0) }}" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white"></div>
            </div>
            <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">العنوان</label>
                <textarea name="address" rows="2" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">{{ old('address') }}</textarea></div>
            <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">نوع الرصيد <span class="text-red-500">*</span></label>
                <select name="balance_type" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:bg-dark-card dark:text-white">
                    <option value="credit" {{ old('balance_type') === 'credit' ? 'selected' : '' }}>دائن (عليه)</option>
                    <option value="debit" {{ old('balance_type') === 'debit' ? 'selected' : '' }}>مدين (له)</option>
                </select></div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-gradient-to-l from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg transition-all">حفظ</button>
                <a href="{{ route('suppliers.index') }}" class="bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 px-8 py-3 rounded-2xl font-bold transition-all">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
