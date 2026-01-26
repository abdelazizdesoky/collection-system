@extends('layouts.app')

@section('title', 'تعديل بيانات القسط')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <div class="max-w-xl mx-auto">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
            <div class="bg-indigo-600 p-6 text-white text-right">
                <h1 class="text-xl font-black">تعديل قسط مستحق</h1>
                <p class="opacity-90 mt-1">تاريخ الاستحقاق الحالي: {{ $installment->due_date->format('Y-m-d') }}</p>
            </div>
            
            <form action="{{ route('installments.item.update', $installment) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">قيمة القسط</label>
                        <div class="relative">
                            <input type="number" name="amount" step="0.01" value="{{ old('amount', $installment->amount) }}" class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-indigo-500 focus:border-indigo-500 text-left" dir="ltr">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">ج.م</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">تاريخ الاستحقاق</label>
                        <input type="date" name="due_date" value="{{ old('due_date', $installment->due_date->format('Y-m-d')) }}" class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">حالة السداد</label>
                        <select name="status" class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="pending" {{ $installment->status == 'pending' ? 'selected' : '' }}>معلق (Pending)</option>
                            <option value="paid" {{ $installment->status == 'paid' ? 'selected' : '' }}>مدفوع (Paid)</option>
                            <option value="partial" {{ $installment->status == 'partial' ? 'selected' : '' }}>مدفوع جزئياً (Partial)</option>
                            <option value="overdue" {{ $installment->status == 'overdue' ? 'selected' : '' }}>متأخر (Overdue)</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg">تحديث القسط</button>
                    <a href="{{ route('installments.show', $installment->installmentPlan) }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
