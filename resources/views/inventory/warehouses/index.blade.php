@extends('layouts.app')
@section('title', 'إدارة المخازن')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">إدارة المخازن</h1>
            <p class="text-sm text-gray-500 mt-1">قائمة جميع المخازن والمستودعات</p>
        </div>
        <a href="{{ route('warehouses.create') }}" class="bg-gradient-to-l from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            إضافة مخزن
        </a>
    </div>
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-dark-tableheader">
                    <th class="text-right px-6 py-4 font-bold text-gray-700 dark:text-gray-300">#</th>
                    <th class="text-right px-6 py-4 font-bold text-gray-700 dark:text-gray-300">اسم المخزن</th>
                    <th class="text-right px-6 py-4 font-bold text-gray-700 dark:text-gray-300">الموقع</th>
                    <th class="text-right px-6 py-4 font-bold text-gray-700 dark:text-gray-300">المسؤول</th>
                    <th class="text-center px-6 py-4 font-bold text-gray-700 dark:text-gray-300">عدد الأصناف</th>
                    <th class="text-center px-6 py-4 font-bold text-gray-700 dark:text-gray-300">الحالة</th>
                    <th class="text-center px-6 py-4 font-bold text-gray-700 dark:text-gray-300">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                @forelse($warehouses as $warehouse)
                <tr class="hover:bg-gray-50 dark:hover:bg-dark-bg/30 transition-colors">
                    <td class="px-6 py-4 font-bold">{{ $warehouse->id }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                        <a href="{{ route('warehouses.show', $warehouse) }}" class="hover:text-blue-600 transition-colors">{{ $warehouse->name }}</a>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $warehouse->location ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $warehouse->manager ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-full text-xs font-bold">{{ $warehouse->stock_items_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($warehouse->is_active)
                            <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-3 py-1 rounded-full text-xs font-bold">نشط</span>
                        @else
                            <span class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 px-3 py-1 rounded-full text-xs font-bold">غير نشط</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('warehouses.show', $warehouse) }}" class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 p-2 rounded-xl hover:bg-blue-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                            <a href="{{ route('warehouses.edit', $warehouse) }}" class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 p-2 rounded-xl hover:bg-amber-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">@csrf @method('DELETE')
                                <button class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 p-2 rounded-xl hover:bg-red-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">لا توجد مخازن بعد</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
