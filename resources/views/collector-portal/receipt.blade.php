@extends('layouts.collector')

@section('title', 'إيصال #' . $collection->formatted_receipt_no)

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
           class="w-full bg-white dark:bg-dark-card hover:bg-gray-50 dark:hover:bg-slate-700/50 text-gray-700 dark:text-gray-300 py-3 px-4 rounded-xl shadow-sm border border-gray-200 dark:border-dark-border transition-colors text-center font-medium flex items-center justify-center gap-2 mt-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            العودة للرئيسية
        </a>
    </div>

    <!-- Receipt Card -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-dark-border" id="receipt">
        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-8 text-white text-center">
            <h1 class="text-2xl font-bold mb-2">{{ get_setting('company_name', 'Alarabia Group') }}</h1>
            <p class="opacity-80">إيصال تحصيل</p>
            @if($activity = get_setting('company_activity'))
                <p class="text-xs opacity-70 mt-1">{{ $activity }}</p>
            @endif
        </div>

        <!-- Receipt Number -->
        <div class="bg-emerald-50 dark:bg-emerald-900/20 px-6 py-4 text-center border-b border-emerald-100 dark:border-emerald-500/20">
            <div class="flex flex-col items-center gap-1">
                <div class="text-sm text-emerald-600 dark:text-emerald-400 font-bold uppercase tracking-wider">رقم الإيصال</div>
                <div class="text-3xl font-black text-emerald-700 dark:text-emerald-300">#{{ $collection->formatted_receipt_no }}</div>
                @if($collection->print_count > 1)
                    <div class="mt-1 px-3 py-0.5 bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400 rounded-full text-[10px] font-black">
                        نسخة رقم {{ $collection->print_count }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Receipt Details -->
        <div class="p-6 space-y-4">
            <!-- Date -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-slate-700">
                <span class="text-gray-500 dark:text-slate-400">التاريخ</span>
                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $collection->collection_date->format('Y/m/d') }}</span>
            </div>

            <!-- Customer -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-slate-700">
                <span class="text-gray-500 dark:text-slate-400">اسم العميل</span>
                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $collection->customer->name }}</span>
            </div>

            <!-- Collector -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-slate-700">
                <span class="text-gray-500 dark:text-slate-400">المندوب</span>
                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $collection->collector->name }}</span>
            </div>

            <!-- Payment Type -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-slate-700">
                <span class="text-gray-500 dark:text-slate-400">طريقة الدفع</span>
                <span class="font-bold text-gray-800 dark:text-gray-200">
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
            <div class="flex justify-between items-center py-4 bg-emerald-50 dark:bg-emerald-900/20 -mx-6 px-6 rounded-lg">
                <span class="text-emerald-600 dark:text-emerald-400 font-bold text-lg">المبلغ المندوب</span>
                <span class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($collection->amount, 2) }} ج.م</span>
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
        <div class="bg-gray-50 dark:bg-slate-800/50 px-6 py-4 text-center text-sm text-gray-500 dark:text-slate-400 border-t border-gray-100 dark:border-slate-700">
            <p>شكراً لتعاملكم معنا</p>
            @if($address = get_setting('company_address'))
                <p class="mt-1 text-xs">{{ $address }}</p>
            @endif
            @if($phone = get_setting('company_phone'))
                <p class="text-xs">{{ $phone }}</p>
            @endif
            <p class="mt-1">© 2026 {{ get_setting('company_name', 'Alarabia Group') }}</p>
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
                <div class="text-sm text-gray-600">توقيع المندوب</div>
            </div>
        </div>
    </div>
</div>

<script>
    // Print session control: Redirect away after the print dialog closes
    window.addEventListener('afterprint', function() {
        @php
            $isCollector = auth()->user()->hasRole('collector');
            $redirectUrl = route('dashboard'); 
            
            if ($collection->planItem) {
                $redirectUrl = $isCollector 
                    ? route('collector.plan', $collection->planItem->collection_plan_id)
                    : route('visit-plans.show', $collection->planItem->visit_plan_id);
            } elseif ($isCollector) {
                $redirectUrl = route('collector.dashboard');
            }
        @endphp
        window.location.href = "{{ $redirectUrl }}";
    });
</script>

<style>
    @media print {
        @page {
            margin: 0;
            size: 80mm auto; /* Standard POS width */
        }
        body { 
            background: white !important; 
            margin: 0;
            padding: 0;
            width: 80mm;
            -webkit-print-color-adjust: exact;
        }
        .no-print { display: none !important; }
        .print-only { display: block !important; }
        
        #receipt { 
            box-shadow: none !important; 
            border: none !important; 
            width: 80mm !important;
            margin: 0 !important;
            padding: 2mm !important;
            border-radius: 0 !important;
            background: white !important;
            color: black !important;
        }

        /* POS Specific Styling */
        .bg-gradient-to-r, .bg-emerald-600, .bg-teal-600, .bg-emerald-50, .bg-blue-50, .bg-amber-50, .bg-gray-50, .dark\:bg-dark-card {
            background: transparent !important;
            color: black !important;
            border-bottom: 1px dashed #000 !important;
        }
        
        .text-white, .text-emerald-100, .text-emerald-600, .text-emerald-700, .text-blue-600, .text-amber-600, .text-gray-500, .text-gray-700, .dark\:text-gray-200 {
            color: black !important;
        }

        .border-b, .border-t, .border, .border-gray-100, .border-emerald-100 {
            border-color: black !important;
            border-style: dashed !important;
            border-width: 0 0 1px 0 !important;
        }

        .flex-row, .flex {
            display: flex !important;
        }

        h1, h2, .font-black, .font-bold {
            color: black !important;
            font-weight: bold !important;
        }

        .rounded-2xl, .rounded-xl, .rounded-lg {
            border-radius: 0 !important;
        }

        /* Logo/Header Optimization */
        .bg-gradient-to-r {
            padding: 5mm 0 !important;
            text-align: center !important;
        }

        /* Better Spacing for Thermal */
        .p-6 { padding: 2mm !important; }
        .py-8 { padding-top: 4mm !important; padding-bottom: 4mm !important; }
        .space-y-4 > :not([hidden]) ~ :not([hidden]) { margin-top: 2mm !important; }
        
        /* Amount highlight for thermal */
        .bg-emerald-50 {
            border: 1px solid black !important;
            margin: 2mm 0 !important;
            padding: 3mm !important;
        }
    }
</style>
@endsection
