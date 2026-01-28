<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إيصالات الأقساط - {{ $plan->customer->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-after: always; break-after: page; }
        }
        .receipt-card {
            border: 2px solid #e2e8f0;
            margin-bottom: 2rem;
            padding: 2rem;
            border-radius: 1rem;
            position: relative;
        }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto no-print mb-8 flex justify-between items-center">
        <h1 class="text-2xl font-bold">معاينة طباعة الأقساط</h1>
        <button onclick="window.print()" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg">طباعة الآن</button>
    </div>

    <div class="space-y-8 print:space-y-0">
        @foreach($plan->installments as $index => $installment)
            <div class="receipt-card bg-white page-break">
                <!-- Header -->
                <div class="flex justify-between items-start border-b-2 border-gray-100 pb-4 mb-6">
                    <div>
                        <h2 class="text-3xl font-black text-indigo-700">{{ get_setting('company_name', 'Alarabia Group') }}</h2>
                        <p class="text-gray-500 font-bold uppercase tracking-widest text-xs">{{ get_setting('company_activity', 'نظام مالي متطور') }}</p>
                    </div>
                    <div class="text-left bg-indigo-50 px-4 py-2 rounded-lg">
                        <span class="block text-indigo-600 font-black">إيصال قسط شهري</span>
                        <span class="text-xs text-gray-400">#{{ $plan->id }}-{{ $index + 1 }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8 mb-8">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400 font-bold">المطلوب من السيد:</span>
                            <span class="text-xl font-black border-b border-gray-200 flex-1">{{ $plan->customer->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400 font-bold">بموجب فاتورة رقم:</span>
                            <span class="font-bold border-b border-gray-200 flex-1">{{ $plan->invoice_no }}</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400 font-bold">تاريخ الاستحقاق:</span>
                            <span class="font-black border-b border-gray-200 flex-1 text-red-600">{{ $installment->due_date->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400 font-bold">هاتف العميل:</span>
                            <span class="font-bold border-b border-gray-200 flex-1">{{ $plan->customer->phone }}</span>
                        </div>
                    </div>
                </div>

                <!-- Final Amount -->
                <div class="bg-gray-50 p-6 rounded-2xl flex justify-between items-center mb-10 border border-gray-200">
                    <div>
                        <span class="text-gray-500 block text-sm">قيمة القسط المستحق</span>
                        <p class="text-4xl font-black text-indigo-600">{{ number_format($installment->amount, 2) }} <span class="text-lg">ج.م</span></p>
                    </div>
                    <div class="text-right">
                        <span class="text-gray-400 text-xs">قسط شهر</span>
                        <p class="text-xl font-bold">{{ $installment->due_date->translatedFormat('F Y') }}</p>
                    </div>
                </div>

                <!-- Footer & Signatures -->
                <div class="flex justify-between items-end mt-12 pb-4">
                    <div class="space-y-1 text-xs text-gray-400">
                        <p>{{ get_setting('company_address') }}</p>
                        <p>{{ get_setting('company_phone') }}</p>
                    </div>
                    <div class="flex gap-20">
                        <div class="text-center">
                            <div class="w-32 h-px bg-gray-300 mb-2"></div>
                            <span class="text-xs text-gray-500">توقيع المستلم</span>
                        </div>
                        <div class="text-center">
                            <div class="w-32 h-px bg-gray-300 mb-2"></div>
                            <span class="text-xs text-gray-500 font-black text-indigo-800">توقيع العميل</span>
                        </div>
                    </div>
                </div>
                
                <!-- Decorative Cut Line -->
                <div class="absolute -bottom-10 left-0 right-0 border-t-2 border-dashed border-gray-200 flex justify-center items-center pointer-events-none no-print">
                    <span class="bg-white px-4 text-gray-300 text-xs italic">قص من هنا</span>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
