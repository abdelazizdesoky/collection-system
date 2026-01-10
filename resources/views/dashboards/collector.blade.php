@extends('layouts.app')

@section('title', 'Collector Dashboard')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Collector Dashboard</h1>
            <p class="text-gray-600 mt-2">Welcome back, {{ $collector->name }}! Record and track your collections here.</p>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Collections Today -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Today's Collections</p>
                        <p class="text-3xl font-bold mt-2">₹{{ number_format($collectionsToday, 2) }}</p>
                    </div>
                    <svg class="h-12 w-12 text-blue-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Collections This Month -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">This Month</p>
                        <p class="text-3xl font-bold mt-2">₹{{ number_format($collectionsThisMonth, 2) }}</p>
                    </div>
                    <svg class="h-12 w-12 text-green-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>

            <!-- Total Collections -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Total Collections</p>
                        <p class="text-3xl font-bold mt-2">₹{{ number_format($totalCollections, 2) }}</p>
                    </div>
                    <svg class="h-12 w-12 text-yellow-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>

            <!-- Assigned Customers -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Assigned Customers</p>
                        <p class="text-3xl font-bold mt-2">{{ $assignedCustomers->count() }}</p>
                    </div>
                    <svg class="h-12 w-12 text-purple-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Collections & Receipts Table -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">📋 Collections & Receipts</h2>
                    <a href="{{ route('collections.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded">
                        ➕ New Collection
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Receipt #</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentCollections as $collection)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $collection->receipt_no }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $collection->customer->name }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="font-semibold text-green-600">₹{{ number_format($collection->amount, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($collection->payment_type === 'cash')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">💵 Cash</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">📄 Cheque</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $collection->created_at->format('M d, Y H:i') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('collections.show', $collection) }}" class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center">
                                        <p class="text-gray-600">No collections recorded yet</p>
                                        <a href="{{ route('collections.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">Record your first collection →</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions & Summary -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🚀 Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('collections.create') }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition">
                            <span class="text-2xl mr-3">💰</span>
                            <div>
                                <p class="font-medium text-gray-900">Record Collection</p>
                                <p class="text-xs text-gray-600">Add cash or cheque</p>
                            </div>
                        </a>
                        <a href="{{ route('customer-accounts.index') }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition">
                            <span class="text-2xl mr-3">📊</span>
                            <div>
                                <p class="font-medium text-gray-900">View Ledger</p>
                                <p class="text-xs text-gray-600">Track accounts</p>
                            </div>
                        </a>
                        <a href="{{ route('customers.index') }}" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg border border-purple-200 transition">
                            <span class="text-2xl mr-3">👥</span>
                            <div>
                                <p class="font-medium text-gray-900">View Customers</p>
                                <p class="text-xs text-gray-600">See assigned list</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Collection Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Collection Summary</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">Today</span>
                            <span class="text-lg font-bold text-blue-600">₹{{ number_format($collectionsToday, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">This Week</span>
                            <span class="text-lg font-bold text-green-600">₹{{ number_format($recentCollections->whereBetween('created_at', [now()->subDays(7), now()])->sum('amount'), 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">This Month</span>
                            <span class="text-lg font-bold text-yellow-600">₹{{ number_format($collectionsThisMonth, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h4 class="font-semibold text-blue-900 mb-3">📋 How to Record Collections:</h4>
                    <ol class="text-sm text-blue-800 space-y-2 list-decimal list-inside">
                        <li>Click "New Collection" button</li>
                        <li>Select customer from assigned list</li>
                        <li>Enter collection amount</li>
                        <li>Select payment type (cash/cheque)</li>
                        <li>Assign receipt number</li>
                        <li>Save and receipt is recorded</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Collection Plans Section -->
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">📅 My Collection Plans</h2>
            </div>
            
            @if($activePlans->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Plan Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Plan Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Collection Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Items</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Expected Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Customers</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($activePlans as $plan)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $plan->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $plan->plan_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $plan->collection_type === 'regular' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ ucfirst($plan->collection_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $plan->items->count() }} items
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-blue-600">₹{{ number_format($plan->items->sum('expected_amount'), 2) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="space-y-1">
                                            @foreach($plan->items->take(3) as $item)
                                                <span class="block text-xs text-gray-700">• {{ $item->customer->name }}</span>
                                            @endforeach
                                            @if($plan->items->count() > 3)
                                                <span class="block text-xs text-gray-600 font-medium">+{{ $plan->items->count() - 3 }} more</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <p class="text-gray-600">No active collection plans assigned yet</p>
                    <p class="text-sm text-gray-500 mt-2">Contact your administrator to assign collection plans</p>
                </div>
            @endif
        </div>

        <!-- Assigned Customers Section -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Assigned Customers List -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">👥 Assigned Customers ({{ $assignedCustomers->count() }})</h2>
                </div>
                
                @if($assignedCustomers->count() > 0)
                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                        @foreach($assignedCustomers as $customer)
                            <div class="px-6 py-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $customer->phone }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $customer->address }}</p>
                                    </div>
                                    <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-8 text-center text-gray-600">
                        No assigned customers
                    </div>
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">⏰ Recent Activity</h2>
                </div>
                
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                    @forelse($recentCollections->take(10) as $collection)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $collection->customer->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $collection->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <p class="text-sm font-semibold text-green-600">₹{{ number_format($collection->amount, 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-600">
                            No recent collections
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Collector Dashboard</h1>
            <p class="text-gray-600 mt-2">Welcome back, {{ $collector->name }}!</p>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Collections Today -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-600 text-sm font-medium">Today's Collections</p>
                        <p class="text-2xl font-bold text-gray-900">₹{{ number_format($collectionsToday, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Collections This Month -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-600 text-sm font-medium">This Month</p>
                        <p class="text-2xl font-bold text-gray-900">₹{{ number_format($collectionsThisMonth, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Collections -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-yellow-500 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-600 text-sm font-medium">Total Collections</p>
                        <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalCollections, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Assigned Customers -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-600 text-sm font-medium">Assigned Customers</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $assignedCustomers->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Collections -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Collections</h2>
                    <a href="{{ route('collections.create') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Add Collection</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receipt #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentCollections as $collection)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-blue-600">{{ $collection->receipt_no }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $collection->customer->name }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600">₹{{ number_format($collection->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $collection->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('collections.show', $collection) }}" class="text-blue-600 hover:text-blue-700">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-600">No collections found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Active Collection Plans -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Active Plans</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($activePlans as $plan)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-medium text-gray-900">{{ $plan->name }}</p>
                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $plan->items->count() }} Items</span>
                            </div>
                            <p class="text-xs text-gray-600 mb-3">Plan Date: {{ $plan->plan_date->format('M d, Y') }}</p>
                            <div class="space-y-1">
                                @foreach($plan->items->take(3) as $item)
                                    <p class="text-xs text-gray-700">
                                        • {{ $item->customer->name }}
                                    </p>
                                @endforeach
                                @if($plan->items->count() > 3)
                                    <p class="text-xs text-gray-600 mt-2">+{{ $plan->items->count() - 3 }} more</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-600">No active plans</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('collections.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium text-center">
                ➕ Add Collection
            </a>
            <a href="{{ route('cheques.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium text-center">
                📄 Add Cheque
            </a>
            <a href="{{ route('customers.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium text-center">
                👥 View Customers
            </a>
        </div>
    </div>
</div>
@endsection
