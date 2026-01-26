@extends('layouts.app')

@section('title', 'كشف الحساب العام')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4 text-right">
                <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-500/30 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black dark:text-white tracking-tight">كشف الحساب العام</h1>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">سجل القيود المالية المفصلة لجميع العملاء</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                <a href="{{ route('customer-accounts.export') }}" class="flex-grow lg:flex-none flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-500/20 transition-all text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    تصدير إكسيل
                </a>
            </div>
        </div>

        <!-- Search Bar Card -->
        <div class="mt-8 bg-white dark:bg-dark-card p-4 rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border">
            <form action="{{ route('customer-accounts.index') }}" method="GET" class="relative">
                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="بحث باسم العميل أو البيان..." 
                       class="w-full pr-12 pl-4 py-4 rounded-xl border-none bg-gray-50 dark:bg-dark-bg/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-lg font-medium"
                >
            </form>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader text-gray-600 dark:text-gray-300">
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">العميل</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">التاريخ</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">البيان</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">مدين (-)</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">دائن (+)</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الرصيد التراكمي</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">النوع</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @forelse ($accounts as $account)
                        <td class="px-6 py-5 whitespace-nowrap">
                            @if($account->customer)
                                <a href="{{ route('customers.show', $account->customer) }}"
                                   class="font-bold text-indigo-600 dark:text-indigo-400 group-hover:underline">
                                    {{ $account->customer->name }}
                                </a>
                            @else
                                <span class="text-gray-400 italic">عميل محذوف</span>
                            @endif
                        </td>

                            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $account->date->format('Y-m-d') }}</td>
                            <td class="px-6 py-5 text-sm dark:text-gray-300">{{ $account->description }}</td>
                            <td class="px-6 py-5 text-center whitespace-nowrap font-bold text-red-600 dark:text-red-400">
                                {{ $account->debit > 0 ? number_format($account->debit, 2) : '-' }}
                            </td>
                            <td class="px-6 py-5 text-center whitespace-nowrap font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $account->credit > 0 ? number_format($account->credit, 2) : '-' }}
                            </td>
                            <td class="px-6 py-5 text-center whitespace-nowrap">
                                <span class="px-3 py-1 bg-gray-100 dark:bg-dark-bg rounded-lg font-black text-gray-900 dark:text-white">
                                    {{ number_format($account->balance, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                    {{ $account->reference_type }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($account->status === 'cancelled')
                                    <span class="px-2 py-1 text-[10px] font-bold uppercase rounded bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">ملغي</span>
                                @else
                                    <span class="px-2 py-1 text-[10px] font-bold uppercase rounded bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">نشط</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center text-gray-400 font-medium italic">لا توجد قيود مالية مسجلة بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $accounts->links() }}
    </div>
</div>
@endsection
