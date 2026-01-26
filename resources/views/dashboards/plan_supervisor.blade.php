@extends('layouts.app')

@section('title', 'لوحة التحكم - مشرف الخطط')

@section('content')
<div class="max-w-7xl mx-auto text-right" dir="rtl">
    <!-- Welcome Header Card -->
    <div class="bg-gradient-to-r from-indigo-600 to-blue-700 rounded-2xl shadow-xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-sm">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black mb-1 flex items-center gap-3">
                        مرحباً، {{ auth()->user()->name }}
                        <span class="text-xs bg-white/20 px-3 py-1 rounded-full font-bold uppercase tracking-widest animate-pulse border border-white/10">مشرف الخطط</span>
                    </h1>
                    <p class="text-indigo-100 text-lg opacity-90 font-medium italic">لوحة تحكم مشرف الخطط - إدارة وتوجيه العمليات الميدانية</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('collection-plans.create') }}" class="bg-white text-indigo-600 hover:bg-indigo-50 px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    خطة تحصيل جديدة
                </a>
                <a href="{{ route('visit-plans.create') }}" class="bg-indigo-800 text-white hover:bg-indigo-900 px-6 py-3 rounded-xl font-bold border border-indigo-400/30 transition-all transform hover:scale-105 shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    خطة زيارات جديدة
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Planning Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Collection Plans -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-2xl shadow-indigo-500/10 border border-indigo-50 dark:border-dark-border p-7 hover:border-indigo-500/50 hover:-translate-y-1.5 transition-all duration-500 group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-indigo-50 dark:bg-indigo-900/40 p-5 rounded-3xl text-indigo-600 dark:text-indigo-400 mb-5 group-hover:scale-110 transition-transform shadow-inner">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div class="text-4xl font-black text-gray-800 dark:text-white mb-1 tracking-tighter">{{ $totalCollectionPlans }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wide">خطط التحصيل</div>
                <a href="{{ route('collection-plans.index') }}" class="mt-4 px-4 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg font-bold text-xs hover:bg-indigo-600 hover:text-white transition-colors">عرض الكل</a>
            </div>
        </div>

        <!-- Total Visit Plans -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-2xl shadow-blue-500/10 border border-blue-50 dark:border-dark-border p-7 hover:border-blue-500/50 hover:-translate-y-1.5 transition-all duration-500 group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-blue-50 dark:bg-blue-900/40 p-5 rounded-3xl text-blue-600 dark:text-blue-400 mb-5 group-hover:scale-110 transition-transform shadow-inner">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <div class="text-4xl font-black text-gray-800 dark:text-white mb-1 tracking-tighter">{{ $totalVisitPlans }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wide">خطط الزيارات</div>
                <a href="{{ route('visit-plans.index') }}" class="mt-4 px-4 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg font-bold text-xs hover:bg-blue-600 hover:text-white transition-colors">إدارة الزيارات</a>
            </div>
        </div>

        <!-- Total Collectors (Logistics View) -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-2xl shadow-emerald-500/10 border border-emerald-50 dark:border-dark-border p-7 hover:border-emerald-500/50 hover:-translate-y-1.5 transition-all duration-500 group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-emerald-50 dark:bg-emerald-900/40 p-5 rounded-3xl text-emerald-600 dark:text-emerald-400 mb-5 group-hover:scale-110 transition-transform shadow-inner">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="text-4xl font-black text-gray-800 dark:text-white mb-1 tracking-tighter">{{ $totalCollectors }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wide">المندوبون المتاحون</div>
                <div class="mt-4 px-4 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg font-bold text-xs">قيد التشغيل</div>
            </div>
        </div>

        <!-- Visit Types (Optimization) -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-2xl shadow-amber-500/10 border border-amber-50 dark:border-dark-border p-7 hover:border-amber-500/50 hover:-translate-y-1.5 transition-all duration-500 group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-amber-50 dark:bg-amber-900/40 p-5 rounded-3xl text-amber-600 dark:text-amber-400 mb-5 group-hover:scale-110 transition-transform shadow-inner">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 leading-relaxed">تحسين التوزيع</div>
                <div class="text-xl font-bold dark:text-white">إدارة المهام</div>
                <a href="{{ route('visit-types.index') }}" class="mt-4 px-4 py-1.5 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg font-bold text-xs hover:bg-amber-600 hover:text-white transition-colors">تخصيص الأنواع</a>
            </div>
        </div>

        <!-- Due Installments -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-2xl shadow-indigo-500/10 border border-indigo-50 dark:border-dark-border p-7 hover:border-indigo-500/50 hover:-translate-y-1.5 transition-all duration-500 group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-indigo-50 dark:bg-indigo-900/40 p-5 rounded-3xl text-indigo-600 dark:text-indigo-400 mb-5 group-hover:scale-110 transition-transform shadow-inner relative">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @if($dueInstallmentsCount > 0)
                        <span class="absolute top-0 right-0 w-4 h-4 bg-rose-500 rounded-full animate-ping border-4 border-white dark:border-dark-card"></span>
                    @endif
                </div>
                <div class="text-4xl font-black text-gray-800 dark:text-white mb-1 tracking-tighter">{{ $dueInstallmentsCount }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wide">أقساط مستحقة</div>
                <a href="{{ route('installments.index') }}" class="mt-4 px-4 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg font-bold text-xs hover:bg-indigo-600 hover:text-white transition-colors">متابعة الأقساط</a>
            </div>
        </div>
    </div>

    <!-- Active Plans Section -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-gray-100 dark:border-dark-border overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-gray-50/50 dark:bg-dark-tableheader/50">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-600 p-2 rounded-xl text-white shadow-lg shadow-indigo-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h2 class="text-xl font-black dark:text-white">خطط التحصيل الجارية</h2>
            </div>
            <a href="{{ route('collection-plans.create') }}" class="text-xs font-black text-indigo-600 dark:text-indigo-400 hover:underline">إضافة خطة جديدة +</a>
        </div>

        @forelse($activePlans as $plan)
            <div class="p-6 border-b border-gray-50 dark:border-dark-border last:border-0 hover:bg-gray-50/50 dark:hover:bg-dark-bg/20 transition-all">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ $plan->name }}</h3>
                            <span class="px-2.5 py-0.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-black border border-indigo-100 dark:border-indigo-800">
                                {{ $plan->collector->name }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">تاريخ الإنشاء: {{ $plan->created_at->format('Y-m-d') }}</p>
                    </div>
                    
                    <div class="w-full lg:w-72">
                        @php $progress = $plan->getProgressPercentage(); @endphp
                        <div class="flex justify-between items-center text-[10px] font-black mb-1.5">
                            <span class="text-indigo-600 dark:text-indigo-400">{{ $progress }}% مكتمل</span>
                            <span class="text-gray-400">{{ $plan->items->whereNotNull('collection_id')->count() }} / {{ $plan->items->count() }} عميل</span>
                        </div>
                        <div class="h-1.5 bg-gray-100 dark:bg-dark-bg/50 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-600 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('collection-plans.show', $plan) }}" class="p-2 bg-gray-100 dark:bg-dark-bg/50 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('collection-plans.edit', $plan) }}" class="p-2 bg-gray-100 dark:bg-dark-bg/50 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-12 text-center text-gray-400 font-medium italic">لا توجد خطط نشطة حالياً</div>
        @endforelse
    </div>

    <!-- Active Visit Plans -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-gray-50/50 dark:bg-dark-tableheader/50">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 p-2 rounded-xl text-white shadow-lg shadow-blue-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <h2 class="text-xl font-black dark:text-white">أحدث خطط الزيارات</h2>
            </div>
            <a href="{{ route('visit-plans.create') }}" class="text-xs font-black text-blue-600 dark:text-blue-400 hover:underline">إضافة خطة زيارة +</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <tbody>
                    @forelse($visitPlans as $vPlan)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800 dark:text-gray-200">{{ $vPlan->name }}</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $vPlan->collector->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg text-xs font-black">
                                    {{ $vPlan->items->count() }} زيارة
                                </span>
                            </td>
                            <td class="px-6 py-4 text-left">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('visit-plans.show', $vPlan) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-400 italic">لا توجد خطط زيارات مسجلة مؤخراً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
