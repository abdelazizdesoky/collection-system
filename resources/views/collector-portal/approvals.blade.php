@extends('layouts.collector')

@section('title', 'متابعة الطلبات - بوابة المندوب')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 text-right" dir="rtl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 mb-2">متابعة الطلبات المعلقة</h1>
        <p class="text-gray-600">هنا يمكنك متابعة حالة الإجراءات التي قمت بها خارج الخطة وبانتظار موافقة الإدارة.</p>
    </div>

    <!-- Tabs Container -->
    <div x-data="{ tab: 'invoices' }">
        <!-- Tab Navigation -->
        <div class="flex gap-2 p-1 bg-gray-100 rounded-2xl mb-8">
            <button @click="tab = 'invoices'" 
                    :class="tab === 'invoices' ? 'bg-white text-violet-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-3 px-4 rounded-xl font-bold transition-all text-sm">
                مبيعات ({{ $pendingInvoices->count() }})
            </button>
            <button @click="tab = 'collections'" 
                    :class="tab === 'collections' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-3 px-4 rounded-xl font-bold transition-all text-sm">
                تحصيلات ({{ $pendingCollections->count() }})
            </button>
            <button @click="tab = 'visits'" 
                    :class="tab === 'visits' ? 'bg-white text-purple-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-3 px-4 rounded-xl font-bold transition-all text-sm">
                زيارات ({{ $pendingVisits->count() }})
            </button>
        </div>

        <!-- Invoices Tab -->
        <div x-show="tab === 'invoices'" x-transition.duration.300ms class="space-y-4">
            @forelse($pendingInvoices as $invoice)
            <div class="bg-white rounded-3xl shadow-md p-5 border border-gray-100 flex justify-between items-center group">
                <div class="flex items-center gap-4">
                    <div class="bg-violet-100 p-3 rounded-2xl text-violet-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <div>
                        <div class="font-black text-lg text-gray-900">{{ $invoice->customer->name }}</div>
                        <div class="text-xs text-gray-500 font-bold">كود الفاتورة: {{ $invoice->code }} | إجمالي: {{ number_format($invoice->total, 2) }} ج.م</div>
                    </div>
                </div>
                <div class="text-left">
                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">قيد المراجعة</span>
                    <div class="text-[10px] text-gray-400 mt-1">{{ $invoice->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                <p class="text-gray-400 font-bold">لا يوجد فواتير معلقة حالياً</p>
            </div>
            @endforelse
        </div>

        <!-- Collections Tab -->
        <div x-show="tab === 'collections'" x-transition.duration.300ms class="space-y-4">
            @forelse($pendingCollections as $collection)
            <div class="bg-white rounded-3xl shadow-md p-5 border border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="bg-emerald-100 p-3 rounded-2xl text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <div class="font-black text-lg text-gray-900">{{ $collection->customer->name }}</div>
                        <div class="text-xs text-gray-500 font-bold">إيصال: {{ $collection->receipt_no }} | مبلغ: {{ number_format($collection->amount, 2) }} ج.م</div>
                    </div>
                </div>
                <div class="text-left">
                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">قيد المراجعة</span>
                    <div class="text-[10px] text-gray-400 mt-1">{{ $collection->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                <p class="text-gray-400 font-bold">لا يوجد تحصيلات معلقة حالياً</p>
            </div>
            @endforelse
        </div>

        <!-- Visits Tab -->
        <div x-show="tab === 'visits'" x-transition.duration.300ms class="space-y-4">
            @forelse($pendingVisits as $visit)
            <div class="bg-white rounded-3xl shadow-md p-5 border border-gray-100 flex justify-between items-center group">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-3 rounded-2xl text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    </div>
                    <div>
                        <div class="font-black text-lg text-gray-900">{{ $visit->customer->name }}</div>
                        <div class="text-xs text-gray-500 font-bold">نوع الزيارة: {{ $visit->visit_type }}</div>
                    </div>
                </div>
                <div class="text-left">
                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">قيد المراجعة</span>
                    <div class="text-[10px] text-gray-400 mt-1">{{ $visit->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                <p class="text-gray-400 font-bold">لا يوجد زيارات معلقة حالياً</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
