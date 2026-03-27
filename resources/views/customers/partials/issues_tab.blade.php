<!-- Issues Tab -->
<div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
    <div class="p-6 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-rose-50/10 dark:bg-rose-900/10">
        <h2 class="text-lg font-black dark:text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            سجل المشكلات والشكاوى
        </h2>
        <span class="px-3 py-1 bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 text-xs font-black rounded-full border border-rose-200">
            إجمالي: {{ $customer->issues->count() }}
        </span>
    </div>
    @if ($customer->issues->count() > 0)
        <div class="overflow-x-auto text-right">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-dark-tableheader text-gray-500 text-xs font-black uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">المشكلة / الوصف</th>
                        <th class="px-6 py-4">بواسطة (المندوب)</th>
                        <th class="px-6 py-4">الحالة الراهنة</th>
                        <th class="px-6 py-4">تاريخ التسجيل</th>
                        <th class="px-6 py-4 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @foreach ($customer->issues->sortByDesc('created_at') as $issue)
                        <tr class="hover:bg-rose-50/10 dark:hover:bg-rose-900/5 transition-all">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold dark:text-gray-200 line-clamp-2">{{ $issue->description }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">
                                {{ $issue->collector->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tighter border-2 {{ $issue->status_color }} rounded-lg">
                                    {{ $issue->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-[11px] font-bold">
                                {{ $issue->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('issues.show', $issue) }}" class="p-2 bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-300 rounded-xl hover:bg-rose-600 hover:text-white transition-all inline-block">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-16 text-center text-gray-400 flex flex-col items-center gap-4">
            <svg class="w-16 h-16 opacity-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="font-bold italic">سجل المشكلات نظيف تماماً لهذا العميل.</p>
        </div>
    @endif
</div>
