@extends('layouts.app')

@section('title', 'تفاصيل الشيك #' . $cheque->cheque_no)

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4 text-right w-full">
            <a href="{{ route('cheques.index') }}" 
               class="bg-white dark:bg-dark-card p-3 rounded-xl shadow-md border border-gray-100 dark:border-dark-border text-gray-500 hover:text-gray-700 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-black dark:text-white tracking-tight">شيك بنكي #{{ $cheque->cheque_no }}</h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium">تفاصيل الشيك وحالة المقاصة البنكية</p>
            </div>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('cheques.edit', $cheque) }}" 
               class="flex-grow md:flex-none bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2 text-sm whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                تعديل الشيك
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Cheque Info Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-dark-border">
                <h2 class="text-lg font-bold dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    بيانات الشيك
                </h2>
                <div class="space-y-4">
                    <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                        <p class="text-xs text-gray-500 mb-1">العميل (الساحب)</p>
                        <a href="{{ route('customers.show', $cheque->customer) }}" class="font-bold text-indigo-600 dark:text-indigo-400 text-lg hover:underline">
                            {{ $cheque->customer->name }}
                        </a>
                    </div>
                    <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                        <p class="text-xs text-gray-500 mb-1">البنك المسحوب عليه</p>
                        <p class="font-bold dark:text-white">{{ $cheque->bank_name }}</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-gray-50 dark:bg-dark-bg/30 p-3 rounded-xl border border-gray-100 dark:border-dark-border">
                            <p class="text-xs text-gray-500 mb-1">تاريخ الاستحقاق</p>
                            <p class="font-bold {{ $cheque->due_date->isPast() && $cheque->status == 'pending' ? 'text-red-500' : 'dark:text-white' }} text-lg">
                                {{ $cheque->due_date->format('Y-m-d') }}
                                @if($cheque->due_date->isPast() && $cheque->status == 'pending')
                                    <span class="text-xs font-medium block mt-1">(متأخر)</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Amount Card -->
            <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-xl p-6 text-white overflow-hidden relative text-center">
                <div class="relative z-10">
                    <p class="text-sm opacity-80 mb-2">قيمة الشيك</p>
                    <h2 class="text-4xl font-black">{{ number_format($cheque->amount, 2) }}</h2>
                    <p class="text-lg opacity-90 mt-1">جنيه مصري</p>
                    
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-bold">
                            {{ $cheque->status == 'pending' ? 'قيد الانتظار' : ($cheque->status == 'cleared' ? 'تم التحصيل' : 'مرفوض / مرتجع') }}
                        </span>
                    </div>
                </div>
                <svg class="absolute -bottom-6 -left-6 w-32 h-32 opacity-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm2-3a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>

        <!-- Attachment & Link -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Alert for overdue -->
            @if($cheque->due_date->isPast() && $cheque->status == 'pending')
            <div class="bg-red-50 dark:bg-red-900/20 border-r-4 border-red-500 p-6 rounded-2xl text-red-700 dark:text-red-400 flex items-center gap-4">
                <svg class="w-8 h-8 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <h3 class="font-black text-lg">تنبيه: شيك متأخر التحصيل</h3>
                    <p class="font-medium opacity-80">لقد تجاوز هذا الشيك تاريخ الاستحقاق المحدد. يرجى مراجعة البنك أو تحصيل المبلغ نقداً.</p>
                </div>
            </div>
            @endif

            <!-- Attachment Section -->
            @if($cheque->collection && $cheque->collection->attachment)
                <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-dark-border">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            صورة الشيك / المرفق
                        </h3>
                        <a href="{{ route('collections.show', $cheque->collection) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-bold flex items-center gap-1 transition-colors">
                            عرض التحصيل المرتبط
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"/></svg>
                        </a>
                    </div>
                    
                    <div class="rounded-2xl overflow-hidden border border-gray-100 dark:border-dark-border bg-gray-50 dark:bg-dark-bg/50 group relative">
                        <img src="{{ asset('storage/' . $cheque->collection->attachment) }}" class="w-full h-auto max-h-[600px] object-contain group-hover:scale-[1.02] transition-transform duration-700 cursor-pointer" onclick="window.open(this.src)">
                        <div class="absolute top-4 left-4 bg-black/60 backdrop-blur-md text-white px-4 py-2 rounded-full text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">
                            انقر للتكبير
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 dark:bg-dark-bg/30 border-2 border-dashed border-gray-200 dark:border-dark-border rounded-2xl p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-gray-500 font-medium italic">لا توجد صورة شيك مرفقة حالياً.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
