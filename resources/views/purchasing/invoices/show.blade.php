@extends('layouts.app')
@section('title', 'فاتورة شراء: ' . $purchaseInvoice->code)
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('purchase-invoices.index') }}" class="bg-gray-100 dark:bg-dark-card p-3 rounded-2xl hover:bg-gray-200 transition-colors"><svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg></a>
            <div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ $purchaseInvoice->code }}</h1>
                <p class="text-sm text-gray-500">فاتورة شراء • {{ $purchaseInvoice->invoice_date->format('Y/m/d') }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            @if($purchaseInvoice->status === 'draft')
                <form action="{{ route('purchase-invoices.confirm', $purchaseInvoice) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من تأكيد الفاتورة؟ سيتم تحديث المخزون.')">
                    @csrf
                    <button class="bg-gradient-to-l from-green-600 to-emerald-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        تأكيد وتحديث المخزون
                    </button>
                </form>
                <form action="{{ route('purchase-invoices.destroy', $purchaseInvoice) }}" method="POST" onsubmit="return confirm('حذف الفاتورة؟')">
                    @csrf @method('DELETE')
                    <button class="bg-red-100 dark:bg-red-900/30 text-red-700 px-4 py-3 rounded-2xl font-bold transition-all">حذف</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Status Badge -->
    <div class="flex gap-4">
        @if($purchaseInvoice->status === 'confirmed')
            <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-4 py-2 rounded-2xl text-sm font-bold">✓ مؤكدة - تم تحديث المخزون</span>
        @elseif($purchaseInvoice->status === 'draft')
            <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 px-4 py-2 rounded-2xl text-sm font-bold">⏳ مسودة - لم يتم تحديث المخزون</span>
        @endif
        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-4 py-2 rounded-2xl text-sm font-bold">
            {{ $purchaseInvoice->payment_type === 'cash' ? '💵 نقدي' : ($purchaseInvoice->payment_type === 'credit' ? '📋 آجل' : '📆 تقسيط') }}
        </span>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-dark-card rounded-2xl p-5 border border-gray-100 dark:border-dark-border shadow-lg">
            <p class="text-xs font-bold text-gray-400 mb-1">المورد</p>
            <p class="text-lg font-black text-gray-900 dark:text-white">{{ $purchaseInvoice->supplier->name }}</p>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-2xl p-5 border border-gray-100 dark:border-dark-border shadow-lg">
            <p class="text-xs font-bold text-gray-400 mb-1">المخزن</p>
            <p class="text-lg font-black text-gray-900 dark:text-white">{{ $purchaseInvoice->warehouse->name }}</p>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-2xl p-5 border border-gray-100 dark:border-dark-border shadow-lg">
            <p class="text-xs font-bold text-gray-400 mb-1">الإجمالي</p>
            <p class="text-xl font-black text-blue-600">{{ number_format($purchaseInvoice->total, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-2xl p-5 border border-gray-100 dark:border-dark-border shadow-lg">
            <p class="text-xs font-bold text-gray-400 mb-1">المتبقي</p>
            <p class="text-xl font-black {{ $purchaseInvoice->remaining > 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($purchaseInvoice->remaining, 2) }}</p>
        </div>
    </div>

    <!-- Items -->
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
                @foreach($purchaseInvoice->items as $item)
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
                <tr><td colspan="4" class="px-6 py-3 text-left font-black">الإجمالي الفرعي</td><td class="px-6 py-3 text-center font-black">{{ number_format($purchaseInvoice->subtotal, 2) }}</td></tr>
                @if($purchaseInvoice->discount > 0)<tr><td colspan="4" class="px-6 py-2 text-left text-red-600 font-bold">خصم</td><td class="px-6 py-2 text-center text-red-600 font-bold">-{{ number_format($purchaseInvoice->discount, 2) }}</td></tr>@endif
                @if($purchaseInvoice->tax > 0)<tr><td colspan="4" class="px-6 py-2 text-left font-bold">ضريبة</td><td class="px-6 py-2 text-center font-bold">+{{ number_format($purchaseInvoice->tax, 2) }}</td></tr>@endif
                <tr class="text-lg"><td colspan="4" class="px-6 py-3 text-left font-black text-blue-600">الإجمالي النهائي</td><td class="px-6 py-3 text-center font-black text-blue-600">{{ number_format($purchaseInvoice->total, 2) }}</td></tr>
            </tfoot>
        </table>
    </div>

    <!-- Payments -->
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-border flex justify-between items-center">
            <h2 class="text-lg font-black text-gray-900 dark:text-white">المدفوعات</h2>
            <span class="text-sm font-bold text-gray-500">المدفوع: <span class="text-green-600">{{ number_format($purchaseInvoice->paid_amount, 2) }}</span> / المتبقي: <span class="text-red-600">{{ number_format($purchaseInvoice->remaining, 2) }}</span></span>
        </div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                @foreach($purchaseInvoice->payments as $payment)
                <tr>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $payment->payment_date->format('Y/m/d') }}</td>
                    <td class="px-6 py-3 font-bold text-green-600">{{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $payment->notes }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($purchaseInvoice->remaining > 0 && $purchaseInvoice->status === 'confirmed')
        <div class="p-6 border-t border-gray-100 dark:border-dark-border">
            <h3 class="text-sm font-black text-gray-700 dark:text-gray-300 mb-3">تسجيل دفعة جديدة</h3>
            <form action="{{ route('purchase-invoices.add-payment', $purchaseInvoice) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                @csrf
                <input type="number" step="0.01" name="amount" required min="0.01" max="{{ $purchaseInvoice->remaining }}" placeholder="المبلغ" class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">
                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">
                <input type="text" name="notes" placeholder="ملاحظات" class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">
                <button type="submit" class="bg-green-600 text-white px-4 py-3 rounded-2xl font-bold hover:bg-green-700 transition-all">تسجيل</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
