@extends('layouts.collector')

@section('title', 'تحصيل خارج الخطة - ' . $customer->name)

@section('content')
<div class="max-w-lg mx-auto">
    <!-- Back Button & Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('collector.adhoc') }}" 
           class="bg-white dark:bg-dark-card hover:bg-gray-50 dark:hover:bg-slate-700/50 p-3 rounded-xl shadow-md transition-colors border border-gray-100 dark:border-dark-border">
            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">تحصيل خارج الخطة</h1>
            <p class="text-gray-500 dark:text-gray-400">{{ $customer->name }}</p>
        </div>
    </div>

    <!-- Pending Approval Notice -->
    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3">
        <div class="text-amber-600 mt-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-amber-800">ملاحظة هامة:</p>
            <p class="text-xs text-amber-700">هذا الإجراء يتم خارج الخطة اليومية، وبالتالي سيتم إرساله للمراجعة والاعتماد من قبل الإدارة قبل أن يتم تسجيله رسمياً في كشف حساب العميل.</p>
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
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $customer->name }}</h2>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $customer->code }}</div>
            </div>
        </div>
        
        <div class="bg-blue-50 dark:bg-slate-800 rounded-xl p-4 text-center border border-blue-100 dark:border-slate-700">
            <div class="text-sm text-gray-500 dark:text-slate-400 mb-1">الرصيد الحالي</div>
            <div class="text-3xl font-black text-blue-600 dark:text-blue-400">{{ number_format($customer->getCurrentBalance(), 2) }} <span class="text-sm font-bold">ج.م</span></div>
        </div>
    </div>

    <!-- Collection Form -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">بيانات التحصيل المعلق</h3>

        <form action="{{ route('collector.adhoc.store-collection', $customer) }}" method="POST" enctype="multipart/form-data">
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
                           value="{{ old('amount') }}"
                           class="w-full px-4 py-3 border-2 border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl focus:outline-none focus:border-emerald-500 text-lg font-black dark:text-white"
                           placeholder="0.00"
                           required>
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">ج.م</span>
                </div>
            </div>

            <!-- Cheque Details -->
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
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" list="banks" class="w-full px-4 py-3 border-2 border-white dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl focus:outline-none focus:border-amber-500 dark:text-white" placeholder="اختر البنك">
                </div>
                <div>
                    <label class="block text-amber-800 dark:text-amber-300 font-bold mb-2">رقم المرجع (Ref No) *</label>
                    <input type="text" name="reference_no" value="{{ old('reference_no') }}" class="w-full px-4 py-3 border-2 border-white dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl focus:outline-none focus:border-amber-500 font-mono dark:text-white">
                </div>
            </div>

            <!-- Receipt Number -->
            <div class="mb-5">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">رقم الإيصال (يدوي) *</label>
                <input type="text" 
                       name="receipt_no" 
                       value="{{ old('receipt_no', $receiptNo) }}"
                       class="w-full px-4 py-3 border-2 border-gray-100 dark:border-slate-700 rounded-xl focus:outline-none focus:border-emerald-500 text-lg bg-gray-50 dark:bg-slate-800 font-black dark:text-white"
                       required>
            </div>

            <!-- Attachment -->
            <div class="mb-5">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">إرفاق صورة الإيصال / الشيك</label>
                <input type="file" name="attachment" accept="image/*" class="w-full">
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">ملاحظات</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-3 border-2 border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl focus:outline-none focus:border-emerald-500 font-medium dark:text-white" placeholder="سبب التحصيل خارج الخطة...">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-4 rounded-xl font-black text-xl shadow-lg transition-all transform hover:scale-[1.02]">
                إرسال للمراجعة
            </button>
        </form>
    </div>
</div>

<script>
    function toggleFields(type) {
        const chequeFields = document.getElementById('cheque_fields');
        const transferFields = document.getElementById('transfer_fields');
        
        chequeFields.classList.add('hidden');
        transferFields.classList.add('hidden');
        
        if (type === 'cheque') {
            chequeFields.classList.remove('hidden');
        } else if (type === 'bank_transfer') {
            transferFields.classList.remove('hidden');
        }
    }
</script>
@endsection
