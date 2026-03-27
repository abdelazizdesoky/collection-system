@extends('layouts.app')
@section('title', 'إدارة الموردين')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">إدارة الموردين</h1>
            <p class="text-sm text-gray-500 mt-1">قائمة جميع الموردين</p>
        </div>
        <a href="{{ route('suppliers.create') }}" class="bg-gradient-to-l from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            إضافة مورد
        </a>
    </div>
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border p-6">
        <form action="{{ route('suppliers.index') }}" method="GET" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الكود أو الهاتف..." class="flex-1 px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 transition-all">بحث</button>
        </form>
    </div>
    <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-dark-tableheader">
                    <th class="text-right px-6 py-4 font-bold text-gray-700 dark:text-gray-300">الكود</th>
                    <th class="text-right px-6 py-4 font-bold text-gray-700 dark:text-gray-300">الاسم</th>
                    <th class="text-right px-6 py-4 font-bold text-gray-700 dark:text-gray-300">الهاتف</th>
                    <th class="text-right px-6 py-4 font-bold text-gray-700 dark:text-gray-300">الرصيد الافتتاحي</th>
                    <th class="text-center px-6 py-4 font-bold text-gray-700 dark:text-gray-300">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                @forelse($suppliers as $supplier)
                <tr class="hover:bg-gray-50 dark:hover:bg-dark-bg/30 transition-colors">
                    <td class="px-6 py-4 font-mono text-xs font-bold text-blue-600 dark:text-blue-400">{{ $supplier->code }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                        <a href="{{ route('suppliers.show', $supplier) }}" class="hover:text-blue-600 transition-colors">{{ $supplier->name }}</a>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $supplier->phone ?? '-' }}</td>
                    <td class="px-6 py-4 font-bold">
                        <span class="{{ $supplier->balance_type === 'credit' ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($supplier->opening_balance, 2) }}
                            ({{ $supplier->balance_type === 'credit' ? 'دائن' : 'مدين' }})
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 p-2 rounded-xl hover:bg-blue-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 p-2 rounded-xl hover:bg-amber-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">@csrf @method('DELETE')
                                <button class="bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 p-2 rounded-xl hover:bg-red-200 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">لا يوجد موردون بعد</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $suppliers->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
