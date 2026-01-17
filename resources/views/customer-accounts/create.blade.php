@extends('layouts.app')

@section('title', 'إضافة عملية مالية - ' . $customer->name)

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4" dir="rtl">
    <!-- Breadcrumbs/Back Button -->
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('customer.ledger', $customer) }}" class="bg-white dark:bg-dark-card p-2 rounded-lg border border-gray-100 dark:border-dark-border shadow-sm text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-2xl font-bold dark:text-white">إضافة عملية مالية جديدة</h1>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-8 bg-blue-50 dark:bg-blue-900/10 p-4 rounded-xl border border-blue-100 dark:border-blue-900/20">
                <div class="bg-blue-500 p-3 rounded-xl shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-blue-600 dark:text-blue-400">الاسم:</p>
                    <p class="text-lg font-bold text-blue-900 dark:text-blue-200">{{ $customer->name }}</p>
                </div>
            </div>

            <form action="{{ route('customer.ledger.store', $customer) }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">تاريخ العملية</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع العملية</label>
                        <select name="type" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            <option value="debit">مدين (إضافة دين جديد)</option>
                            <option value="credit">دائن (خصم من المديونية)</option>
                        </select>
                    </div>
                </div>

                <!-- description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">بيان العملية (نص توضيحي)</label>
                    <input type="text" name="description" required placeholder="مثال: فاتورة مبيعات رقم 123"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">المبلغ (ج.م)</label>
                    <div class="relative">
                        <input type="number" step="0.01" name="amount" required placeholder="0.00"
                               class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-2xl font-bold">
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-bold">ج.م</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4 flex gap-4">
                    <button type="submit" class="flex-grow bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        حفظ العملية
                    </button>
                    <a href="{{ route('customer.ledger', $customer) }}" class="px-8 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 font-bold py-4 rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-all">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
