@extends('layouts.app')

@section('title', 'لوحة التحكم - المحاسب')

@section('content')
<div class="max-w-7xl mx-auto text-right" dir="rtl">
    <!-- Welcome Header Card (Clean - No Action Buttons) -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-2xl shadow-xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-sm">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1">مرحباً، {{ auth()->user()->name }}</h1>
                    <p class="text-emerald-100 text-lg opacity-90">لوحة تحكم المحاسب - مراقبة التدفقات المالية والتحصيلات</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Financial Stats Only -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Collections -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border p-6 hover:border-emerald-500/50 transition-all group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-2xl text-emerald-600 dark:text-emerald-400 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-2xl font-black text-gray-800 dark:text-white mb-1 tracking-tighter">
                    <span class="text-sm font-normal text-gray-400 ml-1">ج.م</span>
                    {{ number_format($totalCollections, 0) }}
                </div>
                <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wide">إجمالي التحصيلات</div>
                <a href="{{ route('collections.index') }}" class="mt-4 px-4 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg font-bold text-xs hover:bg-emerald-600 hover:text-white transition-colors">السجل المالي</a>
            </div>
        </div>

        <!-- Pending Cheques -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border p-6 hover:border-amber-500/50 transition-all group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-2xl text-amber-600 dark:text-amber-400 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="text-4xl font-black text-gray-800 dark:text-white mb-1 tracking-tighter">{{ $pendingCheques }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wide">شيكات معلقة</div>
                <a href="{{ route('cheques.index') }}" class="mt-4 px-4 py-1.5 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg font-bold text-xs hover:bg-amber-600 hover:text-white transition-colors">مراجعة الشيكات</a>
            </div>
        </div>

        <!-- Total Customers (Financial View) -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border p-6 hover:border-blue-500/50 transition-all group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-2xl text-blue-600 dark:text-blue-400 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0"/></svg>
                </div>
                <div class="text-4xl font-black text-gray-800 dark:text-white mb-1 tracking-tighter">{{ $totalCustomers }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wide">العملاء النشطون</div>
                <a href="{{ route('customer-accounts.index') }}" class="mt-4 px-4 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg font-bold text-xs hover:bg-blue-600 hover:text-white transition-colors">كشف الحساب</a>
            </div>
        </div>

        <!-- Audit Logs (Financial Accuracy) -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border p-6 hover:border-indigo-500/50 transition-all group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-2xl text-indigo-600 dark:text-indigo-400 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div class="text-sm font-black text-gray-800 dark:text-white mb-1">تدقيق الحركات</div>
                <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wide">سجل الرقابة</div>
                <a href="{{ route('admin.audit-logs.index') }}" class="mt-4 px-4 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg font-bold text-xs hover:bg-indigo-600 hover:text-white transition-colors">مراجعة السجلات</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Recent Collections (Accountant View) -->
        <div class="lg:col-span-2 bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-gray-100 dark:border-dark-border overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-gray-50/50 dark:bg-dark-tableheader/50">
                <h2 class="text-xl font-black dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    آخر التحصيلات المالية
                </h2>
                <a href="{{ route('collections.index') }}" class="px-4 py-1 text-xs font-black bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full hover:bg-emerald-600 hover:text-white transition-all">مراقبة الكل</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="text-gray-500 dark:text-gray-400 text-xs font-black uppercase tracking-wider bg-gray-50/80 dark:bg-dark-tableheader/30">
                            <th class="px-6 py-4">رقم الإيصال</th>
                            <th class="px-6 py-4 text-center">العميل</th>
                            <th class="px-6 py-4">المبلغ</th>
                            <th class="px-6 py-4 text-left">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                        @forelse($recentCollections as $collection)
                            <tr class="hover:bg-emerald-50/30 dark:hover:bg-slate-700/30 transition-colors group text-sm">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 bg-gray-100 dark:bg-dark-bg/50 text-gray-700 dark:text-gray-300 font-bold px-3 py-1 rounded-lg text-xs border border-gray-200 dark:border-dark-border group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                        #{{ $collection->receipt_no }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="font-bold text-gray-800 dark:text-gray-200">{{ $collection->customer->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-black text-emerald-600 dark:text-emerald-400">
                                    ج.م {{ number_format($collection->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400 text-left">{{ $collection->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-medium italic">لا توجد عمليات تحصيل مسجلة مؤخراً</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Collector Debt Overview (Simplified) -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-gray-100 dark:border-dark-border overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-dark-border bg-gray-50/50 dark:bg-dark-tableheader/50 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <h2 class="text-xl font-black dark:text-white">أداء التحصيل</h2>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-dark-border">
                @forelse($topCollectors as $collector)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors group">
                        <div>
                            <p class="text-sm font-black dark:text-white group-hover:text-emerald-600 transition-colors">{{ $collector->name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium tracking-widest uppercase">Collector</p>
                        </div>
                        <div class="text-left">
                            <div class="text-emerald-600 dark:text-emerald-400 font-black text-sm tracking-tighter">
                                ج.م {{ number_format($collector->collections_sum_amount ?? 0, 0) }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-gray-400 font-medium italic">لا توجد بيانات متاحة</div>
                @endforelse
            </div>
            <div class="p-4 bg-gray-50/50 dark:bg-dark-bg/20">
                <a href="{{ route('collectors.index') }}" class="block w-full text-center py-2 bg-white dark:bg-slate-700 text-gray-600 dark:text-gray-300 rounded-xl font-bold text-xs border border-gray-100 dark:border-slate-600 hover:bg-gray-50 transition-all">إدارة المندوبين</a>
            </div>
        </div>
    </div>

    <!-- Overdue Cheques Floating Alert -->
    @if($overdueCheques->count() > 0)
        <div class="mt-8 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-900 border-dashed rounded-3xl p-8 relative overflow-hidden group">
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                <div class="w-20 h-20 bg-amber-500 text-white rounded-3xl shadow-2xl flex items-center justify-center animate-pulse">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1 text-center md:text-right">
                    <h3 class="text-2xl font-black text-amber-900 dark:text-amber-400 mb-2">تنبيه محاسبي: شيكات متأخرة!</h3>
                    <p class="text-amber-700 dark:text-amber-500 font-bold mb-6">يوجد إجمالي {{ $overdueCheques->count() }} شيكات تجاوزت تاريخ الاستحقاق الفعلي.</p>
                    <a href="{{ route('cheques.index') }}" class="inline-block bg-amber-600 text-white px-8 py-3 rounded-2xl font-black text-sm shadow-lg shadow-amber-600/30 hover:bg-amber-700 transition-all">مباشرة الإجراءات المحاسبية</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
