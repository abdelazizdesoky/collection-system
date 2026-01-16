<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'بوابة تحصيل')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        body { font-family: 'Cairo', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-emerald-50 to-teal-100 min-h-screen">
    <!-- Header -->
    <nav class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg no-print">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="{{ route('collector.dashboard') }}" class="text-2xl font-bold hover:text-emerald-200 flex items-center gap-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        بوابة تحصيل
                    </a>
                    <span class="bg-emerald-800 px-3 py-1 rounded-full text-sm">
                        {{ $collector->name ?? 'Collector' }}
                    </span>
                </div>
                
                <div class="flex items-center gap-4">
                    <span class="text-sm opacity-80">{{ today()->format('Y/m/d') }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            تسجيل خروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-6 rounded-lg shadow no-print">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-700 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 p-4 mb-6 rounded-lg shadow no-print">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-red-700 font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white text-center py-4 mt-auto no-print">
        <p class="text-sm opacity-70">© 2026 Alarabia Group - بوابة تحصيل</p>
    </footer>
</body>
</html>
