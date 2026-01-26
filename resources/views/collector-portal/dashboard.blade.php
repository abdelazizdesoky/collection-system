@extends('layouts.collector')

@section('title', 'خطط اليوم - بوابة المندوب')

@section('content')
<div class="max-w-4xl mx-auto text-right" dir="rtl">
    <!-- Welcome Header -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 mb-8 border border-gray-100 dark:border-dark-border">
        <div class="flex items-center gap-4">
            <div class="bg-emerald-100 dark:bg-emerald-900/30 p-4 rounded-full">
                <svg class="w-12 h-12 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="5" y="2" width="14" height="20" rx="3" stroke="currentColor" stroke-width="2"/>
                    <rect x="8" y="5" width="8" height="6" rx="1" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M8 14H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M8 17H10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M15 17L15 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">مرحبا، {{ $collector->name }}</h1>
                <p class="text-gray-500 dark:text-gray-400">خطط التحصيل الخاصة بك اليوم بتاريخ {{ today()->format('Y/m/d') }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex gap-4 mb-6">
        <a href="{{ route('collector.dashboard') }}" class="flex-1 text-center py-3 px-4 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl font-bold text-white shadow-lg shadow-emerald-500/20">
            <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            التحصيل
        </a>
        <a href="{{ route('collector.visits') }}" class="flex-1 text-center py-3 px-4 bg-white dark:bg-dark-card rounded-xl font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all border border-gray-200 dark:border-dark-border">
            <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            </svg>
            الزيارات
        </a>
    </div>

    <!-- Plans Grid -->
    @if($todayPlans->count() > 0)
        <div class="grid gap-6">
            @foreach($todayPlans as $plan)
                <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg hover:shadow-xl transition-shadow overflow-hidden border border-gray-100 dark:border-dark-border">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">{{ $plan->name }}</h2>
                        <p class="text-emerald-100 text-sm">{{ ($plan->collection_type ?? 'regular') === 'regular' ? 'تحصيل عادي' : 'تحصيل خاص' }}</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                            <div class="bg-gray-50 dark:bg-slate-700/50 rounded-xl p-4 text-center border border-gray-100 dark:border-slate-700">
                                <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $plan->total_customers }}</div>
                                <div class="text-xs text-gray-500 dark:text-slate-400">إجمالي العملاء</div>
                            </div>
                            <div class="bg-orange-50 dark:bg-orange-900/20 rounded-xl p-4 text-center border border-orange-100 dark:border-slate-700">
                                <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $plan->pending_count }}</div>
                                <div class="text-xs text-orange-500 dark:text-orange-300">معلق</div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 text-center border border-green-100 dark:border-slate-700">
                                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $plan->collected_count }}</div>
                                <div class="text-xs text-green-500 dark:text-green-300">تم التحصيل</div>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 text-center border border-blue-100 dark:border-slate-700">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 text-sm md:text-2xl">{{ number_format($plan->total_expected, 2) }}</div>
                                <div class="text-xs text-blue-500 dark:text-blue-300">المتوقع (ج.م)</div>
                            </div>
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4 text-center border border-emerald-100 dark:border-slate -700">
                                <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 text-sm md:text-2xl">{{ number_format($plan->total_collected, 2) }}</div>
                                <div class="text-xs text-emerald-500 dark:text-emerald-300">المندوب (ج.م)</div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-6">
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <span>نسبة الإنجاز</span>
                                <span>{{ $plan->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-3">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-3 rounded-full transition-all" 
                                     style="width: {{ $plan->progress_percentage }}%"></div>
                            </div>
                        </div>

                        <!-- Enter Button -->
                        <a href="{{ route('collector.plan', $plan) }}" 
                           class="block w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white text-center py-4 rounded-xl font-bold text-lg transition-all transform hover:scale-[1.02]">
                            <svg class="w-6 h-6 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                            دخول الخطة
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- No Plans -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-12 text-center text-right border border-gray-100 dark:border-dark-border" dir="rtl">
            <div class="bg-gray-100 dark:bg-gray-800 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200 mb-2">لا توجد خطط لليوم</h2>
            <p class="text-gray-500 dark:text-gray-400">لم يتم تعيين أي خطط تحصيل لك اليوم. يرجى التواصل مع الإدارة.</p>
        </div>
    @endif
</div>
@endsection
