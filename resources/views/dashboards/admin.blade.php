@extends('layouts.app')

@section('title', 'لوحة التحكم - الإدارة')

@section('content')
<div class="max-w-7xl mx-auto text-right" dir="rtl">
    <!-- Welcome Header Card -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-sm">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black mb-1 flex items-center gap-3">
                        مرحباً، {{ auth()->user()->name }}
                        <span class="text-xs bg-white/20 px-3 py-1 rounded-full font-bold uppercase tracking-widest animate-pulse border border-white/10">مدير النظام</span>
                    </h1>
                    <p class="text-blue-100 text-lg opacity-90 font-medium italic">لوحة تحكم النظام - نظرة عامة شاملة على العمليات اللوجستية والمالية</p>
                </div>
            </div>
            <div class="flex gap-3">
                @role('admin|supervisor|accountant')
                <a href="{{ route('collections.create') }}" class="bg-white dark:bg-blue-600 text-blue-600 dark:text-white hover:bg-blue-50 dark:hover:bg-blue-700 px-6 py-3 rounded-xl font-bold transition-all transform hover:scale-105 shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    تحصيل جديد
                </a>
                @endrole

                @role('admin|supervisor|plan_supervisor')
                <a href="{{ route('collection-plans.create') }}" class="bg-blue-800 text-white hover:bg-blue-900 px-6 py-3 rounded-xl font-bold border border-blue-400/30 transition-all transform hover:scale-105 shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    إنشاء خطة
                </a>
                @endrole
            </div>
        </div>
        <!-- Decorative subtle background arcs -->
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -left-10 -top-10 w-48 h-48 bg-blue-400/10 rounded-full blur-2xl"></div>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @role('admin|supervisor|accountant')
        <!-- Total Customers -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-2xl shadow-indigo-500/10 border border-indigo-50 dark:border-dark-border p-7 hover:border-indigo-500/50 hover:-translate-y-1.5 transition-all duration-500 group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-indigo-50 dark:bg-indigo-900/40 p-5 rounded-3xl text-indigo-600 dark:text-indigo-400 mb-5 group-hover:scale-110 transition-transform shadow-inner">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0"/></svg>
                </div>
                <div class="text-4xl font-black text-gray-800 dark:text-white mb-1 tracking-tighter">{{ $totalCustomers }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wide">إجمالي العملاء</div>
                <a href="{{ route('customers.index') }}" class="mt-4 px-4 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg font-bold text-xs hover:bg-indigo-600 hover:text-white transition-colors">عرض الكل</a>
            </div>
        </div>
        @endrole

        @role('admin|supervisor|accountant')
        <!-- Total Collectors -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-2xl shadow-emerald-500/10 border border-emerald-50 dark:border-dark-border p-7 hover:border-emerald-500/50 hover:-translate-y-1.5 transition-all duration-500 group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-emerald-50 dark:bg-emerald-900/40 p-5 rounded-3xl text-emerald-600 dark:text-emerald-400 mb-5 group-hover:scale-110 transition-transform shadow-inner">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="text-4xl font-black text-gray-800 dark:text-white mb-1 tracking-tighter">{{ $totalCollectors }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wide">إجمالي المحصلون</div>
                <a href="{{ route('collectors.index') }}" class="mt-4 px-4 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg font-bold text-xs hover:bg-emerald-600 hover:text-white transition-colors">إدارة المحصلين</a>
            </div>
        </div>
        @endrole

        @role('admin|supervisor|accountant')
        <!-- Total Collections -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-2xl shadow-amber-500/10 border border-amber-50 dark:border-dark-border p-7 hover:border-amber-500/50 hover:-translate-y-1.5 transition-all duration-500 group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-amber-50 dark:bg-amber-900/40 p-5 rounded-3xl text-amber-600 dark:text-amber-400 mb-5 group-hover:scale-110 transition-transform shadow-inner">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-2xl font-black text-gray-800 dark:text-white mb-1 tracking-tighter">
                    <span class="text-sm font-normal text-gray-400 ml-1">ج.م</span>
                    {{ number_format($totalCollections, 0) }}
                </div>
                <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wide">إجمالي التحصيلات</div>
                <a href="{{ route('collections.index') }}" class="mt-4 px-4 py-1.5 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg font-bold text-xs hover:bg-amber-600 hover:text-white transition-colors">السجل المالي</a>
            </div>
        </div>

        <!-- Pending Cheques -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-2xl shadow-rose-500/10 border border-rose-50 dark:border-dark-border p-7 hover:border-rose-500/50 hover:-translate-y-1.5 transition-all duration-500 group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-rose-50 dark:bg-rose-900/40 p-5 rounded-3xl text-rose-600 dark:text-rose-400 mb-5 group-hover:scale-110 transition-transform shadow-inner">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="text-4xl font-black text-gray-800 dark:text-white mb-1 tracking-tighter">{{ $pendingCheques }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wide">شيكات معلقة</div>
                <a href="{{ route('cheques.index') }}" class="mt-4 px-4 py-1.5 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-lg font-bold text-xs hover:bg-rose-600 hover:text-white transition-colors">مراجعة الشيكات</a>
            </div>
        </div>

        <!-- Due Installments -->
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-2xl shadow-amber-500/10 border border-amber-50 dark:border-dark-border p-7 hover:border-amber-500/50 hover:-translate-y-1.5 transition-all duration-500 group">
            <div class="flex flex-col items-center text-center">
                <div class="bg-amber-50 dark:bg-amber-900/40 p-5 rounded-3xl text-amber-600 dark:text-amber-400 mb-5 group-hover:scale-110 transition-transform shadow-inner relative">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @if($dueInstallmentsCount > 0)
                        <span class="absolute top-0 right-0 w-4 h-4 bg-rose-500 rounded-full animate-ping border-4 border-white dark:border-dark-card"></span>
                    @endif
                </div>
                <div class="text-4xl font-black text-gray-800 dark:text-white mb-1 tracking-tighter">{{ $dueInstallmentsCount }}</div>
                <div class="text-gray-500 dark:text-gray-400 font-bold text-sm uppercase tracking-wide">أقساط مستحقة</div>
                <a href="{{ route('installments.index') }}" class="mt-4 px-4 py-1.5 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg font-bold text-xs hover:bg-amber-600 hover:text-white transition-colors">إدارة الأقساط</a>
            </div>
        </div>
        @endrole
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Recent Collections Table Card -->
        <div class="lg:col-span-2 bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-gray-100 dark:border-dark-border overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-gray-50/50 dark:bg-dark-tableheader/50">
                <h2 class="text-xl font-black dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    آخر العمليات
                </h2>
                <a href="{{ route('collections.index') }}" class="px-4 py-1 text-xs font-black bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full hover:bg-indigo-600 hover:text-white transition-all">عرض الكل</a>
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
                            <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 bg-gray-100 dark:bg-dark-bg/50 text-gray-700 dark:text-gray-300 font-bold px-3 py-1 rounded-lg text-xs border border-gray-200 dark:border-dark-border group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                        #{{ $collection->receipt_no }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="font-bold text-gray-800 dark:text-gray-200">{{ $collection->customer->name }}</div>
                                    <div class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $collection->collector->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-black text-emerald-600 dark:text-emerald-400">
                                    <span class="text-[10px] font-normal ml-1">ج.م</span>
                                    {{ number_format($collection->amount, 0) }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400 text-left font-medium">{{ $collection->created_at->format('Y-m-d') }}</td>
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

        <!-- Top Collectors Card -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-gray-100 dark:border-dark-border overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-dark-border bg-gray-50/50 dark:bg-dark-tableheader/50 flex items-center gap-2">
                <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <h2 class="text-xl font-black dark:text-white">نجوم التحصيل</h2>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-dark-border">
                @forelse($topCollectors as $index => $collector)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors group">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white font-black text-lg shadow-lg">
                                    {{ mb_substr($collector->name, 0, 1) }}
                                </div>
                                <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-amber-400 border-2 border-white dark:border-dark-card flex items-center justify-center text-[10px] font-black text-white">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-black dark:text-white group-hover:text-indigo-600 transition-colors">{{ $collector->name }}</p>
                                <p class="text-xs text-gray-400 font-medium">{{ $collector->phone }}</p>
                            </div>
                        </div>
                        <div class="text-left">
                            <div class="text-emerald-600 dark:text-emerald-400 font-black text-sm tracking-tighter">
                                <span class="text-[10px] font-normal">ج.م</span>
                                {{ number_format($collector->collections_sum_amount ?? 0, 0) }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-gray-400 font-medium italic">لا توجد بيانات متاحة</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Active Plans Section -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-gray-100 dark:border-dark-border overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-gray-50/50 dark:bg-dark-tableheader/50">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-600 p-2 rounded-xl text-white shadow-lg shadow-indigo-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h2 class="text-xl font-black dark:text-white">خطط التحصيل الجارية</h2>
            </div>
            @role('admin|supervisor|plan_supervisor')
            <a href="{{ route('collection-plans.create') }}" class="text-xs font-black text-indigo-600 dark:text-indigo-400 hover:underline">إضافة خطة جديدة +</a>
            @endrole
        </div>

        @if($activePlans->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-dark-tableheader/30 text-gray-400 text-[10px] font-black uppercase tracking-wider">
                            <th class="px-6 py-4">الخطة</th>
                            <th class="px-6 py-4 text-center">المحصل</th>
                            <th class="px-6 py-4">الإنجاز</th>
                            <th class="px-6 py-4 text-left">الموقف المالي</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                        @foreach($activePlans as $plan)
                            <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="font-black dark:text-white group-hover:text-indigo-600 transition-colors">{{ $plan->name }}</div>
                                    <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-gray-100 dark:bg-dark-bg/50 text-[10px] font-bold text-gray-500 mt-1 uppercase border border-gray-200 dark:border-dark-border">
                                        {{ $plan->collection_type === 'special' ? 'خطة خاصة' : 'تحصيل عادي' }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center font-bold text-gray-700 dark:text-gray-300">{{ $plan->collector->name }}</td>
                                <td class="px-6 py-5 min-w-[200px]">
                                    @php $progress = $plan->getProgressPercentage(); @endphp
                                    <div class="flex flex-col gap-2">
                                        <div class="flex justify-between items-center text-[10px] font-black">
                                            <span class="{{ $progress >= 100 ? 'text-emerald-600' : 'text-indigo-600' }}">{{ $progress }}% مكتمل</span>
                                            <span class="text-gray-400">{{ $plan->items->whereNotNull('collection_id')->count() }} / {{ $plan->items->count() }} عميل</span>
                                        </div>
                                        <div class="h-1.5 bg-gray-100 dark:bg-dark-bg/50 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-1000 bg-gradient-to-r {{ $progress >= 100 ? 'from-emerald-500 to-teal-400' : 'from-indigo-600 to-blue-500' }}" 
                                                 style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-left">
                                    <div class="font-black text-gray-900 dark:text-gray-100 text-sm">ج.م {{ number_format($plan->getTotalCollectedAmount(), 0) }}</div>
                                    <div class="text-[10px] text-gray-400 font-medium">من أصل {{ number_format($plan->getTotalExpectedAmount(), 0) }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-16 text-center text-gray-400 font-medium">
                <p class="text-lg">لا توجد خطط نشطة في الوقت الحالي</p>
                @role('admin|supervisor|plan_supervisor')
                <a href="{{ route('collection-plans.create') }}" class="text-indigo-600 hover:underline mt-4 inline-block font-black text-sm">ابدأ بإنشاء أول خطة الآن ←</a>
                @endrole
            </div>
        @endif
    </div>

    <!-- Users Management Grid -->
    @role('admin')
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-gray-100 dark:border-dark-border overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-gray-50/50 dark:bg-dark-tableheader/50">
            <h2 class="text-xl font-black dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                فريق العمل
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-1.5 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 text-xs font-black rounded-xl hover:bg-amber-600 hover:text-white transition-all">سجل الرقابة</a>
                <a href="{{ route('users.index') }}" class="px-4 py-1.5 bg-indigo-600 text-white text-xs font-black rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20">إدارة الصلاحيات</a>
            </div>
        </div>

        @if ($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-dark-tableheader/30 text-gray-400 text-[10px] font-black uppercase tracking-wider">
                            <th class="px-6 py-5 text-center">عضو الفريق</th>
                            <th class="px-6 py-5 text-center">أدوار الصلاحية</th>
                            <th class="px-6 py-5 text-center">دخول الإدارة</th>
                            <th class="px-6 py-5 text-center">تاريخ الانضمام</th>
                            <th class="px-6 py-5 text-left">التحكم</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                        @foreach($users as $user)
                            <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-all group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center font-black text-sm">
                                            {{ mb_substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-black text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                            <div class="text-[10px] text-gray-400 font-medium">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap justify-center gap-1.5">
                                        @foreach($user->roles as $role)
                                            <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-tighter border
                                                @if($role->name === 'admin') bg-indigo-600 text-white border-indigo-700
                                                @elseif($role->name === 'supervisor') bg-amber-500 text-white border-amber-600
                                                @elseif($role->name === 'accountant') bg-emerald-500 text-white border-emerald-600
                                                @elseif($role->name === 'plan_supervisor') bg-blue-500 text-white border-blue-600
                                                @else bg-gray-500 text-white border-gray-600
                                                @endif">
                                                @if($role->name === 'admin') مدير نظام 
                                                @elseif($role->name === 'supervisor') مشرف عمليات 
                                                @elseif($role->name === 'accountant') محاسب مالي
                                                @elseif($role->name === 'plan_supervisor') مشرف خطط
                                                @else محصل ميداني 
                                                @endif
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-black rounded-full border border-emerald-100 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                        {{ $user->can('view-dashboard') ? 'مصرح له' : 'مفعل' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center text-xs text-gray-400 font-medium whitespace-nowrap">
                                    {{ $user->created_at->format('Y-M-d') }}
                                </td>
                                <td class="px-6 py-5 text-left">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}')" class="p-2 rounded-lg bg-gray-100 dark:bg-dark-bg/50 text-gray-500 dark:text-gray-400 hover:bg-indigo-600 hover:text-white transition-all border border-gray-200 dark:border-dark-border group-hover:shadow-lg shadow-indigo-500/20" title="تعديل الصلاحيات">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                        </button>
                                        @if($user->id !== auth()->id())
                                        <a href="{{ route('users.edit', $user) }}" class="p-2 rounded-lg bg-gray-100 dark:bg-dark-bg/50 text-gray-500 dark:text-gray-400 hover:bg-blue-600 hover:text-white transition-all border border-gray-200 dark:border-dark-border" title="تعديل البيانات">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @endrole

    <!-- Overdue Cheques Floating Alert -->
    @role('admin|supervisor')
    @if($overdueCheques->count() > 0)
        <div class="mt-8 bg-rose-50 dark:bg-rose-900/10 border border-rose-200 dark:border-rose-900 border-dashed rounded-3xl p-8 relative overflow-hidden group">
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-rose-500 opacity-[0.03] group-hover:scale-110 transition-transform duration-1000"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                <div class="w-20 h-20 bg-rose-500 text-white rounded-3xl shadow-2xl flex items-center justify-center animate-bounce shadow-rose-500/50">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1 text-center md:text-right">
                    <h3 class="text-2xl font-black text-rose-900 dark:text-rose-400 mb-2">تنبيه: شيكات متأخرة للغاية!</h3>
                    <p class="text-rose-700 dark:text-rose-500 font-bold mb-6">يوجد حالياً {{ $overdueCheques->count() }} شيكات تجاوزت تاريخ التحصيل. يرجى المتابعة الفورية.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($overdueCheques->take(6) as $cheque)
                            <a href="{{ route('cheques.index') }}?search={{ $cheque->cheque_no }}" class="bg-white dark:bg-dark-card border border-rose-100 dark:border-rose-900 p-4 rounded-2xl hover:shadow-2xl hover:-translate-y-1 transition-all flex justify-between items-center group/item">
                                <div>
                                    <div class="font-black text-gray-800 dark:text-white text-sm group-hover/item:text-rose-600 transition-colors">{{ $cheque->customer->name }}</div>
                                    <div class="text-[10px] text-gray-400 font-medium tracking-tighter uppercase">{{ $cheque->cheque_no }}</div>
                                </div>
                                <div class="text-rose-600 font-black text-sm">ج.م {{ number_format($cheque->amount, 0) }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endrole
</div>
</div>

<!-- Role Management Modal -->
@role('admin')
<div id="roleModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-[100] p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all scale-100">
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 px-8 py-6 text-white text-right">
            <h3 class="text-2xl font-black">تحديث الصلاحيات</h3>
            <p class="text-blue-100 opacity-80 mt-1" id="userName"></p>
        </div>
        <form id="roleForm" method="POST" action="" class="p-8">
            @csrf
            @method('PUT')
            <div class="space-y-4 mb-8">
                <label class="flex items-center p-4 rounded-2xl border-2 border-gray-100 cursor-pointer hover:border-blue-500 transition-all group has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
                    <input type="checkbox" name="roles[]" value="admin" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div class="mr-4">
                        <span class="block font-black text-gray-800 group-hover:text-blue-700">مدير نظام</span>
                        <span class="text-xs text-gray-400">صلاحيات كاملة لجميع أقسام النظام</span>
                    </div>
                </label>
                <label class="flex items-center p-4 rounded-2xl border-2 border-gray-100 cursor-pointer hover:border-blue-500 transition-all group has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
                    <input type="checkbox" name="roles[]" value="supervisor" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div class="mr-4">
                        <span class="block font-black text-gray-800 group-hover:text-blue-700">مشرف عمليات</span>
                        <span class="text-xs text-gray-400">إدارة الخطط والتحصيلات والمراجعة</span>
                    </div>
                </label>
                <label class="flex items-center p-4 rounded-2xl border-2 border-gray-100 cursor-pointer hover:border-blue-500 transition-all group has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
                    <input type="checkbox" name="roles[]" value="collector" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div class="mr-4">
                        <span class="block font-black text-gray-800 group-hover:text-blue-700">محصل ميداني</span>
                        <span class="text-xs text-gray-400">صلاحيات مقتصرة على تسجيل التحصيلات</span>
                    </div>
                </label>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-lg transition-all transform hover:scale-[1.02]">
                    حفظ التغييرات
                </button>
                <button type="button" onclick="closeRoleModal()" class="px-8 bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 py-4 rounded-2xl transition-all">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRoleModal(userId, userName) {
        document.getElementById('userName').textContent = 'المستخدم: ' + userName;
        const form = document.getElementById('roleForm');
        form.action = `/users/${userId}/roles`;
        document.getElementById('roleModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRoleModal() {
        document.getElementById('roleModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endrole
@endsection
