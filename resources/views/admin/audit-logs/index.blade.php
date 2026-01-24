@extends('layouts.app')

@section('title', 'مراقبة العمليات - Audit Logs')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4 text-right">
                <div class="bg-rose-600 p-3 rounded-2xl shadow-lg shadow-rose-500/30 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black dark:text-white tracking-tight">سجل مراقبة العمليات</h1>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">متابعة دقيقة لكل التغييرات التي تتم على البيانات والعمليات المالية</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                <span class="px-4 py-2 bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-400 rounded-full text-sm font-bold border border-rose-100 dark:border-rose-800">
                    إجمالي السجلات: {{ $logs->total() }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader text-gray-600 dark:text-gray-300">
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">المستخدم</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الحدث</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">النموذج المتأثر</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">تفاصيل التغيير</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap text-center">التاريخ والوقت</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">البيانات التقنية</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @forelse($logs as $log)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-colors group">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-dark-bg flex items-center justify-center text-gray-600 dark:text-gray-400 font-bold">
                                        {{ mb_substr($log->user->name ?? 'ن', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold dark:text-white">{{ $log->user->name ?? 'نظام (System)' }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $log->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1 rounded-lg text-xs font-bold 
                                    @if($log->event === 'create') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                    @elseif($log->event === 'update') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                    @else bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400
                                    @endif">
                                    {{ $log->event === 'create' ? 'إضافة' : ($log->event === 'update' ? 'تعديل' : 'حذف') }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-bold dark:text-gray-300">{{ class_basename($log->auditable_type) }}</div>
                                <div class="text-[10px] font-mono text-gray-400 italic">ID: {{ $log->auditable_id }}</div>
                            </td>
                            <td class="px-6 py-5">
                                @if($log->event === 'update')
                                    <div class="space-y-1.5 max-w-sm">
                                        @foreach($log->new_values as $key => $value)
                                            @if($key !== 'updated_at' && $key !== 'created_at')
                                                <div class="text-xs p-1.5 rounded bg-gray-50 dark:bg-dark-bg/50 border border-gray-100 dark:border-dark-border overflow-hidden text-ellipsis whitespace-nowrap">
                                                    <span class="font-black text-gray-500 dark:text-gray-400">{{ $key }}:</span> 
                                                    <span class="text-rose-500 dark:text-rose-400 line-through opacity-70">{{ is_array($log->old_values[$key]) ? json_encode($log->old_values[$key]) : $log->old_values[$key] }}</span>
                                                    <span class="mx-1 text-gray-400">←</span>
                                                    <span class="text-blue-600 dark:text-blue-400 font-bold">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @elseif($log->event === 'create')
                                    <div class="text-xs text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                        تمت إضافة بيانات جديدة للنظام
                                    </div>
                                @else
                                    <div class="text-xs text-rose-600 dark:text-rose-400 font-medium flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        تم حذف السجل بالكامل من النظام
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                <div class="font-bold">{{ $log->created_at->format('Y-m-d') }}</div>
                                <div class="text-[10px] opacity-70">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-[10px] text-gray-400 space-y-1">
                                    <div class="bg-gray-100 dark:bg-dark-bg py-0.5 px-2 rounded-full inline-block">IP: {{ $log->ip_address }}</div>
                                    <div class="truncate max-w-[120px] opacity-60" title="{{ $log->user_agent }}">{{ $log->user_agent }}</div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center text-gray-400 font-medium italic">لا توجد سجلات مراقبة حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 dark:bg-dark-tableheader border-t border-gray-100 dark:border-dark-border">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
