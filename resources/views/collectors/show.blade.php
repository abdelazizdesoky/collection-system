@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">{{ $collector->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('collectors.edit', $collector) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
            <a href="{{ route('collectors.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">رجوع</a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">معلومات المندوب</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">الاسم:</span>
                    <span>{{ $collector->name }}</span>
                </div>
                <div>
                    <span class="font-semibold">الهاتف:</span>
                    <span>{{ $collector->phone }}</span>
                </div>
                <div>
                    <span class="font-semibold">المنطقة:</span>
                    <span>{{ $collector->area }}</span>
                </div>
                <div>
                    <span class="font-semibold">حساب المستخدم:</span>
                    <span>{{ $collector->user?->name ?? 'غير معين' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">ملخص النشاط</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold">إجمالي التحصيلات:</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $collector->collections->count() }}</span>
                </div>
                <div>
                    <span class="font-semibold">إجمالي المبلغ المحصل:</span>
                    <span class="text-2xl font-bold text-green-600">{{ number_format($collector->collections->sum('amount'), 2) }}</span>
                </div>
                <div>
                    <span class="font-semibold">خطط التحصيل:</span>
                    <span>{{ $collector->collectionPlans->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-bold mb-4">أحدث التحصيلات</h2>
        @if ($collector->collections->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">رقم الإيصال</th>
                            <th class="px-4 py-2 text-left">العميل</th>
                            <th class="px-4 py-2 text-left">المبلغ</th>
                            <th class="px-4 py-2 text-left">النوع</th>
                            <th class="px-4 py-2 text-left">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collector->collections->take(5) as $collection)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $collection->receipt_no }}</td>
                                <td class="px-4 py-2">{{ $collection->customer->name }}</td>
                                <td class="px-4 py-2">{{ number_format($collection->amount, 2) }}</td>
                                <td class="px-4 py-2"><span class="px-2 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ ucfirst($collection->payment_type) }}</span></td>
                                <td class="px-4 py-2">{{ $collection->collection_date->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">لم يتم تسجيل أي تحصيلات حتى الآن.</p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">خطط التحصيل</h2>
        @if ($collector->collectionPlans->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">التاريخ</th>
                            <th class="px-4 py-2 text-left">النوع</th>
                            <th class="px-4 py-2 text-left">عدد العناصر</th>
                            <th class="px-4 py-2 text-left">المبلغ المتوقع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collector->collectionPlans->take(5) as $plan)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $plan->date->format('M d, Y') }}</td>
                                <td class="px-4 py-2">{{ $plan->type }}</td>
                                <td class="px-4 py-2">{{ $plan->items->count() }}</td>
                                <td class="px-4 py-2">{{ number_format($plan->getTotalExpectedAmount(), 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">لم يتم تعيين أي خطط تحصيل حتى الآن.</p>
        @endif
    </div>
</div>
@endsection
