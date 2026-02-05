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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Customer Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-dark-border">
                <h2 class="text-lg font-bold dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
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
                        <p class="font-medium dark:text-gray-300">{{ $customer->address }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                            <p class="text-xs text-gray-500 mb-1">المنطقة</p>
                            <p class="font-medium dark:text-gray-300">{{ $customer->area->name ?? 'غير محدد' }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                            <p class="text-xs text-gray-500 mb-1">المندوب المسؤول</p>
                            <p class="font-medium dark:text-gray-300">{{ $customer->collector->name ?? 'غير محدد' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-6 text-white overflow-hidden relative">
                <div class="relative z-10">
                    <h2 class="text-lg font-bold mb-4 opacity-90">ملخص الحساب</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-end border-b border-white/10 pb-4">
                            <div>
                                <p class="text-xs opacity-75 mb-1">الرصيد الحالي</p>
                                <p class="text-4xl font-black">{{ number_format($customer->getCurrentBalance(), 2) }}</p>
                            </div>
                            <span class="text-sm opacity-90">ج.م</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-2">
                            <div>
                                <p class="text-xs opacity-75">إجمالي التحصيلات</p>
                                <p class="text-xl font-bold">{{ $customer->collections->count() }}</p>
                            </div>
                            <div>
                                <p class="text-xs opacity-75">إجمالي الشيكات</p>
                                <p class="text-xl font-bold">{{ $customer->cheques->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Abstract BG Icon -->
                <svg class="absolute -bottom-6 -left-6 w-32 h-32 opacity-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- Recent Activity Tabs -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Recent Collections -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
                <div class="p-6 border-b border-gray-100 dark:border-dark-border flex justify-between items-center">
                    <h2 class="text-lg font-bold dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        أحدث التحصيلات
                    </h2>
                    <a href="{{ route('customer.ledger', $customer) }}" class="text-blue-500 hover:text-blue-700 text-sm font-bold">عرض كشف الحساب كاملاً &larr;</a>
                </div>
                @if ($customer->collections->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-gray-50 dark:bg-dark-tableheader">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">رقم الإيصال</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">المبلغ</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">النوع</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                                @foreach ($customer->collections->take(5) as $collection)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                                        <td class="px-6 py-4 dark:text-gray-300 font-medium tracking-wider">
                                            <a href="{{ route('collections.show', $collection) }}" class="text-blue-500 hover:underline">
                                                #{{ $collection->receipt_no }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($collection->amount, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-bold rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                {{ $collection->payment_type === 'cash' ? 'نقدي' : 'شيك' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-sm">{{ $collection->collection_date->format('Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-gray-400">لا توجد تحصيلات مسجلة بعد.</div>
                @endif
            </div>

            <!-- Due Installments -->
            @php $dueInstallments = $customer->due_installments; @endphp
            @if ($dueInstallments->count() > 0)
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-red-100 dark:border-red-900/30">
                <div class="p-6 border-b border-red-50 dark:border-red-900/20 flex justify-between items-center bg-red-50/50 dark:bg-red-900/10">
                    <h2 class="text-lg font-bold text-red-700 dark:text-red-400 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        أقساط مستحقة السداد
                    </h2>
                    <span class="px-3 py-1 bg-red-600 text-white text-xs font-black rounded-full shadow-lg shadow-red-500/30 animate-pulse">
                        {{ $dueInstallments->count() }} متأخر
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right">
                        <thead class="bg-gray-50 dark:bg-dark-tableheader">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">تاريخ الاستحقاق</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">رقم الفاتورة</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">المبلغ</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">أيام التأخير</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                            @foreach ($dueInstallments as $installment)
                                <tr class="hover:bg-red-50/30 dark:hover:bg-red-900/5 transition-colors">
                                    <td class="px-6 py-4 font-bold text-red-600 dark:text-red-400 text-sm">
                                        {{ $installment->due_date->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('installments.show', $installment->installment_plan_id) }}" class="text-blue-500 hover:underline font-medium">
                                            #{{ $installment->installmentPlan->invoice_no }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 font-black dark:text-white">{{ number_format($installment->amount, 2) }}</td>
                                    <td class="px-6 py-4">
                                        @php $days = now()->diffInDays($installment->due_date, false); @endphp
                                        <span class="text-xs font-bold text-red-500">
                                            {{ abs($days) }} يوم
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Installment Plans -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
                <div class="p-6 border-b border-gray-100 dark:border-dark-border flex justify-between items-center">
                    <h2 class="text-lg font-bold dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        خطط الأقساط النشطة
                    </h2>
                    <a href="{{ route('installments.create', ['customer_id' => $customer->id]) }}" class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-3 py-1 rounded-lg text-xs font-bold hover:bg-indigo-600 hover:text-white transition-all">+ خطة جديدة</a>
                </div>
                @if ($customer->installmentPlans->count() > 0)
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($customer->installmentPlans as $plan)
                            <div class="p-4 rounded-2xl border border-gray-100 dark:border-dark-border bg-gray-50/50 dark:bg-dark-bg/20 relative overflow-hidden group">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <a href="{{ route('installments.show', $plan) }}" class="text-sm font-black dark:text-white hover:text-indigo-500 flex items-center gap-1 group-hover:translate-x-1 duration-300">
                                            فاتورة #{{ $plan->invoice_no }}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                        <p class="text-[10px] text-gray-500 mt-0.5">بدأت في {{ $plan->start_date->format('Y-m-d') }}</p>
                                    </div>
                                    <span class="px-2 py-0.5 text-[10px] font-black rounded {{ $plan->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }} uppercase">
                                        {{ $plan->status === 'active' ? 'نشطة' : 'مغلقة' }}
                                    </span>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-gray-500">المدفوع:</span>
                                        <span class="font-bold text-emerald-600 tracking-wider">{{ number_format($plan->paid_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-gray-500">المتبقي:</span>
                                        <span class="font-bold text-rose-600 tracking-wider">{{ number_format($plan->remaining_amount, 2) }}</span>
                                    </div>
                                    <!-- Progress Bar -->
                                    <div class="mt-4">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">اكتمال السداد</span>
                                            <span class="text-[10px] font-black text-indigo-500 tracking-widest">{{ $plan->progress_percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-indigo-500 h-full transition-all duration-1000 ease-out" style="width: {{ $plan->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-gray-400">لا توجد خطط أقساط مسجلة.</div>
                @endif
            </div>

            <!-- Recent Cheques -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
                <div class="p-6 border-b border-gray-100 dark:border-dark-border">
                    <h2 class="text-lg font-bold dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        الشيكات الأخيرة
                    </h2>
                </div>
                @if ($customer->cheques->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-gray-50 dark:bg-dark-tableheader">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">رقم الشيك</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">المبلغ</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">البنك</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">الحالة</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                                @foreach ($customer->cheques->take(5) as $cheque)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                                        <td class="px-6 py-4 dark:text-gray-300 font-medium tracking-wider">
                                            <a href="{{ route('cheques.show', $cheque) }}" class="text-amber-600 hover:underline">
                                                #{{ $cheque->cheque_no }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 font-bold dark:text-white">{{ number_format($cheque->amount, 2) }}</td>
                                        <td class="px-6 py-4 dark:text-gray-300">{{ $cheque->bank_name }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-bold rounded-lg
                                                {{ $cheque->status == 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                                   ($cheque->status == 'cleared' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                                                {{ $cheque->status == 'pending' ? 'معلق' : ($cheque->status == 'cleared' ? 'محصل' : 'مرفوض') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-gray-400">لا توجد شيكات مسجلة بعد.</div>
                @endif
            </div>

            <!-- Customer Issues -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
                <div class="p-6 border-b border-gray-100 dark:border-dark-border">
                    <h2 class="text-lg font-bold dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        المشكلات والشكاوى
                    </h2>
                </div>
                @if ($customer->issues->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-gray-50 dark:bg-dark-tableheader">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">المشكلة</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">بواسطة</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">الحالة</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                                @foreach ($customer->issues->sortByDesc('created_at') as $issue)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('issues.show', $issue) }}" class="text-rose-600 hover:underline">
                                                <p class="text-sm font-medium truncate max-w-xs">{{ $issue->description }}</p>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-sm dark:text-gray-400">{{ $issue->collector->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-[10px] font-black uppercase tracking-widest border {{ $issue->status_color }}">
                                                {{ $issue->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-[10px] font-bold">{{ $issue->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-gray-400">لا توجد مشكلات مسجلة لهذا العميل.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
