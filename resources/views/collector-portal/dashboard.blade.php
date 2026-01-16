@extends('layouts.collector')

@section('title', 'خطط اليوم - بوابة المحصل')

@section('content')
<div class="max-w-4xl mx-auto text-right" dir="rtl">
    <!-- Welcome Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="bg-emerald-100 p-4 rounded-full">
                <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">مرحبا، {{ $collector->name }}</h1>
                <p class="text-gray-500">خطط التحصيل الخاصة بك اليوم بتاريخ {{ today()->format('Y/m/d') }}</p>
            </div>
        </div>
    </div>

    <!-- Plans Grid -->
    @if($todayPlans->count() > 0)
        <div class="grid gap-6">
            @foreach($todayPlans as $plan)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">{{ $plan->name }}</h2>
                        <p class="text-emerald-100 text-sm">{{ ($plan->collection_type ?? 'regular') === 'regular' ? 'تحصيل عادي' : 'تحصيل خاص' }}</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold text-gray-800">{{ $plan->total_customers }}</div>
                                <div class="text-xs text-gray-500">إجمالي العملاء</div>
                            </div>
                            <div class="bg-orange-50 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold text-orange-600">{{ $plan->pending_count }}</div>
                                <div class="text-xs text-orange-500">معلق</div>
                            </div>
                            <div class="bg-green-50 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $plan->collected_count }}</div>
                                <div class="text-xs text-green-500">تم التحصيل</div>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold text-blue-600 text-sm md:text-2xl">{{ number_format($plan->total_expected, 2) }}</div>
                                <div class="text-xs text-blue-500">المتوقع (ج.م)</div>
                            </div>
                            <div class="bg-emerald-50 rounded-xl p-4 text-center">
                                <div class="text-2xl font-bold text-emerald-600 text-sm md:text-2xl">{{ number_format($plan->total_collected, 2) }}</div>
                                <div class="text-xs text-emerald-500">المحصل (ج.م)</div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-6">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>نسبة الإنجاز</span>
                                <span>{{ $plan->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
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
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center text-right" dir="rtl">
            <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-700 mb-2">لا توجد خطط لليوم</h2>
            <p class="text-gray-500">لم يتم تعيين أي خطط تحصيل لك اليوم. يرجى التواصل مع الإدارة.</p>
        </div>
    @endif
</div>
@endsection
