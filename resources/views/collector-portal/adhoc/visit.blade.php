@extends('layouts.collector')

@section('title', 'زيارة خارج الخطة - ' . $customer->name)

@section('content')
<div class="max-w-lg mx-auto">
    <!-- Back Button & Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('collector.adhoc') }}" 
           class="bg-white dark:bg-dark-card hover:bg-gray-50 dark:hover:bg-slate-700/50 p-3 rounded-xl shadow-md transition-colors border border-gray-100 dark:border-dark-border">
            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">زيارة خارج الخطة</h1>
            <p class="text-gray-500 dark:text-gray-400">{{ $customer->name }}</p>
        </div>
    </div>

    <!-- Pending Approval Notice -->
    <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-2xl flex items-start gap-3">
        <div class="text-purple-600 mt-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-purple-800">ملاحظة هامة:</p>
            <p class="text-xs text-purple-700">هذه الزيارة غير مجدولة في خطة اليوم. سيتم تسجيلها كمحاولة زيارة وتخضع للمراجعة.</p>
        </div>
    </div>

    <!-- Visit Form -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-dark-border">
        <form action="{{ route('collector.adhoc.store-visit', $customer) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Visit Type -->
            <div class="mb-5">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">نوع الزيارة *</label>
                <select name="visit_type" class="w-full px-4 py-3 border-2 border-gray-100 dark:border-slate-700 rounded-xl focus:border-purple-500 bg-gray-50 dark:bg-slate-800 font-bold dark:text-white" required>
                    <option value="">اختر النوع...</option>
                    @foreach($visitTypes as $type)
                        <option value="{{ $type->name }}">{{ $type->display_name ?? $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Notes -->
            <div class="mb-5">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">تقرير الزيارة *</label>
                <textarea name="notes" rows="4" class="w-full px-4 py-3 border-2 border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl focus:outline-none focus:border-purple-500 font-medium dark:text-white" placeholder="ماذا حدث خلال الزيارة؟" required></textarea>
            </div>

            <!-- Attachment -->
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">صورة من الموقع (اختياري)</label>
                <input type="file" name="attachment" accept="image/*" class="w-full">
            </div>

            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-4 rounded-xl font-black text-xl shadow-lg transition-all transform hover:scale-[1.02]">
                حفظ الزيارة
            </button>
        </form>
    </div>
</div>
@endsection
