@extends('layouts.app')

@section('title', 'مراقبة العمليات - Audit Logs')

@section('content')
<div class="max-w-7xl mx-auto py-8 text-right" dir="rtl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">سجل مراقبة العمليات</h1>
        <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-bold">
            إجمالي التنبيهات: {{ $logs->total() }}
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs font-black uppercase tracking-wider">
                        <th class="px-6 py-4">المستخدم</th>
                        <th class="px-6 py-4">الحدث</th>
                        <th class="px-6 py-4">النموذج</th>
                        <th class="px-6 py-4">التغييرات</th>
                        <th class="px-6 py-4">التاريخ</th>
                        <th class="px-6 py-4">البيانات التقنية</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $log->user->name ?? 'نظام' }}</div>
                                <div class="text-xs text-gray-400">{{ $log->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    @if($log->event === 'create') bg-emerald-100 text-emerald-700
                                    @elseif($log->event === 'update') bg-blue-100 text-blue-700
                                    @else bg-rose-100 text-rose-700
                                    @endif">
                                    {{ $log->event === 'create' ? 'إضافة' : ($log->event === 'update' ? 'تعديل' : 'حذف') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-700">{{ class_basename($log->auditable_type) }}</div>
                                <div class="text-xs text-gray-400">ID: {{ $log->auditable_id }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if($log->event === 'update')
                                    <div class="space-y-1">
                                        @foreach($log->new_values as $key => $value)
                                            @if($key !== 'updated_at' && $key !== 'created_at')
                                                <div>
                                                    <span class="font-black text-gray-600">{{ $key }}:</span> 
                                                    <span class="text-rose-500 line-through">{{ is_array($log->old_values[$key]) ? json_encode($log->old_values[$key]) : $log->old_values[$key] }}</span>
                                                    <span class="text-blue-600"> ← {{ is_array($value) ? json_encode($value) : $value }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @elseif($log->event === 'create')
                                    <div class="text-gray-500">تمت إضافة بيانات جديدة</div>
                                @else
                                    <div class="text-rose-500">تم حذف السجل بالكامل</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-6 py-4 text-[10px] text-gray-400">
                                <div>IP: {{ $log->ip_address }}</div>
                                <div class="truncate max-w-[150px]" title="{{ $log->user_agent }}">{{ $log->user_agent }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">لا توجد سجلات مراقبة حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
