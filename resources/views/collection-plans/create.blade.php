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
                <select name="collector_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" dir="rtl" required>
                    <option value="">اختر المحصل</option>
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

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">إنشاء</button>
                <a href="{{ route('collection-plans.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-center">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
