@extends('layouts.app')
@section('title', 'فاتورة شراء جديدة')
@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('purchase-invoices.index') }}" class="bg-gray-100 dark:bg-dark-card p-3 rounded-2xl hover:bg-gray-200 transition-colors"><svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg></a>
        <h1 class="text-2xl font-black text-gray-900 dark:text-white">فاتورة شراء جديدة</h1>
    </div>
    <form action="{{ route('purchase-invoices.store') }}" method="POST" id="invoice-form">
        @csrf
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">رقم الفاتورة</label>
                    <input type="text" name="code" value="{{ old('code', $nextCode) }}" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">
                    @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror</div>
                <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">المورد <span class="text-red-500">*</span></label>
                    <select name="supplier_id" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:bg-dark-card dark:text-white select2-search">
                        <option value="">-- اختر المورد --</option>
                        @foreach($suppliers as $s)<option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }} ({{ $s->code }})</option>@endforeach
                    </select></div>
                <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">المخزن <span class="text-red-500">*</span></label>
                    <select name="warehouse_id" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:bg-dark-card dark:text-white">
                        <option value="">-- اختر المخزن --</option>
                        @foreach($warehouses as $w)<option value="{{ $w->id }}" {{ old('warehouse_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>@endforeach
                    </select></div>
                <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">تاريخ الفاتورة <span class="text-red-500">*</span></label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white"></div>
                <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">تاريخ الاستحقاق</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white"></div>
                <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">نوع السداد <span class="text-red-500">*</span></label>
                    <select name="payment_type" id="payment_type" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:bg-dark-card dark:text-white">
                        <option value="cash">نقدي</option>
                        <option value="credit">آجل</option>
                        <option value="installment">تقسيط</option>
                    </select></div>
            </div>

            <!-- Items -->
            <div class="border-t border-gray-100 dark:border-dark-border pt-6">
                <h3 class="text-lg font-black text-gray-900 dark:text-white mb-4">بنود الفاتورة</h3>
                <div id="items-container" class="space-y-3">
                    <div class="item-row grid grid-cols-12 gap-3 items-end">
                        <div class="col-span-4"><label class="block text-xs font-bold text-gray-500 mb-1">المنتج</label>
                            <select name="items[0][product_id]" required class="w-full px-3 py-2 border border-gray-200 dark:border-dark-border rounded-xl bg-transparent dark:bg-dark-card dark:text-white text-sm product-select" onchange="fillPrice(this, 0, 'purchase')">
                                <option value="">اختر</option>
                                @foreach($products as $p)<option value="{{ $p->id }}" data-purchase-price="{{ $p->purchase_price }}" data-selling-price="{{ $p->selling_price }}">{{ $p->name }} ({{ $p->code }})</option>@endforeach
                            </select></div>
                        <div class="col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1">الكمية</label>
                            <input type="number" step="0.01" name="items[0][quantity]" required min="0.01" class="w-full px-3 py-2 border border-gray-200 dark:border-dark-border rounded-xl bg-transparent dark:text-white text-sm" onchange="calcRow(0)"></div>
                        <div class="col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1">سعر الوحدة</label>
                            <input type="number" step="0.01" name="items[0][unit_price]" required min="0" id="price_0" class="w-full px-3 py-2 border border-gray-200 dark:border-dark-border rounded-xl bg-transparent dark:text-white text-sm" onchange="calcRow(0)"></div>
                        <div class="col-span-2"><label class="block text-xs font-bold text-gray-500 mb-1">خصم البند</label>
                            <input type="number" step="0.01" name="items[0][discount]" value="0" min="0" class="w-full px-3 py-2 border border-gray-200 dark:border-dark-border rounded-xl bg-transparent dark:text-white text-sm" onchange="calcRow(0)"></div>
                        <div class="col-span-1"><label class="block text-xs font-bold text-gray-500 mb-1">الإجمالي</label>
                            <span class="block py-2 text-sm font-bold text-gray-900 dark:text-white" id="row_total_0">0.00</span></div>
                        <div class="col-span-1 flex items-end pb-1">
                            <button type="button" onclick="removeRow(this)" class="bg-red-100 dark:bg-red-900/30 text-red-700 p-2 rounded-xl hover:bg-red-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addRow()" class="mt-4 bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 px-4 py-2 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    إضافة بند
                </button>
            </div>

            <!-- Totals -->
            <div class="border-t border-gray-100 dark:border-dark-border pt-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">خصم إجمالي</label>
                        <input type="number" step="0.01" name="discount" value="0" min="0" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white"></div>
                    <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ضريبة</label>
                        <input type="number" step="0.01" name="tax" value="0" min="0" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white"></div>
                    <div id="paid_amount_wrapper"><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">المبلغ المدفوع</label>
                        <input type="number" step="0.01" name="paid_amount" value="0" min="0" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white"></div>
                    <div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ملاحظات</label>
                        <input type="text" name="notes" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white"></div>
                </div>
            </div>

            <!-- Installment Details (Hidden by default) -->
            <div id="installment_fields" class="hidden bg-orange-50/50 dark:bg-orange-900/10 p-6 rounded-3xl border border-orange-100 dark:border-orange-900/30 space-y-4">
                <h3 class="text-lg font-black text-orange-900 dark:text-orange-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    تفاصيل خطة التقسيط بالدفع
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">نسبة الفائدة (%)</label>
                        <input type="number" step="0.01" name="increase_percentage" value="0" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-white dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-orange-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">مدة التقسيط (شهور)</label>
                        <input type="number" name="duration_months" value="12" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-white dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-orange-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">تاريخ أول قسط</label>
                        <input type="date" name="start_date" value="{{ date('Y-m-d', strtotime('+1 month')) }}" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-white dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-orange-500 transition-all">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-gradient-to-l from-emerald-600 to-teal-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg transition-all">حفظ الفاتورة</button>
                <a href="{{ route('purchase-invoices.index') }}" class="bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 px-8 py-3 rounded-2xl font-bold transition-all">إلغاء</a>
            </div>
        </div>
    </form>
</div>

<script>
let rowIndex = 1;
function addRow() {
    const container = document.getElementById('items-container');
    const html = `<div class="item-row grid grid-cols-12 gap-3 items-end">
        <div class="col-span-4"><select name="items[${rowIndex}][product_id]" required class="w-full px-3 py-2 border border-gray-200 dark:border-dark-border rounded-xl bg-transparent dark:bg-dark-card dark:text-white text-sm" onchange="fillPrice(this, ${rowIndex}, 'purchase')">
            <option value="">اختر</option>
            @foreach($products as $p)<option value="{{ $p->id }}" data-purchase-price="{{ $p->purchase_price }}" data-selling-price="{{ $p->selling_price }}">{{ $p->name }}</option>@endforeach
        </select></div>
        <div class="col-span-2"><input type="number" step="0.01" name="items[${rowIndex}][quantity]" required min="0.01" class="w-full px-3 py-2 border border-gray-200 dark:border-dark-border rounded-xl bg-transparent dark:text-white text-sm" onchange="calcRow(${rowIndex})"></div>
        <div class="col-span-2"><input type="number" step="0.01" name="items[${rowIndex}][unit_price]" required min="0" id="price_${rowIndex}" class="w-full px-3 py-2 border border-gray-200 dark:border-dark-border rounded-xl bg-transparent dark:text-white text-sm" onchange="calcRow(${rowIndex})"></div>
        <div class="col-span-2"><input type="number" step="0.01" name="items[${rowIndex}][discount]" value="0" min="0" class="w-full px-3 py-2 border border-gray-200 dark:border-dark-border rounded-xl bg-transparent dark:text-white text-sm" onchange="calcRow(${rowIndex})"></div>
        <div class="col-span-1"><span class="block py-2 text-sm font-bold text-gray-900 dark:text-white" id="row_total_${rowIndex}">0.00</span></div>
        <div class="col-span-1 flex items-end pb-1"><button type="button" onclick="removeRow(this)" class="bg-red-100 text-red-700 p-2 rounded-xl hover:bg-red-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></div>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
    rowIndex++;
}
function removeRow(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) btn.closest('.item-row').remove();
}
function fillPrice(select, idx, type) {
    const opt = select.options[select.selectedIndex];
    const price = type === 'purchase' ? opt.dataset.purchasePrice : opt.dataset.sellingPrice;
    const priceInput = document.getElementById('price_' + idx);
    if (priceInput && price) priceInput.value = price;
    calcRow(idx);
}
function calcRow(idx) {
    const form = document.getElementById('invoice-form');
    const qty = parseFloat(form.querySelector(`[name="items[${idx}][quantity]"]`)?.value || 0);
    const price = parseFloat(form.querySelector(`[name="items[${idx}][unit_price]"]`)?.value || 0);
    const disc = parseFloat(form.querySelector(`[name="items[${idx}][discount]"]`)?.value || 0);
    const total = (qty * price) - disc;
    const el = document.getElementById('row_total_' + idx);
    if (el) el.textContent = total.toFixed(2);
}
document.getElementById('payment_type')?.addEventListener('change', function() {
    const paidWrapper = document.getElementById('paid_amount_wrapper');
    const installmentFields = document.getElementById('installment_fields');
    
    if (this.value === 'cash') {
        paidWrapper.classList.add('hidden');
        if(installmentFields) installmentFields.classList.add('hidden');
    } else if (this.value === 'installment') {
        paidWrapper.classList.remove('hidden');
        if(installmentFields) installmentFields.classList.remove('hidden');
    } else {
        paidWrapper.classList.remove('hidden');
        if(installmentFields) installmentFields.classList.add('hidden');
    }
});
</script>
@endsection
