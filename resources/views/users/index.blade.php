@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4 text-right">
                <div class="bg-blue-600 p-3 rounded-2xl shadow-lg shadow-blue-500/30 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black dark:text-white tracking-tight">إدارة مستخدمي النظام</h1>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">التحكم في صلاحيات الوصول والحسابات الإدارية</p>
                </div>
            </div>
            
            <div class="w-full lg:w-auto">
                <span class="px-4 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-full text-sm font-bold border border-blue-100 dark:border-blue-800">
                    إجمالي المستخدين: {{ $users->count() }}
                </span>
            </div>
        </div>
    </div>

    <!-- Create User Form Card -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-8 mb-8 border border-gray-100 dark:border-dark-border">
        <h2 class="text-xl font-bold dark:text-white mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            إضافة حساب مستخدم جديد
        </h2>
        
        <form method="POST" action="{{ route('users.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
            @csrf
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">الاسم بالكامل</label>
                <input name="name" placeholder="مثلاً: أحمد محمد" class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">البريد الإلكتروني</label>
                <input name="email" type="email" placeholder="email@example.com" class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none" required>
            </div>
            <div>
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">كلمة المرور</label>
                <input name="password" type="password" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-blue-500 transition-all outline-none" required>
            </div>
            <div class="flex gap-3">
                <div class="flex-grow">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">الدور</label>
                    <select name="roles[]" class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer appearance-none" required>
                        <option value="admin">مدير نظام</option>
                        <option value="supervisor">مشرف عمليات</option>
                        <option value="accountant">محاسب مالي</option>
                        <option value="plan_supervisor">مشرف خطط</option>
                        <option value="collector">محصل ميداني</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center">
                    إنشاء
                </button>
            </div>
        </form>

        @if ($errors->any())
            <div class="mt-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 text-red-700 dark:text-red-400 rounded-xl">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm font-medium">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader text-gray-600 dark:text-gray-300">
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">المستخدم</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">البريد الإلكتروني</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الدور / الصلاحية</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @foreach($users as $user)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-colors group">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold dark:text-white text-lg group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap dark:text-gray-400 font-mono text-sm">{{ $user->email }}</td>
                            <td class="px-6 py-5 text-center">
                                @php $role = $user->getRoleNames()->first() ?? 'user'; @endphp
                                <span class="px-3 py-1 inline-flex text-xs font-black rounded-lg border
                                    @if($role === 'admin') bg-indigo-50 text-indigo-700 border-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800
                                    @elseif($role === 'supervisor') bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800
                                    @elseif($role === 'accountant') bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800
                                    @elseif($role === 'plan_supervisor') bg-blue-50 text-blue-700 border-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800
                                    @else bg-gray-50 text-gray-700 border-gray-100 dark:bg-gray-900/30 dark:text-gray-400 dark:border-gray-800
                                    @endif">
                                    @if($role === 'admin') مدير نظام 
                                    @elseif($role === 'supervisor') مشرف عمليات 
                                    @elseif($role === 'accountant') محاسب مالي
                                    @elseif($role === 'plan_supervisor') مشرف خطط
                                    @else محصل ميداني 
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الحساب نهائياً؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="حذف">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic">حسابك الحالي</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
