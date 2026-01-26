@extends('layouts.app')

@section('title', $showTrashed ? 'العملاء المحذوفين' : 'قائمة العملاء')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="bg-blue-600 p-3 rounded-2xl shadow-lg shadow-blue-500/30 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black dark:text-white tracking-tight">{{ $showTrashed ? 'العملاء المحذوفين' : 'قائمة العملاء' }}</h1>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">{{ $showTrashed ? 'إدارة العملاء المحذوفين مؤقتاً' : 'إدارة وبيانات جميع العملاء المسجلين' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                @if(!$showTrashed)
                <a href="{{ route('customers.export') }}" class="flex-grow lg:flex-none flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    تصدير إكسيل
                </a>
                <a href="{{ route('customers.create') }}" class="flex-grow lg:flex-none flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-500/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    إضافة عميل جديد
                </a>
                @endif
            </div>
        </div>

        <!-- Tabs for Active/Trashed -->
        <div class="mt-6 flex gap-2">
            <a href="{{ route('customers.index') }}" 
               class="px-6 py-3 rounded-xl font-bold transition-all {{ !$showTrashed ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-100 dark:bg-dark-card text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-dark-border' }}">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    الكل ({{ $activeCount }})
                </span>
            </a>
            <a href="{{ route('customers.index', ['trashed' => '1']) }}" 
               class="px-6 py-3 rounded-xl font-bold transition-all {{ $showTrashed ? 'bg-red-600 text-white shadow-lg shadow-red-500/30' : 'bg-gray-100 dark:bg-dark-card text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-dark-border' }}">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    المحذوفة ({{ $trashedCount }})
                </span>
            </a>
        </div>

        <!-- Search Bar Card -->
        <div class="mt-6 bg-white dark:bg-dark-card p-4 rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border">
            <form action="{{ route('customers.index') }}" method="GET" class="relative">
                @if($showTrashed)
                <input type="hidden" name="trashed" value="1">
                @endif
                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="ابحث باسم العميل أو رقم الهاتف..." 
                       class="w-full pr-12 pl-4 py-4 rounded-xl border-none bg-gray-50 dark:bg-dark-bg/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-lg font-medium"
                >
            </form>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $message }}
        </div>
    @endif

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader text-gray-600 dark:text-gray-300">
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">الكود</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">اسم العميل</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">رقم الهاتف</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap text-center">نوع الرصيد</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">الرصيد الافتتاحي</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">الرصيد الحالي</th>
                        @if($showTrashed)
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">تاريخ الحذف</th>
                        @endif
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-colors group {{ $showTrashed ? 'opacity-75' : '' }}">
                            <td class="px-6 py-5 whitespace-nowrap font-medium text-gray-500 dark:text-gray-400 font-mono">{{ $customer->code ?? '-' }}</td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full {{ $showTrashed ? 'bg-red-100 dark:bg-red-900/30' : 'bg-blue-100 dark:bg-blue-900/30' }} flex items-center justify-center {{ $showTrashed ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400' }} font-bold">
                                        {{ mb_substr($customer->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold dark:text-white text-lg group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors {{ $showTrashed ? 'line-through' : '' }}">{{ $customer->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap font-medium dark:text-gray-300" dir="ltr text-right">{{ $customer->phone }}</td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full {{ $customer->balance_type == 'debit' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' }}">
                                    {{ $customer->balance_type == 'debit' ? 'مدين' : 'دائن' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap font-bold dark:text-gray-200">{{ number_format($customer->opening_balance, 2) }}</td>
                            <td class="px-6 py-5 whitespace-nowrap font-black text-blue-600 dark:text-blue-400">{{ number_format($customer->getCurrentBalance(), 2) }}</td>
                            @if($showTrashed)
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $customer->deleted_at->format('Y-m-d H:i') }}</td>
                            @endif
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-3">
                                    @if($showTrashed)
                                        <!-- Restore Button -->
                                        <form action="{{ route('customers.restore', $customer->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="استعادة">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                            </button>
                                        </form>
                                        <!-- Force Delete Button -->
                                        @if(auth()->user()->hasRole('admin'))
                                        <form action="{{ route('customers.forceDelete', $customer->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('هل أنت متأكد من حذف هذا العميل نهائياً؟ لا يمكن التراجع عن هذا الإجراء!')" title="حذف نهائي">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            </button>
                                        </form>
                                        @endif
                                    @else
                                        <a href="{{ route('customers.show', $customer) }}" class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="عرض">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white transition-all shadow-sm" title="تعديل">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('هل أنت متأكد من حذف هذا العميل؟')" title="حذف">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $showTrashed ? '8' : '7' }}" class="px-6 py-20 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 opacity-10 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($showTrashed)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        @endif
                                    </svg>
                                    <p class="text-xl font-medium">{{ $showTrashed ? 'لا توجد عملاء محذوفين.' : 'لم يتم العثور على أي عملاء.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $customers->links() }}
    </div>
</div>
@endsection
