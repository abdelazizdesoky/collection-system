@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 text-right" dir="rtl">
    <div class="max-w-xl w-full space-y-12 text-center">
        <!-- Error Stage -->
        <div class="relative">
            <!-- Background Glow -->
            <div class="absolute inset-0 -inset-y-20 bg-gradient-to-r from-blue-500/10 to-indigo-500/10 blur-3xl rounded-full"></div>
            
            <div class="relative z-10">
                <div class="text-[180px] font-black leading-none tracking-tighter text-transparent bg-clip-text bg-gradient-to-b from-gray-900 via-gray-700 to-gray-500 dark:from-white dark:via-gray-300 dark:to-gray-600 opacity-20 select-none">
                    @yield('code')
                </div>
                
                <div class="mt-[-80px]">
                    <div class="inline-flex p-6 rounded-[2.5rem] bg-white dark:bg-dark-card shadow-2xl border border-gray-50 dark:border-dark-border mb-8 animate-bounce-slow">
                        @yield('icon')
                    </div>
                </div>
            </div>
        </div>

        <!-- Text Content -->
        <div class="relative z-10">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight mb-4 cyrillic-font">
                @yield('error_title')
            </h1>
            <p class="text-lg text-gray-500 dark:text-gray-400 font-bold max-w-md mx-auto leading-relaxed">
                @yield('error_message')
            </p>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 relative z-10">
            <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-blue-500/30 transition-all transform hover:scale-105 active:scale-95 flex items-center justify-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                العودة للرئيسية
            </a>
            <button onclick="window.history.back()" class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-dark-bg text-gray-700 dark:text-white font-black rounded-2xl border border-gray-200 dark:border-dark-border hover:bg-gray-50 dark:hover:bg-slate-700 transition-all flex items-center justify-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                رجوع للخلف
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 4s ease-in-out infinite;
    }
</style>
@endsection
