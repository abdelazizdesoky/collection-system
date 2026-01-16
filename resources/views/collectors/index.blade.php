@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 text-right" dir="rtl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">المحصلون</h1>
        <div class="flex gap-2">
            <a href="{{ route('collectors.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                تصدير إكسيل
            </a>
            <a href="{{ route('collectors.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + محصل جديد
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
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الهاتف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنطقة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($collectors as $collector)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collector->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collector->phone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collector->area }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('collectors.show', $collector) }}" class="text-blue-600 hover:text-blue-900 ml-3">عرض</a>
                            <a href="{{ route('collectors.edit', $collector) }}" class="text-yellow-600 hover:text-yellow-900 ml-3">تعديل</a>
                            <form action="{{ route('collectors.destroy', $collector) }}" method="POST" class="inline">
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
        {{ $collectors->links() }}
    </div>
</div>
@endsection
