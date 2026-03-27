@extends('layouts.app')
@section('title', 'حركات المخزون')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-black text-gray-900 dark:text-white">حركات المخزون</h1>
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border p-6">
        <form action="{{ route('stock.movements') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select name="warehouse_id" class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:bg-dark-card dark:text-white">
                <option value="">كل المخازن</option>
                @foreach($warehouses as $wh)
                    <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name }}</option>
                @endforeach
            </select>
            <select name="movement_type" class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:bg-dark-card dark:text-white">
                <option value="">كل الحركات</option>
                <option value="in" {{ request('movement_type') == 'in' ? 'selected' : '' }}>وارد</option>
                <option value="out" {{ request('movement_type') == 'out' ? 'selected' : '' }}>صادر</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 transition-all">بحث</button>
        </form>
    </div>
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-dark-tableheader">
                    <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">التاريخ</th>
                    <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">المنتج</th>
                    <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">المخزن</th>
                    <th class="text-center px-4 py-4 font-bold text-gray-700 dark:text-gray-300">النوع</th>
                    <th class="text-center px-4 py-4 font-bold text-gray-700 dark:text-gray-300">الكمية</th>
                    <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">الملاحظات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                @forelse($movements as $mov)
                <tr class="hover:bg-gray-50 dark:hover:bg-dark-bg/30">
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $mov->created_at->format('Y/m/d H:i') }}</td>
                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $mov->product->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $mov->warehouse->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($mov->movement_type === 'in')
                            <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-3 py-1 rounded-full text-xs font-bold">وارد ↓</span>
                        @else
                            <span class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-3 py-1 rounded-full text-xs font-bold">صادر ↑</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center font-bold">{{ number_format($mov->quantity, 2) }} {{ $mov->product->unit->symbol ?? '' }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $mov->note }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">لا توجد حركات</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $movements->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
