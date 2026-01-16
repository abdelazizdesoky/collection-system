@extends('layouts.collector')

@section('title', 'إيصال #' . $collection->receipt_no)

@section('content')
<div class="max-w-lg mx-auto">
    <!-- Action Buttons (No Print) -->
    <div class="flex flex-wrap gap-4 mb-6 no-print">
        @if($collection->planItem)
            <a href="{{ route('collector.plan', $collection->planItem->collection_plan_id) }}" 
               class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white py-3 px-4 rounded-xl shadow-md transition-colors text-center font-bold flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                العودة للخطة اليومية
            </a>
        @endif
        
        <button onclick="window.print()" 
                class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white py-3 px-4 rounded-xl shadow-md transition-colors text-center font-bold flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            طباعة الإيصال
        </button>

        <a href="{{ route('collector.dashboard') }}" 
           class="w-full bg-white hover:bg-gray-50 text-gray-700 py-3 px-4 rounded-xl shadow-sm border border-gray-200 transition-colors text-center font-medium flex items-center justify-center gap-2 mt-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            العودة للرئيسية
        </a>
    </div>

    <!-- Receipt Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" id="receipt">
        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-8 text-white text-center">
            <h1 class="text-2xl font-bold mb-2">Alarabia Group</h1>
            <p class="opacity-80">إيصال تحصيل</p>
        </div>

        <!-- Receipt Number -->
        <div class="bg-emerald-50 px-6 py-4 text-center border-b">
            <div class="text-sm text-emerald-600 mb-1">رقم الإيصال</div>
            <div class="text-2xl font-bold text-emerald-700">{{ $collection->receipt_no }}</div>
        </div>

        <!-- Receipt Details -->
        <div class="p-6 space-y-4">
            <!-- Date -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500">التاريخ</span>
                <span class="font-medium text-gray-800">{{ $collection->collection_date->format('Y/m/d') }}</span>
            </div>

            <!-- Customer -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500">اسم العميل</span>
                <span class="font-medium text-gray-800">{{ $collection->customer->name }}</span>
            </div>

            <!-- Collector -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500">المحصل</span>
                <span class="font-medium text-gray-800">{{ $collection->collector->name }}</span>
            </div>

            <!-- Payment Type -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500">طريقة الدفع</span>
                <span class="font-bold text-gray-800">
                    @if($collection->payment_type === 'cash')
                        نقدي
                    @elseif($collection->payment_type === 'cheque')
                        شيك
                    @else
                        تحويل بنكي
                    @endif
                </span>
            </div>

            @if($collection->payment_type === 'cheque' && $collection->cheque)
                <!-- Cheque Details -->
                <div class="bg-blue-50/50 p-4 rounded-xl space-y-2 text-sm border border-blue-100">
                    <div class="flex justify-between">
                        <span class="text-blue-600">رقم الشيك:</span>
                        <span class="font-bold">{{ $collection->cheque->cheque_no }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-600">البنك:</span>
                        <span class="font-bold">{{ $collection->cheque->bank_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-600">تاريخ الاستحقاق:</span>
                        <span class="font-bold">{{ $collection->cheque->due_date->format('Y/m/d') }}</span>
                    </div>
                </div>
            @elseif($collection->payment_type === 'bank_transfer')
                <!-- Transfer Details -->
                <div class="bg-amber-50/50 p-4 rounded-xl space-y-2 text-sm border border-amber-100">
                    <div class="flex justify-between">
                        <span class="text-amber-600">البنك / البرنامج:</span>
                        <span class="font-bold">{{ $collection->bank_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-amber-600">رقم المرجع (Ref):</span>
                        <span class="font-bold font-mono">{{ $collection->reference_no }}</span>
                    </div>
                </div>
            @endif

            <!-- Amount -->
            <div class="flex justify-between items-center py-4 bg-emerald-50 -mx-6 px-6 rounded-lg">
                <span class="text-emerald-600 font-bold text-lg">المبلغ المحصل</span>
                <span class="text-3xl font-black text-emerald-600">{{ number_format($collection->amount, 2) }} ج.م</span>
            </div>

            @if($collection->attachment)
                <!-- Attachment Proof -->
                <div class="py-4 border-t border-gray-100 no-print">
                    <span class="text-gray-500 block mb-3 font-bold">إثبات الدفع (صورة)</span>
                    <div class="rounded-2xl overflow-hidden border-2 border-gray-100 shadow-sm">
                        <img src="{{ asset('storage/' . $collection->attachment) }}" alt="إثبات الدفع" class="w-full h-auto max-h-64 object-cover">
                    </div>
                    <a href="{{ asset('storage/' . $collection->attachment) }}" target="_blank" class="mt-2 text-blue-600 text-xs font-bold flex items-center gap-1 hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        فتح الصورة بحجم كامل
                    </a>
                </div>
            @endif

            @if($collection->notes)
                <!-- Notes -->
                <div class="py-3 border-t border-gray-100 italic">
                    <span class="text-gray-500 block mb-1 text-xs">ملاحظات إضافية</span>
                    <span class="text-gray-700 font-medium leading-relaxed">{{ $collection->notes }}</span>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 text-center text-sm text-gray-500 border-t">
            <p>شكراً لتعاملكم معنا</p>
            <p class="mt-1">© 2026 Alarabia Group</p>
        </div>
    </div>

    <!-- Signature Area (Print Only) -->
    <div class="hidden print-only mt-8">
        <div class="flex justify-between px-8">
            <div class="text-center">
                <div class="border-t-2 border-gray-400 w-32 mb-2"></div>
                <div class="text-sm text-gray-600">توقيع العميل</div>
            </div>
            <div class="text-center">
                <div class="border-t-2 border-gray-400 w-32 mb-2"></div>
                <div class="text-sm text-gray-600">توقيع المحصل</div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background: white !important; }
        .no-print { display: none !important; }
        #receipt { box-shadow: none !important; border-radius: 0 !important; }
        .print-only { display: block !important; }
    }
</style>
@endsection
