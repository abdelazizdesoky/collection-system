@extends('layouts.collector')

@section('title', 'إيصال #' . $collection->formatted_receipt_no)

@section('content')
<div class="max-w-md mx-auto">
    <!-- Action Buttons (No Print) -->
    <div class="flex flex-wrap gap-4 mb-6 no-print">
        <button onclick="handlePrint()" 
                class="flex-1 @if($collection->print_count >= 3) bg-red-600 hover:bg-red-700 @else bg-emerald-600 hover:bg-emerald-700 @endif text-white py-4 px-4 rounded-xl shadow-md transition-colors text-center font-bold flex items-center justify-center gap-2 text-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            طباعة (نسخة {{ $collection->print_count }} من 3)
        </button>

        <a href="{{ $returnUrl }}" 
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-4 px-6 rounded-xl font-bold transition-colors text-center flex items-center justify-center">
            إنهاء
        </a>
    </div>

    <!-- Receipt Container -->
    <div class="bg-white dark:bg-dark-card rounded-xl shadow-lg border border-gray-100 dark:border-dark-border p-6" id="receipt">
        <!-- Thermal Header -->
        <div class="text-center border-b-4 border-black pb-2 mb-4 header-section">
            @if($logo = get_setting('company_logo'))
                <div class="flex justify-center mb-2 logo-container">
                    <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="max-h-16 w-auto print-logo">
                </div>
            @endif
            <h1 class="font-black text-black mb-1 leading-tight company-name">{{ get_setting('company_name', 'Alarabia Group') }}</h1>
            <p class="font-bold text-black receipt-title">إيصال تحصيل نقدية/شيك</p>
            @if($activity = get_setting('company_activity'))
                <p class="text-black opacity-80 mt-1 company-activity">{{ $activity }}</p>
            @endif
        </div>

        <!-- Receipt Info -->
        <div class="text-center mb-4 info-section">
            <div class="font-bold text-black receipt-no-label">رقم الإيصال</div>
            <div class="font-black text-black receipt-no-value">#{{ $collection->formatted_receipt_no }}</div>
            @if($collection->print_count > 1)
                <div class="inline-block mt-2 px-4 py-1 bg-gray-200 text-black border-2 border-black font-black print-version">
                    نسخة رقم {{ $collection->print_count }}
                </div>
            @endif
            <div class="text-black mt-1 font-bold receipt-date">{{ $collection->collection_date->format('Y/m/d H:i') }}</div>
        </div>

        <!-- Details Table -->
        <div class="space-y-2 details-section mb-4">
            <div class="flex justify-between items-start border-b-2 border-black pb-2">
                <span class="text-black min-w-[100px] font-black">العميل:</span>
                <span class="font-black text-right text-black customer-name">{{ $collection->customer->name }}</span>
            </div>
            
            <div class="flex justify-between items-center border-b-2 border-black pb-2">
                <span class="text-black font-black">المندوب:</span>
                <span class="font-black text-right text-black collector-name">{{ $collection->collector->name }}</span>
            </div>

            @if($collection->payment_type === 'cheque' || $collection->payment_type === 'bank_transfer')
                <div class="flex justify-between border-b-2 border-dashed border-black pb-2">
                    <span class="text-black font-black">اسم البنك:</span>
                    <span class="text-black font-black">{{ $collection->bank_name ?: ($collection->cheque->bank_name ?? 'N/A') }}</span>
                </div>
            @endif
            
            <div class="flex justify-between items-center border-b-2 border-black pb-2">
                <span class="text-black font-black">طريقة الدفع:</span>
                <span class="font-black text-right text-black payment-type">
                    @if($collection->payment_type === 'cash') نقدي
                    @elseif($collection->payment_type === 'cheque') شيك
                    @else تحويل بنكي @endif
                </span>
            </div>

            @if($collection->payment_type === 'cheque' && $collection->cheque)
                <div class="bg-gray-100 p-3 border-4 border-black text-black font-black space-y-1 cheque-details">
                    <div class="flex justify-between"><span>رقم الشيك:</span> <b class="text-black">{{ $collection->cheque->cheque_no }}</b></div>
                    <div class="flex justify-between"><span>البنك:</span> <b class="text-black">{{ $collection->cheque->bank_name }}</b></div>
                    <div class="flex justify-between"><span>تاريخ الاستحقاق:</span> <b class="text-black">{{ $collection->cheque->due_date->format('Y/m/d') }}</b></div>
                </div>
            @elseif($collection->payment_type === 'bank_transfer')
                <div class="bg-gray-100 p-3 border-4 border-black text-black font-black space-y-1 transfer-details">
                    <div class="flex justify-between"><span>البنك:</span> <b class="text-black">{{ $collection->bank_name }}</b></div>
                    <div class="flex justify-between"><span>رقم المرجع:</span> <b class="font-mono text-black">{{ $collection->reference_no }}</b></div>
                </div>
            @endif

            <!-- Grand Total -->
            <div class="border-4 border-black text-black p-4 text-center mt-4 amount-section">
                <div class="font-black mb-0 amount-label">المبلغ المحصل</div>
                <div class="font-black amount-value">{{ number_format($collection->amount, 2) }} <small class="currency">ج.م</small></div>
                <div class="font-bold text-lg mt-1 tafqeet-value">{{ tafqeet($collection->amount) }}</div>
            </div>
        </div>

        @if($collection->notes)
            <div class="text-black mb-4 text-center font-black p-3 border-2 border-black italic notes-section text-lg">
                * {{ $collection->notes }}
            </div>
        @endif

        <!-- QR & Footer Section -->
        <div class="text-center py-1 border-t-4 border-black qr-section">
            <div class="inline-block p-1 bg-white border-2 border-black">
                @php
                    $qrPayload = "Receipt: #{$collection->receipt_no}\nCustomer: {$collection->customer->name}\nAmount: {$collection->amount} EGP\nDate: {$collection->collection_date->format('Y-m-d')}";
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($qrPayload);
                @endphp
                <img src="{{ $qrUrl }}" alt="QR Code" class="w-32 h-32 mx-auto qr-img">
            </div>
            <p class="text-black mt-0 font-black footer-thanks">شكراً لتعاملكم معنا</p>
        </div>

        <!-- Footer Contact -->
        <div class="text-center text-black footer-contact">
            @if($address = get_setting('company_address')) <p class="mb-0 font-bold text-xs">{{ $address }}</p> @endif
            @if($phone = get_setting('company_phone')) <p class="font-black text-sm">ت: {{ $phone }}</p> @endif
        </div>
    </div>
</div>

<script>
    function handlePrint() {
        window.print();
        // Redirect to returnUrl after print dialog
        setTimeout(() => {
            window.location.href = "{{ $returnUrl }}";
        }, 1500);
    }
    
    // Fallback for some browsers where print blocks execution
    window.onafterprint = function() {
        window.location.href = "{{ $returnUrl }}";
    };
</script>

@push('scripts')
<script>
    // Scripts are handled in the content block for maximum reliability
</script>
@endpush

<style>
    @media print {
        @page {
            margin: 0;
            size: 210mm auto; /* Fixed 80mm roll width */
        }
        html, body {
            height: auto !important;
            min-height: 0 !important;
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 210mm !important;
            -webkit-print-color-adjust: exact;
            color: black !important;
            font-family: 'Cairo', Arial, sans-serif;
            font-weight: 900 !important;
            overflow: visible !important;
        }
        #receipt { 
            box-shadow: none !important; 
            border: none !important; 
            width: 210mm !important; 
            margin: 0 !important;
            padding: 2mm 2mm 0 2mm !important;
            border-radius: 0 !important;
            display: block !important;
            page-break-inside: avoid;
            page-break-after: avoid;
        }
        .max-w-md { max-width: 100% !important; width: 100% !important; }
        .no-print { display: none !important; }
        .print-only { display: block !important; }
        
        .print-logo { max-height: 100mm !important; margin: 0 auto 0 !important; display: block !important; -webkit-print-color-adjust: exact; filter: grayscale(100%) contrast(100%); }
        
        /* Ultra-bold, High-contrast sizes */
        .company-name { font-size: 26pt !important; margin-bottom: 2mm !important; font-weight: 900 !important; }
        .receipt-title { font-size: 20pt !important; font-weight: 900 !important; }
        .company-activity { font-size: 14pt !important; font-weight: 900 !important; }
        
        .receipt-no-label { font-size: 16pt !important; font-weight: 900 !important; }
        .receipt-no-value { font-size: 38pt !important; margin: 3mm 0 !important; font-weight: 900 !important; }
        .receipt-date { font-size: 16pt !important; font-weight: 900 !important; }
        .print-version { font-size: 16pt !important; padding: 2mm !important; font-weight: 900 !important; border-width: 3px !important; }
        
        .details-section span { font-size: 22pt !important; font-weight: 900 !important; }
        .customer-name { font-size: 24pt !important; font-weight: 900 !important; }
        
        .amount-section { border: 6px solid black !important; margin-top: 5mm !important; padding: 6mm !important; }
        .amount-label { font-size: 20pt !important; font-weight: 900 !important; }
        .amount-value { font-size: 52pt !important; font-weight: 900 !important; }
        .currency { font-size: 20pt !important; }
        .tafqeet-value { font-size: 20pt !important; font-weight: 900 !important; margin-top: 1mm !important; }
        
        .notes-section { font-size: 18pt !important; margin-bottom: 5mm !important; padding: 3mm !important; border-width: 3px !important; }
        .footer-thanks { font-size: 14pt !important; margin-top: 0mm !important; font-weight: 900 !important; }
        .footer-contact p { font-size: 15pt !important; font-weight: 900 !important; line-height: 1 !important; }
        
        .qr-section { padding-top: 1mm !important; border-top-width: 2px !important; }
        .qr-img { width: 65mm !important; height: 65mm !important; }
        .footer-contact { border-top: none !important; padding-top: 0 !important; }
        
        /* Eliminate all Gray/Opacity for Thermal */
        * { color: black !important; border-color: black !important; opacity: 1 !important; }
        .bg-gray-100, .bg-gray-200 { background-color: #eee !important; -webkit-print-color-adjust: exact; border: 1.5px solid black !important; }
        
        /* Spacing */
        .mb-6, .mb-4, .mb-2 { margin-bottom: 0mm !important; }
        .pb-6, .pb-4, .pb-2 { padding-bottom: 0mm !important; }
        .mt-6, .mt-4, .mt-2 { margin-top: 2mm !important; }
        
        /* Ensure no extra space at the very bottom */
        body { margin-bottom: 0 !important; padding-bottom: 0 !important; }
        .footer-contact { margin-bottom: 0 !important; padding-bottom: 0 !important; }
        main, .container, .py-12, .py-6 { padding-top: 0 !important; padding-bottom: 0 !important; margin-top: 0 !important; margin-bottom: 0 !important; }
    }
</style>
@endsection
