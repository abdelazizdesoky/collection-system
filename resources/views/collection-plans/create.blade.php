@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 text-right" dir="rtl">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">خطة تحصيل جديدة</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded text-right">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('collection-plans.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">اسم الخطة *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="اسم الخطة" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">المحصل *</label>
                <select name="collector_id" class="w-full select2-search" data-placeholder="اختر المندوب..." required>
                    <option value=""></option>
                    @foreach ($collectors as $collector)
                        <option value="{{ $collector->id }}" {{ old('collector_id') == $collector->id ? 'selected' : '' }}>{{ $collector->name }}</option>
                    @endforeach
                </select>
                @error('collector_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">تاريخ الخطة *</label>
                <input type="date" name="plan_date" value="{{ old('plan_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
                @error('plan_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">النوع *</label>
                <select name="collection_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" dir="rtl" required>
                    <option value="">اختر النوع</option>
                    <option value="regular" {{ old('collection_type') == 'regular' ? 'selected' : '' }}>عادي</option>
                    <option value="special" {{ old('collection_type') == 'special' ? 'selected' : '' }}>خاص</option>
                    <option value="bank" {{ old('collection_type') == 'bank' ? 'selected' : '' }}>بنك</option>
                    <option value="cash" {{ old('collection_type') == 'cash' ? 'selected' : '' }}>نقدي</option>
                    <option value="cheque" {{ old('collection_type') == 'cheque' ? 'selected' : '' }}>شيك</option>
                </select>
                @error('collection_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">ملاحظات إضافية (اختياري)</label>
                <input type="text" name="type" value="{{ old('type') }}" placeholder="ملاحظات إضافية" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right">
                @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Customers Selection -->
            <div class="mb-6 border rounded-lg p-4 bg-gray-50">
                <h3 class="font-bold text-lg mb-3">العملاء (يتم التحديث بناءً على المحصل)</h3>
                
                <div class="max-h-60 overflow-y-auto border bg-white rounded">
                    <table class="w-full text-right" dir="rtl">
                        <thead class="bg-gray-100 sticky top-0">
                            <tr>
                                <th class="p-2 border-b"><input type="checkbox" id="select-all"></th>
                                <th class="p-2 border-b">الاسم</th>
                                <th class="p-2 border-b">العنوان</th>
                            </tr>
                        </thead>
                        <tbody id="customers-list">
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-500">الرجاء اختيار محصل أولاً</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const collectorSelect = document.querySelector('select[name="collector_id"]');
                    const customersList = document.getElementById('customers-list');
                    const selectAll = document.getElementById('select-all');

                    selectAll.addEventListener('change', function() {
                        document.querySelectorAll('.customer-check').forEach(cb => cb.checked = this.checked);
                    });

                    // Reuse the route from visit plans or create a new one. 
                    // Using the existing visit plan route for convenience if it returns json customers
                    const fetchUrl = '/visit-plans/collectors'; // We need to verify this route pattern

                    collectorSelect.addEventListener('change', function() {
                        const id = this.value;
                        if(!id) {
                            customersList.innerHTML = '<tr><td colspan="3" class="p-4 text-center text-gray-500">الرجاء اختيار محصل أولاً</td></tr>';
                            return;
                        }

                        customersList.innerHTML = '<tr><td colspan="3" class="p-4 text-center">جاري التحميل...</td></tr>';

                        // Utilizing the existing route: Route::get('/collectors/{collector}/customers', [VisitPlanController::class, 'getCustomers'])
                        fetch(`/collectors/${id}/customers`)
                            .then(res => res.json())
                            .then(data => {
                                if(data.length === 0) {
                                    customersList.innerHTML = '<tr><td colspan="3" class="p-4 text-center text-gray-500">لا يوجد عملاء لهذا المحصل</td></tr>';
                                    return;
                                }
                                
                                customersList.innerHTML = '';
                                data.forEach(c => {
                                    customersList.innerHTML += `
                                        <tr class="hover:bg-gray-50 border-b">
                                            <td class="p-2">
                                                <input type="checkbox" name="customers[]" value="${c.id}" class="customer-check">
                                            </td>
                                            <td class="p-2 font-bold">${c.name}</td>
                                            <td class="p-2 text-sm text-gray-600">${c.address || '-'}</td>
                                        </tr>
                                    `;
                                });
                            })
                            .catch(err => {
                                console.error(err);
                                customersList.innerHTML = '<tr><td colspan="3" class="p-4 text-center text-red-500">حدث خطأ</td></tr>';
                            });
                    });
                });
            </script>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">إنشاء</button>
                <a href="{{ route('collection-plans.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-center">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
