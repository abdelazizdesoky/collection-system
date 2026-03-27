@extends('layouts.app')
@section('title', 'فواتير البيع')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">فواتير البيع</h1>
            <p class="text-sm text-gray-500 mt-1">إدارة فواتير المبيعات</p>
        </div>
        <a href="{{ route('sale-invoices.create') }}" class="bg-gradient-to-l from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            فاتورة بيع جديدة
        </a>
    </div>
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border p-6">
        <form action="{{ route('sale-invoices.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث برقم الفاتورة أو اسم العميل..." class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">
            <select name="status" class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:bg-dark-card dark:text-white">
                <option value="">كل الحالات</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكدة</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغاة</option>
            </select>
            <select name="payment_type" class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:bg-dark-card dark:text-white">
                <option value="">كل أنواع السداد</option>
                <option value="cash" {{ request('payment_type') == 'cash' ? 'selected' : '' }}>نقدي</option>
                <option value="credit" {{ request('payment_type') == 'credit' ? 'selected' : '' }}>آجل</option>
                <option value="installment" {{ request('payment_type') == 'installment' ? 'selected' : '' }}>تقسيط</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 transition-all">بحث</button>
        </form>
    </div>
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader">
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">رقم الفاتورة</th>
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">العميل</th>
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">المخزن</th>
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">التاريخ</th>
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">الإجمالي</th>
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">المدفوع</th>
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">المتبقي</th>
                        <th class="text-center px-4 py-4 font-bold text-gray-700 dark:text-gray-300">نوع السداد</th>
                        <th class="text-center px-4 py-4 font-bold text-gray-700 dark:text-gray-300">الحالة</th>
                        <th class="text-center px-4 py-4 font-bold text-gray-700 dark:text-gray-300">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                    @forelse($invoices as $inv)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-bg/30 transition-colors">
                        <td class="px-4 py-4 font-mono text-xs font-bold text-blue-600">{{ $inv->code }}</td>
                        <td class="px-4 py-4 font-bold text-gray-900 dark:text-white">{{ $inv->customer->name ?? '-' }}</td>
                        <td class="px-4 py-4 text-gray-500">{{ $inv->warehouse->name ?? '-' }}</td>
                        <td class="px-4 py-4 text-gray-500 text-xs">{{ $inv->invoice_date->format('Y/m/d') }}</td>
                        <td class="px-4 py-4 font-bold">{{ number_format($inv->total, 2) }}</td>
                        <td class="px-4 py-4 font-bold text-green-600">{{ number_format($inv->paid_amount, 2) }}</td>
                        <td class="px-4 py-4 font-bold text-red-600">{{ number_format($inv->remaining, 2) }}</td>
                        <td class="px-4 py-4 text-center">
                            @if($inv->payment_type === 'cash') <span class="bg-green-100 dark:bg-green-900/30 text-green-700 px-2 py-1 rounded-full text-xs font-bold">نقدي</span>
                            @elseif($inv->payment_type === 'credit') <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 px-2 py-1 rounded-full text-xs font-bold">آجل</span>
                            @else <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 px-2 py-1 rounded-full text-xs font-bold">تقسيط</span> @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($inv->status === 'confirmed') <span class="bg-green-100 dark:bg-green-900/30 text-green-700 px-2 py-1 rounded-full text-xs font-bold">مؤكدة</span>
                            @elseif($inv->status === 'draft') <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 px-2 py-1 rounded-full text-xs font-bold">مسودة</span>
                            @else <span class="bg-red-100 dark:bg-red-900/30 text-red-700 px-2 py-1 rounded-full text-xs font-bold">ملغاة</span> @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('sale-invoices.show', $inv) }}" class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 p-2 rounded-xl hover:bg-blue-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                <a href="{{ route('sale-invoices.print', $inv) }}" target="_blank" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 p-2 rounded-xl hover:bg-gray-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="px-6 py-12 text-center text-gray-400">لا توجد فواتير بيع بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $invoices->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
