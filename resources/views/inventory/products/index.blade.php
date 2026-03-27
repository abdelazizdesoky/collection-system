@extends('layouts.app')
@section('title', 'إدارة المنتجات')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">إدارة المنتجات</h1>
            <p class="text-sm text-gray-500 mt-1">قائمة جميع المنتجات والأصناف</p>
        </div>
        <a href="{{ route('products.create') }}" class="bg-gradient-to-l from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            إضافة منتج
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border p-6">
        <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الكود..." class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
            <select name="category_id" class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:bg-dark-card dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                <option value="">كل الأقسام</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="is_for_sale" class="px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:bg-dark-card dark:text-white focus:ring-2 focus:ring-blue-500 transition-all">
                <option value="">الكل</option>
                <option value="1" {{ request('is_for_sale') === '1' ? 'selected' : '' }}>متاح للبيع</option>
                <option value="0" {{ request('is_for_sale') === '0' ? 'selected' : '' }}>غير متاح</option>
            </select>
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
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">القسم</th>
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">الوحدة</th>
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">سعر البيع</th>
                        <th class="text-right px-4 py-4 font-bold text-gray-700 dark:text-gray-300">سعر الشراء</th>
                        <th class="text-center px-4 py-4 font-bold text-gray-700 dark:text-gray-300">الرصيد</th>
                        <th class="text-center px-4 py-4 font-bold text-gray-700 dark:text-gray-300">الحالة</th>
                        <th class="text-center px-4 py-4 font-bold text-gray-700 dark:text-gray-300">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-bg/30 transition-colors">
                        <td class="px-4 py-4 font-mono text-xs font-bold text-blue-600 dark:text-blue-400">{{ $product->code }}</td>
                        <td class="px-4 py-4">
                            <a href="{{ route('products.show', $product) }}" class="font-bold text-gray-900 dark:text-white hover:text-blue-600 transition-colors">{{ $product->name }}</a>
                        </td>
                        <td class="px-4 py-4 text-gray-500">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-4 py-4 text-gray-500">{{ $product->unit->name ?? '-' }}</td>
                        <td class="px-4 py-4 font-bold text-emerald-600">{{ number_format($product->selling_price, 2) }}</td>
                        <td class="px-4 py-4 font-bold text-gray-600">{{ number_format($product->purchase_price, 2) }}</td>
                        <td class="px-4 py-4 text-center">
                            @php $totalStock = $product->stock_items_sum_quantity ?? 0; @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $totalStock <= $product->min_stock ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300' : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300' }}">
                                {{ number_format($totalStock, 2) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($product->is_for_sale)
                                <span class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 px-2 py-1 rounded-full text-xs font-bold">للبيع</span>
                            @else
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-full text-xs font-bold">خدمة</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('products.show', $product) }}" class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 p-2 rounded-xl hover:bg-blue-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 p-2 rounded-xl hover:bg-amber-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 p-2 rounded-xl hover:bg-red-200 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-6 py-12 text-center text-gray-400">لا توجد منتجات بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $products->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
