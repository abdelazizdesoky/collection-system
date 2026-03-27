@extends('layouts.app')
@section('title', $product->name)
@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('products.index') }}" class="bg-gray-100 dark:bg-dark-card p-3 rounded-2xl hover:bg-gray-200 transition-colors">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ $product->name }}</h1>
            <p class="text-sm text-gray-500">كود: {{ $product->code }}</p>
        </div>
    </div>

    <!-- Product Info Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-dark-card rounded-2xl p-5 border border-gray-100 dark:border-dark-border shadow-lg">
            <p class="text-xs font-bold text-gray-400 mb-1">سعر البيع</p>
            <p class="text-xl font-black text-emerald-600">{{ number_format($product->selling_price, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-2xl p-5 border border-gray-100 dark:border-dark-border shadow-lg">
            <p class="text-xs font-bold text-gray-400 mb-1">سعر الشراء</p>
            <p class="text-xl font-black text-blue-600">{{ number_format($product->purchase_price, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-2xl p-5 border border-gray-100 dark:border-dark-border shadow-lg">
            <p class="text-xs font-bold text-gray-400 mb-1">إجمالي الرصيد</p>
            <p class="text-xl font-black {{ $product->total_stock <= $product->min_stock ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">{{ number_format($product->total_stock, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-2xl p-5 border border-gray-100 dark:border-dark-border shadow-lg">
            <p class="text-xs font-bold text-gray-400 mb-1">الحد الأدنى</p>
            <p class="text-xl font-black text-amber-600">{{ number_format($product->min_stock, 2) }}</p>
        </div>
    </div>

    <!-- Stock per Warehouse -->
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-border">
            <h2 class="text-lg font-black text-gray-900 dark:text-white">الرصيد حسب المخزن</h2>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-dark-tableheader">
                    <th class="text-right px-6 py-3 font-bold text-gray-700 dark:text-gray-300">المخزن</th>
                    <th class="text-center px-6 py-3 font-bold text-gray-700 dark:text-gray-300">الرصيد</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                @forelse($product->stockItems as $stockItem)
                <tr>
                    <td class="px-6 py-3 font-bold">{{ $stockItem->warehouse->name }}</td>
                    <td class="px-6 py-3 text-center font-bold">{{ number_format($stockItem->quantity, 2) }} {{ $product->unit->symbol ?? '' }}</td>
                </tr>
                @empty
                <tr><td colspan="2" class="px-6 py-8 text-center text-gray-400">لا يوجد رصيد في أي مخزن</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Recent Movements -->
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-border">
            <h2 class="text-lg font-black text-gray-900 dark:text-white">آخر الحركات</h2>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-dark-tableheader">
                    <th class="text-right px-6 py-3 font-bold text-gray-700 dark:text-gray-300">التاريخ</th>
                    <th class="text-right px-6 py-3 font-bold text-gray-700 dark:text-gray-300">المخزن</th>
                    <th class="text-center px-6 py-3 font-bold text-gray-700 dark:text-gray-300">النوع</th>
                    <th class="text-center px-6 py-3 font-bold text-gray-700 dark:text-gray-300">الكمية</th>
                    <th class="text-right px-6 py-3 font-bold text-gray-700 dark:text-gray-300">ملاحظات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                @forelse($product->stockMovements as $mov)
                <tr>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $mov->created_at->format('Y/m/d H:i') }}</td>
                    <td class="px-6 py-3 font-bold">{{ $mov->warehouse->name }}</td>
                    <td class="px-6 py-3 text-center">
                        @if($mov->movement_type === 'in')
                            <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-3 py-1 rounded-full text-xs font-bold">وارد</span>
                        @else
                            <span class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-3 py-1 rounded-full text-xs font-bold">صادر</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-center font-bold">{{ number_format($mov->quantity, 2) }}</td>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $mov->note }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">لا توجد حركات بعد</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
