@extends('layouts.app')
@section('title', $supplier->name)
@section('content')
<div x-data="{ activeTab: 'overview' }" class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('suppliers.index') }}" class="bg-gray-100 dark:bg-dark-card p-3 rounded-2xl hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ $supplier->name }}</h1>
                <p class="text-sm text-gray-500 font-bold">كود المورد: {{ $supplier->code }} {{ $supplier->phone ? '• '.$supplier->phone : '' }}</p>
            </div>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('accounting.adjustments.supplier', $supplier) }}" class="bg-emerald-600 text-white px-6 py-3 rounded-2xl font-black shadow-lg hover:shadow-emerald-500/20 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                تسوية يدوية
            </a>
            <a href="{{ route('suppliers.edit', $supplier) }}" class="bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 px-6 py-3 rounded-2xl font-black transition-all flex items-center justify-center">تعديل</a>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex gap-4 border-b border-gray-100 dark:border-dark-border overflow-x-auto pb-px">
        <button @click="activeTab = 'overview'" 
            :class="activeTab === 'overview' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 font-black border-b-2' : 'text-gray-500 font-bold'"
            class="px-6 py-3 text-sm transition-all whitespace-nowrap">
            نظرة عامة
        </button>
        <button @click="activeTab = 'ledger'" 
            :class="activeTab === 'ledger' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 font-black border-b-2' : 'text-gray-500 font-bold'"
            class="px-6 py-3 text-sm transition-all whitespace-nowrap">
            كشف الحساب (Ledger)
        </button>
        <button @click="activeTab = 'invoices'" 
            :class="activeTab === 'invoices' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 font-black border-b-2' : 'text-gray-500 font-bold'"
            class="px-6 py-3 text-sm transition-all whitespace-nowrap">
            الفواتير ({{ $supplier->purchaseInvoices->count() }})
        </button>
    </div>

    <!-- Tab Contents -->
    
    <!-- Overview Tab -->
    <div x-show="activeTab === 'overview'" x-transition class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-dark-card rounded-3xl p-6 border border-gray-100 dark:border-dark-border shadow-soft text-right">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/10 rounded-2xl text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">رصيد البداية</p>
                </div>
                <p class="text-3xl font-black text-gray-900 dark:text-white">{{ number_format($supplier->opening_balance, 2) }}</p>
                <p class="text-xs text-gray-500 font-bold mt-1">ج.م ({{ $supplier->balance_type === 'credit' ? 'للمورد' : 'على المورد' }})</p>
            </div>
            
            <div class="bg-white dark:bg-dark-card rounded-3xl p-6 border border-gray-100 dark:border-dark-border shadow-soft text-right">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-red-50 dark:bg-red-900/10 rounded-2xl text-red-600 dark:text-red-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">إجمالي المشتريات</p>
                </div>
                <p class="text-3xl font-black text-red-600">{{ number_format($supplier->purchaseInvoices->where('status','confirmed')->sum('total'), 2) }}</p>
                <p class="text-xs text-gray-500 font-bold mt-1">من واقع الفواتير المؤكدة</p>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-3xl p-6 border border-gray-100 dark:border-dark-border shadow-soft text-right">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-900/10 rounded-2xl text-emerald-600 dark:text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">إجمالي المسدد</p>
                </div>
                <p class="text-3xl font-black text-emerald-600">{{ number_format($supplier->purchasePayments->sum('amount'), 2) }}</p>
                <p class="text-xs text-gray-500 font-bold mt-1">دفعات نقدية وشيكات</p>
            </div>
        </div>

        <div class="bg-gradient-to-l from-emerald-600 to-teal-700 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
             <div class="relative z-10 flex justify-between items-end">
                <div class="text-right">
                    <p class="text-emerald-100 font-bold mb-2">الرصيد النهائي المستحق للمورد (Creditor)</p>
                    <p class="text-5xl font-black">{{ number_format($supplier->getCurrentBalance(), 2) }} <span class="text-base font-normal">ج.م</span></p>
                </div>
                <div class="opacity-20">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
             </div>
        </div>
    </div>

    <!-- Ledger Tab -->
    <div x-show="activeTab === 'ledger'" x-transition class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-gray-50/50 dark:bg-dark-bg/20">
            <h2 class="text-lg font-black text-gray-900 dark:text-white">كشف حساب المورد (Statement)</h2>
            <button onclick="window.print()" class="text-xs font-bold text-emerald-600 hover:underline">طباعة الكشف</button>
        </div>
        <div class="overflow-x-auto text-right">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-100 dark:bg-dark-tableheader text-gray-500 font-black">
                    <th class="px-6 py-4">التاريخ</th>
                    <th class="px-6 py-4">البيان / الحركة</th>
                    <th class="px-6 py-4">دائن (+)</th>
                    <th class="px-6 py-4">مدين (-)</th>
                    <th class="px-6 py-4 text-emerald-600">الرصيد</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border font-bold">
                     <tr class="bg-emerald-50/20 dark:bg-emerald-900/5">
                        <td class="px-6 py-4 text-gray-400 italic">البداية</td>
                        <td class="px-6 py-4 dark:text-gray-300">رصيد افتتاحي</td>
                        <td class="px-6 py-4">{{ $supplier->balance_type === 'credit' ? number_format($supplier->opening_balance, 2) : '0.00' }}</td>
                        <td class="px-6 py-4">{{ $supplier->balance_type === 'debit' ? number_format($supplier->opening_balance, 2) : '0.00' }}</td>
                        <td class="px-6 py-4 font-black">{{ number_format($supplier->opening_balance, 2) }}</td>
                    </tr>
                    @foreach($supplier->accounts as $acc)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-bg/30 transition-all">
                        <td class="px-6 py-4 text-gray-500">{{ $acc->date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 dark:text-white">{{ $acc->description }}</td>
                        <td class="px-6 py-4 text-orange-600">{{ $acc->credit > 0 ? '+'.number_format($acc->credit, 2) : '-' }}</td>
                        <td class="px-6 py-4 text-emerald-600">{{ $acc->debit > 0 ? '-'.number_format($acc->debit, 2) : '-' }}</td>
                        <td class="px-6 py-4 font-black text-lg">{{ number_format($acc->balance, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Invoices Tab -->
    <div x-show="activeTab === 'invoices'" x-transition class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-border flex justify-between items-center">
            <h2 class="text-lg font-black text-gray-900 dark:text-white">سجل الفواتير</h2>
            <a href="{{ route('purchase-invoices.create', ['supplier_id' => $supplier->id]) }}" class="text-sm font-bold text-emerald-600 hover:underline">+ فاتورة شراء جديدة</a>
        </div>
        <div class="overflow-x-auto text-right">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 dark:bg-dark-tableheader font-bold text-gray-500">
                    <th class="px-6 py-4">رقم الفاتورة</th>
                    <th class="px-6 py-4">التاريخ</th>
                    <th class="px-6 py-4">الإجمالي</th>
                    <th class="px-6 py-4">المدفوع</th>
                    <th class="px-6 py-4 text-center">الحالة</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                    @forelse($supplier->purchaseInvoices as $inv)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-bg/30">
                        <td class="px-6 py-4"><a href="{{ route('purchase-invoices.show', $inv) }}" class="font-bold text-emerald-600 hover:underline"># {{ $inv->code }}</a></td>
                        <td class="px-6 py-4 text-gray-500 font-bold">{{ $inv->invoice_date->format('Y/m/d') }}</td>
                        <td class="px-6 py-4 font-black">{{ number_format($inv->total, 2) }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600">{{ number_format($inv->paid_amount, 2) }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($inv->status === 'confirmed') <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-black uppercase">مؤكدة</span>
                            @elseif($inv->status === 'draft') <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-black uppercase">مسودة</span>
                            @else <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-black uppercase">ملغاة</span> @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400 font-bold">لا توجد فواتير مسجلة لهذا المورد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
