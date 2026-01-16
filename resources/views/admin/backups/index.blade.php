@extends('layouts.app')

@section('title', 'قواعد البيانات - النسخ الاحتياطي')

@section('content')
<div class="max-w-7xl mx-auto py-8 text-right" dir="rtl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">إدارة النسخ الاحتياطي</h1>
        <form action="{{ route('admin.backups.create') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-xl flex items-center gap-2 shadow-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                إنشاء نسخة احتياطية جديدة
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-xs font-black uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-right">اسم الملف</th>
                        <th class="px-6 py-4 text-right">الحجم</th>
                        <th class="px-6 py-4 text-right">التاريخ</th>
                        <th class="px-6 py-4 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($backups as $backup)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $backup['name'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $backup['size'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $backup['date'] }}</td>
                        <td class="px-6 py-4 flex justify-center gap-3">
                            <a href="{{ route('admin.backups.download', $backup['name']) }}" class="text-emerald-600 hover:text-emerald-800 font-bold text-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                تحميل
                            </a>

                            <form action="{{ route('admin.backups.restore', $backup['name']) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من استعادة البيانات؟ سيؤدي ذلك إلى استبدال البيانات الحالية تماماً!');">
                                @csrf
                                <button type="submit" class="text-amber-600 hover:text-amber-800 font-bold text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    استرجاع
                                </button>
                            </form>

                            <form action="{{ route('admin.backups.destroy', $backup['name']) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الملف؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">لا توجد نسخ احتياطية حالياً</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
