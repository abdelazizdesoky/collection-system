@extends('layouts.app')

@section('title', 'تفاصيل خطة التقسيط')

@section('content')
<div class="container mx-auto py-8 px-4 text-right" dir="rtl">
    <!-- Header with Back Link -->
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center gap-5">
            <div class="bg-amber-600 p-4 rounded-3xl shadow-2xl shadow-amber-500/30 text-white">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <h1 class="text-4xl font-black dark:text-white tracking-tighter">{{ $plan->customer->name }}</h1>
                <p class="text-gray-500 dark:text-gray-400 font-bold mt-1 uppercase tracking-widest text-xs">خطة تقسيط نشطة • فاتورة رقم {{ $plan->invoice_no }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('installments.edit', $plan) }}" class="flex items-center gap-2 bg-amber-100 hover:bg-amber-200 text-amber-800 font-bold py-3 px-6 rounded-xl transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                تعديل الخطة
            </a>
            <form action="{{ route('installments.destroy', $plan) }}" method="POST" onsubmit="return confirm('تحذير: هل أنت متأكد من حذف خطة التقسيط بالكامل؟ سيتم حذف جميع الأقساط المرتبطة بها.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center gap-2 bg-red-100 hover:bg-red-200 text-red-800 font-bold py-3 px-6 rounded-xl transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    حذف الخطة
                </button>
            </form>
            <a href="{{ route('installments.index') }}" class="flex items-center gap-2 text-gray-500 font-bold hover:underline">
                <span>العودة للقائمة</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-2xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Stats Card -->
            <div class="bg-white dark:bg-dark-card rounded-[2rem] shadow-2xl p-8 border border-gray-50 dark:border-dark-border relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full blur-2xl"></div>
                
                <h3 class="text-lg font-black dark:text-white mb-6 border-b border-gray-50 dark:border-dark-border pb-4">ملخص التمويل</h3>
                
                <div class="space-y-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-bold text-sm">إجمالي الفاتورة</span>
                        <span class="font-black dark:text-white tabular-nums">{{ number_format($plan->total_amount, 2) }} ج.م</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-emerald-500 font-black text-sm">المقدم المدفوع</span>
                        <span class="font-black text-emerald-600 tabular-nums">- {{ number_format($plan->down_payment, 2) }} ج.م</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-rose-500 font-black text-sm">نسبة الزيادة</span>
                        <span class="font-black text-rose-600 tabular-nums">+ {{ $plan->increase_percentage }}%</span>
                    </div>
                    <div class="h-px bg-gray-100 dark:bg-dark-border"></div>
                    <div class="flex justify-between items-center">
                        <span class="text-amber-600 font-black text-lg">المبلغ الممول</span>
                        <span class="text-2xl font-black text-amber-600 tabular-nums">{{ number_format($plan->financed_amount, 2) }} ج.م</span>
                    </div>
                </div>

                <div class="mt-8">
                    @php $progress = $plan->progress_percentage; @endphp
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-black text-gray-400 uppercase">التقدم المحرز</span>
                        <span class="text-xs font-black text-emerald-600">{{ $progress }}%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-100 dark:bg-dark-bg rounded-full overflow-hidden border border-gray-50 dark:border-dark-border">
                        <div class="h-full bg-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.4)]" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Schedule Summary -->
            <div class="bg-indigo-600 rounded-[2rem] shadow-2xl p-8 text-white">
                <h3 class="text-lg font-black mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    بيانات السداد الشهري
                </h3>
                <div class="text-4xl font-black tabular-nums border-b border-white/20 pb-6 mb-6">
                    {{ number_format($plan->monthly_amount, 2) }}
                    <span class="text-xs font-bold opacity-70 mr-1 uppercase">ج.م / شهرياً</span>
                </div>
                <div class="space-y-4">
                    <p class="text-sm font-medium leading-relaxed opacity-90 italic">
                        توزيع المديونية على مدار <strong>{{ $plan->duration_months }} شهراً</strong> تبدأ من تاريخ <strong>{{ $plan->start_date->format('Y-M-d') }}</strong>.
                    </p>
                    <div class="bg-white/10 p-4 rounded-2xl border border-white/10 text-xs font-bold tracking-wide">
                        ملاحظة: تظهر هذه الأقساط تلقائياً في قائمة التحصيلات المطلوبة للمناديب.
                    </div>
                </div>
            </div>
        </div>

        <!-- Installments Table -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-dark-card rounded-[2rem] shadow-2xl overflow-hidden border border-gray-50 dark:border-dark-border">
                <div class="px-8 py-6 bg-gray-50/50 dark:bg-dark-tableheader/50 border-b border-gray-50 dark:border-dark-border flex justify-between items-center">
                    <h2 class="text-xl font-black dark:text-white">جدول الدفعات الشهرية</h2>
                    <span class="px-4 py-1 bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 rounded-full text-[10px] font-black uppercase tracking-widest border border-amber-100 dark:border-amber-800">
                        {{ $plan->installments->count() }} دفعة مجدولة
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="text-gray-400 text-[10px] font-black uppercase tracking-widest">
                                <th class="px-8 py-5 border-b border-gray-50 dark:border-dark-border">الرقم</th>
                                <th class="px-8 py-5 border-b border-gray-50 dark:border-dark-border">تاريخ الاستحقاق</th>
                                <th class="px-8 py-5 border-b border-gray-50 dark:border-dark-border text-center">المبلغ</th>
                                <th class="px-8 py-5 border-b border-gray-50 dark:border-dark-border text-left">الحالة</th>
                                <th class="px-8 py-5 border-b border-gray-50 dark:border-dark-border text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                            @foreach($plan->installments as $index => $installment)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/20 transition-colors group">
                                    <td class="px-8 py-6 font-black text-gray-300 group-hover:text-amber-600 transition-colors">#{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-8 py-6">
                                        <div class="font-bold dark:text-white">{{ $installment->due_date->format('Y-M-d') }}</div>
                                        <div class="text-[10px] text-gray-400 font-medium uppercase mt-0.5">{{ $installment->due_date->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-8 py-6 text-center whitespace-nowrap">
                                        <span class="text-lg font-black dark:text-white tabular-nums">{{ number_format($installment->amount, 2) }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold mr-1">ج.م</span>
                                    </td>
                                    <td class="px-8 py-6 text-left">
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border {{ $installment->status_color }}">
                                            {{ $installment->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('installments.item.edit', $installment) }}" class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="تعديل">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="{{ route('installments.item.destroy', $installment) }}" method="POST" class="inline" onsubmit="return confirm('حذف هذا القسط؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="حذف">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($plan->notes)
                <div class="mt-8 bg-gray-50 dark:bg-dark-bg/50 rounded-2xl p-6 border border-gray-100 dark:border-dark-border">
                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">ملاحظات الخطة</h4>
                    <p class="text-gray-600 dark:text-gray-300 font-medium leading-relaxed">{{ $plan->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
