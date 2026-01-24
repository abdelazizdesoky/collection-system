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
                                        <td class="px-6 py-4 dark:text-gray-300 font-medium">#{{ $collection->receipt_no }}</td>
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
                                        <td class="px-6 py-4 dark:text-gray-300 font-medium">#{{ $cheque->cheque_no }}</td>
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
        </div>
    </div>
</div>
@endsection
