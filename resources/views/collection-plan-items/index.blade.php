@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">خطة التحصيل</h1>
        <a href="{{ route('collection-plan-items.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + إضافة عنصر جديد
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ $message }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الخطة</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المندوب</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الأولوية</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ المتوقع</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($items as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->collectionPlan->date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->collectionPlan->collector->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->customer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->priority }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ number_format($item->expected_amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('collection-plan-items.show', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">عرض</a>
                            <a href="{{ route('collection-plan-items.edit', $item) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">تعديل</a>
                            <form action="{{ route('collection-plan-items.destroy', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل انت متاكد?')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
</div>
@endsection
