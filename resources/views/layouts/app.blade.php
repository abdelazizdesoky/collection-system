<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Collection System')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none'%3E%3Crect x='5' y='2' width='14' height='20' rx='3' stroke='%232563eb' stroke-width='2'/%3E%3Crect x='8' y='5' width='8' height='6' rx='1' fill='%232563eb' fill-opacity='0.2' stroke='%232563eb' stroke-width='1.5'/%3E%3Cpath d='M8 14H16' stroke='%232563eb' stroke-width='2' stroke-linecap='round'/%3E%3Cpath d='M8 17H10' stroke='%232563eb' stroke-width='2' stroke-linecap='round'/%3E%3Cpath d='M15 17L15 20' stroke='%232563eb' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E">
    
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
    <!-- Cairo Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body { font-family: 'Cairo', sans-serif !important; }
        
        /* Smooth transition for theme switching */
        * { transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease; }

        /* Select2 Premium Customization */
        .select2-container--default .select2-selection--single {
            height: 3.5rem !important;
            padding: 0.75rem 1rem !important;
            border-radius: 1rem !important;
            border-color: #e5e7eb !important;
            background-color: transparent !important;
            display: flex !important;
            align-items: center !important;
        }
        .dark .select2-container--default .select2-selection--single {
            border-color: #64748b !important;
            background-color: #334155 !important;
            color: white !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: inherit !important;
            font-weight: 700 !important;
            padding-right: 2rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 3.5rem !important;
            left: 1rem !important;
            right: auto !important;
        }
        .select2-dropdown {
            border-radius: 1rem !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1) !important;
            overflow: hidden !important;
            z-index: 9999 !important;
        }
        .dark .select2-dropdown {
            background-color: #475569 !important;
            border-color: #64748b !important;
            color: white !important;
        }
        .select2-search__field {
            border-radius: 0.5rem !important;
            padding: 0.5rem 1rem !important;
        }
        .dark .select2-search__field {
            background-color: #334155 !important;
            border-color: #64748b !important;
            color: white !important;
        }
        .select2-results__option {
            padding: 0.75rem 1rem !important;
            font-weight: 600 !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #2563eb !important;
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

        .dark .hover\:bg-gray-50:hover, 
        .dark .hover\:bg-blue-50:hover { 
            background-color: #64748b !important; 
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-dark-bg antialiased overflow-hidden">
    <div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-dark-bg" id="app-container">
        
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 right-0 z-50 w-72 bg-white dark:bg-dark-card border-l border-gray-100 dark:border-dark-border transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0" :class="{'translate-x-0': sidebarOpen, 'translate-x-full': !sidebarOpen}">
            <div class="flex flex-col h-full overflow-hidden">
                <!-- Sidebar Header -->
                <div class="flex items-center gap-3 px-6 h-20 border-b border-gray-50 dark:border-dark-border bg-gray-50/50 dark:bg-dark-bg/20">
                    <div class="bg-blue-600 p-2 rounded-xl shadow-lg shadow-blue-500/20">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="5" y="2" width="14" height="20" rx="3" stroke="currentColor" stroke-width="2"/>
                            <rect x="8" y="5" width="8" height="6" rx="1" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M8 14H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M8 17H10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M15 17L15 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="sidebar-header-text">
                        <span class="text-lg font-black text-gray-900 dark:text-white tracking-tight">النظام المالي</span>
                        <p class="text-[10px] text-blue-600 font-bold uppercase tracking-widest">Premium Dashboard</p>
                    </div>
                </div>

                <!-- Sidebar Content -->
                <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-8 custom-scrollbar">
                    <!-- Dashboard -->
                    <div>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-gradient-to-l from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/30 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-dark-bg/50' }}">
                            <svg class="w-6 h-6 shrink-0 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span class="text-sm sidebar-text">لوحة التحكم</span>
                        </a>
                    </div>

                    @role('admin|supervisor|accountant')
                    <!-- Financial Operations -->
                    <div class="space-y-2">
                        <h3 class="px-4 text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[2px] mb-3 flex items-center gap-2 sidebar-group-header">
                            <span>العمليات المالية</span>
                            <div class="h-px bg-gray-100 dark:bg-dark-border flex-1"></div>
                        </h3>
                        <div class="space-y-1">
                            <a href="{{ route('collections.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('collections.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-sm sidebar-text">سجل التحصيلات</span>
                            </a>
                            <a href="{{ route('customer-accounts.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('customer-accounts.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-sm sidebar-text">كشف الحساب العام</span>
                            </a>
                            <a href="{{ route('cheques.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('cheques.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                <span class="text-sm sidebar-text">إدارة الشيكات</span>
                            </a>
                             @hasrole('admin|supervisor')
                             <a href="{{ route('banks.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('banks.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                 <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                 <span class="text-sm sidebar-text">البنوك والمحافظ</span>
                             </a>
                             @endhasrole
                             
                             <a href="{{ route('installments.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('installments.*') ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                 <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                 <span class="text-sm sidebar-text font-black">نظام التقسيط</span>
                             </a>
@endrole
                        </div>
                    </div>
                    @endrole

                    <!-- Plans & Follow-up -->
                    @role('admin|supervisor|plan_supervisor')
                    <div class="space-y-2">
                        <h3 class="px-4 text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[2px] mb-3 flex items-center gap-2 sidebar-group-header">
                            <span>الخطط والمتابعة</span>
                            <div class="h-px bg-gray-100 dark:bg-dark-border flex-1"></div>
                        </h3>
                        <div class="space-y-1">
                            <a href="{{ route('collection-plans.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('collection-plans.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                <span class="text-sm sidebar-text">خطط التحصيل المالي</span>
                            </a>
                            <a href="{{ route('visit-plans.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('visit-plans.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                <span class="text-sm sidebar-text">خطط الزيارات الميدانية</span>
                            </a>
                            @hasrole('admin|supervisor')
                            <a href="{{ route('visit-types.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('visit-types.*') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                <span class="text-sm sidebar-text">أنواع وتصنيف الزيارات</span>
                            </a>
                            @endhasrole
                        </div>
                    </div>
                    @endrole

                    <!-- Core Data -->
                    @role('admin|supervisor|accountant')
                    <div class="space-y-2">
                        <h3 class="px-4 text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[2px] mb-3 flex items-center gap-2 sidebar-group-header">
                            <span>البيانات الأساسية</span>
                            <div class="h-px bg-gray-100 dark:bg-dark-border flex-1"></div>
                        </h3>
                        <div class="space-y-1">
                            <a href="{{ route('customers.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('customers.*') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0"></path></svg>
                                <span class="text-sm sidebar-text">العملاء</span>
                            </a>
                            <a href="{{ route('collectors.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('collectors.*') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="text-sm sidebar-text">فريق المندوبين</span>
                            </a>
                            @hasrole('admin|supervisor')
                            <a href="{{ route('areas.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('areas.*') ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="text-sm sidebar-text">المناطق الجغرافية</span>
                            </a>
                            @endhasrole
                        </div>
                    </div>
                    @endrole

                    <!-- System Administration (Partitioned for Admin vs Accountant) -->
                    <div class="space-y-2">
                        <h3 class="px-4 text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[2px] mb-3 flex items-center gap-2 sidebar-group-header">
                            <span>إدارة النظام</span>
                            <div class="h-px bg-gray-100 dark:bg-dark-border flex-1"></div>
                        </h3>
                        <div class="space-y-1">
                            @hasrole('admin')
                            <a href="{{ route('users.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('users.*') ? 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span class="text-sm sidebar-text">إدارة المستخدمين</span>
                            </a>
                            @endhasrole
                            
                            @hasrole('admin')
                            <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.audit-logs.*') ? 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="text-sm sidebar-text">مراقبة العمليات</span>
                            </a>
                            @endhasrole

                            @hasrole('admin')
                            <a href="{{ route('admin.backups.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.backups.*') ? 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-bg/30' }}">
                                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                <span class="text-sm sidebar-text">النسخ الاحتياطي</span>
                            </a>
                            @endhasrole
                        </div>
                    </div>
                </nav>

                <!-- Sidebar Footer -->
                @auth
                <div class="p-4 border-t border-gray-50 dark:border-dark-border bg-gray-50/30 dark:bg-dark-bg/10">
                    <div class="flex items-center gap-3 px-4 py-2">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                            {{ mb_substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="flex-1 overflow-hidden sidebar-text">
                            <p class="text-xs font-bold dark:text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </aside>

        <!-- Sidebar Overlay (mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 bg-gray-50 dark:bg-dark-bg h-screen overflow-hidden">
            
            <!-- Top Navbar -->
            <header class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 text-white shadow-xl z-30">
                <div class="h-20 px-6 flex justify-between items-center bg-white/5 backdrop-blur-sm">
                    <div class="flex items-center gap-6">
                        <!-- Toggle Button -->
                        <button id="toggle-sidebar" class="p-2.5 rounded-xl hover:bg-white/10 focus:outline-none transition-all border border-white/10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                            <div class="bg-white/20 p-2 rounded-lg backdrop-blur-md">
                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="5" y="2" width="14" height="20" rx="3" stroke="currentColor" stroke-width="2"/>
                                    <rect x="8" y="5" width="8" height="6" rx="1" fill="white" fill-opacity="0.2" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M8 14H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <span class="text-xl md:text-2xl font-black tracking-tighter">Alarabia Group</span>
                        </a>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <!-- Dark Mode Toggle -->
                        <button id="dark-mode-toggle" class="p-3 rounded-xl hover:bg-white/10 transition-all border border-white/5 active:scale-95">
                            <svg id="sun-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707m12.728 0A9 9 0 115.636 5.636m12.728 12.728L12 12"></path></svg>
                            <svg id="moon-icon" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        </button>

                        @auth
                        <div class="h-10 w-px bg-white/10 mx-2 hidden md:block"></div>

                        <div class="hidden md:flex flex-col items-start text-sm leading-tight">
                            <span class="font-black text-white">{{ auth()->user()->name }}</span>
                            <span class="text-blue-200 text-[10px] font-bold uppercase tracking-wider">
                                @php
                                    $roleNames = auth()->user()->getRoleNames();
                                @endphp
                                @if($roleNames->contains('admin')) مدير النظام @elseif($roleNames->contains('supervisor')) مشرف عمليات @elseif($roleNames->contains('collector')) محصل ميداني @else مستخدم @endif
                            </span>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-rose-500 hover:bg-rose-600 p-3 rounded-xl transition-all shadow-lg shadow-rose-500/30 flex items-center justify-center active:scale-95" title="تسجيل الخروج">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                        @endauth
                        
                        @guest
                        <a href="{{ route('login') ?? url('/login') }}" class="px-6 py-2 bg-white/20 hover:bg-white/30 rounded-xl font-bold transition-all">دخول</a>
                        @endguest
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
            </main>  
               
        </div>
        
    </div>
    <!-- Footer (Inside main to scroll with content) -->
                <footer class="mt-auto py-8 text-center text-gray-500 text-sm border-t border-gray-200">
                    <p>&copy; 2026 Alarabia Group جميع الحقوق محفوظة.</p>
                </footer>
    <!-- Toggle Script -->
    <!-- JQuery and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            function initSelect2() {
                $('.select2-search').select2({
                    dir: "rtl",
                    width: '100%',
                    placeholder: $(this).data('placeholder') || "ابحث هنا...",
                    allowClear: true
                });
            }
            
            initSelect2();
            
            // Re-init on AJAX if needed
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-sidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const appContainer = document.getElementById('app-container');

            // Sidebar State
            let isSidebarOpen = window.innerWidth >= 1024;
            let isSidebarMini = false;

            function updateSidebarUI() {
                if (window.innerWidth >= 1024) {
                    // Desktop/Tablet Desktop logic
                    overlay.classList.add('hidden');
                    sidebar.classList.remove('translate-x-full');
                    
                    if (isSidebarMini) {
                        sidebar.classList.remove('w-72');
                        sidebar.classList.add('w-20');
                        sidebar.classList.add('sidebar-mini');
                        document.querySelectorAll('.sidebar-text').forEach(el => el.classList.add('lg:hidden'));
                        document.querySelectorAll('.sidebar-header-text').forEach(el => el.classList.add('lg:hidden'));
                        document.querySelectorAll('.sidebar-group-header').forEach(el => el.classList.add('lg:hidden'));
                    } else {
                        sidebar.classList.remove('w-20');
                        sidebar.classList.add('w-72');
                        sidebar.classList.remove('sidebar-mini');
                        document.querySelectorAll('.sidebar-text').forEach(el => el.classList.remove('lg:hidden'));
                        document.querySelectorAll('.sidebar-header-text').forEach(el => el.classList.remove('lg:hidden'));
                        document.querySelectorAll('.sidebar-group-header').forEach(el => el.classList.remove('lg:hidden'));
                    }
                } else {
                    // Mobile logic (Drawer)
                    sidebar.classList.add('w-72');
                    sidebar.classList.remove('w-20');
                    sidebar.classList.remove('sidebar-mini');
                    document.querySelectorAll('.sidebar-text').forEach(el => el.classList.remove('lg:hidden'));
                    document.querySelectorAll('.sidebar-header-text').forEach(el => el.classList.remove('lg:hidden'));
                    document.querySelectorAll('.sidebar-group-header').forEach(el => el.classList.remove('lg:hidden'));

                    if (isSidebarOpen) {
                        sidebar.classList.remove('translate-x-full');
                        overlay.classList.remove('hidden');
                    } else {
                        sidebar.classList.add('translate-x-full');
                        overlay.classList.add('hidden');
                    }
                }
            }

            toggleBtn.addEventListener('click', function() {
                if (window.innerWidth >= 1024) {
                    isSidebarMini = !isSidebarMini;
                } else {
                    isSidebarOpen = !isSidebarOpen;
                }
                updateSidebarUI();
            });

            overlay.addEventListener('click', function() {
                isSidebarOpen = false;
                updateSidebarUI();
            });

            // Dark Mode Logic
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const sunIcon = document.getElementById('sun-icon');
            const moonIcon = document.getElementById('moon-icon');

            function updateDarkIcons() {
                if (document.documentElement.classList.contains('dark')) {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
            }

            updateDarkIcons();

            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', () => {
                    document.documentElement.classList.toggle('dark');
                    if (document.documentElement.classList.contains('dark')) {
                        localStorage.setItem('darkMode', 'enabled');
                    } else {
                        localStorage.setItem('darkMode', 'disabled');
                    }
                    updateDarkIcons();
                });
            }

            // Initial UI Update
            updateSidebarUI();

            // Responsive behavior
            window.addEventListener('resize', function() {
                updateSidebarUI();
            });
        });
    </script>
     
</body>
</html>
