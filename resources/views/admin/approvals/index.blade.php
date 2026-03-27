@extends('layouts.app')

@section('title', 'مراجعة الإجراءات المعلقة')

@section('content')
<div class="px-4 py-8 max-w-7xl mx-auto" dir="rtl" x-data="{ tab: 'invoices', selectedItem: null, actionType: '', itemId: '' }">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white">طلبات المراجعة والاعتماد</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">إجراءات المندوبين خارج الخطط التي تتطلب موافقة إدارية لتفعيل تأثيرها المالي والمخزني.</p>
        </div>
        <div class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-4 py-2 rounded-2xl font-bold flex items-center gap-2 border border-amber-200 dark:border-amber-800">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
            </span>
            إجمالي المعلق: {{ $pendingInvoices->count() + $pendingCollections->count() + $pendingVisits->count() }}
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex gap-4 mb-8 bg-gray-100 dark:bg-slate-800 p-1.5 rounded-3xl w-fit">
        <button @click="tab = 'invoices'" 
                :class="tab === 'invoices' ? 'bg-white dark:bg-slate-700 text-violet-600 dark:text-violet-400 shadow-lg' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
                class="px-8 py-3 rounded-2xl font-black transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            مبيعات
            <span class="bg-violet-100 dark:bg-violet-900/40 text-violet-700 dark:text-violet-300 px-2 py-0.5 rounded-lg text-xs">{{ $pendingInvoices->count() }}</span>
        </button>
        <button @click="tab = 'collections'" 
                :class="tab === 'collections' ? 'bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-lg' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
                class="px-8 py-3 rounded-2xl font-black transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            تحصيلات
            <span class="bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 px-2 py-0.5 rounded-lg text-xs">{{ $pendingCollections->count() }}</span>
        </button>
        <button @click="tab = 'visits'" 
                :class="tab === 'visits' ? 'bg-white dark:bg-slate-700 text-purple-600 dark:text-purple-400 shadow-lg' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
                class="px-8 py-3 rounded-2xl font-black transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            زيارات
            <span class="bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 px-2 py-0.5 rounded-lg text-xs">{{ $pendingVisits->count() }}</span>
        </button>
    </div>

    <!-- Content Sections -->
    <div class="space-y-6">
        
        <!-- Tab: Invoices -->
        <div x-show="tab === 'invoices'" x-transition @class(['hidden' => $pendingInvoices->isEmpty()])>
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-slate-700">
                <table class="w-full text-right">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-900/50 text-gray-400 text-xs font-black uppercase tracking-widest border-b border-gray-100 dark:border-slate-700">
                            <th class="px-6 py-4">المندوب / التاريخ</th>
                            <th class="px-6 py-4">العميل / الفاتورة</th>
                            <th class="px-6 py-4">الإجمالي / المدفوع</th>
                            <th class="px-6 py-4">النوع</th>
                            <th class="px-6 py-4 text-left">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @foreach($pendingInvoices as $invoice)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $invoice->creator->name }}</div>
                                <div class="text-[10px] text-gray-500">{{ $invoice->created_at->format('Y-m-d H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-black text-gray-900 dark:text-white">{{ $invoice->customer->name }}</div>
                                <div class="text-xs text-violet-600 font-bold">#{{ $invoice->code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-black text-gray-900 dark:text-white">{{ number_format($invoice->total, 2) }} ج.م</div>
                                <div class="text-xs text-emerald-600 font-bold">المقدم: {{ number_format($invoice->paid_amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-600 dark:text-gray-400">
                                {{ $invoice->payment_type === 'cash' ? 'نقدي' : ($invoice->payment_type === 'credit' ? 'آجل' : 'تقسيط') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <button @click="selectedItem = 'invoice'; itemId = {{ $invoice->id }}; actionType = 'approve'" class="p-2 bg-emerald-100 text-emerald-700 rounded-xl hover:bg-emerald-600 hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                    <button @click="selectedItem = 'invoice'; itemId = {{ $invoice->id }}; actionType = 'reject'" class="p-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-600 hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab: Collections -->
        <div x-show="tab === 'collections'" x-transition @class(['hidden' => $pendingCollections->isEmpty()])>
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-slate-700">
                <table class="w-full text-right">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-900/50 text-gray-400 text-xs font-black uppercase tracking-widest border-b border-gray-100 dark:border-slate-700">
                            <th class="px-6 py-4">المندوب / التاريخ</th>
                            <th class="px-6 py-4">العميل / الإيصال</th>
                            <th class="px-6 py-4">المبلغ</th>
                            <th class="px-6 py-4">طريقة الدفع</th>
                            <th class="px-6 py-4 text-left">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @foreach($pendingCollections as $collection)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $collection->collector->name }}</div>
                                <div class="text-[10px] text-gray-500">{{ $collection->created_at->format('Y-m-d H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-black text-gray-900 dark:text-white">{{ $collection->customer->name }}</div>
                                <div class="text-xs text-emerald-600 font-bold">إيصال: #{{ $collection->receipt_no }}</div>
                            </td>
                            <td class="px-6 py-4 font-black text-emerald-600">
                                {{ number_format($collection->amount, 2) }} ج.م
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-600 dark:text-gray-400">
                                {{ $collection->payment_type === 'cash' ? 'نقدي' : ($collection->payment_type === 'cheque' ? 'شيك' : 'تحويل') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <button @click="selectedItem = 'collection'; itemId = {{ $collection->id }}; actionType = 'approve'" class="p-2 bg-emerald-100 text-emerald-700 rounded-xl hover:bg-emerald-600 hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                    <button @click="selectedItem = 'collection'; itemId = {{ $collection->id }}; actionType = 'reject'" class="p-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-600 hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab: Visits -->
        <div x-show="tab === 'visits'" x-transition @class(['hidden' => $pendingVisits->isEmpty()])>
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-slate-700">
                <table class="w-full text-right">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-900/50 text-gray-400 text-xs font-black uppercase tracking-widest border-b border-gray-100 dark:border-slate-700">
                            <th class="px-6 py-4">المندوب / التاريخ</th>
                            <th class="px-6 py-4">العميل / النوع</th>
                            <th class="px-6 py-4">الملاحظات</th>
                            <th class="px-6 py-4 text-left">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @foreach($pendingVisits as $visit)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $visit->collector->name }}</div>
                                <div class="text-[10px] text-gray-500">{{ $visit->created_at->format('Y-m-d H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-black text-gray-900 dark:text-white">{{ $visit->customer->name }}</div>
                                <div class="text-xs text-purple-600 font-bold">{{ $visit->visitType->display_name ?? $visit->visit_type }}</div>
                            </td>
                            <td class="px-6 py-4 max-w-md">
                                <p class="text-xs text-gray-500 line-clamp-2">{{ $visit->notes }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <button @click="selectedItem = 'visit'; itemId = {{ $visit->id }}; actionType = 'approve'" class="p-2 bg-emerald-100 text-emerald-700 rounded-xl hover:bg-emerald-600 hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                    <button @click="selectedItem = 'visit'; itemId = {{ $visit->id }}; actionType = 'reject'" class="p-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-600 hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State (Global) -->
        <div x-show="(tab === 'invoices' && {{ $pendingInvoices->count() }} === 0) || (tab === 'collections' && {{ $pendingCollections->count() }} === 0) || (tab === 'visits' && {{ $pendingVisits->count() }} === 0)" 
             class="text-center py-20 bg-gray-50 dark:bg-slate-800/50 rounded-3xl border-2 border-dashed border-gray-200 dark:border-slate-700">
            <svg class="w-16 h-16 text-gray-300 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-2.286 6.857A2 2 0 0115.819 21H8.181a2 2 0 01-1.895-1.143L4 13"/></svg>
            <h3 class="text-xl font-bold text-gray-400">لا يوجد طلبات معلقة من هذا النوع</h3>
        </div>

    </div>

    <!-- Approval/Rejection Modal (Hidden by default) -->
    <div x-show="selectedItem" 
         x-transition.opacity 
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         @click.away="selectedItem = null"
         style="display: none;">
        
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-gray-100 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-xl font-black text-gray-900 dark:text-white" x-text="actionType === 'approve' ? 'اعتماد العملية' : 'رفض العملية'"></h3>
                <button @click="selectedItem = null" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form :action="'{{ url('approvals') }}/' + selectedItem + '/' + itemId + '/' + actionType" method="POST" class="p-6">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ملاحظات المراجعة (اختياري)</label>
                    <textarea name="notes" rows="4" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-2xl focus:ring-2 transition-all dark:text-white" :class="actionType === 'approve' ? 'focus:ring-emerald-500' : 'focus:ring-red-500'" :placeholder="actionType === 'approve' ? 'أضف ملاحظات الاعتماد...' : 'لماذا تم رفض هذا الإجراء؟'"></textarea>
                </div>
                
                <div class="flex gap-4">
                    <button type="button" @click="selectedItem = null" class="flex-1 py-3 px-4 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 rounded-xl font-bold transition-all hover:bg-gray-200 dark:hover:bg-slate-600">إلغاء</button>
                    <button type="submit" 
                            :class="actionType === 'approve' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700'"
                            class="flex-1 py-3 px-4 text-white rounded-xl font-black shadow-lg transition-all transform hover:scale-105"
                            x-text="actionType === 'approve' ? 'تأكيد الموافقة' : 'تأكيد الرفض'">
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
