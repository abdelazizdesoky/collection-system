@extends('layouts.app')

@section('title', 'تعديل خطة التقسيط')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
            <div class="bg-amber-600 p-6 text-white text-right">
                <h1 class="text-2xl font-black">تعديل بيانات الخطة</h1>
                <p class="opacity-90 mt-1">{{ $plan->customer->name ?? 'عميل محذوف' }}</p>
            </div>
            
            <form action="{{ route('installments.update', $plan) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">رقم الفاتورة</label>
                        <input type="text" name="invoice_no" value="{{ old('invoice_no', $plan->invoice_no) }}" class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">حالة الخطة</label>
                        <select name="status" class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-amber-500 focus:border-amber-500">
                            <option value="pending" {{ $plan->status == 'pending' ? 'selected' : '' }}>جارية (Pending)</option>
                            <option value="paid" {{ $plan->status == 'paid' ? 'selected' : '' }}>مكتملة (Paid)</option>
                            <option value="overdue" {{ $plan->status == 'overdue' ? 'selected' : '' }}>متأخرة (Overdue)</option>
                            <option value="partial" {{ $plan->status == 'partial' ? 'selected' : '' }}>متعثرة جزئياً (Partial)</option>
                        </select>


                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ملاحظات</label>
                        <textarea name="notes" rows="4" class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-amber-500 focus:border-amber-500">{{ old('notes', $plan->notes) }}</textarea>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg">حفظ التغييرات</button>
                    <a href="{{ route('installments.show', $plan) }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
