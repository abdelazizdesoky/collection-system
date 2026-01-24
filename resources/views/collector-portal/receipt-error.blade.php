@extends('layouts.collector')

@section('title', 'خطأ في الطباعة')

@section('content')
<div class="max-w-lg mx-auto text-center px-4 py-12">
    <!-- Error Icon -->
    <div class="bg-red-50 dark:bg-red-900/20 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-8 text-red-500 shadow-lg shadow-red-500/10">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
    </div>

    <!-- Message -->
    <h1 class="text-2xl font-black text-gray-900 dark:text-white mb-4">عذراً، لا يمكن طباعة الإيصال</h1>
    <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
        {{ $message ?? 'لقد تجاوزت الحد المسموح لطباعة هذا الإيصال (3 مرات كحد أقصى).' }}
        <br>
        رقم الإيصال الأصلي: <span class="font-bold text-gray-800 dark:text-gray-200">#{{ $collection->receipt_no }}</span>
    </p>

    <!-- Actions -->
    <div class="space-y-4">
        <a href="{{ route('collector.dashboard') }}" class="block w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white py-4 rounded-2xl font-bold font-Cairo shadow-lg shadow-emerald-500/20 transition-all">
            العودة للوحة التحكم
        </a>
        
        @php
            $redirectUrl = $collection->planItem 
                ? route('collector.plan', $collection->planItem->collection_plan_id) 
                : route('collector.dashboard');
        @endphp
        
        <a href="{{ $redirectUrl }}" class="block w-full bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 py-4 rounded-2xl font-bold border border-gray-200 dark:border-slate-600 transition-all">
            العودة للخطة اليومية
        </a>
    </div>

    <p class="mt-8 text-xs text-gray-400">يرجى التواصل مع الإدارة إذا كنت بحاجة لإعادة طباعة الإيصال بتصريح خاص.</p>
</div>
@endsection
