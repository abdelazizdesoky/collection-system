@extends('layouts.app')

@section('title', 'تفاصيل التحصيل #' . $collection->receipt_no)

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4 text-right w-full">
            <a href="{{ route('collections.index') }}" 
               class="bg-white dark:bg-dark-card p-3 rounded-xl shadow-md border border-gray-100 dark:border-dark-border text-gray-500 hover:text-gray-700 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-black dark:text-white tracking-tight">إيصال تحصيل #{{ $collection->receipt_no }}</h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium">تفاصيل العملية المالية والقيود المرتبطة</p>
            </div>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('collections.edit', $collection) }}" 
               class="flex-grow md:flex-none bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2 text-sm whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                تعديل الإيصال
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Collection Info Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-dark-border">
                <h2 class="text-lg font-bold dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    بيانات العملية
                </h2>
                <div class="space-y-4">
                    <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                        <p class="text-xs text-gray-500 mb-1">العميل</p>
                        <a href="{{ route('customers.show', $collection->customer) }}" class="font-bold text-indigo-600 dark:text-indigo-400 text-lg hover:underline">
                            {{ $collection->customer->name }}
                        </a>
                    </div>
                    <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                        <p class="text-xs text-gray-500 mb-1">المندوب المسؤول</p>
                        <p class="font-bold dark:text-white">{{ $collection->collector->name }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                            <p class="text-xs text-gray-500 mb-1">التاريخ</p>
                            <p class="font-bold dark:text-white">{{ $collection->collection_date->format('Y-m-d') }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                            <p class="text-xs text-gray-500 mb-1">طريقة الدفع</p>
                            <span class="px-2 py-1 text-xs font-bold rounded-lg 
                                {{ $collection->payment_type == 'cash' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                   ($collection->payment_type == 'cheque' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400') }}">
                                {{ $collection->payment_type === 'cash' ? 'نقدي' : ($collection->payment_type === 'cheque' ? 'شيك' : 'تحويل') }}
                            </span>
                        </div>
                    </div>
                    @if($collection->bank_name)
                    <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                        <p class="text-xs text-gray-500 mb-1">البنك / الجهة</p>
                        <p class="font-bold dark:text-white">{{ $collection->bank_name }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Amount Card -->
            <div class="bg-gradient-to-br from-emerald-600 to-green-700 rounded-2xl shadow-xl p-6 text-white overflow-hidden relative">
                <div class="relative z-10 text-center">
                    <p class="text-sm opacity-80 mb-2">المبلغ المندوب</p>
                    <h2 class="text-4xl font-black">{{ number_format($collection->amount, 2) }}</h2>
                    <p class="text-lg opacity-90 mt-1">جنيه مصري</p>
                </div>
                <svg class="absolute -bottom-6 -left-6 w-32 h-32 opacity-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- Details & Ledger -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Notes & Attachment -->
            @if ($collection->notes || $collection->attachment)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if ($collection->notes)
                    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-dark-border">
                        <h3 class="text-lg font-bold dark:text-white mb-4">ملاحظات</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $collection->notes }}</p>
                    </div>
                    @endif

                    @if ($collection->attachment)
                    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-dark-border">
                        <h3 class="text-lg font-bold dark:text-white mb-4">المرفق (إثبات الدفع)</h3>
                        <div class="rounded-xl overflow-hidden border border-gray-100 dark:border-dark-border group relative">
                            <img src="{{ asset('storage/' . $collection->attachment) }}" class="w-full h-40 object-cover group-hover:scale-110 transition-transform duration-500 cursor-pointer" onclick="window.open(this.src)">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @endif

            <!-- Ledger Entry (Qaid) -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
                <div class="p-6 border-b border-gray-100 dark:border-dark-border bg-gray-50 dark:bg-dark-tableheader">
                    <h3 class="text-lg font-bold dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        قيد اليومية المرتبط
                    </h3>
                </div>
                <div class="p-6">
                    @php $entry = $collection->accountEntry; @endphp
                    @if ($entry)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">تاريخ القيد</p>
                                <p class="font-bold dark:text-gray-300">{{ $entry->date->format('Y-m-d') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">البيان</p>
                                <p class="font-bold dark:text-gray-300 text-sm">{{ $entry->description }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">دائن (تحصيل)</p>
                                <p class="font-black text-emerald-600 dark:text-emerald-400">{{ number_format($entry->credit, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">الرصيد بعد العملية</p>
                                <p class="font-black text-indigo-600 dark:text-indigo-400">{{ number_format($entry->balance, 2) }}</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800 rounded-xl text-amber-700 dark:text-amber-400">
                            لا يوجد قيد مالي مسجل حالياً لهذه العملية.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
