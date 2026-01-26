@extends('layouts.app')

@section('title', 'تعديل خطة الزيارات')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('visit-plans.show', $visitPlan) }}" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-black dark:text-white">تعديل خطة الزيارات</h1>
                <p class="text-gray-500 dark:text-gray-400">{{ $visitPlan->name }}</p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-xl">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('visit-plans.update', $visitPlan) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Info Card -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-dark-border">
            <h2 class="text-xl font-bold dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                معلومات الخطة
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">اسم الخطة <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $visitPlan->name) }}" required
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">المندوب <span class="text-red-500">*</span></label>
                    <select name="collector_id" required class="w-full select2-search" data-placeholder="اختر المندوب...">
                        @foreach($collectors as $collector)
                            <option value="{{ $collector->id }}" {{ old('collector_id', $visitPlan->collector_id) == $collector->id ? 'selected' : '' }}>
                                {{ $collector->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">نوع الخطة <span class="text-red-500">*</span></label>
                    <select name="frequency" required id="frequency-select"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-purple-500 focus:border-purple-500">
                        <option value="daily" {{ old('frequency', $visitPlan->frequency) == 'daily' ? 'selected' : '' }}>يومي</option>
                        <option value="weekly" {{ old('frequency', $visitPlan->frequency) == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                        <option value="monthly" {{ old('frequency', $visitPlan->frequency) == 'monthly' ? 'selected' : '' }}>شهري</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">تاريخ البداية <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date', $visitPlan->start_date->format('Y-m-d')) }}" required
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div id="end-date-container">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">تاريخ النهاية</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $visitPlan->end_date?->format('Y-m-d')) }}"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">الحالة <span class="text-red-500">*</span></label>
                    <select name="status" required
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-purple-500 focus:border-purple-500">
                        <option value="open" {{ old('status', $visitPlan->status) == 'open' ? 'selected' : '' }}>مفتوحة</option>
                        <option value="in_progress" {{ old('status', $visitPlan->status) == 'in_progress' ? 'selected' : '' }}>جارية</option>
                        <option value="closed" {{ old('status', $visitPlan->status) == 'closed' ? 'selected' : '' }}>مغلقة</option>
                    </select>
                </div>

                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ملاحظات</label>
                    <textarea name="notes" rows="3"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-purple-500 focus:border-purple-500"
                        placeholder="أي ملاحظات إضافية للخطة...">{{ old('notes', $visitPlan->notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('visit-plans.show', $visitPlan) }}" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                إلغاء
            </a>
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-purple-500/20 transition-all">
                <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                حفظ التغييرات
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const frequencySelect = document.getElementById('frequency-select');
    const endDateContainer = document.getElementById('end-date-container');
    
    function toggleEndDate() {
        if (frequencySelect.value === 'daily') {
            endDateContainer.style.display = 'none';
        } else {
            endDateContainer.style.display = 'block';
        }
    }
    
    frequencySelect.addEventListener('change', toggleEndDate);
    toggleEndDate();
});
</script>
@endsection
