<!-- Due Installments -->
@php $dueInstallments = $customer->due_installments; @endphp
@if ($dueInstallments->count() > 0)
<div class="mb-8 bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-red-100 dark:border-red-900/30">
    <div class="p-6 border-b border-red-50 dark:border-red-900/20 flex justify-between items-center bg-red-50/50 dark:bg-red-900/10">
        <h2 class="text-lg font-bold text-red-700 dark:text-red-400 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            أقساط متأخرة مستحقة السداد
        </h2>
        <span class="px-3 py-1 bg-red-600 text-white text-xs font-black rounded-full shadow-lg shadow-red-500/30 animate-pulse">
            {{ $dueInstallments->count() }} متأخر
        </span>
    </div>
    <div class="overflow-x-auto text-right">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-dark-tableheader text-gray-500 text-xs font-bold uppercase">
                <tr>
                    <th class="px-6 py-4">تاريخ الاستحقاق</th>
                    <th class="px-6 py-4">رقم الفاتورة</th>
                    <th class="px-6 py-4">المبلغ</th>
                    <th class="px-6 py-4">آخر غرامة مطبقة</th>
                    <th class="px-6 py-4">قيمة الغرامة</th>
                    <th class="px-6 py-4">أيام التأخير</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                @foreach ($dueInstallments as $installment)
                    <tr class="hover:bg-red-50/30 dark:hover:bg-red-900/5 transition-colors">
                        <td class="px-6 py-4 font-bold text-red-600 dark:text-red-400 text-sm">
                            {{ $installment->due_date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('installments.show', $installment->installment_plan_id) }}" class="text-blue-500 hover:underline font-medium">
                                #{{ $installment->installmentPlan->invoice_no }}
                            </a>
                        </td>
                        <td class="px-6 py-4 font-black dark:text-white">{{ number_format($installment->amount, 2) }}</td>
                        <td class="px-6 py-4 text-xs dark:text-gray-400">{{ $installment->last_penalty_date ? $installment->last_penalty_date->format('Y-m-d') : '-' }}</td>
                        <td class="px-6 py-4 font-bold text-orange-600">{{ number_format($installment->penalty_amount, 2) }}</td>
                        <td class="px-6 py-4">
                            @php $days = now()->diffInDays($installment->due_date, false); @endphp
                            <span class="text-xs font-bold text-red-500">
                                {{ abs($days) }} يوم
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- All Installment Plans -->
<div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
    <div class="p-6 border-b border-gray-100 dark:border-dark-border flex justify-between items-center">
        <h2 class="text-lg font-bold dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            خطط الأقساط النشطة والسابقة
        </h2>
        <a href="{{ route('installments.create', ['customer_id' => $customer->id]) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-black shadow-lg shadow-indigo-500/30 hover:scale-105 transition-all text-right">حساب خطة جديدة +</a>
    </div>
    @if ($customer->installmentPlans->count() > 0)
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($customer->installmentPlans as $plan)
                <div class="p-5 rounded-3xl border border-gray-100 dark:border-dark-border bg-gray-50/50 dark:bg-dark-bg/20 relative overflow-hidden group">
                    <div class="flex justify-between items-start mb-6">
                        <div class="text-right">
                            <a href="{{ route('installments.show', $plan) }}" class="text-base font-black dark:text-white hover:text-indigo-500 flex items-center gap-2 group-hover:translate-x-1 transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                فاتورة #{{ $plan->invoice_no }}
                            </a>
                            <p class="text-[10px] text-gray-400 mt-1 font-bold">تاريخ البدء: {{ $plan->start_date->format('Y-m-d') }}</p>
                        </div>
                        <span class="px-3 py-1 text-[10px] font-black rounded-full {{ $plan->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200' : 'bg-gray-100 text-gray-700' }} uppercase">
                            {{ $plan->status === 'active' ? 'نشطة' : 'مكتملة' }}
                        </span>
                    </div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-white dark:bg-dark-bg/40 rounded-2xl border border-gray-50 dark:border-dark-border text-right">
                                <span class="text-[10px] text-gray-500 block mb-1">المدفوع</span>
                                <span class="font-black text-emerald-600 text-sm">{{ number_format($plan->paid_amount, 2) }}</span>
                            </div>
                            <div class="p-3 bg-white dark:bg-dark-bg/40 rounded-2xl border border-gray-50 dark:border-dark-border text-right">
                                <span class="text-[10px] text-gray-500 block mb-1">المتبقي</span>
                                <span class="font-black text-rose-600 text-sm">{{ number_format($plan->remaining_amount, 2) }}</span>
                            </div>
                        </div>
                        <!-- Progress Bar -->
                        <div class="mt-2">
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">نسبة السداد</span>
                                <span class="text-[10px] font-black text-indigo-500 tracking-widest">{{ $plan->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden shadow-inner">
                                <div class="bg-indigo-600 h-full transition-all duration-1000 ease-out relative" style="width: {{ $plan->progress_percentage }}%">
                                    <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center text-gray-400 flex flex-col items-center gap-4">
            <svg class="w-16 h-16 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="font-bold">لا توجد خطط أقساط مسجلة لهذا العميل.</p>
        </div>
    @endif
</div>
