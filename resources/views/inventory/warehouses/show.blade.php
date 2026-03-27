@extends('layouts.app')
@section('title', $warehouse->name)
@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('warehouses.index') }}" class="bg-gray-100 dark:bg-dark-card p-3 rounded-2xl hover:bg-gray-200 transition-colors"><svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg></a>
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ $warehouse->name }}</h1>
            <p class="text-sm text-gray-500">{{ $warehouse->location ?? '' }} {{ $warehouse->manager ? '• المسؤول: '.$warehouse->manager : '' }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-border">
            <h2 class="text-lg font-black text-gray-900 dark:text-white">أرصدة المخزن</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader">
                        <th class="text-right px-6 py-3 font-bold text-gray-700 dark:text-gray-300">كود</th>
                        <th class="text-right px-6 py-3 font-bold text-gray-700 dark:text-gray-300">المنتج</th>
                        <th class="text-right px-6 py-3 font-bold text-gray-700 dark:text-gray-300">القسم</th>
                        <th class="text-right px-6 py-3 font-bold text-gray-700 dark:text-gray-300">الوحدة</th>
                        <th class="text-center px-6 py-3 font-bold text-gray-700 dark:text-gray-300">الرصيد</th>
                        <th class="text-center px-6 py-3 font-bold text-gray-700 dark:text-gray-300">الحد الأدنى</th>
                        <th class="text-center px-6 py-3 font-bold text-gray-700 dark:text-gray-300">الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                    @forelse($warehouse->stockItems as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-bg/30">
                        <td class="px-6 py-3 font-mono text-xs font-bold text-blue-600">{{ $item->product->code }}</td>
                        <td class="px-6 py-3 font-bold text-gray-900 dark:text-white">{{ $item->product->name }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $item->product->category->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $item->product->unit->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-center font-bold">{{ number_format($item->quantity, 2) }}</td>
                        <td class="px-6 py-3 text-center text-gray-500">{{ number_format($item->product->min_stock, 2) }}</td>
                        <td class="px-6 py-3 text-center">
                            @if($item->quantity <= $item->product->min_stock)
                                <span class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-3 py-1 rounded-full text-xs font-bold">منخفض</span>
                            @else
                                <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-3 py-1 rounded-full text-xs font-bold">جيد</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">لا توجد أصناف في هذا المخزن</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
