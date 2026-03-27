<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة بيع: {{ $saleInvoice->code }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Cairo', sans-serif; direction: rtl; padding: 20px; color: #1a1a1a; font-size: 13px; }
        .header { text-align: center; border-bottom: 3px solid #2563eb; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; font-weight: 900; color: #2563eb; }
        .header p { color: #666; font-size: 12px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; }
        .info-box label { font-size: 10px; color: #94a3b8; font-weight: 700; display: block; margin-bottom: 2px; }
        .info-box span { font-weight: 800; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #2563eb; color: white; padding: 8px 12px; text-align: right; font-weight: 700; font-size: 12px; }
        td { padding: 8px 12px; border-bottom: 1px solid #e2e8f0; }
        .total-row { background: #f1f5f9; font-weight: 800; }
        .grand-total { background: #2563eb; color: white; font-weight: 900; font-size: 16px; }
        .badge { display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .badge-cash { background: #dcfce7; color: #166534; }
        .badge-credit { background: #fef3c7; color: #92400e; }
        .badge-installment { background: #ede9fe; color: #5b21b6; }
        .footer { text-align: center; margin-top: 40px; padding-top: 15px; border-top: 2px solid #e2e8f0; color: #94a3b8; font-size: 11px; }
        @media print { body { padding: 0; } @page { margin: 15mm; } }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>{{ get_setting('company_name', 'الشركة') }}</h1>
        <p>فاتورة بيع رقم: <strong>{{ $saleInvoice->code }}</strong></p>
    </div>

    <div class="info-grid">
        <div class="info-box"><label>العميل</label><span>{{ $saleInvoice->customer->name }}</span></div>
        <div class="info-box"><label>التاريخ</label><span>{{ $saleInvoice->invoice_date->format('Y/m/d') }}</span></div>
        <div class="info-box"><label>المخزن</label><span>{{ $saleInvoice->warehouse->name }}</span></div>
        <div class="info-box"><label>نوع السداد</label>
            <span class="badge {{ $saleInvoice->payment_type === 'cash' ? 'badge-cash' : ($saleInvoice->payment_type === 'credit' ? 'badge-credit' : 'badge-installment') }}">
                {{ $saleInvoice->payment_type === 'cash' ? 'نقدي' : ($saleInvoice->payment_type === 'credit' ? 'آجل' : 'تقسيط') }}
            </span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>المنتج</th>
                <th>الوحدة</th>
                <th>الكمية</th>
                <th>سعر الوحدة</th>
                <th>الخصم</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($saleInvoice->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-weight:700;">{{ $item->product->name }}</td>
                <td>{{ $item->product->unit->name ?? '-' }}</td>
                <td>{{ number_format($item->quantity, 2) }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ number_format($item->discount, 2) }}</td>
                <td style="font-weight:700;">{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row"><td colspan="6" style="text-align:left;">الإجمالي الفرعي</td><td>{{ number_format($saleInvoice->subtotal, 2) }}</td></tr>
            @if($saleInvoice->discount > 0)<tr class="total-row"><td colspan="6" style="text-align:left;color:#dc2626;">خصم</td><td style="color:#dc2626;">-{{ number_format($saleInvoice->discount, 2) }}</td></tr>@endif
            @if($saleInvoice->tax > 0)<tr class="total-row"><td colspan="6" style="text-align:left;">ضريبة</td><td>+{{ number_format($saleInvoice->tax, 2) }}</td></tr>@endif
            <tr class="grand-total"><td colspan="6" style="text-align:left;">الإجمالي النهائي</td><td>{{ number_format($saleInvoice->total, 2) }}</td></tr>
        </tbody>
    </table>

    <div class="info-grid" style="margin-top:15px;">
        <div class="info-box"><label>المبلغ المدفوع</label><span style="color:#16a34a;">{{ number_format($saleInvoice->paid_amount, 2) }}</span></div>
        <div class="info-box"><label>المبلغ المتبقي</label><span style="color:#dc2626;">{{ number_format($saleInvoice->remaining, 2) }}</span></div>
    </div>

    <div class="footer">
        <p>{{ get_setting('company_name', 'الشركة') }} &copy; {{ date('Y') }} - جميع الحقوق محفوظة</p>
    </div>
</body>
</html>
