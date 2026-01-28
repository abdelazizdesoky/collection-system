@extends('layouts.app')

@section('title', 'الإعدادات العامة')

@section('content')
<div class="container mx-auto py-8 px-4" dir="rtl">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center gap-4">
            <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-500/30 text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-black dark:text-white tracking-tight">الإعدادات العامة</h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium">إدارة بيانات الشركة والشعار والنشاط</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl border border-gray-100 dark:border-dark-border overflow-hidden">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Company Name -->
                    <div class="space-y-2">
                        <label class="block text-sm font-black dark:text-gray-300">اسم الشركة</label>
                        <input type="text" name="company_name" value="{{ $settings['company_name'] ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-border bg-gray-50 dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    </div>

                    <!-- Company Activity -->
                    <div class="space-y-2">
                        <label class="block text-sm font-black dark:text-gray-300">نشاط الشركة</label>
                        <input type="text" name="company_activity" value="{{ $settings['company_activity'] ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-border bg-gray-50 dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    </div>

                    <!-- Company Phone -->
                    <div class="space-y-2">
                        <label class="block text-sm font-black dark:text-gray-300">رقم الهاتف</label>
                        <input type="text" name="company_phone" value="{{ $settings['company_phone'] ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-border bg-gray-50 dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    </div>

                    <!-- Company Address -->
                    <div class="space-y-2">
                        <label class="block text-sm font-black dark:text-gray-300">العنوان</label>
                        <input type="text" name="company_address" value="{{ $settings['company_address'] ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-border bg-gray-50 dark:bg-dark-bg dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    </div>
                </div>

                <!-- Company Logo -->
                <div class="mb-8 p-6 bg-gray-50 dark:bg-dark-bg border border-dashed border-gray-300 dark:border-dark-border rounded-2xl text-center">
                    <label class="block text-sm font-black dark:text-gray-300 mb-4 text-right">شعار الشركة (Logo)</label>
                    
                    <div class="flex items-center justify-center gap-8 flex-col md:flex-row">
                        @if(isset($settings['company_logo']))
                            <div class="w-32 h-32 rounded-xl overflow-hidden shadow-lg border border-white">
                                <img src="{{ asset('storage/' . $settings['company_logo']) }}" class="w-full h-full object-contain bg-white">
                            </div>
                        @endif
                        
                        <div class="flex-grow flex flex-col items-center">
                            <input type="file" name="company_logo" id="logo_input" class="hidden">
                            <label for="logo_input" class="cursor-pointer bg-white dark:bg-dark-card border border-gray-200 dark:border-dark-border px-6 py-3 rounded-xl shadow-sm hover:shadow-md transition-all font-bold dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                اختر الملف
                            </label>
                            <p class="mt-2 text-xs text-gray-500">PNG, JPG (أقصى حجم 2 ميجا)</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-100 dark:border-dark-border">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-12 rounded-xl shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
                        حفظ التعديلات
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
