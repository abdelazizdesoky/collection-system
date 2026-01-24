@extends('layouts.app')

@section('title', 'لوحة تحكم المحصل')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl shadow-xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-md border border-white/10">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <h1 class="text-3xl font-black mb-1 tracking-tight">مرحباً، {{ $collector->name }}</h1>
                    <p class="text-blue-100 text-lg opacity-90 font-medium">بوابة التحصيل الميداني - تابع إنجازاتك اليومية هنا</p>
                </div>
            </div>
            <div class="flex gap-4 w-full md:w-auto">
                <a href="{{ route('collections.create') }}" class="flex-1 md:flex-none bg-white text-blue-600 hover:bg-blue-50 px-8 py-4 rounded-2xl font-black transition-all transform hover:scale-105 shadow-xl flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    تسجيل تحصيل خارجي
                </a>
            </div>
        </div>
        <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -left-16 -top-16 w-48 h-48 bg-blue-400/10 rounded-full blur-2xl"></div>
    </div>

    <!-- Plans Content Flow -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- Collection Plans -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-2xl border border-gray-100 dark:border-dark-border overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-blue-50/30 dark:bg-blue-900/10">
                <h2 class="text-xl font-black text-blue-700 dark:text-blue-400 flex items-center gap-3">
                    <div class="p-2 bg-blue-600 rounded-lg shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    خطط التحصيل الحالية
                </h2>
            </div>

            <div class="p-6 space-y-4 max-h-[500px] overflow-y-auto custom-scrollbar">
                @forelse($activeCollectionPlans as $plan)
                    <div class="bg-gray-50 dark:bg-dark-bg/40 rounded-2xl p-5 border border-gray-100 dark:border-dark-border group hover:border-blue-500/30 transition-all">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-black text-gray-900 dark:text-white mb-1 group-hover:text-blue-600 transition-colors">{{ $plan->name }}</h3>
                                <p class="text-xs text-gray-400">تاريخ الخطة: {{ $plan->date->format('Y-m-d') }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $plan->status === 'closed' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $plan->status === 'closed' ? 'مكتملة' : 'قيد التنفيذ' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-gray-500 dark:text-gray-400">نسبة التحصيل:</span>
                            <span class="font-black text-blue-600">{{ $plan->getProgressPercentage() }}%</span>
                        </div>
                        <div class="h-2 bg-gray-200 dark:bg-dark-border rounded-full overflow-hidden mb-5">
                            <div class="h-full bg-blue-600 rounded-full" style="width: {{ $plan->getProgressPercentage() }}%"></div>
                        </div>

                        <a href="{{ route('collector.plan', $plan) }}" class="w-full flex items-center justify-center gap-2 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black text-sm transition-all shadow-lg shadow-blue-600/20 active:scale-95">
                            بدء التحصيل الآن
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <div class="bg-gray-100 dark:bg-dark-bg/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 opacity-50">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-gray-400 italic">لا توجد خطط تحصيل مسندة لك حالياً.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Visit Plans -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-2xl border border-gray-100 dark:border-dark-border overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-indigo-50/30 dark:bg-indigo-900/10">
                <h2 class="text-xl font-black text-indigo-700 dark:text-indigo-400 flex items-center gap-3">
                    <div class="p-2 bg-indigo-600 rounded-lg shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    </div>
                    خطط الزيارات الميدانية
                </h2>
            </div>

            <div class="p-6 space-y-4 max-h-[500px] overflow-y-auto custom-scrollbar">
                @forelse($activeVisitPlans as $plan)
                    <div class="bg-gray-50 dark:bg-dark-bg/40 rounded-2xl p-5 border border-gray-100 dark:border-dark-border group hover:border-indigo-500/30 transition-all">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-black text-gray-900 dark:text-white mb-1 group-hover:text-indigo-600 transition-colors">{{ $plan->name }}</h3>
                                <p class="text-xs text-gray-400">تاريخ البدء: {{ $plan->start_date->format('Y-m-d') }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $plan->status === 'closed' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ $plan->status === 'closed' ? 'مكتملة' : 'قيد الزيارة' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-gray-500 dark:text-gray-400">عدد العملاء: {{ $plan->items->count() }}</span>
                            <span class="font-black text-indigo-600">{{ $plan->getProgressPercentage() }}%</span>
                        </div>
                        <div class="h-2 bg-gray-200 dark:bg-dark-border rounded-full overflow-hidden mb-5">
                            <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $plan->getProgressPercentage() }}%"></div>
                        </div>

                        <a href="{{ route('collector.visit-plan', $plan) }}" class="w-full flex items-center justify-center gap-2 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black text-sm transition-all shadow-lg shadow-indigo-600/20 active:scale-95">
                            فتح ملف الزيارة
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <div class="bg-gray-100 dark:bg-dark-bg/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 opacity-50">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <p class="text-gray-400 italic">لا توجد خطط زيارات نشطة حالياً.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Bottom Activity -->
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 dark:border-dark-border bg-gray-50/50 flex justify-between items-center">
            <h2 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2 font- Cairo">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/></svg>
                آخر تحصيلاتك اليوم
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @forelse($recentCollections as $collection)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors group">
                            <td class="px-8 py-5">
                                <span class="font-bold block text-gray-800 dark:text-white">{{ $collection->customer->name }}</span>
                                <span class="text-[10px] text-gray-400 font-bold">#{{ $collection->receipt_no }}</span>
                            </td>
                            <td class="px-8 py-5 text-center font-black text-emerald-600 dark:text-emerald-400">
                                {{ number_format($collection->amount, 2) }} ج.م
                            </td>
                            <td class="px-8 py-5 text-left text-xs text-gray-400">{{ $collection->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-8 py-10 text-center text-gray-400 italic">لا توجد عمليات مؤكدة اليوم.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
</style>
@endsection
