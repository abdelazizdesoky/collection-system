@extends('layouts.app')

@section('title', 'نظام التقسيط')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8 text-right">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="bg-amber-600 p-3 rounded-2xl shadow-lg shadow-amber-500/30 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black dark:text-white tracking-tight">إدارة خطط التقسيط</h1>
                    <p class="text-gray-500 dark:text-gray-400 font-medium tracking-wide">مراقبة التسهيلات المالية وجداول السداد الشهرية</p>
                </div>
            </div>
            
            <a href="{{ route('installments.create') }}" class="w-full lg:w-auto flex items-center justify-center gap-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-black py-4 px-8 rounded-2xl shadow-xl shadow-amber-500/30 transition-all transform hover:scale-105 active:scale-95 text-sm uppercase tracking-widest whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                بدء خطة تقسيط جديدة
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-3xl flex items-center gap-3 animate-pulse">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-2xl overflow-hidden border border-gray-50 dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-dark-tableheader/50 text-gray-500 dark:text-gray-400">
                        <th class="px-6 py-6 text-xs font-black uppercase tracking-widest">العميل والمبيعات</th>
                        <th class="px-6 py-6 text-xs font-black text-center uppercase tracking-widest">تفاصيل التمويل</th>
                        <th class="px-6 py-6 text-xs font-black text-center uppercase tracking-widest">المدة والأقساط</th>
                        <th class="px-6 py-6 text-xs font-black text-center uppercase tracking-widest">حالة السداد</th>
                        <th class="px-6 py-6 text-xs font-black text-left uppercase tracking-widest">التحكم</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @forelse($plans as $plan)
                        <tr class="hover:bg-amber-50/30 dark:hover:bg-slate-700/30 transition-all group">
                            <td class="px-6 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400 font-black text-sm shadow-inner group-hover:scale-110 transition-transform">
                                        {{ mb_substr($plan->customer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-black text-gray-900 dark:text-white text-lg group-hover:text-amber-600 transition-colors uppercase">{{ $plan->customer->name }}</div>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-[10px] bg-gray-100 dark:bg-dark-bg text-gray-500 px-2 py-0.5 rounded font-bold uppercase tracking-tighter">INV #{{ $plan->invoice_no ?? 'N/A' }}</span>
                                            <span class="text-xs text-gray-400 font-medium italic">بداية: {{ $plan->start_date->format('Y-M-d') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 text-center whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-400 decoration-red-500 line-through mb-0.5">{{ number_format($plan->total_amount, 0) }}</div>
                                <div class="text-xl font-black text-amber-600 dark:text-amber-400 tabular-nums">
                                    {{ number_format($plan->financed_amount, 2) }}
                                    <span class="text-[10px] text-gray-400 font-medium mr-1 uppercase">ج.م</span>
                                </div>
                                <div class="text-[10px] text-emerald-500 font-black uppercase mt-1 tracking-widest">+{{ $plan->increase_percentage }}% فائدة</div>
                            </td>
                            <td class="px-6 py-6 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1 bg-gray-50 dark:bg-dark-bg/50 px-3 py-1 rounded-full border border-gray-100 dark:border-dark-border mb-1.5">
                                    <span class="text-sm font-black text-gray-700 dark:text-gray-300">{{ $plan->duration_months }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">شهر</span>
                                </div>
                                <div class="text-xs font-black text-indigo-600 dark:text-indigo-400">
                                    {{ number_format($plan->monthly_amount, 2) }} / شهر
                                </div>
                            </td>
                            <td class="px-6 py-6 text-center">
                                @php $progress = $plan->progress_percentage; @endphp
                                <div class="flex flex-col items-center gap-2">
                                    <span class="px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm
                                        {{ $plan->status === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-900/30' : 'bg-gray-100 text-gray-600 dark:bg-dark-bg' }}">
                                        {{ $plan->status === 'active' ? 'جارية' : 'مكتملة' }}
                                    </span>
                                    <div class="w-32 h-1.5 bg-gray-100 dark:bg-dark-bg rounded-full overflow-hidden border border-gray-200/50 dark:border-dark-border">
                                        <div class="h-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)] transition-all duration-1000" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-black">{{ $progress }}% مكتمل</span>
                                </div>
                            </td>
                            <td class="px-6 py-6 text-left">
                                <a href="{{ route('installments.show', $plan) }}" class="inline-flex items-center justify-center p-3 bg-gray-50 dark:bg-dark-bg/50 text-gray-500 dark:text-gray-400 hover:bg-amber-600 hover:text-white transition-all rounded-2xl border border-gray-100 dark:border-dark-border shadow-sm group-hover:shadow-lg group-hover:border-amber-500/50">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-32 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="bg-gray-50 dark:bg-dark-bg/50 p-6 rounded-full">
                                        <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="text-gray-400 font-bold text-xl tracking-wide italic">لا توجد خطط تقسيظ مسجلة حالياً</div>
                                    <a href="{{ route('installments.create') }}" class="mt-2 text-amber-600 font-black hover:underline tracking-widest text-sm uppercase">أنشئ خطتك الأولى الآن +</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10">
        {{ $plans->links() }}
    </div>
</div>
@endsection
