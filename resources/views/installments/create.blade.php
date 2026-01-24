@extends('layouts.app')

@section('title', 'إنشاء خطة تقسيط')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 text-right" dir="rtl">
    <!-- Header -->
    <div class="mb-10">
        <div class="flex items-center gap-5">
            <div class="bg-amber-600 p-4 rounded-3xl shadow-2xl shadow-amber-500/30 text-white">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <div>
                <h1 class="text-4xl font-black dark:text-white tracking-tighter">إضافة خطة تقسيط جديدة</h1>
                <p class="text-gray-500 dark:text-gray-400 font-bold mt-1">قم بإدخال تفاصيل الفاتورة وحساب الأقسام تلقائياً</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-dark-card rounded-[2.5rem] shadow-2xl border border-gray-50 dark:border-dark-border p-10 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
        
        <form action="{{ route('installments.store') }}" method="POST" id="installmentForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                <!-- Customer Selection -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-black mb-3 uppercase tracking-widest">اختيار العميل</label>
                    <select name="customer_id" class="w-full select2-search" required data-placeholder="اختر العميل بالاسم أو الكود...">
                        <option value=""></option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Invoice Details -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-black mb-3 uppercase tracking-widest">رقم الفاتورة الأصلي</label>
                        <input type="text" name="invoice_no" placeholder="INV-2024-XXXX" class="w-full px-6 py-4 rounded-xl border-gray-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none font-bold" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-black mb-3 uppercase tracking-widest">إجمالي مبلغ الفاتورة</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="total_amount" id="total_amount" placeholder="0.00" class="w-full px-6 py-4 rounded-xl border-gray-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none font-black text-2xl tabular-nums" required>
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-bold">ج.م</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-black mb-3 uppercase tracking-widest">المقدم (بدفعة أولى)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="down_payment" id="down_payment" value="0.00" class="w-full px-6 py-4 rounded-xl border-gray-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none font-black text-2xl tabular-nums text-emerald-600" required>
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400 font-bold">ج.م</span>
                        </div>
                    </div>
                </div>

                <!-- Financing Params -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-black mb-3 uppercase tracking-widest">نسبة الفائدة (الزيادة)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="increase_percentage" id="increase_percentage" value="10.00" class="w-full px-6 py-4 rounded-xl border-gray-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none font-black text-2xl tabular-nums text-rose-500" required>
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-rose-400 font-bold">%</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-black mb-3 uppercase tracking-widest">مدة التقسيط</label>
                            <div class="relative">
                                <input type="number" name="duration_months" id="duration_months" value="12" class="w-full px-6 py-4 rounded-xl border-gray-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none font-black text-2xl tabular-nums text-blue-600" required>
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-400 font-bold">شهر</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-black mb-3 uppercase tracking-widest">تاريخ البداية (الأول)</label>
                            <input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="w-full px-6 py-4 rounded-xl border-gray-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none font-bold" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-black mb-3 uppercase tracking-widest">ملاحظات إضافية</label>
                        <textarea name="notes" rows="2" class="w-full px-6 py-4 rounded-xl border-gray-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all outline-none font-medium"></textarea>
                    </div>
                </div>

                <!-- Calculator Result Card -->
                <div class="md:col-span-2 bg-gray-50 dark:bg-dark-bg/50 rounded-[2rem] p-8 border border-gray-100 dark:border-dark-border flex flex-col md:flex-row justify-between items-center gap-8 mt-4 group">
                    <div class="text-center md:text-right">
                        <div class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">صافي المبلغ المراد تقسيطه (بعد المقدم + الفائدة)</div>
                        <div class="text-4xl font-black text-gray-900 dark:text-white tabular-nums group-hover:text-amber-600 transition-colors">
                            <span id="final_financed">0.00</span>
                            <span class="text-xs text-gray-400 uppercase tracking-tighter mr-2">جنيه مصري</span>
                        </div>
                    </div>
                    
                    <div class="h-14 w-px bg-gray-200 dark:bg-dark-border hidden md:block"></div>
                    
                    <div class="text-center md:text-left">
                        <div class="text-[10px] text-indigo-500 font-black uppercase tracking-widest mb-1">قيمة القسط الشهري الثابت</div>
                        <div class="text-4xl font-black text-indigo-600 dark:text-indigo-400 tabular-nums">
                            <span id="monthly_payment">0.00</span>
                            <span class="text-xs text-indigo-400 uppercase tracking-tighter ml-2">ج.م / قسط</span>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="md:col-span-2 pt-6">
                    <button type="submit" class="w-full bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-black py-5 px-8 rounded-3xl shadow-2xl shadow-amber-500/40 transition-all transform hover:scale-[1.02] active:scale-[0.98] text-xl flex items-center justify-center gap-4">
                        حفظ وتفعيل خطة التقسيط
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                    </button>
                    <p class="text-center text-xs text-gray-400 font-medium mt-4">بمجرد الحفظ، سيتم إضافة مديونية القسط بالكامل إلى كشف حساب العميل تلقائياً.</p>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const form = document.getElementById('installmentForm');
    const totalAmountInput = document.getElementById('total_amount');
    const downPaymentInput = document.getElementById('down_payment');
    const increaseInput = document.getElementById('increase_percentage');
    const durationInput = document.getElementById('duration_months');
    
    function calculate() {
        const total = parseFloat(totalAmountInput.value) || 0;
        const down = parseFloat(downPaymentInput.value) || 0;
        const increasePercent = parseFloat(increaseInput.value) || 0;
        const duration = parseInt(durationInput.value) || 1;

        const remaining = total - down;
        const interest = remaining * (increasePercent / 100);
        const finalFinanced = remaining + interest;
        const monthly = finalFinanced / duration;

        document.getElementById('final_financed').innerText = finalFinanced.toLocaleString('ar-EG', {minimumFractionDigits: 2});
        document.getElementById('monthly_payment').innerText = monthly.toLocaleString('ar-EG', {minimumFractionDigits: 2});
    }

    [totalAmountInput, downPaymentInput, increaseInput, durationInput].forEach(input => {
        input.addEventListener('input', calculate);
    });
</script>
@endsection
