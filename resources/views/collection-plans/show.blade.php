@extends('layouts.app')

@section('title', 'تفاصيل خطة التحصيل')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-500/30 text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-black dark:text-white tracking-tight">خطة تحصيل: {{ $collectionPlan->date->format('Y-m-d') }}</h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium">عرض تفاصيل الخطة والمستهدفات الخاصة بها</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 w-full lg:w-auto">
            <a href="{{ route('collection-plans.edit', $collectionPlan) }}" class="flex-grow lg:flex-none flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-amber-500/20 transition-all text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                تعديل الخطة
            </a>
            <a href="{{ route('collection-plan-items.create', ['collection_plan_id' => $collectionPlan->id]) }}" class="flex-grow lg:flex-none flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-500/20 transition-all text-sm whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                إضافة عميل للخطة
            </a>
            <a href="{{ route('collection-plans.index') }}" class="flex-grow lg:flex-none flex items-center justify-center gap-2 bg-gray-100 dark:bg-dark-card text-gray-700 dark:text-white font-bold py-3 px-6 rounded-xl transition-all text-sm">
                العودة للقائمة
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Collector Card -->
        <div class="bg-white dark:bg-dark-card p-6 rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <span class="text-gray-500 dark:text-gray-400 font-bold">المندوب</span>
            </div>
            <a href="{{ route('collectors.show', $collectionPlan->collector) }}" class="text-xl font-black dark:text-white hover:text-blue-600 transition-colors">
                {{ $collectionPlan->collector->name }}
            </a>
        </div>

        <!-- Expected Amount Card -->
        <div class="bg-white dark:bg-dark-card p-6 rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-gray-500 dark:text-gray-400 font-bold">المبلغ المستهدف</span>
            </div>
            <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                <span class="text-sm font-normal ml-1">ج.م</span>
                {{ number_format($collectionPlan->getTotalExpectedAmount(), 2) }}
            </div>
        </div>

        <!-- Collection Progress Card -->
        <div class="bg-white dark:bg-dark-card p-6 rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <span class="text-gray-500 dark:text-gray-400 font-bold">نسبة التحصيل الفعلي</span>
                </div>
                @php
                    $collected = $collectionPlan->getActualCollectedAmount();
                    $expected = $collectionPlan->getTotalExpectedAmount();
                    $percentage = $expected > 0 ? ($collected / $expected) * 100 : 0;
                @endphp
                <span class="text-2xl font-black dark:text-white">{{ round($percentage) }}%</span>
            </div>
            <div class="w-full bg-gray-100 dark:bg-dark-bg/50 rounded-full h-4 overflow-hidden shadow-inner">
                <div class="bg-gradient-to-r from-indigo-500 to-blue-500 h-full rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
            </div>
            <div class="flex justify-between mt-2 text-xs font-bold text-gray-400 uppercase">
                <span>تم تحصيل: {{ number_format($collected, 2) }}</span>
                <span>المتبقي: {{ number_format($expected - $collected, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Items Table Card -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="p-6 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-gray-50/50 dark:bg-dark-tableheader/50">
            <h2 class="text-xl font-black dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                قائمة عملاء الخطة
            </h2>
            <span class="px-4 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-sm font-black">
                {{ $collectionPlan->items->count() }} عملاء
            </span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader text-gray-600 dark:text-gray-300">
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap text-center">أولوية</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">العميل</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">المبلغ المتوقع</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">المبلغ المندوب</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الحالة</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @forelse ($collectionPlan->items->sortBy('priority') as $item)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-colors group">
                            <td class="px-6 py-5 text-center">
                                <span class="w-8 h-8 inline-flex items-center justify-center bg-gray-100 dark:bg-dark-bg/50 rounded-lg font-black text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-dark-border">
                                    {{ $item->priority }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <a href="{{ route('customers.show', $item->customer) }}" class="font-bold dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $item->customer->name }}
                                </a>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap font-bold text-gray-600 dark:text-gray-300">
                                {{ number_format($item->expected_amount, 2) }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap font-black text-emerald-600 dark:text-emerald-400">
                                {{ number_format($item->collected_amount ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($item->is_collected)
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 rounded-lg text-xs font-black flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        تم التحصيل
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 dark:bg-dark-bg/50 dark:text-gray-400 rounded-lg text-xs font-black">
                                        قيد الانتظار
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('collection-plan-items.edit', $item) }}" class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white transition-all shadow-sm" title="تعديل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('collection-plan-items.destroy', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('هل أنت متأكد من حذف هذا المسار؟')" title="حذف">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 opacity-10 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <p class="text-xl font-medium">لم يتم إضافة أي مسارات لهذه الخطة.</p>
                                    <a href="{{ route('collection-plan-items.create', ['collection_plan_id' => $collectionPlan->id]) }}" class="mt-4 text-blue-500 hover:underline font-bold">+ أضف العميل الأول الآن</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
