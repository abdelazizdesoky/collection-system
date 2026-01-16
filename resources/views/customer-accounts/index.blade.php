@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 text-right" dir="rtl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">كشف الحساب</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 text-right">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العملاء</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مدين</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">دائن</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرصيد</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($accounts as $account)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('customers.show', $account->customer) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $account->customer->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $account->date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">{{ $account->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-red-600 font-semibold">
                            {{ $account->debit > 0 ? number_format($account->debit, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-green-600 font-semibold">
                            {{ $account->credit > 0 ? number_format($account->credit, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold">{{ number_format($account->balance, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $account->reference_type }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $accounts->links() }}
    </div>
</div>
@endsection
