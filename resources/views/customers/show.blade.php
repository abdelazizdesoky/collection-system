@extends('layouts.app')

@section('title', 'بيانات العميل - ' . $customer->name)

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4 text-right w-full">
            <a href="{{ route('customers.index') }}" 
               class="bg-white dark:bg-dark-card p-3 rounded-xl shadow-md border border-gray-100 dark:border-dark-border text-gray-500 hover:text-gray-700 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold dark:text-white">{{ $customer->name }}</h1>
                <p class="text-gray-500 dark:text-gray-400">ملف العميل المالي والشخصي</p>
            </div>
        </div>
        
        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('customer.ledger.create', $customer) }}" 
               class="flex-grow md:flex-none bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                عملية مالية جديدة
            </a>
            <a href="{{ route('customers.edit', $customer) }}" 
               class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center justify-center">
               تعديل البيانات
            </a>
        </div>
    </div>

    @if($customer->hasDueInstallments())
    <div class="mb-8 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-6 flex flex-col md:flex-row items-center gap-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-2 h-full bg-red-500"></div>
        <div class="bg-red-500 text-white p-4 rounded-xl shadow-lg shadow-red-500/30 animate-bounce">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div class="flex-1 text-center md:text-right">
            <h3 class="text-xl font-bold text-red-800 dark:text-red-400 mb-1">تنبيه هام: أقساط مستحقة السداد!</h3>
            <p class="text-red-600 dark:text-red-300 font-medium">هذا العميل لديه <span class="font-black text-red-800 dark:text-white">{{ $customer->due_installments->count() }}</span> أقساط حل موعد سدادها. يرجى مراجعة خطة الأقساط واتخاذ الإجراء اللازم فوراً.</p>
        </div>
        <a href="{{ route('installments.index', ['customer_id' => $customer->id]) }}" class="w-full md:w-auto text-center bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-red-500/20 transition-all transform hover:scale-105">
            عرض الأقساط
        </a>
    </div>
    @endif

    <div x-data="{ activeTab: 'overview' }" class="space-y-8">
        <!-- Tabs Navigation -->
        <div class="flex flex-wrap gap-2 border-b border-gray-100 dark:border-dark-border pb-px overflow-x-auto">
            <button @click="activeTab = 'overview'" 
                :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600 dark:text-blue-400 font-black px-6 py-3 border-b-2' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 px-6 py-3 font-bold'"
                class="transition-all text-sm whitespace-nowrap">
                نظرة عامة
            </button>
            <button @click="activeTab = 'ledger'" 
                :class="activeTab === 'ledger' ? 'border-blue-500 text-blue-600 dark:text-blue-400 font-black px-6 py-3 border-b-2' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 px-6 py-3 font-bold'"
                class="transition-all text-sm whitespace-nowrap">
                كشف الحساب (Ledger)
            </button>
            <button @click="activeTab = 'installments'" 
                :class="activeTab === 'installments' ? 'border-blue-500 text-blue-600 dark:text-blue-400 font-black px-6 py-3 border-b-2' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 px-6 py-3 font-bold'"
                class="transition-all text-sm whitespace-nowrap">
                الأقساط المتبقية
            </button>
            <button @click="activeTab = 'collections'" 
                :class="activeTab === 'collections' ? 'border-blue-500 text-blue-600 dark:text-blue-400 font-black px-6 py-3 border-b-2' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 px-6 py-3 font-bold'"
                class="transition-all text-sm whitespace-nowrap">
                التحصيلات والشيكات
            </button>
            <button @click="activeTab = 'issues'" 
                :class="activeTab === 'issues' ? 'border-blue-500 text-blue-600 dark:text-blue-400 font-black px-6 py-3 border-b-2' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 px-6 py-3 font-bold'"
                class="transition-all text-sm whitespace-nowrap">
                المشكلات ({{ $customer->issues->count() }})
            </button>
        </div>

        <!-- Tab Contents -->
        
        <!-- Overview Tab -->
        <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Customer Info (Moved from sidebar) -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-dark-border">
                    <h2 class="text-lg font-bold dark:text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        المعلومات الأساسية
                    </h2>
                    <div class="space-y-4">
                        <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                            <p class="text-xs text-gray-500 mb-1">الكود</p>
                            <p class="font-bold dark:text-white text-lg font-mono">{{ $customer->code ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                            <p class="text-xs text-gray-500 mb-1">الاسم الكامل</p>
                            <p class="font-bold dark:text-white text-lg">{{ $customer->name }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                            <p class="text-xs text-gray-500 mb-1">رقم الهاتف</p>
                            <p class="font-bold dark:text-white text-lg" dir="ltr">{{ $customer->phone }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                            <p class="text-xs text-gray-500 mb-1">العنوان</p>
                            <p class="font-medium dark:text-gray-300 text-sm">{{ $customer->address }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-6 text-white overflow-hidden relative">
                    <div class="relative z-10">
                        <h2 class="text-lg font-bold mb-4 opacity-90 text-right">ملخص الحساب</h2>
                        <div class="space-y-4 text-right">
                            <div class="flex flex-row-reverse justify-between items-end border-b border-white/10 pb-4">
                                <div>
                                    <p class="text-xs opacity-75 mb-1">الرصيد الحالي</p>
                                    <p class="text-4xl font-black">{{ number_format($customer->getCurrentBalance(), 2) }}</p>
                                </div>
                                <span class="text-sm opacity-90">ج.م</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Summary Dash (Move existing tables here but simplified) -->
            <div class="lg:col-span-2 space-y-6 text-right">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-emerald-50 dark:bg-emerald-900/10 p-4 rounded-2xl border border-emerald-100 dark:border-emerald-900/20">
                        <p class="text-emerald-600 dark:text-emerald-400 font-bold mb-1">إجمالي التحصيلات</p>
                        <p class="text-2xl font-black text-emerald-800 dark:text-emerald-200">{{ number_format($customer->collections->sum('amount'), 2) }} ج.م</p>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/10 p-4 rounded-2xl border border-blue-100 dark:border-blue-900/20">
                        <p class="text-blue-600 dark:text-blue-400 font-bold mb-1">إجمالي المبيعات</p>
                        <p class="text-2xl font-black text-blue-800 dark:text-blue-200">{{ number_format($customer->accounts->sum('debit'), 2) }} ج.م</p>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border p-6">
                    <h3 class="text-md font-black dark:text-white mb-4">آخر العمليات</h3>
                    <div class="space-y-3">
                        @foreach($customer->accounts->take(5) as $entry)
                            <div class="flex justify-between items-center p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-all border border-transparent hover:border-gray-100">
                                <span class="text-gray-400 text-[10px] font-bold">{{ $entry->date->format('Y/m/d') }}</span>
                                <div class="flex-1 px-4">
                                    <span class="text-sm font-bold dark:text-gray-300">{{ $entry->description }}</span>
                                </div>
                                <span class="font-black {{ $entry->debit > 0 ? 'text-red-500' : 'text-emerald-500' }}">
                                    {{ $entry->debit > 0 ? '+' . number_format($entry->debit, 2) : '-' . number_format($entry->credit, 2) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <button @click="activeTab = 'ledger'" class="w-full mt-4 py-2 text-blue-500 font-bold text-sm hover:underline font-bold">مشاهدة الكشف الكامل &rarr;</button>
                </div>
            </div>
        </div>

        <!-- Ledger Tab (The requested Account Statement) -->
        <div x-show="activeTab === 'ledger'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
                <div class="p-6 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-gray-50/50 dark:bg-dark-bg/20">
                    <h2 class="text-lg font-black dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5m11 0h2a4 4 0 014 4v2m-6-10a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        كشف حساب تفصيلي (Ledger)
                    </h2>
                    <div class="flex gap-2">
                        <a href="{{ route('accounting.adjustments.customer', $customer) }}" class="bg-amber-100 text-amber-700 px-4 py-2 rounded-xl text-xs font-black shadow-sm hover:bg-amber-200 transition-all flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            تسجيل تسوية يدوية
                        </a>
                        <button onclick="window.print()" class="bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-xl text-xs font-black hover:bg-gray-200 transition-all">طباعة الكشف</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead class="bg-gray-100 dark:bg-dark-tableheader text-gray-500 text-xs font-black uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 border-b border-gray-200 dark:border-dark-border">التاريخ</th>
                                <th class="px-6 py-4 border-b border-gray-200 dark:border-dark-border">البيان / التفاصيل</th>
                                <th class="px-6 py-4 border-b border-gray-200 dark:border-dark-border">مدين (Debitor)</th>
                                <th class="px-6 py-4 border-b border-gray-200 dark:border-dark-border">دائن (Creditor)</th>
                                <th class="px-6 py-4 border-b border-gray-200 dark:border-dark-border text-blue-600 dark:text-blue-400 underline">الرصيد المتبقي</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                            <!-- Opening Balance Row -->
                            <tr class="bg-blue-50/30 dark:bg-blue-900/5">
                                <td class="px-6 py-4 text-xs font-bold text-gray-400 italic">البداية</td>
                                <td class="px-6 py-4 font-black dark:text-gray-300">رصيد افتتاحي</td>
                                <td class="px-6 py-4">{{ $customer->balance_type === 'debit' ? number_format($customer->opening_balance, 2) : '0.00' }}</td>
                                <td class="px-6 py-4">{{ $customer->balance_type === 'credit' ? number_format($customer->opening_balance, 2) : '0.00' }}</td>
                                <td class="px-6 py-4 font-black">{{ number_format($customer->opening_balance, 2) }}</td>
                            </tr>
                            @foreach ($customer->accounts as $account)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/20 transition-colors">
                                    <td class="px-6 py-3 text-sm font-medium dark:text-gray-400">{{ $account->date->format('Y-m-d') }}</td>
                                    <td class="px-6 py-3 font-bold dark:text-white text-sm">
                                        {{ $account->description }}
                                        @if($account->reference_id)
                                            <span class="text-[10px] text-gray-400 font-normal mr-1 tracking-tighter">(#{{ $account->reference_type }} {{ $account->reference_id }})</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-red-600 font-bold">{{ $account->debit > 0 ? number_format($account->debit, 2) : '-' }}</td>
                                    <td class="px-6 py-3 text-emerald-600 font-bold">{{ $account->credit > 0 ? number_format($account->credit, 2) : '-' }}</td>
                                    <td class="px-6 py-3 font-black dark:text-blue-400">{{ number_format($account->balance, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-dark-tablefooter border-t-2 border-gray-200 dark:border-dark-border">
                            <tr class="font-black text-lg">
                                <td colspan="2" class="px-6 py-4 dark:text-white text-left">الرصيد النهائي:</td>
                                <td colspan="3" class="px-6 py-4 text-blue-600 dark:text-blue-400">{{ number_format($customer->getCurrentBalance(), 2) }} ج.م</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Installments Tab -->
        <div x-show="activeTab === 'installments'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
             @include('customers.partials.installments_tab')
        </div>

        <!-- Collections Tab -->
        <div x-show="activeTab === 'collections'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
             @include('customers.partials.collections_tab')
        </div>

        <!-- Issues Tab -->
        <div x-show="activeTab === 'issues'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
             @include('customers.partials.issues_tab')
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        .container { max-width: 100% !important; padding: 0 !important; }
        body { background: white !important; }
    }
</style>
@endsection
