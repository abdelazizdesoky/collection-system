@extends('layouts.app')

@section('title', 'التقارير المالية')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-500/30 text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-black dark:text-white tracking-tight">التقارير المالية العامة</h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium">ملخص حركة الحسابات الشهرية والسنوية</p>
            </div>
        </div>
        
        <form method="GET" class="flex items-center gap-2">
            <label class="text-sm font-bold text-gray-700 dark:text-gray-300">السنة:</label>
            <select name="year" onchange="this.form.submit()" class="rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-indigo-500">
                @for($y = now()->year; $y >= 2023; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-6 border border-emerald-100 dark:border-emerald-800">
            <p class="text-emerald-600 dark:text-emerald-400 font-bold mb-2">إجمالي التحصيلات (الدائن) - {{ $year }}</p>
            <p class="text-3xl font-black text-emerald-700 dark:text-emerald-300">{{ number_format($monthlyData->sum('total_credit'), 2) }} ج.م</p>
        </div>
        <div class="bg-rose-50 dark:bg-rose-900/20 rounded-2xl p-6 border border-rose-100 dark:border-rose-800">
            <p class="text-rose-600 dark:text-rose-400 font-bold mb-2">إجمالي المديونيات (المدين) - {{ $year }}</p>
            <p class="text-3xl font-black text-rose-700 dark:text-rose-300">{{ number_format($monthlyData->sum('total_debit'), 2) }} ج.م</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-6 border border-blue-100 dark:border-blue-800">
            <p class="text-blue-600 dark:text-blue-400 font-bold mb-2">صافي التدفق - {{ $year }}</p>
            <p class="text-3xl font-black text-blue-700 dark:text-blue-300" dir="ltr">{{ number_format($monthlyData->sum('total_credit') - $monthlyData->sum('total_debit'), 2) }}</p>
        </div>
    </div>

    <!-- Tabs -->
    <div x-data="{ activeTab: 'monthly' }" class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="flex border-b border-gray-100 dark:border-dark-border">
            <button @click="activeTab = 'monthly'" :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20': activeTab === 'monthly', 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:hover:bg-dark-bg/50': activeTab !== 'monthly' }" class="flex-1 py-4 px-6 text-center font-bold border-b-2 transition-all">
                التقرير الشهري ({{ $year }})
            </button>
            <button @click="activeTab = 'yearly'" :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20': activeTab === 'yearly', 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:hover:bg-dark-bg/50': activeTab !== 'yearly' }" class="flex-1 py-4 px-6 text-center font-bold border-b-2 transition-all">
                التقرير السنوي (شامل)
            </button>
        </div>

        <div class="p-6">
            <!-- Monthly Content -->
            <div x-show="activeTab === 'monthly'" x-transition>
                <div class="overflow-x-auto">
                    <table class="w-full text-right">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-dark-tableheader text-gray-500 dark:text-gray-400 text-sm">
                                <th class="px-6 py-4 font-bold rounded-r-xl">الشهر</th>
                                <th class="px-6 py-4 font-bold">إجمالي المديونية (Debit)</th>
                                <th class="px-6 py-4 font-bold">إجمالي التحصيل (Credit)</th>
                                <th class="px-6 py-4 font-bold rounded-l-xl">الصافي</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                            @foreach($monthlyData as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-dark-bg/50 transition-colors">
                                    <td class="px-6 py-4 font-bold dark:text-white">
                                        {{ \Carbon\Carbon::create()->month($row->month)->translatedFormat('F') }}
                                    </td>
                                    <td class="px-6 py-4 text-rose-600 font-bold tabular-nums">{{ number_format($row->total_debit, 2) }}</td>
                                    <td class="px-6 py-4 text-emerald-600 font-bold tabular-nums">{{ number_format($row->total_credit, 2) }}</td>
                                    <td class="px-6 py-4 font-black tabular-nums {{ ($row->total_credit - $row->total_debit) >= 0 ? 'text-blue-600' : 'text-amber-600' }}" dir="ltr">
                                        {{ number_format($row->total_credit - $row->total_debit, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            @if($monthlyData->isEmpty())
                                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">لا توجد حركات مالية لهذه السنة</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Yearly Content -->
            <div x-show="activeTab === 'yearly'" x-transition style="display: none;">
                <div class="overflow-x-auto">
                    <table class="w-full text-right">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-dark-tableheader text-gray-500 dark:text-gray-400 text-sm">
                                <th class="px-6 py-4 font-bold rounded-r-xl">السنة</th>
                                <th class="px-6 py-4 font-bold">إجمالي المديونية</th>
                                <th class="px-6 py-4 font-bold">إجمالي التحصيل</th>
                                <th class="px-6 py-4 font-bold rounded-l-xl">الصافي</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                            @foreach($yearlyData as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-dark-bg/50 transition-colors">
                                    <td class="px-6 py-4 font-black text-lg dark:text-white">{{ $row->year }}</td>
                                    <td class="px-6 py-4 text-rose-600 font-bold tabular-nums">{{ number_format($row->total_debit, 2) }}</td>
                                    <td class="px-6 py-4 text-emerald-600 font-bold tabular-nums">{{ number_format($row->total_credit, 2) }}</td>
                                    <td class="px-6 py-4 font-black tabular-nums {{ ($row->total_credit - $row->total_debit) >= 0 ? 'text-blue-600' : 'text-amber-600' }}" dir="ltr">
                                        {{ number_format($row->total_credit - $row->total_debit, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js for Tabs (assuming it's not in layout, adding inline if needed or checking layout) -->
<!-- Layout usually has it or we use vanilla JS. The dashboard uses x-data, so layout likely has Alpine. If not, fallback script below -->
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
