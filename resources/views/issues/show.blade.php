@extends('layouts.app')

@section('title', 'تفاصيل المشكلة - ' . $issue->customer->name)

@section('content')
<div class="container mx-auto py-8 px-4 text-right" dir="rtl">
    <!-- Header -->
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center gap-5">
            <div class="bg-rose-600 p-4 rounded-3xl shadow-2xl shadow-rose-500/30 text-white">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-4xl font-black dark:text-white tracking-tighter">{{ $issue->customer->name }}</h1>
                <p class="text-gray-500 dark:text-gray-400 font-bold mt-1 uppercase tracking-widest text-xs">رقم المشكلة: #{{ $issue->id }} • {{ $issue->status_label }}</p>
            </div>
        </div>
        <a href="{{ route('issues.index') }}" class="flex items-center gap-2 text-gray-500 font-bold hover:underline">
            <span>العودة للقائمة</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-2xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Info & Status Update -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Customer Card -->
            <div class="bg-white dark:bg-dark-card rounded-[2rem] shadow-2xl p-8 border border-gray-50 dark:border-dark-border">
                <h3 class="text-lg font-black dark:text-white mb-6 border-b border-gray-50 dark:border-dark-border pb-4">معلومات العميل</h3>
                <div class="space-y-4">
                    <div>
                        <span class="text-gray-400 text-xs font-bold uppercase block mb-1">الاسم</span>
                        <span class="font-black dark:text-white">{{ $issue->customer->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs font-bold uppercase block mb-1">الهاتف</span>
                        <span class="font-bold dark:text-white" dir="ltr">{{ $issue->customer->phone }}</span>
                    </div>
                    @if($issue->customer->address)
                    <div>
                        <span class="text-gray-400 text-xs font-bold uppercase block mb-1">العنوان</span>
                        <span class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">{{ $issue->customer->address }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Resolution Form -->
            <div class="bg-white dark:bg-dark-card rounded-[2rem] shadow-2xl p-8 border border-gray-50 dark:border-dark-border">
                <h3 class="text-lg font-black dark:text-white mb-6 border-b border-gray-50 dark:border-dark-border pb-4">تحديث حالة المشكلة</h3>
                <form action="{{ route('issues.update', $issue) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">الحالة الجديدة</label>
                        <select name="status" id="status_select" class="w-full rounded-xl border-gray-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-rose-500 transition-all">
                            <option value="pending" {{ $issue->status == 'pending' ? 'selected' : '' }}>معلق</option>
                            <option value="processing" {{ $issue->status == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="resolved" {{ $issue->status == 'resolved' ? 'selected' : '' }}>تم الحل</option>
                            <option value="escalated" {{ $issue->status == 'escalated' ? 'selected' : '' }}>تم التصعيد</option>
                            <option value="closed" {{ $issue->status == 'closed' ? 'selected' : '' }}>مغلقة</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">الأولوية</label>
                        <select name="priority" class="w-full rounded-xl border-gray-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-rose-500 transition-all">
                            <option value="low" {{ $issue->priority == 'low' ? 'selected' : '' }}>منخفضة</option>
                            <option value="normal" {{ $issue->priority == 'normal' ? 'selected' : '' }}>عادية</option>
                            <option value="high" {{ $issue->priority == 'high' ? 'selected' : '' }}>عالية</option>
                            <option value="urgent" {{ $issue->priority == 'urgent' ? 'selected' : '' }}>طارئة</option>
                        </select>
                    </div>

                    <div id="escalation_field" class="{{ $issue->status == 'escalated' ? '' : 'hidden' }}">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">سبب التصعيد</label>
                        <textarea name="escalation_reason" rows="3" class="w-full rounded-xl border-gray-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-rose-500 transition-all">{{ $issue->escalation_reason }}</textarea>
                    </div>

                    <div id="resolution_field" class="{{ $issue->status == 'resolved' ? '' : 'hidden' }}">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">ملاحظات الحل</label>
                        <textarea name="resolution_notes" rows="3" class="w-full rounded-xl border-gray-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-rose-500 transition-all">{{ $issue->resolution_notes }}</textarea>
                    </div>

                    <div id="closure_field" class="{{ $issue->status == 'closed' ? '' : 'hidden' }}">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">سبب الإغلاق</label>
                        <textarea name="closure_reason" rows="3" class="w-full rounded-xl border-gray-100 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-rose-500 transition-all">{{ $issue->closure_reason }}</textarea>
                    </div>

                <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-black py-4 rounded-xl transition-all shadow-lg shadow-rose-500/30">حفظ التغييرات</button>
                </form>
            </div>

            <!-- Delete Actions -->
            <div class="bg-white dark:bg-dark-card rounded-[2rem] shadow-2xl p-8 border border-gray-50 dark:border-dark-border space-y-4">
                <h3 class="text-lg font-black dark:text-white mb-4 border-b border-gray-50 dark:border-dark-border pb-4 italic text-rose-600">منطقة العمليات الحساسة</h3>
                
                @if($issue->trashed())
                    @if(auth()->user()->hasAnyRole(['admin', 'supervisor', 'plan_supervisor']))
                        <form action="{{ route('issues.restore', $issue->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-xl transition-all shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l5 5m-5-5l5-5"/></svg>
                                استعادة المشكلة
                            </button>
                        </form>
                    @endif

                    @if(auth()->user()->hasRole('admin'))
                        <form action="{{ route('issues.force-delete', $issue->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف النهائي؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-black py-4 rounded-xl transition-all shadow-lg shadow-red-500/30 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                حذف نهائي للأبد
                            </button>
                        </form>
                    @endif
                @else
                    @if(auth()->user()->hasAnyRole(['admin', 'supervisor', 'plan_supervisor']))
                        <form action="{{ route('issues.destroy', $issue) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-black py-4 rounded-xl transition-all shadow-lg shadow-orange-500/30 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                حذف (نقل للمحذوفات)
                            </button>
                        </form>
                    @endif

                    @if(auth()->user()->hasRole('admin'))
                        <form action="{{ route('issues.force-delete', $issue->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف النهائي والمباشر؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full border-2 border-red-600 text-red-600 hover:bg-red-600 hover:text-white font-black py-4 rounded-xl transition-all flex items-center justify-center gap-2 mt-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                حذف نهائي مباشر
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <!-- Issue Details Content -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-dark-card rounded-[2rem] shadow-2xl p-8 border border-gray-50 dark:border-dark-border">
                <div class="flex justify-between items-start mb-8">
                    <h3 class="text-xl font-black dark:text-white">تفاصيل المشكلة والزيارة</h3>
                    <span class="px-4 py-1 bg-gray-50 dark:bg-dark-bg text-gray-400 rounded-full text-[10px] font-black uppercase tracking-widest border border-gray-100 dark:border-dark-border">
                        سجلت بتاريخ: {{ $issue->created_at->format('Y-m-d H:i') }}
                    </span>
                </div>

                <div class="prose prose-sm dark:prose-invert max-w-none mb-10">
                    <h4 class="text-xs font-black text-rose-600 uppercase mb-2">وصف المشكلة</h4>
                    <p class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed bg-gray-50 dark:bg-dark-bg/50 p-6 rounded-2xl italic">
                        {{ $issue->description }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-50 dark:border-dark-border">
                    <div class="bg-indigo-50/50 dark:bg-indigo-900/10 p-6 rounded-2xl">
                        <span class="text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase block mb-2">جهة التسجيل (المندوب)</span>
                        <div class="flex items-center gap-3">
                            <div class="bg-indigo-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-black">
                                {{ mb_substr($issue->collector->name ?? 'N', 0, 1) }}
                            </div>
                            <span class="font-bold dark:text-white">{{ $issue->collector->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                    @if($issue->visit)
                    <div class="bg-amber-50/50 dark:bg-amber-900/10 p-6 rounded-2xl">
                        <span class="text-amber-600 dark:text-amber-400 text-xs font-black uppercase block mb-2">مرتبطة بزيارة العميل</span>
                        <div class="flex flex-col">
                            <span class="font-bold dark:text-white">خطة زيارة: {{ $issue->visit->visitPlanItem->visitPlan->title ?? 'زيارة مفردة' }}</span>
                            <span class="text-xs text-gray-500 mt-1">وقت الزيارة: {{ $issue->visit->visit_time->format('H:i') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($issue->history->count() > 0 || $issue->escalation_reason || $issue->resolution_notes)
            <div class="bg-white dark:bg-dark-card rounded-[2rem] shadow-2xl p-8 border border-gray-50 dark:border-dark-border">
                <h3 class="text-lg font-black dark:text-white mb-8 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    سجل المتابعة والتحديثات
                </h3>

                <div class="space-y-8 relative before:absolute before:inset-y-0 before:right-7 before:w-0.5 before:bg-gray-100 dark:before:bg-dark-border">
                    @foreach($issue->history()->latest()->get() as $log)
                    <div class="relative pr-16">
                        <div class="absolute right-5 top-0 w-4 h-4 rounded-full bg-indigo-500 border-4 border-white dark:border-dark-card z-10 shadow-sm"></div>
                        <div class="bg-gray-50 dark:bg-dark-bg/30 p-5 rounded-2xl border border-gray-100 dark:border-dark-border">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xs font-black dark:text-white">{{ $log->user->name }}</span>
                                <span class="text-[10px] text-gray-400 font-bold">{{ $log->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-3">{{ $log->comment }}</p>
                            @if($log->old_status != $log->new_status)
                            <div class="flex items-center gap-2 text-[9px] font-black uppercase">
                                <span class="text-gray-400 line-through">{{ $log->old_status }}</span>
                                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                <span class="text-indigo-500">{{ $log->new_status }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    @if($issue->escalation_reason)
                    <div class="relative pr-16">
                        <div class="absolute right-5 top-0 w-4 h-4 rounded-full bg-rose-500 border-4 border-white dark:border-dark-card z-10 shadow-sm"></div>
                        <div class="bg-rose-50/50 dark:bg-rose-900/10 p-5 rounded-2xl border border-rose-100 dark:border-rose-800/30">
                            <span class="text-rose-600 text-[10px] font-black uppercase block mb-2">تحديث إداري: تصعيد المشكلة</span>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $issue->escalation_reason }}</p>
                        </div>
                    </div>
                    @endif

                    @if($issue->resolution_notes)
                    <div class="relative pr-16">
                        <div class="absolute right-5 top-0 w-4 h-4 rounded-full bg-emerald-500 border-4 border-white dark:border-dark-card z-10 shadow-sm"></div>
                        <div class="bg-emerald-50/50 dark:bg-emerald-900/10 p-5 rounded-2xl border border-emerald-100 dark:border-emerald-800/30">
                            <span class="text-emerald-600 text-[10px] font-black uppercase block mb-2">تحديث إداري: ملاحظات الحل النهائي</span>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $issue->resolution_notes }}</p>
                        </div>
                    </div>
                    @endif

                    @if($issue->closure_reason)
                    <div class="relative pr-16">
                        <div class="absolute right-5 top-0 w-4 h-4 rounded-full bg-slate-500 border-4 border-white dark:border-dark-card z-10 shadow-sm"></div>
                        <div class="bg-slate-50/50 dark:bg-slate-900/10 p-5 rounded-2xl border border-slate-100 dark:border-slate-800/30">
                            <span class="text-slate-600 text-[10px] font-black uppercase block mb-2">تحديث إداري: سبب الإغلاق</span>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $issue->closure_reason }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.getElementById('status_select').addEventListener('change', function() {
        const val = this.value;
        const esc = document.getElementById('escalation_field');
        const res = document.getElementById('resolution_field');
        const cls = document.getElementById('closure_field');
        
        esc.classList.add('hidden');
        res.classList.add('hidden');
        cls.classList.add('hidden');
        
        if (val === 'escalated') esc.classList.remove('hidden');
        if (val === 'resolved') res.classList.remove('hidden');
        if (val === 'closed') cls.classList.remove('hidden');
    });
</script>
@endsection
