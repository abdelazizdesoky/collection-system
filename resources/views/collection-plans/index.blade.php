@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 text-right" dir="rtl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">خطط التحصيل</h1>
        <div class="flex gap-2">
            <a href="{{ route('collection-plans.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                تصدير إكسيل
            </a>
            <a href="{{ route('collection-plans.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + خطة جديدة
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
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الخطة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم المحصل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العناصر</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ المتوقع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($plans as $plan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $plan->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $plan->date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $plan->collector->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $plan->collection_type === 'regular' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ ($plan->collection_type ?? 'regular') === 'regular' ? 'عادي' : 'خاص' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $plan->items->count() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">جنيه مصري {{ number_format($plan->getTotalExpectedAmount(), 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('collection-plans.show', $plan) }}" class="text-blue-600 hover:text-blue-900 ml-3">عرض</a>
                            <a href="{{ route('collection-plans.edit', $plan) }}" class="text-yellow-600 hover:text-yellow-900 ml-3">تعديل</a>
                            <form action="{{ route('collection-plans.destroy', $plan) }}" method="POST" class="inline">
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
        {{ $plans->links() }}
    </div>
</div>
@endsection
