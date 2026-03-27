@extends('layouts.app')
@section('title', 'كشف المخزون')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-black text-gray-900 dark:text-white">كشف المخزون الشامل</h1>
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border p-6">
        <form action="{{ route('stock.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الكود..." class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">
            <div class="flex items-center gap-3">
                <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }} id="low_stock" class="w-5 h-5 rounded border-gray-300 text-red-600">
                <label for="low_stock" class="text-sm font-bold text-gray-700 dark:text-gray-300">عرض المخزون المنخفض فقط</label>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 transition-all">بحث</button>
        </form>
    </div>
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader">
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">الكود</th>
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">المنتج</th>
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">الوحدة</th>
                        @foreach($warehouses as $wh)
                        <th class="text-center px-4 py-4 font-bold text-gray-700 dark:text-gray-300">{{ $wh->name }}</th>
                        @endforeach
                        <th class="text-center px-4 py-4 font-bold text-blue-700 dark:text-blue-300">الإجمالي</th>
                        <th class="text-center px-4 py-4 font-bold text-gray-700 dark:text-gray-300">الحد الأدنى</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-bg/30">
                        <td class="px-4 py-3 font-mono text-xs font-bold text-blue-600">{{ $product->code }}</td>
                        <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $product->unit->name ?? '-' }}</td>
                        @foreach($warehouses as $wh)
                            @php $qty = $product->stockItems->where('warehouse_id', $wh->id)->first()?->quantity ?? 0; @endphp
                            <td class="px-4 py-3 text-center font-bold">{{ number_format($qty, 2) }}</td>
                        @endforeach
                        <td class="px-4 py-3 text-center font-black text-blue-600">{{ number_format($product->stock_items_sum_quantity ?? 0, 2) }}</td>
                        <td class="px-4 py-3 text-center text-gray-500">{{ number_format($product->min_stock, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="{{ 5 + $warehouses->count() }}" class="px-6 py-12 text-center text-gray-400">لا توجد بيانات</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $products->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
