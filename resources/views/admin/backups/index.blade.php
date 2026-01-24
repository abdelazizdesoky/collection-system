@extends('layouts.app')

@section('title', 'إدارة النسخ الاحتياطي')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4 text-right">
                <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-500/30 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black dark:text-white tracking-tight">إدارة النسخ الاحتياطي</h1>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">تأمين بيانات النظام واستعادتها في حالات الطوارئ</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                <form action="{{ route('admin.backups.create') }}" method="POST" class="w-full lg:w-auto">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-500/20 transition-all text-sm whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        إنشاء نسخة احتياطية جديدة
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader text-gray-600 dark:text-gray-300">
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">اسم النسخة الاحتياطية</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الحجم</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">تاريخ الإنشاء</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الإجراءات والسحب</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @forelse($backups as $backup)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-colors group">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-dark-bg flex items-center justify-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 1.1.9 2 2 2h12a2 2 0 002-2V7M4 7a2 2 0 012-2h12a2 2 0 012 2M4 7l8 5 8-5M12 11l8-5-8 5-8-5"/></svg>
                                    </div>
                                    <span class="font-bold dark:text-white text-md">{{ $backup['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center whitespace-nowrap text-sm font-medium dark:text-gray-400">
                                <span class="px-2 py-1 bg-gray-100 dark:bg-dark-bg rounded-lg">{{ $backup['size'] }}</span>
                            </td>
                            <td class="px-6 py-5 text-center whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $backup['date'] }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.backups.download', $backup['name']) }}" 
                                       class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="تحميل الملف">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>

                                    <form action="{{ route('admin.backups.restore', $backup['name']) }}" method="POST" class="inline" onsubmit="return confirm('استعادة البيانات؟ سيتم استبدال البيانات الحالية تماماً بالبيانات الموجودة في هذه النسخة!')">
                                        @csrf
                                        <button type="submit" 
                                                class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white transition-all shadow-sm" title="استجراع النسخة">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.backups.destroy', $backup['name']) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف ملف النسخة الاحتياطية نهائياً؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="حذف النسخة">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center text-gray-400 font-medium italic">لا توجد نسخ احتياطية مسجلة حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
