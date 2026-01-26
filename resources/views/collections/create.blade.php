@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 text-right" dir="rtl">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">تسجيل تحصيل</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded text-right">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('collections.store') }}" method="POST">
            @csrf

            <div class="mb-4 text-right">
                <label class="block text-gray-700 text-sm font-bold mb-2">العملاء *</label>
                <select name="customer_id" class="w-full select2-search" data-placeholder="اختر العميل..." required>
                    <option value=""></option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4 text-right">
                <label class="block text-gray-700 text-sm font-bold mb-2">مندوب التحصيل *</label>
                <select name="collector_id" class="w-full select2-search" data-placeholder="اختر المندوب..." required>
                    <option value=""></option>
                    @foreach ($collectors as $collector)
                        <option value="{{ $collector->id }}" {{ old('collector_id') == $collector->id ? 'selected' : '' }}>{{ $collector->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">المبلغ *</label>
                <input type="number" name="amount" step="0.01" value="{{ old('amount') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">النوع *</label>
                <select name="payment_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" dir="rtl" required>
                    <option value="">اختر النوع</option>
                    <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>نقدي</option>
                    <option value="cheque" {{ old('payment_type') == 'cheque' ? 'selected' : '' }}>شيك</option>
                </select>
            </div>

            <div class="mb-4" id="bank-container" style="display: none;">
                <label class="block text-gray-700 text-sm font-bold mb-2">اسم البنك</label>
                <select name="bank_name" class="w-full select2-search" data-placeholder="اختر البنك...">
                    <option value=""></option>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->name }}" {{ old('bank_name') == $bank->name ? 'selected' : '' }}>{{ $bank->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">التاريخ *</label>
                <input type="date" name="collection_date" value="{{ old('collection_date', today()->toDateString()) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">رقم الإيصال *</label>
                <input type="text" name="receipt_no" value="{{ old('receipt_no') }}" placeholder="رقم الإيصال" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">ملاحظات</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تسجيل</button>
                <a href="{{ route('collections.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-center">إلغاء</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentType = document.querySelector('select[name="payment_type"]');
        const bankContainer = document.getElementById('bank-container');

        function toggleBank() {
            if (paymentType.value === 'cheque') {
                bankContainer.style.display = 'block';
            } else {
                bankContainer.style.display = 'none';
            }
        }

        // Use jQuery for Select2 change event if applicable, or native
        $('select[name="payment_type"]').on('change', toggleBank);
        toggleBank();
    });
</script>
@endsection
