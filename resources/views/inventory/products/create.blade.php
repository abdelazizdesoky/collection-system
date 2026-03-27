@extends('layouts.app')
@section('title', 'إضافة منتج')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('products.index') }}" class="bg-gray-100 dark:bg-dark-card p-3 rounded-2xl hover:bg-gray-200 transition-colors">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-black text-gray-900 dark:text-white">إضافة منتج جديد</h1>
    </div>
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border p-8">
        <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">كود المنتج <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">اسم المنتج <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">القسم</label>
                    <select name="category_id" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:bg-dark-card dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                        <option value="">-- اختر القسم --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">وحدة القياس</label>
                    <select name="unit_id" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:bg-dark-card dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                        <option value="">-- اختر الوحدة --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }} {{ $unit->symbol ? "({$unit->symbol})" : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">سعر البيع <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price', 0) }}" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">سعر الشراء <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price', 0) }}" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">الحد الأدنى للمخزون</label>
                    <input type="number" step="0.01" name="min_stock" value="{{ old('min_stock', 0) }}" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">حد إعادة الطلب</label>
                    <input type="number" step="0.01" name="reorder_level" value="{{ old('reorder_level', 0) }}" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>

            <!-- Opening Stock Section -->
            <div class="bg-blue-50/50 dark:bg-blue-900/10 p-6 rounded-3xl border border-blue-100 dark:border-blue-900/30 space-y-4">
                <h3 class="text-lg font-bold text-blue-900 dark:text-blue-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    الرصيد الافتتاحي (اختياري)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">الكمية الابتدائية</label>
                        <input type="number" step="0.01" name="opening_stock" value="{{ old('opening_stock', 0) }}" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-white dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">المخزن</label>
                        <select name="warehouse_id" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-white dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                            <option value="">-- اختر المخزن --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">الوصف</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">{{ old('description') }}</textarea>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_for_sale" id="is_for_sale" value="1" checked class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="is_for_sale" class="text-sm font-bold text-gray-700 dark:text-gray-300">متاح للبيع</label>
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="is_active" class="text-sm font-bold text-gray-700 dark:text-gray-300">نشط</label>
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-gradient-to-l from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg transition-all">حفظ</button>
                <a href="{{ route('products.index') }}" class="bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 px-8 py-3 rounded-2xl font-bold transition-all">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
