<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Collection System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 antialiased overflow-hidden">
    <div class="flex h-screen overflow-hidden" id="app-container">
        
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 right-0 z-50 w-64 bg-white border-l border-gray-200 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0" :class="{'translate-x-0': sidebarOpen, 'translate-x-full': !sidebarOpen}">
            <div class="flex flex-col h-full">
                <!-- Sidebar Header (Optional Logo/Title) -->
                <div class="flex items-center justify-center h-16 border-b border-gray-200">
                    <span class="text-xl font-bold text-blue-600">القائمة</span>
                </div>

                <!-- Sidebar Content -->
                <nav class="flex-1 overflow-y-auto py-4 px-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        لوحة التحكم
                    </a>

                    @role('admin|supervisor')
                    <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('customers.*') ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0"></path></svg>
                        العملاء
                    </a>
                    <a href="{{ route('collectors.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('collectors.*') ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        المحصلون
                    </a>
                    <a href="{{ route('collections.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('collections.*') ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        التحصيلات
                    </a>
                    <a href="{{ route('collection-plans.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('collection-plans.*') ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        الخطط
                    </a>
                    <a href="{{ route('customer-accounts.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('customer-accounts.*') ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        السجل
                    </a>
                    <a href="{{ route('cheques.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('cheques.*') ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        الشيكات
                    </a>
                    @endrole

                    @role('admin')
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('users.*') ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            المستخدمون
                        </a>
                        <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.audit-logs.*') ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            مراقبة العمليات
                        </a>
                        <a href="{{ route('admin.backups.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.backups.*') ? 'bg-blue-100 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            نسخ احتياطي
                        </a>
                    </div>
                    @endrole
                </nav>
            </div>
        </aside>

        <!-- Sidebar Overlay (mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 bg-gray-50 h-screen overflow-hidden">
            
            <!-- Top Navbar -->
            <header class="bg-blue-600 text-white shadow-md z-30">
                <div class="h-16 px-4 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <!-- Toggle Button -->
                        <button id="toggle-sidebar" class="p-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <a href="{{ route('dashboard') }}" class="text-xl md:text-2xl font-bold">Alarabia Group</a>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="hidden md:flex flex-col items-end text-sm">
                            <span class="font-bold">{{ auth()->user()?->name ?? 'زائر' }}</span>
                            @php
                                $roleNames = auth()->user()?->getRoleNames() ?? collect();
                            @endphp
                            <span class="text-blue-200 text-xs">
                                @if($roleNames->contains('admin')) مدير النظام @elseif($roleNames->contains('supervisor')) مشرف @elseif($roleNames->contains('collector')) محصل @else مستخدم @endif
                            </span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 p-2 rounded-lg title="تسجيل الخروج">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
                
                <!-- Session Messages -->
                <div class="max-w-7xl mx-auto mb-6">
                    @if (session('success'))
                        <div class="bg-green-100 border-r-4 border-green-500 p-4 rounded-lg flex justify-between items-center mb-4 shadow-sm">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-green-500 ml-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                            <button class="text-green-500 hover:text-green-700" onclick="this.parentElement.style.display='none';">✕</button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border-r-4 border-red-500 p-4 rounded-lg flex justify-between items-center mb-4 shadow-sm">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-red-500 ml-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                            <button class="text-red-500 hover:text-red-700" onclick="this.parentElement.style.display='none';">✕</button>
                        </div>
                    @endif
                </div>

                <!-- Page Content -->
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>

                <!-- Footer (Inside main to scroll with content) -->
                <footer class="mt-auto py-8 text-center text-gray-500 text-sm border-t border-gray-200">
                    <p>&copy; 2026 Alarabia Group جميع الحقوق محفوظة.</p>
                </footer>
            </main>
        </div>
    </div>

    <!-- Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-sidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const appContainer = document.getElementById('app-container');

            function toggleSidebar() {
                const isHidden = sidebar.classList.contains('translate-x-full');
                
                if (window.innerWidth >= 1024) {
                    // Desktop: Toggle visibility and layout space
                    sidebar.classList.toggle('lg:hidden');
                } else {
                    // Mobile: Toggle slide and overlay
                    sidebar.classList.toggle('translate-x-full');
                    overlay.classList.toggle('hidden');
                }
            }

            toggleBtn.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);

            // Responsive behavior
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    overlay.classList.add('hidden');
                } else if (!sidebar.classList.contains('translate-x-full')) {
                    overlay.classList.remove('hidden');
                }
            });
        });
    </script>
</body>
</html>
