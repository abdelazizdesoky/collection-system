@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between" dir="rtl">
            <h1 class="text-2xl font-bold text-gray-900 text-right">المستخدمون</h1>
            <div class="flex gap-2">
                <a href="{{ route('users.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    تصدير إكسيل
                </a>
                <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded font-bold">+ مستخدم جديد</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-right">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">الاسم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">البريد الإلكتروني</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">الأدوار</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 text-right">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-sm text-right">
                                    @foreach($user->roles as $role)
                                        @php
                                            $roleNames = ['admin' => 'مدير', 'supervisor' => 'مشرف', 'collector' => 'محصل'];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mr-1">{{ $roleNames[$role->name] ?? $role->name }}</span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <a href="{{ route('users.edit', $user) }}" class="text-green-600 hover:text-green-800 ml-3">تعديل</a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline" onsubmit="return confirm('هل أنت متأكد من حذف المستخدم؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
