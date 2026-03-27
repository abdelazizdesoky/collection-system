@extends('layouts.collector')

@section('title', 'إصدار فاتورة بيع')

@section('content')
<div class="max-w-3xl mx-auto space-y-4 pb-12" dir="rtl">
    <!-- Header -->
    <div class="bg-gradient-to-l from-violet-600 to-fuchsia-600 rounded-3xl shadow-xl p-6 text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-white/10" style="clip-path: polygon(0 0, 100% 0, 100% 60%, 0 100%);"></div>
        
        <div class="flex justify-between items-start relative z-10 mb-2">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('collector.dashboard') }}" class="bg-white/20 p-2 rounded-xl backdrop-blur-sm hover:bg-white/30 transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm shadow-inner">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div class="w-9 h-9"></div><!-- Spacer for centering -->
        </div>
        
        <h1 class="text-2xl font-black relative z-10">إصدار فاتورة بيع جديدة</h1>
        <p class="text-violet-100 text-sm mt-1 relative z-10">إنشاء فاتورة وتسجيل المبيعات للعميل</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl text-sm font-bold flex flex-col gap-1">
            @foreach($errors->all() as $e)
                <span>• {{ $e }}</span>
            @endforeach
        </div>
    @endif

    <form action="{{ route('collector.sale-invoice.store') }}" method="POST" id="invoice-form" class="space-y-4">
        @csrf
        @if(request('adhoc'))
            <input type="hidden" name="adhoc" value="1">
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3 shadow-sm mb-4">
                <div class="text-amber-500 mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-amber-900 leading-tight">فاتورة خارج الخطة (تحت المراجعة)</p>
                    <p class="text-[10px] text-amber-700 mt-0.5">هذه الفاتورة لن تؤثر على المخزون أو الحسابات إلا بعد مراجعة واعتماد الإدارة.</p>
                </div>
            </div>
        @endif
        
        <!-- Basic Info Box -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-lg border border-gray-100 dark:border-dark-border p-5 space-y-4">
            <h3 class="text-lg font-black text-gray-800 dark:text-gray-100 mb-2 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-violet-500 rounded-full inline-block"></span>
                بيانات الفاتورة
            </h3>

            <!-- Customer -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">العميل <span class="text-red-500">*</span></label>
                <select name="customer_id" required class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-2xl dark:text-white focus:ring-2 focus:ring-violet-500 transition-all font-bold text-sm">
                    <option value="">-- اختر العميل --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ (old('customer_id') == $c->id || $customerId == $c->id) ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <!-- Warehouse -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">المخزن <span class="text-red-500">*</span></label>
                    <select name="warehouse_id" required class="w-full px-3 py-3 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-2xl dark:text-white text-sm">
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}" {{ old('warehouse_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Payment Type -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">نوع السداد</label>
                    <select name="payment_type" id="payment_type" class="w-full px-3 py-3 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-2xl dark:text-white text-sm font-bold text-violet-700 dark:text-violet-400">
                        <option value="cash">نقدي (كاش)</option>
                        <option value="credit">آجل (على الحساب)</option>
                        <option value="installment">تقسيط</option>
                    </select>
                </div>
            </div>

            <!-- Installment Details for Collector (Hidden by default) -->
            <div id="installment_fields" class="hidden bg-pink-50 dark:bg-pink-900/10 p-5 rounded-3xl border border-pink-100 dark:border-pink-900/30 space-y-4 animate-[fade-in_0.3s_ease-out]">
                <h3 class="text-sm font-black text-pink-700 dark:text-pink-400 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    خطة التقسيط
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1">المقدم (اختياري)</label>
                        <input type="number" step="0.01" name="paid_amount" value="0" class="w-full px-3 py-2.5 bg-white dark:bg-dark-bg border border-gray-200 dark:border-slate-600 rounded-xl dark:text-white text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1">نسبة الفائدة %</label>
                        <input type="number" step="0.01" name="increase_percentage" value="0" class="w-full px-3 py-2.5 bg-white dark:bg-dark-bg border border-gray-200 dark:border-slate-600 rounded-xl dark:text-white text-sm font-bold">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1">المدة (شهور)</label>
                        <input type="number" name="duration_months" value="12" class="w-full px-3 py-2.5 bg-white dark:bg-dark-bg border border-gray-200 dark:border-slate-600 rounded-xl dark:text-white text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1">تاريخ أول قسط</label>
                        <input type="date" name="start_date" value="{{ date('Y-m-d', strtotime('+1 month')) }}" class="w-full px-3 py-2.5 bg-white dark:bg-dark-bg border border-gray-200 dark:border-slate-600 rounded-xl dark:text-white text-sm font-bold">
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1.5">ملاحظات الفاتورة</label>
                <input type="text" name="notes" value="{{ old('notes') }}" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl dark:text-white text-sm" placeholder="ملاحظات اختيارية..." />
            </div>
        </div>

        <!-- Items Box -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-lg border border-gray-100 dark:border-dark-border p-5 space-y-4">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-black text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-pink-500 rounded-full inline-block"></span>
                    الأصناف المبيعة
                </h3>
                <button type="button" onclick="addRow()" class="bg-gray-100 dark:bg-slate-700 text-violet-600 dark:text-violet-400 p-2 rounded-xl text-xs font-bold hover:bg-violet-50 transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    أضف صنف
                </button>
            </div>

            <!-- Items Container -->
            <div id="items-container" class="space-y-3">
                <div class="item-row bg-gray-50/80 dark:bg-slate-800/80 p-3 rounded-2xl border border-gray-100 dark:border-slate-700 relative">
                    <button type="button" onclick="removeRow(this)" class="absolute top-2 left-2 text-red-400 hover:text-red-600 bg-red-50 dark:bg-red-900/20 p-1.5 rounded-lg z-10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    
                    <div class="mb-2">
                        <label class="block text-xs font-bold text-gray-500 mb-1">المنتج (الصنف)</label>
                        <select name="items[0][product_id]" required class="w-full px-3 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl dark:text-white text-sm font-bold select-product" onchange="fillPrice(this, 0)">
                            <option value="">-- اختر المنتج --</option>
                            @foreach($products as $p)
                                <option value="{{ $p['id'] }}" data-price="{{ $p['selling_price'] }}">{{ $p['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex gap-2 items-center">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-500 mb-1">الكمية</label>
                            <input type="number" step="0.01" name="items[0][quantity]" required min="0.01"  class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl dark:text-white text-sm font-bold text-center" oninput="calcRow(0)">
                        </div>
                        <div class="flex-[1.5]">
                            <label class="block text-xs font-bold text-gray-500 mb-1">السعر</label>
                            <input type="number" step="0.01" name="items[0][unit_price]" required min="0" id="price_0" class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl dark:text-white text-sm font-bold text-center" oninput="calcRow(0)">
                        </div>
                        <div class="flex-1 text-center bg-violet-50 dark:bg-violet-900/20 py-2 px-1 rounded-xl border border-violet-100 dark:border-violet-900/50 mt-5">
                            <span class="block text-[10px] text-violet-600 dark:text-violet-400 font-bold mb-0.5">الإجمالي</span>
                            <span class="block text-sm font-black text-violet-800 dark:text-violet-300" id="row_total_0">0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Totals Summary -->
        <div class="bg-violet-600 rounded-3xl shadow-xl p-5 text-white flex justify-between items-center mt-6">
            <div>
                <p class="text-violet-200 text-sm font-bold">إجمالي الفاتورة</p>
                <div class="text-3xl font-black mt-1" id="grand_total">0.00 <span class="text-sm font-normal">ج.م</span></div>
            </div>
            
            <button type="submit" id="submitBtn" class="bg-white text-violet-700 px-6 py-3.5 rounded-2xl font-black shadow-lg hover:shadow-white/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                تأكيد الفاتورة
            </button>
        </div>

    </form>
</div>

<script>
    const productsData = @json($products);

    document.getElementById('payment_type')?.addEventListener('change', function() {
        const instFields = document.getElementById('installment_fields');
        if (this.value === 'installment') {
            instFields.classList.remove('hidden');
        } else {
            instFields.classList.add('hidden');
        }
    });

    // Re-validate all rows if warehouse changes
    document.querySelector('select[name="warehouse_id"]')?.addEventListener('change', function() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach((row, i) => {
            const select = row.querySelector('.select-product');
            const idx = select.name.match(/\[(\d+)\]/)[1];
            calcRow(idx);
        });
    });

    let rowIndex = 1;
    function addRow() {
        const container = document.getElementById('items-container');
        const html = `
        <div class="item-row bg-gray-50/80 dark:bg-slate-800/80 p-3 rounded-2xl border border-gray-100 dark:border-slate-700 relative mt-3 animate-[fade-in_0.3s_ease-out]">
            <button type="button" onclick="removeRow(this)" class="absolute top-2 left-2 text-red-400 hover:text-red-600 bg-red-50 dark:bg-red-900/20 p-1.5 rounded-lg z-10 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <div class="mb-2">
                <label class="block text-xs font-bold text-gray-500 mb-1">المنتج (الصنف)</label>
                <select name="items[${rowIndex}][product_id]" required class="w-full px-3 py-2.5 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl dark:text-white text-sm font-bold select-product" onchange="fillPrice(this, ${rowIndex})">
                    <option value="">-- اختر المنتج --</option>
                    @foreach($products as $p)
                        <option value="{{ $p['id'] }}" data-price="{{ $p['selling_price'] }}">{{ $p['name'] }}</option>
                    @endforeach
                </select>
                <div id="stock_info_${rowIndex}" class="mt-1 flex gap-2"></div>
            </div>
            
            <div class="flex gap-2 items-center">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 mb-1">الكمية</label>
                    <input type="number" step="0.01" name="items[${rowIndex}][quantity]" required min="0.01" class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl dark:text-white text-sm font-bold text-center" oninput="calcRow(${rowIndex})">
                </div>
                <div class="flex-[1.5]">
                    <label class="block text-xs font-bold text-gray-500 mb-1">السعر</label>
                    <input type="number" step="0.01" name="items[${rowIndex}][unit_price]" required min="0" id="price_${rowIndex}" class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl dark:text-white text-sm font-bold text-center" oninput="calcRow(${rowIndex})">
                </div>
                <div class="flex-1 text-center bg-violet-50 dark:bg-violet-900/20 py-2 px-1 rounded-xl border border-violet-100 dark:border-violet-900/50 mt-5">
                    <span class="block text-[10px] text-violet-600 dark:text-violet-400 font-bold mb-0.5">الإجمالي</span>
                    <span class="block text-sm font-black text-violet-800 dark:text-violet-300" id="row_total_${rowIndex}">0.00</span>
                </div>
            </div>
        </div>`;
        
        container.insertAdjacentHTML('beforeend', html);
        rowIndex++;
        calcTotals();
    }
    
    function removeRow(btn) {
        const rows = document.querySelectorAll('.item-row');
        if(rows.length > 1) {
            btn.closest('.item-row').remove();
            calcTotals();
        } else {
            alert('يجب أن تحتوي الفاتورة على صنف واحد على الأقل.');
        }
    }
    
    function fillPrice(select, idx) {
        const opt = select.options[select.selectedIndex];
        const price = opt.dataset.price;
        const priceInput = document.getElementById('price_' + idx);
        if(priceInput && price) priceInput.value = price;
        calcRow(idx);
    }
    
    function calcRow(idx) {
        const form = document.getElementById('invoice-form');
        const warehouseId = form.querySelector('select[name="warehouse_id"]')?.value;
        const productId = form.querySelector(`[name="items[${idx}][product_id]"]`)?.value;
        const qtyInput = form.querySelector(`[name="items[${idx}][quantity]"]`);
        const priceInput = form.querySelector(`[name="items[${idx}][unit_price]"]`);
        const stockInfoDiv = document.getElementById('stock_info_' + idx);
        
        const qty = parseFloat(qtyInput?.value || 0);
        const price = parseFloat(priceInput?.value || 0);
        
        // Stock Validation Logic
        if (productId) {
            const p = productsData.find(item => item.id == productId);
            const warehouseStock = p.warehouse_stocks[warehouseId] || 0;
            
            let stockHtml = `<span class="text-[10px] font-bold px-2 py-0.5 rounded-full ${warehouseStock > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'}">متاح بالمخزن: ${warehouseStock}</span>`;
            
            if (p.is_low_stock && p.total_stock <= p.min_stock) {
                stockHtml += `<span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 animate-pulse">⚠️ مخزون منخفض كلياً</span>`;
            }

            if (qty > warehouseStock) {
                stockHtml += `<span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-600 text-white">الكمية غير متوفرة!</span>`;
                qtyInput.classList.add('border-red-500', 'bg-red-50');
            } else {
                qtyInput.classList.remove('border-red-500', 'bg-red-50');
            }
            
            if(stockInfoDiv) stockInfoDiv.innerHTML = stockHtml;
        } else {
            if(stockInfoDiv) stockInfoDiv.innerHTML = '';
        }

        const rowTotal = qty * price;
        const el = document.getElementById('row_total_' + idx);
        
        if(el) el.textContent = rowTotal.toFixed(2);
        calcTotals();
    }

    function calcTotals() {
        let gt = 0;
        const rows = document.querySelectorAll('.item-row');
        rows.forEach((row, i) => {
            // Find the index of this row based on its position or id
            const el = row.querySelector('[id^="row_total_"]');
            if(el) gt += parseFloat(el.textContent) || 0;
        });
        document.getElementById('grand_total').innerHTML = gt.toFixed(2) + ' <span class="text-sm font-normal">ج.م</span>';
        
        // Prevent submission if total is 0
        const submitBtn = document.getElementById('submitBtn');
        if (gt <= 0) {
            submitBtn.classList.add('opacity-50', 'pointer-events-none');
        } else {
            submitBtn.classList.remove('opacity-50', 'pointer-events-none');
        }
    }

    // Initialize calculate on page load to set button state properly
    document.addEventListener('DOMContentLoaded', function() {
        calcTotals();
    });
</script>

<style>
    @keyframes fade-in { 0% { opacity: 0; transform: translateY(-10px); } 100% { opacity: 1; transform: translateY(0); } }
</style>
@endsection
