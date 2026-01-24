@extends('layouts.app')

@section('title', 'إنشاء خطة زيارات جديدة')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('visit-plans.index') }}" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-black dark:text-white">إنشاء خطة زيارات جديدة</h1>
                <p class="text-gray-500 dark:text-gray-400">تحديد جدول زيارات للمحصل</p>
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

    <form action="{{ route('visit-plans.store') }}" method="POST" class="space-y-6">
        @csrf

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
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-purple-500 focus:border-purple-500"
                        placeholder="مثال: زيارات منطقة المعادي">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">المحصل <span class="text-red-500">*</span></label>
                    <select name="collector_id" required class="w-full select2-search" data-placeholder="اختر المحصل بالاسم أو الكود...">
                        <option value=""></option>
                        @foreach($collectors as $collector)
                            <option value="{{ $collector->id }}" {{ old('collector_id') == $collector->id ? 'selected' : '' }}>
                                {{ $collector->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">نوع الخطة <span class="text-red-500">*</span></label>
                    <select name="frequency" required id="frequency-select"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-purple-500 focus:border-purple-500">
                        <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>يومي</option>
                        <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                        <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>شهري</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">تاريخ البداية <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date', today()->format('Y-m-d')) }}" required
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div id="end-date-container" style="display: none;">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">تاريخ النهاية</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ملاحظات</label>
                    <textarea name="notes" rows="3"
                        class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-purple-500 focus:border-purple-500"
                        placeholder="أي ملاحظات إضافية للخطة...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Customers Selection Card -->
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-dark-border">
            <h2 class="text-xl font-bold dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                العملاء المستهدفون
                <span class="text-sm font-normal text-gray-500">(اختياري - يمكن إضافتهم لاحقاً)</span>
            </h2>

            <div class="mb-4">
                <input type="text" id="customer-search" placeholder="ابحث عن عميل..."
                    class="w-full rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div class="max-h-96 overflow-y-auto border border-gray-200 dark:border-dark-border rounded-xl">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-dark-tableheader sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-right text-sm font-bold text-gray-700 dark:text-gray-300">
                                <input type="checkbox" id="select-all" class="rounded text-purple-500 focus:ring-purple-500">
                            </th>
                            <th class="px-4 py-3 text-right text-sm font-bold text-gray-700 dark:text-gray-300">العميل</th>
                            <th class="px-4 py-3 text-right text-sm font-bold text-gray-700 dark:text-gray-300">الهاتف</th>
                            <th class="px-4 py-3 text-right text-sm font-bold text-gray-700 dark:text-gray-300">العنوان</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-700 dark:text-gray-300">الأولوية</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-dark-border" id="customers-list">
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                الرجاء اختيار محصل لعرض العملاء المرتبطين به
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                حدد العملاء المراد زيارتهم وترتيب أولوية الزيارة لكل عميل
            </p>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('visit-plans.index') }}" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                إلغاء
            </a>
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-purple-500/20 transition-all">
                <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                إنشاء الخطة
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Frequency change handler
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

    // Select all checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    
    // Dynamic event listener for customer checkboxes since they might be replaced
    document.getElementById('customers-list').addEventListener('change', function(e) {
        if (e.target.classList.contains('customer-checkbox')) {
            // Update select all state if needed
            const all = document.querySelectorAll('.customer-checkbox');
            const checked = document.querySelectorAll('.customer-checkbox:checked');
            selectAllCheckbox.checked = all.length > 0 && all.length === checked.length;
            selectAllCheckbox.indeterminate = checked.length > 0 && checked.length < all.length;
        }
    });
    
    selectAllCheckbox.addEventListener('change', function() {
        const customerCheckboxes = document.querySelectorAll('.customer-checkbox');
        customerCheckboxes.forEach(cb => cb.checked = this.checked);
    });

    // Customer search
    const searchInput = document.getElementById('customer-search');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const customerRows = document.querySelectorAll('.customer-row');
        customerRows.forEach(row => {
            const name = row.querySelector('.customer-name').textContent.toLowerCase();
            row.style.display = name.includes(searchTerm) ? '' : 'none';
        });
    });

    // Collector change handler (AJAX) - Updated for Select2 compatibility
    const customersListBody = document.getElementById('customers-list');
    
    $('select[name="collector_id"]').on('change', function() {
        const collectorId = $(this).val();
        if (!collectorId) {
            customersListBody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">الرجاء اختيار محصل أولاً</td></tr>';
            return;
        }

        // Show loading
        customersListBody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">جاري تحميل العملاء...</td></tr>';

        fetch(`/collectors/${collectorId}/customers`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    customersListBody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">لا يوجد عملاء مرتبطين بهذا المحصل</td></tr>';
                    return;
                }

                customersListBody.innerHTML = '';
                data.forEach((customer, index) => {
                    const row = `
                        <tr class="hover:bg-purple-50/50 dark:hover:bg-slate-700/30 customer-row">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="customers[]" value="${customer.id}" 
                                    class="customer-checkbox rounded text-purple-500 focus:ring-purple-500">
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-bold dark:text-white customer-name">${customer.name}</span>
                                ${customer.due_installments_count > 0 ? `
                                    <span class="mr-2 px-2 py-0.5 bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 rounded-lg text-[10px] font-black border border-amber-200 animate-pulse">قسط مستحق</span>
                                ` : ''}
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">${customer.phone || '-'}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 text-sm">${customer.address ? customer.address.substring(0, 30) : '-'}</td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" name="priority_${customer.id}" value="${index + 1}" min="1"
                                    class="w-16 text-center rounded-lg border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white text-sm">
                            </td>
                        </tr>
                    `;
                    customersListBody.innerHTML += row;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                customersListBody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-red-500">حدث خطأ أثناء تحميل العملاء</td></tr>';
            });
    });
});
</script>
@endsection
