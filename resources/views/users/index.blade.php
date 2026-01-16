@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 text-right" dir="rtl">
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h1 class="text-2xl font-bold mb-6">مستخدم جديد</h1>
        <form method="POST" action="{{ route('users.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            @csrf
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">الاسم</label>
                <input name="name" placeholder="الاسم" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                <input name="email" type="email" placeholder="البريد الإلكتروني" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">كلمة المرور</label>
                <input name="password" type="password" placeholder="كلمة المرور" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" required>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">الدور</label>
                    <select name="roles[]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-right" dir="rtl" required>
                        <option value="admin">مدير نظام</option>
                        <option value="collector">محصل</option>
                        <option value="supervisor">مشرف</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 h-[42px]">إنشاء</button>
            </div>
        </form>

        @if ($errors->any())
            <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded text-right">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 text-right">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدور</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php $role = strtolower($user->getRoleNames()->first() ?? 'user'); @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $role === 'admin' ? 'مدير نظام' : ($role === 'collector' ? 'محصل' : ($role === 'supervisor' ? 'مشرف' : 'مستخدم')) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العنصر؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
