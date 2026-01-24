@extends('layouts.app')

@section('title', 'إدارة البنوك والمحافظ')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4 text-right">
                <div class="bg-blue-600 p-3 rounded-2xl shadow-lg shadow-blue-500/30 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black dark:text-white tracking-tight">البنوك والمحافظ</h1>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">إدارة الوجهات المالية للتحصيل (بنوك، محافظ إلكترونية)</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                <button onclick="document.getElementById('createModal').classList.remove('hidden')" 
                        class="flex-grow lg:flex-none flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-500/20 transition-all text-sm whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    إضافة جهة جديدة
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

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader text-gray-600 dark:text-gray-300">
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">اسم الجهة</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">النوع</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap text-center">تاريخ الإضافة</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @forelse($banks as $bank)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-colors group">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="font-bold dark:text-white text-lg group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $bank->name }}</span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-lg {{ $bank->type === 'wallet' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                    {{ $bank->type === 'wallet' ? 'محفظة إلكترونية' : 'بنك' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $bank->created_at->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-3">
                                    <button onclick="editBank({{ $bank->id }}, '{{ $bank->name }}', '{{ $bank->type }}')" 
                                            class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white transition-all shadow-sm" title="تعديل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('banks.destroy', $bank) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الجهة؟ قد يؤثر ذلك على سجلات التحصيل المرتبطة.')">
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
                            <td colspan="4" class="px-6 py-20 text-center text-gray-400 font-medium italic">لم يتم إضافة أي جهات بنكية بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $banks->links() }}
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm shadow-2xl overflow-y-auto h-full w-full hidden z-50 transition-all">
    <div class="relative top-20 mx-auto p-8 border-none w-full max-w-md shadow-2xl rounded-2xl bg-white dark:bg-dark-card animate-slide-up">
        <div class="text-right" dir="rtl">
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                جهة مالية جديدة
            </h3>
            <form action="{{ route('banks.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">اسم البنك أو المحفظة</label>
                    <input type="text" name="name" class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none" required placeholder="مثلاً: بنك مصر، فودافون كاش">
                </div>
                <div class="mb-8">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">النوع</label>
                    <select name="type" class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none appearance-none cursor-pointer">
                        <option value="bank">بنك (Bank)</option>
                        <option value="wallet">محفظة إلكترونية (Wallet)</option>
                    </select>
                </div>
                <div class="flex flex-row-reverse gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-500/20 transition-all">حفظ البيانات</button>
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
                تعديل البيانات
            </h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">اسم البنك أو المحفظة</label>
                    <input type="text" id="editName" name="name" class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none" required>
                </div>
                <div class="mb-8">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">النوع</label>
                    <select name="type" id="editType" class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none appearance-none cursor-pointer">
                        <option value="bank">بنك (Bank)</option>
                        <option value="wallet">محفظة إلكترونية (Wallet)</option>
                    </select>
                </div>
                <div class="flex flex-row-reverse gap-3">
                    <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-amber-500/20 transition-all">تحديث</button>
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold py-3 px-6 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editBank(id, name, type) {
    document.getElementById('editForm').action = `/banks/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editType').value = type;
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
