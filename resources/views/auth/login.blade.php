<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Collection System</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none'%3E%3Crect x='5' y='2' width='14' height='20' rx='3' stroke='%232563eb' stroke-width='2'/%3E%3Crect x='8' y='5' width='8' height='6' rx='1' fill='%232563eb' fill-opacity='0.2' stroke='%232563eb' stroke-width='1.5'/%3E%3Cpath d='M8 14H16' stroke='%232563eb' stroke-width='2' stroke-linecap='round'/%3E%3Cpath d='M8 17H10' stroke='%232563eb' stroke-width='2' stroke-linecap='round'/%3E%3Cpath d='M15 17L15 20' stroke='%232563eb' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <div class="flex flex-col items-center mb-8">
                <svg class="w-16 h-16 text-blue-600 mb-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="5" y="2" width="14" height="20" rx="3" stroke="currentColor" stroke-width="2"/>
                    <rect x="8" y="5" width="8" height="6" rx="1" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M8 14H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M8 17H10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M15 17L15 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <h1 class="text-3xl font-bold text-gray-800">Alarabia Group</h1>
            </div>
            
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="admin@example.com"
                        required
                    >
                    @error('email')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <button 
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Login
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
