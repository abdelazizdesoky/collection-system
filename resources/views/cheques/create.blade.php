@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 text-right" dir="rtl">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">شيك جديد</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded text-right">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cheques.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">العملاء *</label>
                <select name="customer_id" class="w-full select2-search" data-placeholder="اختر العميل..." required>
                    <option value=""></option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">رقم الشيك *</label>
                <input type="text" name="cheque_no" value="{{ old('cheque_no') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">اسم البنك *</label>
                <select name="bank_name" class="w-full select2-search" data-placeholder="اختر البنك..." required>
                    <option value=""></option>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->name }}" {{ old('bank_name') == $bank->name ? 'selected' : '' }}>{{ $bank->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">المبلغ *</label>
                <input type="number" name="amount" step="0.01" value="{{ old('amount') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">تاريخ الاستحقاق *</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">الحالة *</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" dir="rtl" required>
                    <option value="">اختر الحالة</option>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                    <option value="cleared" {{ old('status') == 'cleared' ? 'selected' : '' }}>تم التحصيل</option>
                    <option value="bounced" {{ old('status') == 'bounced' ? 'selected' : '' }}>مرفوض</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">إنشاء</button>
                <a href="{{ route('cheques.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-center">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
