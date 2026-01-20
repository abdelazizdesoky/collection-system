<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none'%3E%3Crect x='5' y='2' width='14' height='20' rx='3' stroke='%232563eb' stroke-width='2'/%3E%3Crect x='8' y='5' width='8' height='6' rx='1' fill='%232563eb' fill-opacity='0.2' stroke='%232563eb' stroke-width='1.5'/%3E%3Cpath d='M8 14H16' stroke='%232563eb' stroke-width='2' stroke-linecap='round'/%3E%3Cpath d='M8 17H10' stroke='%232563eb' stroke-width='2' stroke-linecap='round'/%3E%3Cpath d='M15 17L15 20' stroke='%232563eb' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E">
    
    <script>
        if (localStorage.getItem('darkMode') === 'enabled' || 
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
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
                            border: '#64748b'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-dark-bg antialiased">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-md bg-white dark:bg-dark-card rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-dark-border">
            <div class="flex flex-col items-center mb-8">
                <svg class="w-16 h-16 text-blue-600 dark:text-blue-400 mb-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="5" y="2" width="14" height="20" rx="3" stroke="currentColor" stroke-width="2"/>
                    <rect x="8" y="5" width="8" height="6" rx="1" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M8 14H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M8 17H10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M15 17L15 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Alarabia Group</h1>
            </div>
            
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">البريد الالكتروني</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-300 dark:border-dark-border rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="admin@example.com"
                        required
                    >
                    @error('email')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">كلمة المرور</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 border border-gray-300 dark:border-dark-border rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <button 
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-blue-500/30 transform transition-all active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    تسجيل الدخول
                </button>
            </form>
            
            <!-- <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                <p class="text-sm text-blue-800"><strong>collection system   </strong>
                <br>
                <span>Alarabia Group</span>
            
                </p>
            </div> -->
        </div>
    </div>
</body>
</html>
