@extends('layouts.collector')

@section('title', 'نقطة البيع - POS')

@section('content')
<div x-data="posSystem()" class="max-w-6xl mx-auto h-[calc(100vh-120px)] flex flex-col md:flex-row gap-6 p-2" dir="rtl">
    
    <!-- Left: Products Catalog -->
    <div class="flex-1 bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border flex flex-col overflow-hidden">
        <!-- Search & Category Header -->
        <div class="p-6 border-b border-gray-50 dark:border-slate-800">
            <div class="relative group">
                <input type="text" 
                       x-model="searchQuery" 
                       placeholder="ابحث عن منتج بالاسم أو الكود..." 
                       class="w-full pl-4 pr-12 py-4 bg-gray-50 dark:bg-slate-800 border-2 border-transparent focus:border-violet-500 rounded-2xl dark:text-white font-bold transition-all shadow-inner">
                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 text-gray-400 group-focus-within:text-violet-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            
            <div class="flex gap-4 mt-6">
                <div class="flex-1">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 mr-2">المستودع المختار</label>
                    <select x-model="selectedWarehouseId" class="w-full bg-violet-50 dark:bg-violet-900/10 border-none rounded-xl py-3 px-4 text-violet-700 dark:text-violet-300 font-black text-sm transition-all focus:ring-2 focus:ring-violet-500">
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Scrollable Grid -->
        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="p in filteredProducts" :key="p.id">
                    <div @click="addToCart(p)" 
                         class="group relative bg-gray-50 dark:bg-slate-800/50 hover:bg-violet-50 dark:hover:bg-violet-900/20 border-2 border-transparent hover:border-violet-400 rounded-3xl p-4 transition-all cursor-pointer transform hover:-translate-y-1 active:scale-95">
                        
                        <!-- Stock Badge -->
                        <div class="absolute top-3 left-3 flex flex-col gap-1 items-end">
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-black" 
                                  :class="getWarehouseStock(p) > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'"
                                  x-text="'المخزن: ' + getWarehouseStock(p)"></span>
                            <template x-if="p.is_low_stock">
                                <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-lg text-[9px] font-black">⚠️ منخفض</span>
                            </template>
                        </div>

                        <div class="w-12 h-12 bg-white dark:bg-slate-700 rounded-2xl shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>

                        <h4 class="font-black text-gray-800 dark:text-white text-sm mb-1 leading-tight" x-text="p.name"></h4>
                        <div class="text-[10px] text-gray-400 font-bold mb-3" x-text="p.code"></div>
                        
                        <div class="flex justify-between items-end">
                            <div class="text-violet-600 dark:text-violet-400 font-black text-lg">
                                <span x-text="formatNumber(p.selling_price)"></span>
                                <span class="text-xs">ج.م</span>
                            </div>
                            <div class="bg-violet-500 text-white p-2 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Right: Cart & Checkout -->
    <div class="w-full md:w-[400px] bg-white dark:bg-dark-card rounded-3xl shadow-2xl border border-gray-100 dark:border-dark-border flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="p-6 bg-violet-600 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-black">سلة المشتريات</h3>
                <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold" x-text="cart.length + ' أصناف'"></span>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-violet-200 uppercase mb-1">العميل المستهدف</label>
                    <select x-model="customerId" class="w-full bg-white/10 hover:bg-white/20 border-none rounded-xl py-2 px-3 text-white font-bold text-sm focus:ring-2 focus:ring-white/50 transition-all">
                        <option value="" class="text-gray-800">-- اختر العميل --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" class="text-gray-800">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-violet-200 uppercase mb-1">طريقة السداد</label>
                    <div class="grid grid-cols-3 gap-2">
                        <template x-for="type in paymentTypes">
                            <button @click="paymentType = type.id" 
                                    :class="paymentType === type.id ? 'bg-white text-violet-700' : 'bg-white/10 text-white'"
                                    class="py-2 px-1 rounded-xl text-[10px] font-black transition-all border border-transparent"
                                    x-text="type.label"></button>
                        </template>
                    </div>
                </div>

                <!-- Conditional Installment Section -->
                <div x-show="paymentType === 'installment'" 
                     x-transition:enter="transition ease-out duration-300" 
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     class="bg-white/10 p-4 rounded-2xl border border-white/20 space-y-3 mt-2">
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-[10px] font-black text-violet-100 uppercase mb-1">المقدم</label>
                      <input type="number" x-model.number="paidAmount" class="w-full bg-white/10 border-none rounded-xl py-2 px-3 text-white font-bold text-xs">
                    </div>
                    <div>
                      <label class="block text-[10px] font-black text-violet-100 uppercase mb-1">الفائدة %</label>
                      <input type="number" x-model.number="interestRate" class="w-full bg-white/10 border-none rounded-xl py-2 px-3 text-white font-bold text-xs">
                    </div>
                  </div>
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-[10px] font-black text-violet-100 uppercase mb-1">المدة (شهور)</label>
                      <input type="number" x-model.number="duration" class="w-full bg-white/10 border-none rounded-xl py-2 px-3 text-white font-bold text-xs">
                    </div>
                    <div>
                      <label class="block text-[10px] font-black text-violet-100 uppercase mb-1">تاريخ البدء</label>
                      <input type="date" x-model="startDate" class="w-full bg-white/10 border-none rounded-xl py-2 px-3 text-white font-bold text-xs">
                    </div>
                  </div>
                </div>

                <!-- Conditional Paid Amount (Cash/Credit) -->
                <div x-show="paymentType !== 'installment'">
                    <label class="block text-[10px] font-black text-violet-200 uppercase mb-1" x-text="paymentType === 'cash' ? 'المبلغ المدفوع' : 'دفعة مقدمة (اختياري)'"></label>
                    <input type="number" x-model.number="paidAmount" 
                           :disabled="paymentType === 'cash'"
                           class="w-full bg-white/10 border-none rounded-xl py-2 px-3 text-white font-bold text-sm">
                </div>
            </div>
        </div>

        <!-- Cart Items List -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
            <template x-if="cart.length === 0">
                <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-4">
                    <div class="bg-gray-50 dark:bg-slate-800 p-8 rounded-full">
                        <svg class="w-16 h-16 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="font-black text-sm">السلة فارغة حالياً</p>
                </div>
            </template>

            <template x-for="(item, index) in cart" :key="item.id">
                <div class="flex items-center gap-3 bg-gray-50 dark:bg-slate-800/50 p-3 rounded-2xl border border-transparent hover:border-violet-200 transition-all group">
                    <div class="w-10 h-10 bg-white dark:bg-slate-700 rounded-lg flex items-center justify-center font-black text-violet-600 text-xs shadow-sm" x-text="index + 1"></div>
                    
                    <div class="flex-1 min-w-0">
                        <h5 class="font-black text-gray-800 dark:text-white text-xs truncate" x-text="item.name"></h5>
                        <div class="text-[10px] text-violet-600 dark:text-violet-400 font-bold" x-text="formatNumber(item.price) + ' لكل وحدة'"></div>
                    </div>

                    <div class="flex items-center bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-700 px-1">
                        <button @click="updateQty(item, -1)" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/></svg>
                        </button>
                        <input type="number" 
                               x-model.number="item.qty" 
                               @input="validateStock(item)"
                               class="w-10 text-center bg-transparent border-none text-xs font-black p-0 focus:ring-0 dark:text-white">
                        <button @click="updateQty(item, 1)" class="p-1.5 text-emerald-500 hover:bg-emerald-50 rounded-lg transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>

                    <div class="text-right min-w-[60px]">
                        <div class="text-xs font-black text-gray-900 dark:text-white" x-text="formatNumber(item.qty * item.price)"></div>
                        <button @click="removeFromCart(index)" class="text-[9px] text-red-400 font-bold hover:text-red-600">حذف</button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Checkout Footer -->
        <div class="p-6 bg-gray-50 dark:bg-slate-900/80 border-t border-gray-100 dark:border-slate-800 space-y-4">
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-bold">المجموع الفرعي</span>
                    <span class="text-gray-800 dark:text-white font-black" x-text="formatNumber(cartTotal()) + ' ج.م'"></span>
                </div>
                <div class="flex justify-between items-center bg-violet-600 text-white p-4 rounded-2xl shadow-xl shadow-violet-500/20">
                    <span class="text-xs font-black uppercase tracking-widest opacity-80">الإجمالي النهائي</span>
                    <span class="text-2xl font-black" x-text="formatNumber(cartTotal()) + ' ج.م'"></span>
                </div>
            </div>

            <button @click="submitSale()" 
                    :disabled="cart.length === 0 || !customerId || processing"
                    class="w-full bg-black dark:bg-violet-500 text-white py-4 rounded-2xl font-black text-lg transition-all transform hover:scale-[1.02] active:scale-95 disabled:opacity-30 flex items-center justify-center gap-3">
                <template x-if="processing">
                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </template>
                <template x-if="!processing">
                    <div class="flex items-center gap-2">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                         تأكيد وإتمام البيع
                    </div>
                </template>
            </button>
        </div>
    </div>
</div>

<form id="pos-submit-form" action="{{ route('pos.store') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="customer_id" id="hidden_customer_id">
    <input type="hidden" name="warehouse_id" id="hidden_warehouse_id">
    <input type="hidden" name="payment_type" id="hidden_payment_type">
    <input type="hidden" name="paid_amount" id="hidden_paid_amount">
    <input type="hidden" name="increase_percentage" id="hidden_increase_percentage">
    <input type="hidden" name="duration_months" id="hidden_duration_months">
    <input type="hidden" name="start_date" id="hidden_start_date">
    <div id="hidden_items"></div>
</form>

<script>
function posSystem() {
    return {
        products: @json($products),
        warehouses: @json($warehouses),
        searchQuery: '',
        selectedWarehouseId: '{{ $warehouses->first()->id ?? "" }}',
        customerId: '',
        paymentType: 'cash',
        paidAmount: 0,
        interestRate: 0,
        duration: 12,
        startDate: '{{ date("Y-m-d", strtotime("+1 month")) }}',
        cart: [],
        processing: false,
        paymentTypes: [
            { id: 'cash', label: 'نقدي (كاش)' },
            { id: 'credit', label: 'آجل' },
            { id: 'installment', label: 'تقسيط' }
        ],

        get filteredProducts() {
            if (!this.searchQuery) return this.products;
            const q = this.searchQuery.toLowerCase();
            return this.products.filter(p => 
                p.name.toLowerCase().includes(q) || 
                p.code.toLowerCase().includes(q)
            );
        },

        getWarehouseStock(product) {
            return product.warehouse_stocks[this.selectedWarehouseId] || 0;
        },

        addToCart(product) {
            const stock = this.getWarehouseStock(product);
            if (stock <= 0) {
                alert('عذراً، هذا المنتج غير متوفر في المستودع المختار حالياً.');
                return;
            }

            const existing = this.cart.find(item => item.id === product.id);
            if (existing) {
                if (existing.qty + 1 > stock) {
                    alert('لا يمكنك تجاوز الكمية المتوفرة بالمخزن.');
                    return;
                }
                existing.qty++;
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.selling_price,
                    qty: 1
                });
            }
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        updateQty(item, delta) {
            const product = this.products.find(p => p.id === item.id);
            const stock = this.getWarehouseStock(product);
            const newQty = item.qty + delta;

            if (newQty <= 0) {
                this.removeFromCart(this.cart.indexOf(item));
                return;
            }
            if (newQty > stock) {
                alert('الكمية المتاحة في المخزن هي: ' + stock);
                return;
            }
            item.qty = newQty;
        },

        validateStock(item) {
            const product = this.products.find(p => p.id === item.id);
            const stock = this.getWarehouseStock(product);
            if (item.qty > stock) {
                item.qty = stock;
                alert('تم تعديل الكمية لأقصى رصيد متاح بالمخزن.');
            }
        },

        cartTotal() {
            return this.cart.reduce((sum, item) => sum + (item.qty * item.price), 0);
        },

        formatNumber(num) {
            return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(num);
        },

        submitSale() {
            if (this.processing) return;

            // Ensure paidAmount is correct for cash
            if(this.paymentType === 'cash') this.paidAmount = this.cartTotal();

            this.processing = true;

            const form = document.getElementById('pos-submit-form');
            document.getElementById('hidden_customer_id').value = this.customerId;
            document.getElementById('hidden_warehouse_id').value = this.selectedWarehouseId;
            document.getElementById('hidden_payment_type').value = this.paymentType;
            document.getElementById('hidden_paid_amount').value = this.paidAmount;
            
            if (this.paymentType === 'installment') {
              document.getElementById('hidden_increase_percentage').value = this.interestRate;
              document.getElementById('hidden_duration_months').value = this.duration;
              document.getElementById('hidden_start_date').value = this.startDate;
            }

            const itemsContainer = document.getElementById('hidden_items');
            itemsContainer.innerHTML = '';
            
            this.cart.forEach((item, index) => {
                itemsContainer.innerHTML += `
                    <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.qty}">
                    <input type="hidden" name="items[${index}][unit_price]" value="${item.price}">
                `;
            });

            form.submit();
        }
    }
}
</script>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>
@endsection
