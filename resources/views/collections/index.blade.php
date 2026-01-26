@extends('layouts.app')

@section('title', $showTrashed ? 'التحصيلات المحذوفة' : 'قائمة التحصيلات')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="bg-emerald-600 p-3 rounded-2xl shadow-lg shadow-emerald-500/30 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black dark:text-white tracking-tight">{{ $showTrashed ? 'التحصيلات المحذوفة' : 'سجل التحصيلات' }}</h1>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">{{ $showTrashed ? 'إدارة التحصيلات المحذوفة مؤقتاً' : 'متابعة كافة عمليات التحصيل النقدي والبنكي' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                @if(!$showTrashed)
                <a href="{{ route('collections.export') }}" class="flex-grow lg:flex-none flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-500/20 transition-all text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    تصدير إكسيل
                </a>
                <a href="{{ route('collections.create') }}" class="flex-grow lg:flex-none flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-500/20 transition-all text-sm whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    تسجيل تحصيل جديد
                </a>
                @endif
            </div>
        </div>

        <!-- Tabs for Active/Trashed -->
        <div class="mt-6 flex gap-2">
            <a href="{{ route('collections.index') }}" 
               class="px-6 py-3 rounded-xl font-bold transition-all {{ !$showTrashed ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/30' : 'bg-gray-100 dark:bg-dark-card text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-dark-border' }}">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    الكل ({{ $activeCount }})
                </span>
            </a>
            <a href="{{ route('collections.index', ['trashed' => '1']) }}" 
               class="px-6 py-3 rounded-xl font-bold transition-all {{ $showTrashed ? 'bg-red-600 text-white shadow-lg shadow-red-500/30' : 'bg-gray-100 dark:bg-dark-card text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-dark-border' }}">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    المحذوفة ({{ $trashedCount }})
                </span>
            </a>
        </div>

        <!-- Search Bar Card -->
        <div class="mt-6 bg-white dark:bg-dark-card p-4 rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border">
            <form action="{{ route('collections.index') }}" method="GET" class="relative">
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
                       placeholder="بحث برقم الإيصال، اسم العميل، أو المندوب..." 
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
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">رقم الإيصال</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">اسم العميل</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">المندوب</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">المبلغ</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">النوع</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">التاريخ</th>
                        @if($showTrashed)
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">تاريخ الحذف</th>
                        @else
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">المرفق</th>
                        @endif
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @forelse ($collections as $collection)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-colors group {{ $showTrashed ? 'opacity-75' : '' }}">
                            <td class="px-6 py-5 whitespace-nowrap font-bold text-blue-600 dark:text-blue-400 {{ $showTrashed ? 'line-through' : '' }}">#{{ $collection->receipt_no }}</td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="font-bold dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $collection->customer->name ?? 'عميل محذوف' }}</span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="text-sm dark:text-gray-300">{{ $collection->collector->name ?? 'محصل محذوف' }}</span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap font-black text-lg dark:text-white">{{ number_format($collection->amount, 2) }}</td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-lg {{ ($collection->payment_type ?? 'cash') === 'cash' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                    {{ ($collection->payment_type ?? 'cash') === 'cash' ? 'نقدي' : 'شيك' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $collection->collection_date->format('Y-m-d') }}</td>
                            @if($showTrashed)
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $collection->deleted_at->format('Y-m-d H:i') }}</td>
                            @else
                            <td class="px-6 py-5 text-center">
                                @if($collection->attachment)
                                    <a href="{{ asset('storage/' . $collection->attachment) }}" target="_blank" class="p-2 rounded-lg bg-gray-100 dark:bg-dark-bg/50 text-gray-500 dark:text-gray-400 hover:bg-blue-500 hover:text-white transition-all inline-block shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                @else
                                    <span class="text-gray-300 dark:text-gray-600">-</span>
                                @endif
                            </td>
                            @endif
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-3">
                                    @if($showTrashed)
                                        <!-- Restore Button -->
                                        <form action="{{ route('collections.restore', $collection->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="استعادة">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                            </button>
                                        </form>
                                        <!-- Force Delete Button -->
                                        @if(auth()->user()->hasRole('admin'))
                                        <form action="{{ route('collections.forceDelete', $collection->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('هل أنت متأكد من حذف هذا التحصيل نهائياً؟ لا يمكن التراجع عن هذا الإجراء!')" title="حذف نهائي">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            </button>
                                        </form>
                                        @endif
                                    @else
                                        <a href="{{ route('collections.show', $collection) }}" class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="عرض">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('collections.edit', $collection) }}" class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white transition-all shadow-sm" title="تعديل">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('collections.destroy', $collection) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('هل أنت متأكد من حذف هذا التحصيل؟')" title="حذف">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $showTrashed ? '8' : '8' }}" class="px-6 py-20 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 opacity-10 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($showTrashed)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        @endif
                                    </svg>
                                    <p class="text-xl font-medium">{{ $showTrashed ? 'لا توجد تحصيلات محذوفة.' : 'لم يتم تسجيل أي عمليات تحصيل حتى الآن.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $collections->links() }}
    </div>
</div>
@endsection
