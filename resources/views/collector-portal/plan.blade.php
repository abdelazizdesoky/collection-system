@extends('layouts.collector')

@section('title', $plan->name . ' - بوابة التحصيل')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button & Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('collector.dashboard') }}" 
           class="bg-white dark:bg-dark-card hover:bg-gray-50 dark:hover:bg-slate-700/50 p-3 rounded-xl shadow-md transition-colors border border-gray-100 dark:border-dark-border">
            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $plan->name }}</h1>
            <p class="text-gray-500 dark:text-gray-400">{{ $plan->items->count() }} عميل في هذه الخطة</p>
        </div>
    </div>

    <!-- Customer List -->
    <div class="space-y-4">
        @foreach($plan->items as $item)
            <div class="rounded-2xl shadow-lg overflow-hidden transition-all duration-200 {{ $item->status === 'collected' ? 'bg-emerald-50 dark:bg-emerald-900/10 border-2 border-emerald-400 dark:border-emerald-500/50 opacity-90' : 'bg-white dark:bg-dark-card hover:shadow-xl border border-gray-100 dark:border-dark-border' }}">
                <div class="flex items-center p-4 gap-4">
                    <!-- Status Icon -->
                    <div class="flex-shrink-0">
                        @if($item->status === 'collected')
                            <div class="bg-gray-100 dark:bg-slate-700/50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        @elseif($item->status === 'skipped')
                            <div class="bg-gray-100 dark:bg-slate-700/50 p-3 rounded-full">
                                <svg class="w-8 h-8 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                        @else
                            <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-full">
                                <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Customer Info -->
                    <div class="flex-grow">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white {{ $item->status === 'collected' ? 'text-gray-500 dark:text-slate-500 line-through' : '' }}">{{ $item->customer->name }}</h3>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-500 dark:text-slate-400 mt-1">
                            @if($item->customer->phone)
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $item->customer->phone }}
                                </span>
                            @endif
                            @if($item->customer->address)
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ Str::limit($item->customer->address, 30) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Amount & Action -->
                    <div class="flex-shrink-0 text-left">
                        @if($item->status === 'collected' && $item->collection)
                            <div class="text-sm text-gray-500 mb-1">تم التحصيل</div>
                            <div class="text-xl font-bold text-gray-600 mb-2">{{ number_format($item->collection->amount, 2) }} ج.م</div>
                        @else
                            <div class="text-sm text-gray-500 mb-1">المبلغ المتوقع</div>
                            <div class="text-xl font-bold text-emerald-600 mb-2">{{ number_format($item->expected_amount, 2) }} ج.م</div>
                        @endif
                        
                        @if($item->status === 'collected')
                            <span class="inline-flex items-center gap-1 bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                تم القفل
                            </span>
                        @elseif($item->status === 'pending')
                            <a href="{{ route('collector.collect', $item) }}" 
                               class="inline-flex items-center gap-1 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                تحصيل
                            </a>
                        @else
                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 px-4 py-2 rounded-lg text-sm">
                                تم تخطيه
                            </span>
                        @endif
                    </div>
                </div>

                @if($item->status === 'collected' && $item->collection)
                    <div class="bg-gray-50 dark:bg-slate-800 px-4 py-2 border-t border-gray-100 dark:border-slate-700 text-sm text-gray-600 dark:text-slate-400 flex justify-between items-center">
                        <span>
                            <span class="font-medium text-gray-900 dark:text-slate-200"></span> 
                            إيصال رقم :{{ $item->collection->receipt_no }}
                        </span>
                        <a href="{{ route('collector.receipt', $item->collection) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline font-bold">عرض الإيصال</a>
                    </div>
                @endif
        
            </div>
        @endforeach
    </div>
</div>
@endsection
