@extends('layouts.collector')

@section('title', 'تفاصيل الزيارة - ' . $visit->customer->name)

@section('content')
<div class="max-w-4xl mx-auto text-right" dir="rtl">
    <!-- Header -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 mb-6 border border-gray-100 dark:border-dark-border">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                @php
                    $isCollector = auth()->user()->hasRole('collector');
                    $backUrl = route('dashboard'); // Fallback
                    
                    if ($visit->visitPlanItem) {
                        $backUrl = $isCollector 
                            ? route('collector.visit-plan', $visit->visitPlanItem->visit_plan_id)
                            : route('visit-plans.show', $visit->visitPlanItem->visit_plan_id);
                    } elseif ($isCollector) {
                        $backUrl = route('collector.dashboard');
                    }
                @endphp
                <a href="{{ $backUrl }}" 
                   class="p-2 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">تفاصيل الزيارة</h1>
                    <p class="text-gray-500 dark:text-gray-400">{{ $visit->visit_time->format('Y/m/d h:i A') }}</p>
                </div>
            </div>
            <span class="px-4 py-2 rounded-xl font-bold text-sm {{ $visit->visit_type_color }}">
                {!! $visit->visit_type_icon !!} {{ $visit->visit_type_label }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="md:col-span-2 space-y-6">
            <!-- Visit Type Specific Info -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
                <h2 class="text-lg font-bold text-purple-600 dark:text-purple-400 mb-4 flex items-center gap-2 border-b border-gray-100 dark:border-slate-700 pb-2">
                    {!! $visit->visit_type_icon !!}
                    بيانات الزيارة ({{ $visit->visit_type_label }})
                </h2>

                <div class="space-y-4">
                    @if($visit->visit_type === 'collection' && $visit->collection)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                                <p class="text-xs text-emerald-600 dark:text-emerald-400 mb-1">المبلغ المحصل</p>
                                <p class="text-2xl font-black text-emerald-700 dark:text-emerald-300">{{ number_format($visit->collection->amount, 2) }} ج.م</p>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800/30">
                                <p class="text-xs text-blue-600 dark:text-blue-400 mb-1">طريقة الدفع</p>
                                <p class="text-lg font-bold text-blue-700 dark:text-blue-300">{{ $visit->collection->payment_type === 'cash' ? 'نقدي' : ($visit->collection->payment_type === 'cheque' ? 'شيك' : 'تحويل') }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center bg-gray-50 dark:bg-slate-800/50 p-4 rounded-xl">
                            <div>
                                <p class="text-xs text-gray-500">رقم الإيصال</p>
                                <p class="font-bold text-gray-800 dark:text-gray-200">#{{ $visit->collection->receipt_no }}</p>
                            </div>
                            <a href="{{ route('shared.receipt', $visit->collection_id) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold text-sm hover:bg-emerald-700 transition-all">
                                عرض الإيصال
                            </a>
                        </div>

                    @elseif($visit->visit_type === 'order')
                        <div class="bg-blue-50 dark:bg-blue-900/10 p-4 rounded-xl mb-4">
                            <p class="text-xs text-blue-600 dark:text-blue-400 mb-1">قيمة الأوردر</p>
                            <p class="text-2xl font-black text-blue-700 dark:text-blue-300">{{ number_format($visit->order_amount, 2) }} ج.م</p>
                        </div>
                        <div class="py-2">
                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">تفاصيل الأوردر:</p>
                            <div class="bg-gray-50 dark:bg-slate-800/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $visit->order_details ?: 'لا توجد تفاصيل إضافية' }}</div>
                        </div>

                    @elseif($visit->visit_type === 'issue')
                        <div class="bg-red-50 dark:bg-red-900/10 p-4 rounded-xl mb-4 flex justify-between items-center">
                            <div>
                                <p class="text-xs text-red-600 dark:text-red-400 mb-1">حالة المشكلة</p>
                                <p class="text-lg font-black text-red-700 dark:text-red-300">{{ $visit->issue_status_label }}</p>
                            </div>
                            <span class="p-3 bg-red-100 dark:bg-red-900/40 rounded-full text-red-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </span>
                        </div>
                        <div class="py-2">
                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">وصف المشكلة:</p>
                            <div class="bg-gray-50 dark:bg-slate-800/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $visit->issue_description }}</div>
                        </div>

                    @elseif($visit->visit_type === 'market')
                        <div class="bg-orange-50 dark:bg-orange-900/10 p-6 rounded-2xl border-2 border-dashed border-orange-200 dark:border-orange-800/50 text-center">
                            <div class="bg-orange-100 dark:bg-orange-900/30 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-orange-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-orange-700 dark:text-orange-400">زيارة تسويق</h3>
                            <p class="text-sm text-orange-600/70 dark:text-orange-400/60 mt-1">عرض المنتجات وتطوير العلاقة مع العميل</p>
                        </div>
                    @else
                         <div class="p-4 bg-gray-50 dark:bg-slate-800/50 rounded-xl border border-gray-100 dark:border-slate-700">
                            <p class="text-gray-500 dark:text-gray-400 text-center italic">لا توجد بيانات هيكلية لهذا النوع من الزيارات</p>
                         </div>
                    @endif
                </div>
            </div>

            <!-- Notes & General -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 border-b border-gray-50 dark:border-slate-700 pb-2">ملاحظات المحصل</h3>
                <p class="text-gray-700 dark:text-gray-300 bg-slate-50 dark:bg-slate-800/30 p-4 rounded-xl leading-relaxed whitespace-pre-wrap">
                    {{ $visit->notes ?: 'لا توجد ملاحظات مسجلة' }}
                </p>

                @if($visit->attachment)
                    <div class="mt-6">
                        <h3 class="text-sm font-bold text-gray-500 mb-3">المرفقات والصور:</h3>
                        <div class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm max-w-sm">
                            <img src="{{ asset('storage/' . $visit->attachment) }}" class="w-full h-auto cursor-pointer" onclick="window.open(this.src)">
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar / Customer Info -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
                <h3 class="text-lg font-bold dark:text-white mb-4 border-b border-gray-50 dark:border-slate-700 pb-2">العميل</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">الاسم</p>
                        <p class="font-bold text-gray-800 dark:text-gray-100">{{ $visit->customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">رقم الهاتف</p>
                        <p class="text-gray-700 dark:text-gray-300">{{ $visit->customer->phone }}</p>
                    </div>
                    @if($visit->customer->address)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">العنوان</p>
                            <p class="text-gray-600 dark:text-gray-400 text-sm italic">{{ $visit->customer->address }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
                <h3 class="text-lg font-bold dark:text-white mb-4 border-b border-gray-50 dark:border-slate-700 pb-2">الخطة</h3>
                @if($visit->visitPlanItem && $visit->visitPlanItem->visitPlan)
                    <div class="bg-purple-50 dark:bg-purple-900/10 p-4 rounded-xl border border-purple-100/50">
                        <p class="font-bold text-purple-700 dark:text-purple-300 mb-1">{{ $visit->visitPlanItem->visitPlan->name }}</p>
                        <p class="text-xs text-purple-600/70">{{ $visit->visitPlanItem->visitPlan->frequency_label }}</p>
                    </div>
                @else
                    <p class="text-gray-400 text-center italic text-sm">زيارة خارج خطة سارية</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
