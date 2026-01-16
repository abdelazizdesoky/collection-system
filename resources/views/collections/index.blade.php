@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 text-right" dir="rtl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">التحصيلات</h1>
        <div class="flex gap-2">
            <a href="{{ route('collections.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                تصدير إكسيل
            </a>
            <a href="{{ route('collections.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + تسجيل تحصيل
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ $message }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 text-right">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الإيصال</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العملاء</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المحصل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المرفق</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($collections as $collection)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collection->receipt_no }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collection->customer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collection->collector->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ number_format($collection->amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ($collection->payment_type ?? 'cash') === 'cash' ? 'نقدي' : 'شيك' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collection->collection_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($collection->attachment)
                                <a href="{{ asset('storage/' . $collection->attachment) }}" target="_blank" class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </a>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('collections.show', $collection) }}" class="text-blue-600 hover:text-blue-900 ml-3">عرض</a>
                            <a href="{{ route('collections.edit', $collection) }}" class="text-yellow-600 hover:text-yellow-900 ml-3">تعديل</a>
                            <form action="{{ route('collections.destroy', $collection) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من حذف هذا العنصر؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $collections->links() }}
    </div>
</div>
@endsection
