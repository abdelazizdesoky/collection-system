@extends('layouts.app')
@section('title', 'تسوية حساب العميل: ' . $customer->name)
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('customers.show', $customer) }}" class="bg-gray-100 dark:bg-dark-card p-3 rounded-2xl hover:bg-gray-200 transition-colors"><svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg></a>
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">تسوية حساب عميل</h1>
            <p class="text-sm text-gray-500">{{ $customer->name }} ({{ $customer->code }})</p>
        </div>
    </div>

    <form action="{{ route('accounting.adjustments.customer.store', $customer) }}" method="POST">
        @csrf
        <div class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">التاريخ</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">نوع التسوية</label>
                    <select name="type" required class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:bg-dark-card dark:text-white">
                        <option value="debit">مدين (+) - زيادة مديونية العميل</option>
                        <option value="credit">دائن (-) - خفض مديونية العميل</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">المبلغ</label>
                <input type="number" step="0.01" name="amount" required min="0.01" class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white text-lg font-bold">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">البيان / الوصف</label>
                <textarea name="description" rows="3" required placeholder="مثال: تسوية رصيد سابق، خصم مسموح به..." class="w-full px-4 py-3 border border-gray-200 dark:border-dark-border rounded-2xl bg-transparent dark:text-white"></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-gradient-to-l from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-2xl font-bold shadow-lg transition-all hover:scale-105 active:scale-95">حفظ التسوية</button>
                <a href="{{ route('customers.show', $customer) }}" class="bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 px-8 py-4 rounded-2xl font-bold transition-all">إلغاء</a>
            </div>
        </div>
    </form>
</div>
@endsection
