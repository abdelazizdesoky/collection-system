@extends('layouts.app')

@section('title', 'إدارة المشكلات والشكاوى')

@section('content')
<div class="container mx-auto py-8 px-4 text-right" dir="rtl">
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center gap-5">
            <div class="bg-rose-600 p-4 rounded-3xl shadow-2xl shadow-rose-500/30 text-white">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-4xl font-black dark:text-white tracking-tighter">إدارة المشكلات</h1>
                <p class="text-gray-500 dark:text-gray-400 font-bold mt-1 uppercase tracking-widest text-xs">متابعة شكاوى العملاء وحلها</p>
            </div>
        </div>
    </div>

    <!-- View Tabs (Active / Trashed) -->
    <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between border-b border-gray-100 dark:border-dark-border pb-6">
        <div class="flex flex-col md:flex-row gap-2">
            <a href="{{ route('issues.index') }}" 
               class="px-6 py-2 rounded-xl font-bold transition-all {{ !$showTrashed ? 'bg-rose-600 text-white shadow-lg shadow-rose-500/30' : 'bg-white dark:bg-dark-card text-gray-500 border border-gray-100 dark:border-dark-border' }}">
                النشطة ({{ $activeCount }})
            </a>
            <a href="{{ route('issues.index', ['trashed' => '1']) }}" 
               class="px-6 py-2 rounded-xl font-bold transition-all {{ $showTrashed ? 'bg-rose-600 text-white shadow-lg shadow-rose-500/30' : 'bg-white dark:bg-dark-card text-gray-500 border border-gray-100 dark:border-dark-border' }}">
                المحذوفات ({{ $trashedCount }})
            </a>
        </div>
    </div>

    <!-- Statistics Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white dark:bg-dark-card p-6 rounded-[2rem] shadow-xl border border-gray-100 dark:border-dark-border flex flex-col items-center justify-center group hover:bg-rose-600 transition-all duration-500">
            <span class="text-[10px] font-black uppercase text-gray-400 group-hover:text-rose-100 mb-1">الكل</span>
            <span class="text-3xl font-black dark:text-white group-hover:text-white">{{ $stats['total'] }}</span>
        </div>
        <div class="bg-white dark:bg-dark-card p-6 rounded-[2rem] shadow-xl border border-gray-100 dark:border-dark-border flex flex-col items-center justify-center group hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-500">
            <span class="text-[10px] font-black uppercase text-gray-400 group-hover:text-gray-500 mb-1">معلق</span>
            <span class="text-3xl font-black dark:text-white group-hover:text-gray-800 dark:group-hover:text-white">{{ $stats['pending'] }}</span>
        </div>
        <div class="bg-white dark:bg-dark-card p-6 rounded-[2rem] shadow-xl border border-gray-100 dark:border-dark-border flex flex-col items-center justify-center group hover:bg-blue-600 transition-all duration-500">
            <span class="text-[10px] font-black uppercase text-gray-400 group-hover:text-blue-100 mb-1">قيد المعالجة</span>
            <span class="text-3xl font-black dark:text-white group-hover:text-white">{{ $stats['processing'] }}</span>
        </div>
        <div class="bg-white dark:bg-dark-card p-6 rounded-[2rem] shadow-xl border border-gray-100 dark:border-dark-border flex flex-col items-center justify-center group hover:bg-emerald-600 transition-all duration-500">
            <span class="text-[10px] font-black uppercase text-gray-400 group-hover:text-emerald-100 mb-1">تم الحل</span>
            <span class="text-3xl font-black dark:text-white group-hover:text-white">{{ $stats['resolved'] }}</span>
        </div>
        <div class="bg-white dark:bg-dark-card p-6 rounded-[2rem] shadow-xl border border-gray-100 dark:border-dark-border flex flex-col items-center justify-center group hover:bg-rose-600 transition-all duration-500">
            <span class="text-[10px] font-black uppercase text-gray-400 group-hover:text-rose-100 mb-1">تم التصعيد</span>
            <span class="text-3xl font-black dark:text-white group-hover:text-white">{{ $stats['escalated'] }}</span>
        </div>
        <div class="bg-white dark:bg-dark-card p-6 rounded-[2rem] shadow-xl border border-gray-100 dark:border-dark-border flex flex-col items-center justify-center group hover:bg-slate-600 transition-all duration-500">
            <span class="text-[10px] font-black uppercase text-gray-400 group-hover:text-slate-100 mb-1">مغلقة</span>
            <span class="text-3xl font-black dark:text-white group-hover:text-white">{{ $stats['closed'] }}</span>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-dark-card rounded-[2rem] shadow-xl p-6 mb-8 border border-gray-50 dark:border-dark-border">
        <form action="{{ route('issues.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase mb-2 mr-2">الحالة</label>
                <select name="status" class="w-full rounded-xl border-gray-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-rose-500 transition-all">
                    <option value="">كل الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>تم الحل</option>
                    <option value="escalated" {{ request('status') == 'escalated' ? 'selected' : '' }}>تم التصعيد</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلقة</option>
                </select>
            </div>
            <div class="md:col-start-4 flex items-end">
                <button type="submit" class="w-full bg-gray-900 dark:bg-rose-600 text-white font-bold py-3 rounded-xl hover:bg-rose-600 transition-all shadow-lg">فلترة النتائج</button>
            </div>
        </form>
    </div>

    <!-- Issues Table -->
    <div class="bg-white dark:bg-dark-card rounded-[2rem] shadow-2xl overflow-hidden border border-gray-50 dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="text-gray-400 text-[10px] font-black uppercase tracking-widest bg-gray-50/50 dark:bg-dark-tableheader/50">
                        <th class="px-8 py-5 border-b border-gray-50 dark:border-dark-border">العميل</th>
                        <th class="px-8 py-5 border-b border-gray-50 dark:border-dark-border">مندوب الزيارة</th>
                        <th class="px-8 py-5 border-b border-gray-50 dark:border-dark-border">التاريخ</th>
                        <th class="px-8 py-5 border-b border-gray-50 dark:border-dark-border">وصف المشكلة</th>
                        <th class="px-8 py-5 border-b border-gray-50 dark:border-dark-border">الحالة</th>
                        <th class="px-8 py-5 border-b border-gray-50 dark:border-dark-border text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-dark-border">
                    @forelse($issues as $issue)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/20 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="font-black dark:text-white">{{ $issue->customer->name }}</div>
                                <div class="text-[10px] text-gray-400 font-bold mt-1 text-left" dir="ltr">{{ $issue->customer->phone }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-gray-600 dark:text-gray-300">{{ $issue->collector->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-bold dark:text-white text-sm">{{ $issue->created_at->format('Y-m-d') }}</div>
                                <div class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $issue->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-8 py-6 max-w-xs">
                                <div class="text-xs text-gray-600 dark:text-gray-400 truncate" title="{{ $issue->description }}">
                                    {{ Str::limit($issue->description, 50) }}
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border {{ $issue->status_color }}">
                                    {{ $issue->status_label }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center flex justify-center gap-2">
                                <a href="{{ route('issues.show', $issue) }}" class="inline-flex items-center gap-2 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white px-4 py-2 rounded-xl text-xs font-black transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    عرض
                                </a>

                                @if($issue->trashed())
                                    @if(auth()->user()->hasAnyRole(['admin', 'supervisor']))
                                        <form action="{{ route('issues.restore', $issue->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white px-4 py-2 rounded-xl text-xs font-black transition-all shadow-sm flex items-center gap-2" title="استعادة">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l5 5m-5-5l5-5"/></svg>
                                                استعادة
                                            </button>
                                        </form>
                                    @endif
                                    @if(auth()->user()->hasRole('admin'))
                                        <form action="{{ route('issues.force-delete', $issue->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف النهائي؟ لا يمكن التراجع!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white px-4 py-2 rounded-xl text-xs font-black transition-all shadow-sm flex items-center gap-2" title="حذف نهائي">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                حذف نهائي
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    @if(auth()->user()->hasAnyRole(['admin', 'supervisor']))
                                        <form action="{{ route('issues.destroy', $issue) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المشكلة؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 hover:bg-orange-600 hover:text-white px-4 py-2 rounded-xl text-xs font-black transition-all shadow-sm flex items-center gap-2" title="حذف">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                حذف
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-gray-400 font-bold">لا يوجد مشكلات مسجلة حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($issues->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 dark:border-dark-border">
                {{ $issues->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
