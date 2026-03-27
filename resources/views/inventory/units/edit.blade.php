@extends('layouts.app')
@section('title', 'تعديل الوحدة')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('units.index') }}" class="bg-gray-100 dark:bg-dark-card p-3 rounded-2xl hover:bg-gray-200 transition-colors">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-black text-gray-900 dark:text-white">تعديل: {{ $unit->name }}</h1>
    </div>
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border p-8">
        <form action="{{ route('units.update', $unit) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">اسم الوحدة <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $unit->name) }}" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">الرمز</label>
                <input type="text" name="symbol" value="{{ old('symbol', $unit->symbol) }}" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-gradient-to-l from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg transition-all">تحديث</button>
                <a href="{{ route('units.index') }}" class="bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 px-8 py-3 rounded-2xl font-bold transition-all">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
