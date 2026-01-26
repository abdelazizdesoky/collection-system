@extends('layouts.app')

@section('title', 'تعديل بيانات المستخدم')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="bg-blue-600 p-3 rounded-2xl shadow-lg shadow-blue-500/30 text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-black dark:text-white tracking-tight">تعديل بيانات المستخدم</h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium">تحديث المعلومات والصلاحيات</p>
            </div>
        </div>
        <a href="{{ route('users.index') }}" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition-colors font-bold">
            <span>عودة للقائمة</span>
            <svg class="w-5 h-5 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('users.update', $user) }}" method="POST" class="bg-white dark:bg-dark-card rounded-3xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-8 space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">الاسم بالكامل <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full pr-10 pl-4 py-3 rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                    </div>
                    @error('name') <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 00-2-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full pr-10 pl-4 py-3 rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                    </div>
                    @error('email') <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">كلمة المرور (اتركه فارغاً للإبقاء على الحالية)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input type="password" name="password" class="w-full pr-10 pl-4 py-3 rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                        @error('password') <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">تأكيد كلمة المرور</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <input type="password" name="password_confirmation" class="w-full pr-10 pl-4 py-3 rounded-xl border-gray-300 dark:border-dark-border dark:bg-dark-bg dark:text-white focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Roles -->
                <div class="pt-4 border-t border-gray-100 dark:border-dark-border">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">الصلاحيات (الأدوار)</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($roles as $role)
                            <label class="relative flex cursor-pointer rounded-xl border border-gray-200 dark:border-dark-border p-4 shadow-sm hover:bg-gray-50 dark:hover:bg-dark-bg/50 transition-all {{ $user->hasRole($role->name) ? 'bg-blue-50 border-blue-200 ring-1 ring-blue-500' : '' }}">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="peer sr-only" {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                <span class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white uppercase">{{ $role->name }}</span>
                                    <span class="text-xs text-gray-500">صلاحيات {{ $role->name }}</span>
                                </span>
                                <span class="absolute top-4 left-4 text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-dark-bg/30 px-8 py-6 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-dark-border">
                <a href="{{ route('users.index') }}" class="px-6 py-3 rounded-xl font-bold text-gray-600 hover:bg-gray-200 transition-colors">إلغاء</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold shadow-lg shadow-blue-500/20 transition-all transform hover:scale-105">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
