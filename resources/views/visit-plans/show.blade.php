@extends('layouts.app')

@section('title', $visitPlan->name)

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('visit-plans.index') }}" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black dark:text-white">{{ $visitPlan->name }}</h1>
                    <p class="text-gray-500 dark:text-gray-400">
                        {{ $visitPlan->collector->name }} • {{ $visitPlan->frequency_label }} • {{ $visitPlan->start_date->format('Y-m-d') }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <span class="px-4 py-2 rounded-xl font-bold text-sm {{ $visitPlan->status_color }}">
                    {{ $visitPlan->status_label }}
                </span>
                <a href="{{ route('visit-plans.edit', $visitPlan) }}" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-xl transition-all text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    تعديل
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $message }}
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $message }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
            <div class="flex items-center gap-4">
                <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-black dark:text-white">{{ $visitPlan->items->count() }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">إجمالي العملاء</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-black text-green-600 dark:text-green-400">{{ $visitPlan->items->where('status', 'visited')->count() }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">تمت زيارتهم</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
            <div class="flex items-center gap-4">
                <div class="bg-orange-100 dark:bg-orange-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-black text-orange-600 dark:text-orange-400">{{ $visitPlan->items->where('status', 'pending')->count() }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">معلق</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-black text-blue-600 dark:text-blue-400">{{ $visitPlan->getProgressPercentage() }}%</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">نسبة الإنجاز</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Customer Form -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 mb-6 border border-gray-100 dark:border-dark-border">
        <h2 class="text-lg font-bold dark:text-white mb-4">إضافة عميل للخطة</h2>
        <form action="{{ route('visit-plan-items.store') }}" method="POST" class="flex flex-wrap gap-4">
            @csrf
            <input type="hidden" name="visit_plan_id" value="{{ $visitPlan->id }}">
            <div class="flex-grow">
                <select name="customer_id" required class="w-full select2-search" data-placeholder="اختر العميل بالاسم أو الكود...">
                    <option value=""></option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-24">
                <input type="number" name="priority" placeholder="الأولوية" min="1" 
                    class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white text-center">
            </div>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-xl transition-all">
                <svg class="w-5 h-5 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                إضافة
            </button>
        </form>
    </div>

    <!-- Customers List -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="bg-gray-50 dark:bg-dark-tableheader px-6 py-4 border-b border-gray-100 dark:border-dark-border">
            <h2 class="text-lg font-bold dark:text-white">قائمة العملاء</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader text-gray-600 dark:text-gray-300">
                        <th class="px-6 py-4 text-sm font-bold">#</th>
                        <th class="px-6 py-4 text-sm font-bold">العميل</th>
                        <th class="px-6 py-4 text-sm font-bold">الهاتف</th>
                        <th class="px-6 py-4 text-sm font-bold text-center">الحالة</th>
                        <th class="px-6 py-4 text-sm font-bold text-center">نوع الزيارة</th>
                        <th class="px-6 py-4 text-sm font-bold text-center">وقت الزيارة</th>
                        <th class="px-6 py-4 text-sm font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @forelse($visitPlan->items as $item)
                        <tr class="hover:bg-purple-50/30 dark:hover:bg-slate-700/30">
                            <td class="px-6 py-4">
                                <span class="w-8 h-8 inline-flex items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 font-bold text-sm">
                                    {{ $item->priority }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('customers.show', $item->customer) }}" class="font-bold dark:text-white hover:text-purple-600 dark:hover:text-purple-400">
                                    {{ $item->customer->name }}
                                </a>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($item->customer->address, 40) }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $item->customer->phone }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $item->status_color }}">
                                    {{ $item->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->visit)
                                    <span class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $item->visit->visit_type_color }}">
                                        {{ $item->visit->visit_type_label }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                @if($item->visit)
                                    {{ $item->visit->visit_time->format('H:i') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    @if($item->visit)
                                        <a href="{{ route('visit.details', $item->visit) }}" 
                                           class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all" 
                                           title="عرض التفاصيل">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </a>
                                        @if($item->visit->collection_id)
                                            <a href="{{ route('shared.receipt', $item->visit->collection_id) }}" 
                                               class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white transition-all" 
                                               title="طباعة الإيصال">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                                </svg>
                                            </a>
                                        @endif
                                    @endif
                                    
                                    @if($item->status === 'pending')
                                        <form action="{{ route('visit-plan-items.update-status', $item) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="skipped">
                                            <button type="submit" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all" title="تخطي">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('visit-plan-items.destroy', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all" 
                                            onclick="return confirm('هل أنت متأكد من حذف هذا العميل من الخطة؟')" title="حذف">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto opacity-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-lg font-medium">لم يتم إضافة عملاء لهذه الخطة بعد</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($visitPlan->notes)
        <div class="mt-6 bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
            <h3 class="text-lg font-bold dark:text-white mb-2">ملاحظات</h3>
            <p class="text-gray-600 dark:text-gray-400">{{ $visitPlan->notes }}</p>
        </div>
    @endif
</div>
@endsection
