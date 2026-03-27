@extends('layouts.app')
@section('title', 'وحدات القياس')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">وحدات القياس</h1>
            <p class="text-sm text-gray-500 mt-1">كيلو، جرام، كارتونة، قطعة...</p>
        </div>
        <a href="{{ route('units.create') }}" class="bg-gradient-to-l from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            إضافة وحدة
        </a>
    </div>
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-dark-tableheader">
                    <th class="text-right px-6 py-4 font-bold text-gray-700 dark:text-gray-300">#</th>
                    <th class="text-right px-6 py-4 font-bold text-gray-700 dark:text-gray-300">اسم الوحدة</th>
                    <th class="text-right px-6 py-4 font-bold text-gray-700 dark:text-gray-300">الرمز</th>
                    <th class="text-center px-6 py-4 font-bold text-gray-700 dark:text-gray-300">المنتجات</th>
                    <th class="text-center px-6 py-4 font-bold text-gray-700 dark:text-gray-300">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                @forelse($units as $unit)
                <tr class="hover:bg-gray-50 dark:hover:bg-dark-bg/30 transition-colors">
                    <td class="px-6 py-4 font-bold">{{ $unit->id }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $unit->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $unit->symbol ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-full text-xs font-bold">{{ $unit->products_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('units.edit', $unit) }}" class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 p-2 rounded-xl hover:bg-amber-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('units.destroy', $unit) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf @method('DELETE')
                                <button class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 p-2 rounded-xl hover:bg-red-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">لا توجد وحدات بعد</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
