<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Collection System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-6">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold hover:text-blue-200">Alarabia Group</a>
                    <div class="hidden md:flex gap-2 text-sm">
                        @php
                            $userRoles = auth()->user()?->getRoleNames() ?? collect();
                        @endphp
                        @if($userRoles->contains('admin'))
                            <span class="bg-red-500 px-2 py-1 rounded">Admin</span>
                        @elseif($userRoles->contains('collector'))
                            <span class="bg-green-500 px-2 py-1 rounded">Collector</span>
                        @else
                            <span class="bg-gray-500 px-2 py-1 rounded">User</span>
                        @endif
                    </div>
                </div>
                
                <ul class="hidden md:flex gap-6">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-blue-200">{{ __('messages.dashboard') }}</a></li>
                    
                    @role('Admin|Collector')
                        <li><a href="{{ route('customers.index') }}" class="hover:text-blue-200">{{ __('messages.customers') }}</a></li>
                        <li><a href="{{ route('collections.index') }}" class="hover:text-blue-200">{{ __('messages.collections') }}</a></li>
                        <li><a href="{{ route('customer-accounts.index') }}" class="hover:text-blue-200">{{ __('messages.ledger') }}</a></li>
                    @endrole
                    
                    @role('Admin')
                        <li><a href="{{ route('collectors.index') }}" class="hover:text-blue-200">{{ __('messages.collectors') }}</a></li>
                        <li><a href="{{ route('cheques.index') }}" class="hover:text-blue-200">{{ __('messages.cheques') }}</a></li>
                        <li><a href="{{ route('collection-plans.index') }}" class="hover:text-blue-200">{{ __('messages.plans') }}</a></li>
                    @endrole
                </ul>
                
                <div class="flex items-center gap-4">
                    <!-- Language Switcher -->
                    <div class="flex gap-2 text-sm">
                        <a href="{{ route('set-language', 'en') }}" class="px-2 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-white text-blue-600 font-bold' : 'hover:bg-blue-500' }}">EN</a>
                        <a href="{{ route('set-language', 'ar') }}" class="px-2 py-1 rounded {{ app()->getLocale() === 'ar' ? 'bg-white text-blue-600 font-bold' : 'hover:bg-blue-500' }}">AR</a>
                    </div>
                    
                    <span class="text-sm">{{ auth()->user()?->name ?? 'Guest' }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded">{{ __('messages.logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <!-- Session Messages -->
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                    <button class="ml-auto text-green-500 hover:text-green-700" onclick="this.parentElement.parentElement.style.display='none';">✕</button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                    <button class="ml-auto text-red-500 hover:text-red-700" onclick="this.parentElement.parentElement.style.display='none';">✕</button>
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                    </div>
                    <button class="ml-auto text-yellow-500 hover:text-yellow-700" onclick="this.parentElement.parentElement.style.display='none';">✕</button>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">{{ session('info') }}</p>
                    </div>
                    <button class="ml-auto text-blue-500 hover:text-blue-700" onclick="this.parentElement.parentElement.style.display='none';">✕</button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white text-center py-4 mt-12">
        <p>&copy; 2026 Alarabia Group All rights reserved.</p>
    </footer>
</body>
</html>
