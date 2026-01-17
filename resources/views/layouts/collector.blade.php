<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'بوابة تحصيل')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none'%3E%3Crect x='5' y='2' width='14' height='20' rx='3' stroke='%23059669' stroke-width='2'/%3E%3Crect x='8' y='5' width='8' height='6' rx='1' fill='%23059669' fill-opacity='0.2' stroke='%23059669' stroke-width='1.5'/%3E%3Cpath d='M8 14H16' stroke='%23059669' stroke-width='2' stroke-linecap='round'/%3E%3Cpath d='M8 17H10' stroke='%23059669' stroke-width='2' stroke-linecap='round'/%3E%3Cpath d='M15 17L15 20' stroke='%23059669' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E">
    
    <script>
        // Check for dark mode preference
        if (localStorage.getItem('darkMode') === 'enabled' || 
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            bg: '#334155',
                            card: '#475569',
                            border: '#64748b',
                            tableheader: '#334155'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        body { font-family: 'Cairo', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
        }

        /* Dark Mode Global Refinements */
        .dark body { background-color: #334155 !important; }
        .dark .bg-white, 
        .dark .bg-gray-50, 
        .dark .bg-blue-50, 
        .dark .bg-emerald-50, 
        .dark .bg-amber-50, 
        .dark .bg-rose-50,
        .dark .bg-gray-100 { 
            background-color: #475569 !important; 
        }
        
        .dark .text-gray-800, 
        .dark .text-gray-900, 
        .dark .text-gray-700 { 
            color: #e2e8f0 !important; 
        }
        
        .dark .text-gray-600, 
        .dark .text-gray-500, 
        .dark .text-gray-400 { 
            color: #cbd5e1 !important; 
        }
        
        .dark .border-gray-100, 
        .dark .border-gray-200, 
        .dark .border-gray-300 { 
            border-color: #64748b !important; 
        }

        .dark table {
            background-color: #475569 !important;
            border: 1px solid #64748b !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            border-radius: 0.75rem !important;
            overflow: hidden !important;
        }

        .dark thead tr {
            background-color: #334155 !important;
        }

        .dark th, .dark td {
            border-bottom: 1px solid #64748b !important;
            border-left: 1px solid #64748b !important;
            color: #e2e8f0 !important;
            padding: 1rem !important;
        }

        .dark th:last-child, .dark td:last-child {
            border-left: none !important;
        }

        .dark tr:last-child td {
            border-bottom: none !important;
        }

        .dark .divide-gray-50 > *, .dark .divide-gray-100 > * { 
            border-color: #64748b !important; 
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-dark-bg min-h-screen">
    <!-- Header -->
    <nav class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg no-print">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="{{ route('collector.dashboard') }}" class="text-2xl font-bold hover:text-emerald-200 flex items-center gap-2">
                        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="5" y="2" width="14" height="20" rx="3" stroke="currentColor" stroke-width="2"/>
                            <rect x="8" y="5" width="8" height="6" rx="1" fill="white" fill-opacity="0.2" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M8 14H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M8 17H10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M15 17L15 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        بوابة تحصيل
                    </a>
           <!-- <span class="bg-emerald-800 px-3 py-1 rounded-full text-sm"> -->
                        <!-- {{ $collector->name ?? 'Collector' }} -->
                    <!-- </span> -->
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    <button id="dark-mode-toggle" class="p-2 rounded-lg hover:bg-emerald-700 transition-colors focus:outline-none">
                        <svg id="sun-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707m12.728 0A9 9 0 115.636 5.636m12.728 12.728L12 12"></path></svg>
                        <svg id="moon-icon" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    <!-- <span class="text-sm opacity-80">{{ today()->format('Y/m/d') }}</span> -->
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dark Mode Logic
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const sunIcon = document.getElementById('sun-icon');
            const moonIcon = document.getElementById('moon-icon');

            function updateIcons() {
                if (document.documentElement.classList.contains('dark')) {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
            }

            updateIcons();

            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', () => {
                    document.documentElement.classList.toggle('dark');
                    if (document.documentElement.classList.contains('dark')) {
                        localStorage.setItem('darkMode', 'enabled');
                    } else {
                        localStorage.setItem('darkMode', 'disabled');
                    }
                    updateIcons();
                });
            }
        });
    </script>
</body>
</html>
