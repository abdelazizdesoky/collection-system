@extends('layouts.app')

@section('title', 'كشف حساب - ' . $customer->name)

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4 text-right w-full">
            <a href="{{ route('customers.show', $customer) }}" 
               class="bg-white dark:bg-dark-card p-3 rounded-xl shadow-md border border-gray-100 dark:border-dark-border text-gray-500 hover:text-gray-700 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold dark:text-white">كشف حساب العملاء</h1>
                <p class="text-gray-500 dark:text-gray-400">{{ $customer->name }}</p>
            </div>
        </div>
        
        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('customer.ledger.create', $customer) }}" 
               class="flex-grow md:flex-none bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                عملية مالية جديدة
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ $message }}
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-dark-border">
            <h2 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-2">نوع الحساب</h2>
            <div class="flex items-center gap-3">
                <span class="px-4 py-1 inline-flex text-lg font-bold rounded-full {{ $customer->balance_type == 'debit' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' }}">
                    {{ $customer->balance_type == 'debit' ? 'مدين' : 'دائن' }}
                </span>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-dark-border">
            <h2 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-2">الرصيد الافتتاحي</h2>
            <span class="text-2xl font-bold dark:text-white">{{ number_format($customer->opening_balance, 2) }} <small class="text-sm text-gray-400">ج.م</small></span>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-dark-border ring-2 ring-blue-500/20">
            <h2 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-2">الرصيد الحالي</h2>
            <span class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($customer->getCurrentBalance(), 2) }} <small class="text-sm">ج.م</small></span>
        </div>
    </div>

    <!-- Ledger Table -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader text-gray-600 dark:text-gray-300">
                        <th class="px-6 py-4 text-sm font-bold">التاريخ</th>
                        <th class="px-6 py-4 text-sm font-bold">البيان</th>
                        <th class="px-6 py-4 text-sm font-bold">المرجع</th>
                        <th class="px-6 py-4 text-sm font-bold text-red-500">مدين (+)</th>
                        <th class="px-6 py-4 text-sm font-bold text-emerald-500">دائن (-)</th>
                        <th class="px-6 py-4 text-sm font-bold bg-gray-100 dark:bg-dark-bg/50">الرصيد</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @forelse ($accounts as $account)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300">{{ $account->date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 font-medium dark:text-white">{{ $account->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs px-2 py-1 rounded bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400">
                                    {{ $account->reference_type ?? 'يدوي' }} 
                                    @if($account->reference_id) #{{ $account->reference_id }} @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-red-600 dark:text-red-400 font-bold">
                                {{ $account->debit > 0 ? number_format($account->debit, 2) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-emerald-600 dark:text-emerald-400 font-bold">
                                {{ $account->credit > 0 ? number_format($account->credit, 2) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-black text-lg bg-gray-50 dark:bg-dark-bg/30 dark:text-white">
                                {{ number_format($account->balance, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                لا توجد حركات مالية مسجلة بعد.
                            </td>
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
