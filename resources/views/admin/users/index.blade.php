@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Premium Header Area -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4 text-right">
                <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-500/30 text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black dark:text-white tracking-tight">إدارة المستخدمين</h1>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">التحكم في صلاحيات الوصول والحسابات الإدارية لفريق العمل</p>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3 w-full lg:w-auto">
                <a href="{{ route('users.export') }}" class="flex-grow lg:flex-none flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-500/20 transition-all text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    تصدير إكسيل
                </a>
                <a href="{{ route('users.create') }}" class="flex-grow lg:flex-none flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-indigo-500/20 transition-all text-sm whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    إضافة مستخدم جديد
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-dark-border">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-tableheader text-gray-600 dark:text-gray-300">
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap">المستخدم</th>
                        <th class="px-6 py-5 text-sm font-black whitespace-nowrap text-center">البريد الإلكتروني</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الأدوار / الصلاحيات</th>
                        <th class="px-6 py-5 text-sm font-black text-center whitespace-nowrap">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-border">
                    @foreach($users as $user)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/30 transition-colors group">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold group-hover:scale-110 transition-transform">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold dark:text-white text-lg group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center whitespace-nowrap dark:text-gray-400 font-mono text-sm leading-relaxed">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex flex-wrap justify-center gap-1.5">
                                    @foreach($user->roles as $role)
                                        <span class="px-3 py-1 inline-flex text-xs font-black rounded-lg border
                                            @if($role->name === 'admin') bg-indigo-50 text-indigo-700 border-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800
                                            @elseif($role->name === 'supervisor') bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800
                                            @elseif($role->name === 'accountant') bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800
                                            @elseif($role->name === 'plan_supervisor') bg-blue-50 text-blue-700 border-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800
                                            @else bg-gray-50 text-gray-700 border-gray-100 dark:bg-gray-900/30 dark:text-gray-400 dark:border-gray-800
                                            @endif">
                                            @if($role->name === 'admin') مدير نظام 
                                            @elseif($role->name === 'supervisor') مشرف عمليات 
                                            @elseif($role->name === 'accountant') محاسب مالي
                                            @elseif($role->name === 'plan_supervisor') مشرف خطط
                                            @else محصل ميداني 
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('users.edit', $user) }}" class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white transition-all shadow-sm" title="تعديل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم نهائياً؟')" title="حذف">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $users->links() }}
    </div>
</div>
@endsection
