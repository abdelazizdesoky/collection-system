@extends('layouts.app')

@section('title', 'إدارة المناطق')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4 text-right">
                <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-500/30 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black dark:text-white tracking-tight">إدارة المناطق</h1>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">تنظيم وتوزيع العملاء والمحصلين حسب النطاق الجغرافي</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                <button onclick="document.getElementById('createModal').classList.remove('hidden')" 
                        class="flex-grow lg:flex-none flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-indigo-500/20 transition-all text-sm whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    إضافة منطقة جديدة
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader text-gray-600 dark:text-gray-300">
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">المنطقة</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">إجمالي العملاء</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @forelse($areas as $area)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-colors group">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    </div>
                                    <span class="font-bold dark:text-white text-lg group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $area->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-4 py-2 rounded-full bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 font-bold text-sm">
                                    {{ $area->customers_count ?? $area->customers()->count() }} عميل
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-3">
                                    <button onclick="editArea({{ $area->id }}, '{{ $area->name }}')" 
                                            class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white transition-all shadow-sm" title="تعديل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('areas.destroy', $area) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المنطقة؟ سيتم إلغاء ربط العملاء التابعين لها.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="حذف">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-20 text-center text-gray-400 font-medium italic">لم يتم إضافة أي مناطق جغرافية بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $areas->links() }}
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm shadow-2xl overflow-y-auto h-full w-full hidden z-50 transition-all">
    <div class="relative top-20 mx-auto p-8 border-none w-full max-w-md shadow-2xl rounded-2xl bg-white dark:bg-dark-card animate-slide-up">
        <div class="text-right" dir="rtl">
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                إضافة منطقة جديدة
            </h3>
            <form action="{{ route('areas.store') }}" method="POST">
                @csrf
                <div class="mb-8">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">اسم المنطقة</label>
                    <input type="text" name="name" class="w-full px-4 py-4 rounded-xl border-gray-200 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none text-lg font-bold" required placeholder="مثلاً: القاهرة الجديدة، وسط البلد">
                </div>
                <div class="flex flex-row-reverse gap-3">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-indigo-500/20 transition-all">حفظ المنطقة</button>
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold py-3 px-6 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm shadow-2xl overflow-y-auto h-full w-full hidden z-50 transition-all">
    <div class="relative top-20 mx-auto p-8 border-none w-full max-w-md shadow-2xl rounded-2xl bg-white dark:bg-dark-card animate-slide-up">
        <div class="text-right" dir="rtl">
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                تعديل المنطقة
            </h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-8">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">اسم المنطقة</label>
                    <input type="text" id="editName" name="name" class="w-full px-4 py-4 rounded-xl border-gray-200 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none text-lg font-bold" required>
                </div>
                <div class="flex flex-row-reverse gap-3">
                    <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-amber-500/20 transition-all">تحديث البيانات</button>
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold py-3 px-6 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editArea(id, name) {
    document.getElementById('editForm').action = `/areas/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editModal').classList.remove('hidden');
}

// Close modals on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.getElementById('createModal').classList.add('hidden');
        document.getElementById('editModal').classList.add('hidden');
    }
});
</script>

<style>
@keyframes slide-up {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-slide-up {
    animation: slide-up 0.3s ease-out;
}
</style>
@endsection
