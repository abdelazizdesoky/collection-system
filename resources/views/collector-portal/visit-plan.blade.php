@extends('layouts.collector')

@section('title', $visitPlan->name . ' - بوابة المندوب')

@section('content')
<div class="max-w-4xl mx-auto text-right" dir="rtl">
    <!-- Header -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 mb-6 border border-gray-100 dark:border-dark-border">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('collector.visits') }}" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $visitPlan->name }}</h1>
                    <p class="text-gray-500 dark:text-gray-400">{{ $visitPlan->frequency_label }} • {{ $visitPlan->start_date->format('Y/m/d') }}</p>
                </div>
            </div>
            <span class="px-4 py-2 rounded-xl font-bold text-sm {{ $visitPlan->status_color }}">
                {{ $visitPlan->status_label }}
            </span>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $message }}
        </div>
    @endif

    @if ($message = Session::get('warning'))
        <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 text-yellow-700 dark:text-yellow-400 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            {{ $message }}
        </div>
    @endif

    <!-- Visit Type Legend -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-4 mb-6 border border-gray-100 dark:border-dark-border">
        <p class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">أنواع الزيارات:</p>
        <div class="flex flex-wrap gap-3">
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                تحصيل
            </span>
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                عمل أوردر
            </span>
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                حل مشكلة
            </span>
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                زيارة عامة
            </span>
        </div>
    </div>

    <!-- Customers List -->
    <div class="space-y-4">
        @forelse($visitPlan->items as $item)
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-dark-border {{ $item->status === 'visited' ? 'opacity-75' : '' }}">
                <div class="flex items-stretch">
                    <!-- Priority Badge -->
                    <div class="w-16 flex items-center justify-center {{ $item->status === 'visited' ? 'bg-green-500' : ($item->status === 'skipped' ? 'bg-red-500' : 'bg-purple-500') }} text-white">
                        <span class="text-2xl font-black">{{ $item->priority }}</span>
                    </div>
                    
                    <!-- Customer Info -->
                    <div class="flex-grow p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $item->customer->name }}</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $item->customer->phone }}</p>
                                @if($item->customer->address)
                                    <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">{{ Str::limit($item->customer->address, 50) }}</p>
                                @endif
                            </div>
                            <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $item->status_color }}">
                                {{ $item->status_label }}
                            </span>
                        </div>

                        @if($item->visit)
                            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-dark-border">
                                <div class="flex items-center gap-4 text-sm">
                                    <span class="px-2 py-1 rounded-lg text-xs font-bold {{ $item->visit->visit_type_color }}">
                                        {{ $item->visit->visit_type_label }}
                                    </span>
                                    <span class="text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $item->visit->visit_time->format('h:i A') }}
                                    </span>
                                    @if($item->visit->visit_type === 'collection' && $item->visit->collection)
                                        <span class="text-green-600 dark:text-green-400 font-bold">
                                            {{ number_format($item->visit->collection->amount, 2) }} ج.م
                                        </span>
                                    @endif
                                </div>
                                @if($item->visit->notes)
                                    <p class="text-gray-500 dark:text-gray-400 text-xs mt-2">{{ $item->visit->notes }}</p>
                                @endif
                            </div>
                        @endif

                        @if($item->status === 'pending')
                            <div class="mt-4">
                                <a href="{{ route('collector.visit', $item) }}" 
                                   class="inline-block w-full text-center bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 text-white py-3 rounded-xl font-bold transition-all">
                                    <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    تسجيل الزيارة
                                </a>
                            </div>
                        @elseif($item->status === 'visited' && $item->visit)
                            <div class="mt-4 flex flex-col sm:flex-row gap-3">
                                @if($item->visit->collection_id)
                                    <a href="{{ route('shared.receipt', $item->visit->collection_id) }}" 
                                       class="flex-1 bg-white dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 py-3 rounded-xl font-bold border-2 border-emerald-500/20 hover:border-emerald-500 transition-all text-center">
                                        <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                        طباعة الإيصال
                                    </a>
                                @endif
                                <a href="{{ route('visit.details', $item->visit) }}" 
                                   class="flex-1 bg-gray-50 dark:bg-slate-700/50 text-gray-600 dark:text-gray-300 py-3 rounded-xl font-bold border border-gray-200 dark:border-slate-600 hover:bg-gray-100 transition-all text-center">
                                    <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    عرض التفاصيل
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-12 text-center border border-gray-100 dark:border-dark-border">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 text-lg">لا يوجد عملاء في هذه الخطة</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
