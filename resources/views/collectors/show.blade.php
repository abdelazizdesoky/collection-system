@extends('layouts.app')

@section('title', 'ملف المندوب - ' . $collector->name)

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4 text-right w-full">
            <a href="{{ route('collectors.index') }}" 
               class="bg-white dark:bg-dark-card p-3 rounded-xl shadow-md border border-gray-100 dark:border-dark-border text-gray-500 hover:text-gray-700 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-black dark:text-white tracking-tight">{{ $collector->name }}</h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium">ملف المندوب وإحصائيات الأداء</p>
            </div>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('collectors.edit', $collector) }}" 
               class="flex-grow md:flex-none bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                تعديل البيانات
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Collector Info Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-dark-border">
                <h2 class="text-lg font-bold dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    المعلومات الشخصية
                </h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                            <p class="text-xs text-gray-500 mb-1">الكود</p>
                            <p class="font-bold dark:text-white text-lg font-mono">{{ $collector->code ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                            <p class="text-xs text-gray-500 mb-1">المستخدم المرتبط</p>
                            <p class="font-medium dark:text-gray-300 truncate" title="{{ $collector->user?->name }}">{{ $collector->user?->name ?? 'غير محدد' }}</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                        <p class="text-xs text-gray-500 mb-1">رقم الهاتف</p>
                        <p class="font-bold dark:text-white text-lg" dir="ltr">{{ $collector->phone }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                        <p class="text-xs text-gray-500 mb-1">المنطقة</p>
                        <p class="font-medium dark:text-gray-300">{{ $collector->area }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl shadow-xl p-6 text-white overflow-hidden relative">
                <div class="relative z-10">
                    <h2 class="text-lg font-bold mb-4 opacity-90">ملخص الأداء</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-end border-b border-white/10 pb-4">
                            <div>
                                <p class="text-xs opacity-75 mb-1">إجمالي التحصيلات</p>
                                <p class="text-3xl font-black">{{ number_format($collector->collections->sum('amount'), 2) }}</p>
                            </div>
                            <span class="text-sm opacity-90">ج.م</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-2">
                            <div>
                                <p class="text-xs opacity-75">عدد العمليات</p>
                                <p class="text-xl font-bold">{{ $collector->collections->count() }}</p>
                            </div>
                            <div>
                                <p class="text-xs opacity-75">خطط التحصيل</p>
                                <p class="text-xl font-bold">{{ $collector->collectionPlans->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Abstract BG Icon -->
                <svg class="absolute -bottom-6 -left-6 w-32 h-32 opacity-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        أحدث التحصيلات
                    </h2>
                </div>
                @if ($collector->collections->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-gray-50 dark:bg-dark-tableheader">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">رقم الإيصال</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">العميل</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">المبلغ</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">النوع</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                                @foreach ($collector->collections->take(5) as $collection)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                                        <td class="px-6 py-4 dark:text-gray-300 font-medium font-mono text-sm">{{ $collection->receipt_no }}</td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('customers.show', $collection->customer) }}" class="font-bold text-gray-700 dark:text-gray-200 hover:text-indigo-600 transition-colors">
                                                {{ $collection->customer->name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($collection->amount, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-bold rounded-lg 
                                                {{ $collection->payment_type == 'cash' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                                   ($collection->payment_type == 'cheque' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400') }}">
                                                {{ $collection->payment_type === 'cash' ? 'نقدي' : ($collection->payment_type === 'cheque' ? 'شيك' : 'تحويل') }}
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

            <!-- Recent Plans -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
                <div class="p-6 border-b border-gray-100 dark:border-dark-border">
                    <h2 class="text-lg font-bold dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        أحدث خطط التحصيل
                    </h2>
                </div>
                @if ($collector->collectionPlans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-gray-50 dark:bg-dark-tableheader">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">النوع</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">عدد العملاء</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">المستهدف</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">الإجراء</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                                @foreach ($collector->collectionPlans->take(5) as $plan)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                                        <td class="px-6 py-4 dark:text-gray-300 font-medium">{{ $plan->date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 dark:text-gray-300">{{ $plan->type ?? 'عام' }}</td>
                                        <td class="px-6 py-4 dark:text-gray-300">{{ $plan->items->count() }}</td>
                                        <td class="px-6 py-4 font-bold text-gray-700 dark:text-gray-300">{{ number_format($plan->getTotalExpectedAmount(), 2) }}</td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('collection-plans.show', $plan) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-bold">عرض</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-gray-400">لا توجد خطط مسجلة بعد.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
