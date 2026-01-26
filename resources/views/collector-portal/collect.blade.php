@extends('layouts.collector')

@section('title', 'تحصيل من ' . $planItem->customer->name)

@section('content')
<div class="max-w-lg mx-auto">
    <!-- Back Button & Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('collector.plan', $planItem->collectionPlan) }}" 
           class="bg-white dark:bg-dark-card hover:bg-gray-50 dark:hover:bg-slate-700/50 p-3 rounded-xl shadow-md transition-colors border border-gray-100 dark:border-dark-border">
            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">تسجيل تحصيل</h1>
            <p class="text-gray-500 dark:text-gray-400">{{ $planItem->customer->name }}</p>
        </div>
    </div>

    <!-- Customer Info Card -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 mb-6 border border-gray-100 dark:border-dark-border">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-emerald-100 dark:bg-emerald-900/30 p-3 rounded-full">
                <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $planItem->customer->name }}</h2>
                @if($planItem->customer->phone)
                    <p class="text-gray-500 dark:text-gray-400">{{ $planItem->customer->phone }}</p>
                @endif
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-center border border-gray-100 dark:border-slate-700">
                <div class="text-sm text-gray-500 dark:text-slate-400 mb-1">المبلغ المتوقع</div>
                <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($planItem->expected_amount, 2) }}</div>
                <div class="text-sm text-gray-400">ج.م</div>
            </div>
            <div class="bg-gray-50 dark:bg-slate-800 rounded-xl p-4 text-center border border-gray-100 dark:border-slate-700">
                <div class="text-sm text-gray-500 dark:text-slate-400 mb-1">الرصيد الحالي</div>
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($planItem->customer->getCurrentBalance(), 2) }}</div>
                <div class="text-sm text-gray-400">ج.م</div>
            </div>
        </div>
    </div>

    <!-- Collection Form -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">بيانات التحصيل</h3>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-500/50 text-red-700 dark:text-red-400 rounded-xl">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            </div>
        @endif

        @if($planItem->customer->hasDueInstallments())
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
                
                <div class="space-y-2 mb-4">
                    @foreach($planItem->customer->due_installments as $installment)
                    <div class="flex justify-between items-center text-sm p-3 bg-white dark:bg-slate-800/50 rounded-lg border border-red-100 dark:border-red-900/30">
                        <span class="text-gray-700 dark:text-gray-300 font-medium">استحقاق {{ $installment->due_date->format('Y-m-d') }}</span>
                        <span class="font-black text-red-600 dark:text-red-400">{{ number_format($installment->amount, 2) }} ج.م</span>
                    </div>
                    @endforeach
                </div>

                <div class="bg-white dark:bg-slate-800/50 p-3 rounded-xl border border-red-100 dark:border-red-900/30">
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
            </div>

            <script>
                document.getElementById('is_installment_checkbox')?.addEventListener('change', function(e) {
                    const amountInput = document.querySelector('input[name="amount"]');
                    if(e.target.checked) {
                        const firstAmount = {{ $planItem->customer->due_installments->first()->amount ?? 0 }};
                        if(firstAmount > 0) {
                            amountInput.value = firstAmount;
                            // Trigger pulse effect on amount input
                            amountInput.parentElement.classList.add('animate-pulse');
                            setTimeout(() => amountInput.parentElement.classList.remove('animate-pulse'), 500);
                        }
                    }
                });
            </script>
        @endif

        <form action="{{ route('collector.collect.store', $planItem) }}" method="POST" enctype="multipart/form-data" id="collectionForm">
            @csrf

            <!-- Payment Type -->
            <div class="mb-5">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">نوع الدفع *</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_type" value="cash" class="peer hidden" checked onchange="toggleFields('cash')">
                        <div class="p-3 text-center border-2 border-gray-100 dark:border-slate-700 rounded-xl peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 dark:text-gray-400 peer-checked:text-emerald-700 dark:peer-checked:text-emerald-400 transition-all font-bold text-sm bg-gray-50 dark:bg-slate-800">
                            نقدي
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_type" value="cheque" class="peer hidden" onchange="toggleFields('cheque')" {{ old('payment_type') === 'cheque' ? 'checked' : '' }}>
                        <div class="p-3 text-center border-2 border-gray-100 dark:border-slate-700 rounded-xl peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 dark:text-gray-400 peer-checked:text-emerald-700 dark:peer-checked:text-emerald-400 transition-all font-bold text-sm bg-gray-50 dark:bg-slate-800">
                            شيك
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_type" value="bank_transfer" class="peer hidden" onchange="toggleFields('bank_transfer')" {{ old('payment_type') === 'bank_transfer' ? 'checked' : '' }}>
                        <div class="p-3 text-center border-2 border-gray-100 dark:border-slate-700 rounded-xl peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 dark:text-gray-400 peer-checked:text-emerald-700 dark:peer-checked:text-emerald-400 transition-all font-bold text-sm bg-gray-50 dark:bg-slate-800">
                            تحويل بنكي
                        </div>
                    </label>
                </div>
            </div>

            <!-- Amount -->
            <div class="mb-5">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">المبلغ المندوب *</label>
                <div class="relative">
                    <input type="number" 
                           name="amount" 
                           step="0.01" 
                           value="{{ old('amount', $planItem->expected_amount) }}"
                           class="w-full px-4 py-3 border-2 border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl focus:outline-none focus:border-emerald-500 text-lg font-black dark:text-white"
                           placeholder="0.00"
                           required>
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">ج.م</span>
                </div>
            </div>

            <!-- Cheque Details (Conditional) -->
            <div id="cheque_fields" class="hidden space-y-5 mb-5 p-4 bg-blue-50/50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-500/30">
                <div>
                    <label class="block text-blue-800 dark:text-blue-300 font-bold mb-2">رقم الشيك *</label>
                    <input type="text" name="cheque_no" value="{{ old('cheque_no') }}" class="w-full px-4 py-3 border-2 border-white dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl focus:outline-none focus:border-blue-500 dark:text-white">
                </div>
                <div>
                    <label class="block text-blue-800 dark:text-blue-300 font-bold mb-2">اسم البنك *</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" list="banks" class="w-full px-4 py-3 border-2 border-white dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl focus:outline-none focus:border-blue-500 dark:text-white" placeholder="اختر البنك">
                    <datalist id="banks">
                        @foreach($banks as $bank)
                            <option value="{{ $bank->name }}">
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block text-blue-800 dark:text-blue-300 font-bold mb-2">تاريخ الاستحقاق *</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full px-4 py-3 border-2 border-white dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl focus:outline-none focus:border-blue-500 text-right dark:text-white">
                </div>
            </div>

            <!-- Bank Transfer Details (Conditional) -->
            <div id="transfer_fields" class="hidden space-y-5 mb-5 p-4 bg-amber-50/50 dark:bg-amber-900/10 rounded-2xl border border-amber-100 dark:border-amber-500/30">
                <div>
                    <label class="block text-amber-800 dark:text-amber-300 font-bold mb-2">اسم البنك / البرنامج *</label>
                    <input type="text" name="bank_name_transfer" value="{{ old('bank_name') }}" list="banks" class="w-full px-4 py-3 border-2 border-white dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl focus:outline-none focus:border-amber-500 dark:text-white" placeholder="اختر البنك">
                </div>
                <div>
                    <label class="block text-amber-800 dark:text-amber-300 font-bold mb-2">رقم المرجع (Ref No) *</label>
                    <input type="text" name="reference_no" value="{{ old('reference_no') }}" class="w-full px-4 py-3 border-2 border-white dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl focus:outline-none focus:border-amber-500 font-mono dark:text-white">
                </div>
            </div>

            <!-- Attachment -->
            <div class="mb-5">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">إرفاق صورة (اختياري)</label>
                <div class="relative group">
                    <input type="file" 
                           name="attachment" 
                           accept="image/*"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           onchange="updateFileName(this)">
                    <div class="px-4 py-3 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl group-hover:border-emerald-500 transition-colors flex items-center justify-center gap-2 bg-gray-50/30 dark:bg-slate-800/30">
                        <svg class="w-6 h-6 text-gray-400 dark:text-slate-500 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span id="file_name" class="text-gray-500 dark:text-slate-400 font-bold">اضغط هنا لالتقاط صورة</span>
                    </div>
                </div>
            </div>

            <!-- Receipt Number -->
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">رقم الإيصال *</label>
                <input type="text" 
                       name="receipt_no" 
                       value="{{ old('receipt_no', $receiptNo) }}"
                       class="w-full px-4 py-3 border-2 border-gray-100 dark:border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500 text-lg bg-gray-50 dark:bg-slate-800 font-black dark:text-white"
                       readonly
                       required>
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">ملاحظات</label>
                <textarea name="notes" 
                          rows="3" 
                          class="w-full px-4 py-3 border-2 border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl focus:outline-none focus:border-emerald-500 font-medium dark:text-white"
                          placeholder="أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white py-4 rounded-xl font-black text-xl shadow-lg transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                حفظ وتسجيل التحصيل
            </button>
        </form>

        <script>
            function toggleFields(type) {
                const chequeFields = document.getElementById('cheque_fields');
                const transferFields = document.getElementById('transfer_fields');
                
                chequeFields.classList.add('hidden');
                transferFields.classList.add('hidden');
                
                // Clear required if hidden, set if visible
                const chequeInputs = chequeFields.querySelectorAll('input');
                const transferInputs = transferFields.querySelectorAll('input');
                
                chequeInputs.forEach(i => i.required = false);
                transferInputs.forEach(i => i.required = false);

                if (type === 'cheque') {
                    chequeFields.classList.remove('hidden');
                    chequeInputs.forEach(i => i.required = true);
                } else if (type === 'bank_transfer') {
                    transferFields.classList.remove('hidden');
                    transferInputs.forEach(i => i.required = true);
                }
            }

            function updateFileName(input) {
                const fileNameSpan = document.getElementById('file_name');
                if (input.files && input.files.length > 0) {
                    fileNameSpan.textContent = input.files[0].name;
                    fileNameSpan.classList.add('text-emerald-600');
                } else {
                    fileNameSpan.textContent = 'اضغط هنا لالتقاط صورة';
                    fileNameSpan.classList.remove('text-emerald-600');
                }
            }

            // Sync bank_name field for transfer if needed since controller looks for 'bank_name'
            document.querySelector('input[name="bank_name_transfer"]').addEventListener('input', function(e) {
                document.querySelector('input[name="bank_name"]').value = e.target.value;
            });

            // Handle old input on load
            window.onload = function() {
                const selectedType = document.querySelector('input[name="payment_type"]:checked').value;
                toggleFields(selectedType);
            };
        </script>
    </div>
</div>
@endsection
