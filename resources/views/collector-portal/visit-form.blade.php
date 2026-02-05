@extends('layouts.collector')

@section('title', 'تسجيل زيارة - ' . $visitPlanItem->customer->name)

@section('content')
<div class="max-w-2xl mx-auto text-right" dir="rtl">
    <!-- Header -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 mb-6 border border-gray-100 dark:border-dark-border">
        <div class="flex items-center gap-4">
            <a href="{{ route('collector.visit-plan', $visitPlanItem->visit_plan_id) }}" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">تسجيل زيارة</h1>
                <p class="text-gray-500 dark:text-gray-400">{{ $visitPlanItem->customer->name }}</p>
            </div>
        </div>
    </div>

    <!-- Customer Info Card -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-500 rounded-2xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center gap-4">
            <div class="bg-white/20 backdrop-blur p-3 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold">{{ $visitPlanItem->customer->name }}</h2>
                <p class="text-purple-100">{{ $visitPlanItem->customer->phone }}</p>
                @if($visitPlanItem->customer->address)
                    <p class="text-purple-200 text-sm mt-1">{{ $visitPlanItem->customer->address }}</p>
                @endif
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/20">
            <div class="flex justify-between items-center">
                <span class="text-purple-100">الرصيد الحالي:</span>
                <span class="text-2xl font-black">{{ number_format($visitPlanItem->customer->getCurrentBalance(), 2) }} ج.م</span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-xl">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if($visitPlanItem->customer->hasDueInstallments())
        <div class="mb-6 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800/50 rounded-xl p-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="bg-red-100 dark:bg-red-900/30 p-2 rounded-lg text-red-600 dark:text-red-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-red-800 dark:text-red-400">تنبيه: أقساط مستحقة!</h3>
                    <p class="text-xs text-red-600 dark:text-red-300">يوجد أقساط حل ميعاد سدادها لهذا العميل</p>
                </div>
            </div>
            
            <div class="space-y-2">
                @foreach($visitPlanItem->customer->due_installments as $installment)
                <div class="flex justify-between items-center text-sm p-3 bg-white dark:bg-slate-800/50 rounded-lg border border-red-100 dark:border-red-900/30">
                    <span class="text-gray-700 dark:text-gray-300 font-medium">استحقاق {{ $installment->due_date->format('Y-m-d') }}</span>
                    <span class="font-black text-red-600 dark:text-red-400">{{ number_format($installment->amount, 2) }} ج.م</span>
                </div>
                @endforeach
            </div>
        </div>
    @endif


    <!-- Visit Form -->
    <form action="{{ route('collector.visit.store', $visitPlanItem) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Visit Type Selection -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
            <h3 class="text-lg font-bold dark:text-white mb-4">نوع الزيارة</h3>
            <div class="grid grid-cols-2 gap-3">
                @foreach($visitTypes as $type)
                    <label class="visit-type-option">
                        <input type="radio" name="visit_type" value="{{ $type->name }}" class="hidden peer" {{ old('visit_type') == $type->name ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-dark-border peer-checked:border-purple-500 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 cursor-pointer transition-all text-center h-full flex flex-col items-center justify-center">
                            @if($type->name == 'collection')
                                <svg class="w-8 h-8 mb-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            @elseif($type->name == 'order')
                                <svg class="w-8 h-8 mb-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            @elseif($type->name == 'issue')
                                <svg class="w-8 h-8 mb-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            @else
                                <svg class="w-8 h-8 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            @endif
                            <span class="font-bold text-gray-700 dark:text-gray-300">{{ $type->label }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Collection Fields -->
        <div id="collection-fields" class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border hidden">
            <h3 class="text-lg font-bold dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                بيانات التحصيل
            </h3>
            
            @if($visitPlanItem->customer->hasDueInstallments())
            <div class="mb-4 bg-red-50 dark:bg-slate-800/50 p-3 rounded-xl border border-red-100 dark:border-red-900/30">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative flex items-center">
                        <input type="checkbox" name="is_installment" id="is_installment_checkbox" value="1" class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-gray-300 transition-all checked:border-red-500 checked:bg-red-500 hover:border-red-400">
                        <svg class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 pointer-events-none opacity-0 peer-checked:opacity-100 text-white transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="flex-1">
                        <span class="block font-bold text-gray-800 dark:text-white group-hover:text-red-600 transition-colors">تحصيل كقسط مستحق</span>
                        <span class="text-xs text-gray-500">سيتم خصم المبلغ من خطة الأقساط</span>
                    </div>
                </label>
            </div>
            <script>
                document.getElementById('is_installment_checkbox')?.addEventListener('change', function(e) {
                    const amountInput = document.querySelector('input[name="amount"]');
                    if(e.target.checked) {
                        const firstAmount = {{ $visitPlanItem->customer->due_installments->first()->amount ?? 0 }};
                        if(firstAmount > 0) {
                            amountInput.value = firstAmount;
                            amountInput.parentElement.classList.add('animate-pulse');
                            setTimeout(() => amountInput.parentElement.classList.remove('animate-pulse'), 500);
                        }
                    }
                });
            </script>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">المبلغ <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount') }}"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white text-2xl font-bold text-center"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">رقم الإيصال <span class="text-red-500">*</span></label>
                    <input type="text" name="receipt_no" value="{{ old('receipt_no', $receiptNo) }}" readonly
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white bg-gray-50 text-center font-mono">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">طريقة الدفع <span class="text-red-500">*</span></label>
                    <select name="payment_type" id="payment-type"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white">
                        <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>نقدي</option>
                        <option value="cheque" {{ old('payment_type') == 'cheque' ? 'selected' : '' }}>شيك</option>
                        <option value="bank_transfer" {{ old('payment_type') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                    </select>
                </div>
                <!-- Cheque Fields -->
                <div id="cheque-fields" class="space-y-4 hidden">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">رقم الشيك</label>
                        <input type="text" name="cheque_no" value="{{ old('cheque_no') }}"
                            class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">اسم البنك <span class="text-red-500">*</span></label>
                        <select name="bank_name_cheque" class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white">
                            <option value="">اختر البنك / المحفظة</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->name }}" {{ old('bank_name_cheque') == $bank->name ? 'selected' : '' }}>
                                    {{ $bank->name }} ({{ $bank->type == 'wallet' ? 'محفظة' : 'بنك' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">تاريخ الاستحقاق</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}"
                            class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white">
                    </div>
                </div>
                <!-- Bank Transfer Fields -->
                <div id="bank-transfer-fields" class="space-y-4 hidden">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">اسم البنك <span class="text-red-500">*</span></label>
                    <select name="bank_name_transfer" class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white">
                        <option value="">اختر البنك / المحفظة</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->name }}" {{ old('bank_name_transfer') == $bank->name ? 'selected' : '' }}>
                                {{ $bank->name }} ({{ $bank->type == 'wallet' ? 'محفظة' : 'بنك' }})
                            </option>
                        @endforeach
                    </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">رقم المرجع</label>
                        <input type="text" name="reference_no" value="{{ old('reference_no') }}"
                            class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white">
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Fields -->
        <div id="order-fields" class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border hidden">
            <h3 class="text-lg font-bold dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                بيانات الأوردر
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">تفاصيل الأوردر <span class="text-red-500">*</span></label>
                    <textarea name="order_details" rows="4"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white"
                        placeholder="اكتب تفاصيل الأوردر هنا...">{{ old('order_details') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">قيمة الأوردر <span class="text-red-500">*</span></label>
                    <input type="number" name="order_amount" step="0.01" min="0" value="{{ old('order_amount') }}"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white"
                        placeholder="0.00">
                </div>
            </div>
        </div>

        <!-- Issue Fields -->
        <div id="issue-fields" class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border hidden">
            <h3 class="text-lg font-bold dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                بيانات المشكلة
            </h3>

            @if(isset($activeIssues) && $activeIssues->count() > 0)
            <!-- Choice Toggle (New vs Existing) -->
            <div class="mb-6 grid grid-cols-2 gap-4">
                <label class="cursor-pointer group">
                    <input type="radio" name="issue_action_type" value="existing" class="hidden peer" {{ old('issue_action_type', 'existing') == 'existing' ? 'checked' : '' }}>
                    <div class="p-4 rounded-2xl border-2 border-gray-100 dark:border-dark-border peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/20 text-center transition-all">
                        <svg class="w-6 h-6 mx-auto mb-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">متابعة مشكلة سابقة</span>
                    </div>
                </label>
                <label class="cursor-pointer group">
                    <input type="radio" name="issue_action_type" value="new" class="hidden peer" {{ old('issue_action_type') == 'new' ? 'checked' : '' }}>
                    <div class="p-4 rounded-2xl border-2 border-gray-100 dark:border-dark-border peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/20 text-center transition-all">
                        <svg class="w-6 h-6 mx-auto mb-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">تسجيل مشكلة جديدة</span>
                    </div>
                </label>
            </div>

            <!-- Follow-up Section -->
            <div id="existing-issue-section" class="mb-6 space-y-4">
                <div class="bg-rose-50 dark:bg-rose-900/10 border border-rose-200 dark:border-rose-800/50 rounded-xl p-5">
                    @if($activeIssues->count() > 1)
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-rose-800 dark:text-rose-400 mb-2">اختر المشكلة للمتابعة <span class="text-red-500">*</span></label>
                        <select name="selected_issue_id" id="selected_issue_id" class="w-full rounded-xl border-rose-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-rose-500 transition-all text-sm h-14">
                            @foreach($activeIssues as $issue)
                                <option value="{{ $issue->id }}" {{ old('selected_issue_id') == $issue->id ? 'selected' : '' }} data-desc="{{ $issue->description }}">
                                    [{{ $issue->status_label }}] {{ Str::limit($issue->description, 50) }} ({{ $issue->created_at->format('Y-m-d') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <input type="hidden" name="selected_issue_id" value="{{ $activeIssues->first()->id }}">
                    <div class="mb-5 p-4 bg-white dark:bg-slate-800/50 rounded-xl border border-rose-100 dark:border-rose-900/30">
                        <span class="block text-[10px] font-black uppercase text-rose-600 mb-1">المشكلة العالقة</span>
                        <p class="text-xs text-gray-700 dark:text-gray-300 italic">"{{ $activeIssues->first()->description }}"</p>
                    </div>
                    @endif

                    <div class="space-y-4 pt-4 border-t border-rose-100 dark:border-rose-800/30">
                        <div>
                            <label class="block text-sm font-bold text-rose-800 dark:text-rose-400 mb-2">تحديث أو تعليق <span class="text-red-500">*</span></label>
                            <textarea name="followup_comment" rows="3" class="w-full text-sm rounded-xl border-rose-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-rose-500 transition-all" placeholder="أضف تفاصيل المتابعة هنا...">{{ old('followup_comment') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-rose-800 dark:text-rose-400 mb-2">تغيير الحالة</label>
                            <select name="followup_status" class="w-full text-sm rounded-xl border-rose-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-rose-500 transition-all h-12">
                                <option value="pending" {{ old('followup_status') == 'pending' ? 'selected' : '' }}>معلق (بدون تغيير)</option>
                                <option value="processing" {{ old('followup_status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                                <option value="resolved">تم الحل (إغلاق)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- New Issue Section -->
            <div id="new-issue-section" class="space-y-4 {{ (isset($activeIssues) && $activeIssues->count() > 0 && old('issue_action_type', 'existing') == 'existing') ? 'hidden' : '' }}">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">وصف المشكلة الجديدة <span class="text-red-500">*</span></label>
                    <textarea name="issue_description" rows="4"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-rose-500 transition-all"
                        placeholder="اكتب وصف المشكلة بالتفصيل هنا...">{{ old('issue_description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">حالة المشكلة <span class="text-red-500">*</span></label>
                    <select name="issue_status"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white h-12 focus:ring-rose-500">
                        <option value="pending" {{ old('issue_status') == 'pending' ? 'selected' : '' }}>قيد المعالجة</option>
                        <option value="resolved" {{ old('issue_status') == 'resolved' ? 'selected' : '' }}>تم الحل</option>
                        <option value="escalated" {{ old('issue_status') == 'escalated' ? 'selected' : '' }}>تم التصعيد</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Common Fields -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
            <h3 class="text-lg font-bold dark:text-white mb-4">معلومات إضافية</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ملاحظات</label>
                    <textarea name="notes" rows="3"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white"
                        placeholder="أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">صورة/مرفق</label>
                    <input type="file" name="attachment" accept="image/*" capture="environment"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 text-white py-4 rounded-xl font-bold text-lg transition-all shadow-lg shadow-purple-500/20">
            <svg class="w-6 h-6 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            تسجيل الزيارة
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const visitTypeRadios = document.querySelectorAll('input[name="visit_type"]');
    const collectionFields = document.getElementById('collection-fields');
    const orderFields = document.getElementById('order-fields');
    const issueFields = document.getElementById('issue-fields');
    const paymentType = document.getElementById('payment-type');
    const chequeFields = document.getElementById('cheque-fields');
    const bankTransferFields = document.getElementById('bank-transfer-fields');
    const issueActionRadios = document.querySelectorAll('input[name="issue_action_type"]');
    const existingIssueSection = document.getElementById('existing-issue-section');
    const newIssueSection = document.getElementById('new-issue-section');

    function updateVisitTypeFields() {
        const selectedType = document.querySelector('input[name="visit_type"]:checked')?.value;
        
        collectionFields.classList.add('hidden');
        orderFields.classList.add('hidden');
        issueFields.classList.add('hidden');
        
        if (selectedType === 'collection') {
            collectionFields.classList.remove('hidden');
        } else if (selectedType === 'order') {
            orderFields.classList.remove('hidden');
        } else if (selectedType === 'issue') {
            issueFields.classList.remove('hidden');
            updateIssueSection();
        }
    }

    function updateIssueSection() {
        const existingSection = document.getElementById('existing-issue-section');
        const newSection = document.getElementById('new-issue-section');
        if (!existingSection || !newSection) return;
        
        const selectedAction = document.querySelector('input[name="issue_action_type"]:checked')?.value;
        
        if (selectedAction === 'existing') {
            existingSection.classList.remove('hidden');
            newSection.classList.add('hidden');
            
            // Disable new issue inputs to avoid validation errors
            newSection.querySelectorAll('textarea, select').forEach(el => {
                el.disabled = true;
                el.required = false;
            });
            // Enable existing section inputs
            existingSection.querySelectorAll('textarea, select').forEach(el => {
                el.disabled = false;
                if (el.name === 'followup_comment') el.required = true;
            });
        } else {
            existingSection.classList.add('hidden');
            newSection.classList.remove('hidden');
            
            // Enable new issue inputs
            newSection.querySelectorAll('textarea, select').forEach(el => {
                el.disabled = false;
                el.required = true;
            });
            // Disable existing section inputs
            existingSection.querySelectorAll('textarea, select').forEach(el => {
                el.disabled = true;
                el.required = false;
            });
        }
    }

    function updatePaymentFields() {
        const payment = paymentType?.value;
        if (chequeFields) chequeFields.classList.add('hidden');
        if (bankTransferFields) bankTransferFields.classList.add('hidden');
        
        if (payment === 'cheque' && chequeFields) {
            chequeFields.classList.remove('hidden');
        } else if (payment === 'bank_transfer' && bankTransferFields) {
            bankTransferFields.classList.remove('hidden');
        }
    }

    visitTypeRadios.forEach(radio => {
        radio.addEventListener('change', updateVisitTypeFields);
    });

    issueActionRadios.forEach(radio => {
        radio.addEventListener('change', updateIssueSection);
    });

    if (paymentType) {
        paymentType.addEventListener('change', updatePaymentFields);
    }

    // Initialize
    updateVisitTypeFields();
    updatePaymentFields();
});
</script>
@endsection
