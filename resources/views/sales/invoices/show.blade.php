@extends('layouts.app')
@section('title', 'فاتورة بيع: ' . $saleInvoice->code)
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('sale-invoices.index') }}" class="bg-gray-100 dark:bg-dark-card p-3 rounded-2xl hover:bg-gray-200 transition-colors"><svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg></a>
            <div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ $saleInvoice->code }}</h1>
                <p class="text-sm text-gray-500">فاتورة بيع • {{ $saleInvoice->invoice_date->format('Y/m/d') }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('sale-invoices.print', $saleInvoice) }}" target="_blank" class="bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 px-4 py-3 rounded-2xl font-bold transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                طباعة
            </a>
            @if($saleInvoice->status === 'draft')
                <form action="{{ route('sale-invoices.confirm', $saleInvoice) }}" method="POST" onsubmit="return confirm('تأكيد الفاتورة وخصم المخزون؟')">
                    @csrf
                    <button class="bg-gradient-to-l from-green-600 to-emerald-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        تأكيد وخصم المخزون
                    </button>
                </form>
                <form action="{{ route('sale-invoices.destroy', $saleInvoice) }}" method="POST" onsubmit="return confirm('حذف؟')">@csrf @method('DELETE')
                    <button class="bg-red-100 text-red-700 px-4 py-3 rounded-2xl font-bold">حذف</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Status -->
    <div class="flex gap-4 flex-wrap">
        @if($saleInvoice->status === 'confirmed')
            <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-4 py-2 rounded-2xl text-sm font-bold">✓ مؤكدة - تم خصم المخزون</span>
        @elseif($saleInvoice->status === 'draft')
            <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 px-4 py-2 rounded-2xl text-sm font-bold">⏳ مسودة</span>
        @endif
        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-4 py-2 rounded-2xl text-sm font-bold">
            {{ $saleInvoice->payment_type === 'cash' ? '💵 نقدي' : ($saleInvoice->payment_type === 'credit' ? '📋 آجل' : '📆 تقسيط') }}
        </span>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-dark-card rounded-2xl p-5 border border-gray-100 dark:border-dark-border shadow-lg">
            <p class="text-xs font-bold text-gray-400 mb-1">العميل</p>
            <p class="text-lg font-black text-gray-900 dark:text-white">{{ $saleInvoice->customer->name }}</p>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-2xl p-5 border border-gray-100 dark:border-dark-border shadow-lg">
            <p class="text-xs font-bold text-gray-400 mb-1">المخزن</p>
            <p class="text-lg font-black text-gray-900 dark:text-white">{{ $saleInvoice->warehouse->name }}</p>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-2xl p-5 border border-gray-100 dark:border-dark-border shadow-lg">
            <p class="text-xs font-bold text-gray-400 mb-1">الإجمالي</p>
            <p class="text-xl font-black text-blue-600">{{ number_format($saleInvoice->total, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-2xl p-5 border border-gray-100 dark:border-dark-border shadow-lg">
            <p class="text-xs font-bold text-gray-400 mb-1">المتبقي</p>
            <p class="text-xl font-black {{ $saleInvoice->remaining > 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($saleInvoice->remaining, 2) }}</p>
        </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-border"><h2 class="text-lg font-black text-gray-900 dark:text-white">بنود الفاتورة</h2></div>
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 dark:bg-dark-tableheader">
                <th class="text-right px-6 py-3 font-bold text-gray-700 dark:text-gray-300">المنتج</th>
                <th class="text-center px-6 py-3 font-bold text-gray-700 dark:text-gray-300">الكمية</th>
                <th class="text-center px-6 py-3 font-bold text-gray-700 dark:text-gray-300">سعر الوحدة</th>
                <th class="text-center px-6 py-3 font-bold text-gray-700 dark:text-gray-300">الخصم</th>
                <th class="text-center px-6 py-3 font-bold text-gray-700 dark:text-gray-300">الإجمالي</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                @foreach($saleInvoice->items as $item)
                <tr>
                    <td class="px-6 py-3 font-bold text-gray-900 dark:text-white">{{ $item->product->name }} <span class="text-xs text-gray-400">({{ $item->product->unit->symbol ?? '' }})</span></td>
                    <td class="px-6 py-3 text-center font-bold">{{ number_format($item->quantity, 2) }}</td>
                    <td class="px-6 py-3 text-center">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="px-6 py-3 text-center text-red-500">{{ number_format($item->discount, 2) }}</td>
                    <td class="px-6 py-3 text-center font-bold">{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 dark:bg-dark-tableheader">
                <tr><td colspan="4" class="px-6 py-3 text-left font-black">الإجمالي الفرعي</td><td class="px-6 py-3 text-center font-black">{{ number_format($saleInvoice->subtotal, 2) }}</td></tr>
                @if($saleInvoice->discount > 0)<tr><td colspan="4" class="px-6 py-2 text-left text-red-600 font-bold">خصم</td><td class="px-6 py-2 text-center text-red-600 font-bold">-{{ number_format($saleInvoice->discount, 2) }}</td></tr>@endif
                @if($saleInvoice->tax > 0)<tr><td colspan="4" class="px-6 py-2 text-left font-bold">ضريبة</td><td class="px-6 py-2 text-center font-bold">+{{ number_format($saleInvoice->tax, 2) }}</td></tr>@endif
                <tr class="text-lg"><td colspan="4" class="px-6 py-3 text-left font-black text-blue-600">الإجمالي النهائي</td><td class="px-6 py-3 text-center font-black text-blue-600">{{ number_format($saleInvoice->total, 2) }}</td></tr>
            </tfoot>
        </table>
    </div>

    <!-- Payments -->
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-border flex justify-between items-center">
            <h2 class="text-lg font-black text-gray-900 dark:text-white">المدفوعات</h2>
            <span class="text-sm font-bold">المدفوع: <span class="text-green-600">{{ number_format($saleInvoice->paid_amount, 2) }}</span> / المتبقي: <span class="text-red-600">{{ number_format($saleInvoice->remaining, 2) }}</span></span>
        </div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                @foreach($saleInvoice->payments as $payment)
                <tr>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $payment->payment_date->format('Y/m/d') }}</td>
                    <td class="px-6 py-3 font-bold text-green-600">{{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $payment->notes }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($saleInvoice->remaining > 0 && $saleInvoice->status === 'confirmed')
        <div class="p-6 border-t border-gray-100 dark:border-dark-border">
            <h3 class="text-sm font-black text-gray-700 dark:text-gray-300 mb-3">تسجيل دفعة جديدة</h3>
            <form action="{{ route('sale-invoices.add-payment', $saleInvoice) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                @csrf
                <input type="number" step="0.01" name="amount" required min="0.01" max="{{ $saleInvoice->remaining }}" placeholder="المبلغ" class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">
                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">
                <input type="text" name="notes" placeholder="ملاحظات" class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">
                <button type="submit" class="bg-green-600 text-white px-4 py-3 rounded-2xl font-bold hover:bg-green-700 transition-all">تسجيل</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
