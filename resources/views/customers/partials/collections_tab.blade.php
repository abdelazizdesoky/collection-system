<!-- Collections & Cheques Tab -->
<div class="space-y-8">
    <!-- Collections -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="p-6 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-emerald-50/30 dark:bg-emerald-900/10">
            <h2 class="text-lg font-black dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                سجل التحصيلات
            </h2>
            <div class="bg-emerald-600 text-white px-4 py-1.5 rounded-full text-xs font-black shadow-lg shadow-emerald-500/30">
                إجمالي: {{ number_format($customer->collections->sum('amount'), 2) }} ج.م
            </div>
        </div>
        @if ($customer->collections->count() > 0)
            <div class="overflow-x-auto text-right">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-dark-tableheader text-gray-500 text-xs font-black uppercase">
                        <tr>
                            <th class="px-6 py-4">رقم الإيصال</th>
                            <th class="px-6 py-4">المبلغ المحصل</th>
                            <th class="px-6 py-4">طريقة الدفع</th>
                            <th class="px-6 py-4">التاريخ</th>
                            <th class="px-6 py-4">المندوب</th>
                            <th class="px-6 py-4">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                        @foreach ($customer->collections->sortByDesc('collection_date') as $collection)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-all group">
                                <td class="px-6 py-4 font-bold dark:text-white">#{{ $collection->receipt_no }}</td>
                                <td class="px-6 py-4 font-black text-emerald-600 dark:text-emerald-400 text-lg">{{ number_format($collection->amount, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-[10px] font-black rounded-lg {{ $collection->payment_type === 'cash' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $collection->payment_type === 'cash' ? 'نقدي' : 'شيك' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $collection->collection_date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-xs font-bold dark:text-gray-300">{{ $collection->collector->name ?? 'مباشر' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('shared.receipt', $collection) }}" target="_blank" class="p-2 bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-300 rounded-xl hover:bg-violet-600 hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        </a>
                                        <a href="{{ route('collections.show', $collection) }}" class="p-2 bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-300 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center text-gray-400 font-bold italic">لا توجد تحصيلات مسجلة بعد.</div>
        @endif
    </div>

    <!-- Cheques -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="p-6 border-b border-gray-100 dark:border-dark-border flex justify-between items-center bg-amber-50/30 dark:bg-amber-900/10">
            <h2 class="text-lg font-black dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                محفظة الشيكات
            </h2>
            <div class="bg-amber-500 text-white px-4 py-1.5 rounded-full text-xs font-black">
                إجمالي: {{ number_format($customer->cheques->sum('amount'), 2) }} ج.م
            </div>
        </div>
        @if ($customer->cheques->count() > 0)
            <div class="overflow-x-auto text-right">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-dark-tableheader text-gray-500 text-xs font-black uppercase">
                        <tr>
                            <th class="px-6 py-4">رقم الشيك</th>
                            <th class="px-6 py-4">المبلغ</th>
                            <th class="px-6 py-4">تاريخ الاستحقاق</th>
                            <th class="px-6 py-4">البنك</th>
                            <th class="px-6 py-4">الحالة</th>
                            <th class="px-6 py-4">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                        @foreach ($customer->cheques->sortBy('due_date') as $cheque)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-all">
                                <td class="px-6 py-4 font-bold dark:text-white">#{{ $cheque->cheque_no }}</td>
                                <td class="px-6 py-4 font-black dark:text-white">{{ number_format($cheque->amount, 2) }}</td>
                                <td class="px-6 py-4 text-sm {{ $cheque->due_date < now() && $cheque->status == 'pending' ? 'text-red-600 font-black' : 'text-gray-500' }}">
                                    {{ $cheque->due_date->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 font-bold text-xs dark:text-gray-300">{{ $cheque->bank_name }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-[10px] font-black rounded-full shadow-sm
                                        {{ $cheque->status == 'pending' ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : 
                                           ($cheque->status == 'cleared' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-red-100 text-red-700 border border-red-200') }}">
                                        {{ $cheque->status == 'pending' ? 'معلق / للتحصيل' : ($cheque->status == 'cleared' ? 'تم التحصيل' : 'مرفوض / مرتجع') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('cheques.show', $cheque) }}" class="p-2 bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-300 rounded-xl hover:bg-amber-500 hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center text-gray-400 font-bold italic">لا توجد شيكات في المحفظة.</div>
        @endif
    </div>
</div>
