@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Welcome to Collection System</h1>
            <p class="text-gray-600 mt-2">Hello, {{ auth()->user()->name }}!</p>
        </div>

        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-8 text-white mb-8">
            <h2 class="text-2xl font-bold mb-2">Collection Management System</h2>
            <p class="text-blue-100 mb-4">Track customers, manage payments, and organize collection activities efficiently.</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('customers.index') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded font-medium">
                    View Customers
                </a>
                <a href="{{ route('collections.index') }}" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded font-medium border border-blue-400">
                    View Collections
                </a>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Feature 1: Customer Management -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0"></path>
                        </svg>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Customer Management</h3>
                </div>
                <p class="text-gray-600 mb-4">Manage customer profiles, track balances, and view transaction history.</p>
                <a href="{{ route('customers.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    Go to Customers →
                </a>
            </div>

            <!-- Feature 2: Collection Tracking -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-100 text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Collections</h3>
                </div>
                <p class="text-gray-600 mb-4">Record payment collections, track receipts, and maintain ledger entries.</p>
                <a href="{{ route('collections.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    Go to Collections →
                </a>
            </div>

            <!-- Feature 3: Financial Overview -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-yellow-100 text-yellow-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Financial Reports</h3>
                </div>
                <p class="text-gray-600 mb-4">View detailed ledgers, balance summaries, and customer account statements.</p>
                <a href="{{ route('customer-accounts.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                    Go to Accounts →
                </a>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('customers.create') }}" class="flex items-center p-3 hover:bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-xl mr-2">➕</span>
                    <span class="text-sm font-medium">New Customer</span>
                </a>
                <a href="{{ route('collections.create') }}" class="flex items-center p-3 hover:bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-xl mr-2">💰</span>
                    <span class="text-sm font-medium">New Collection</span>
                </a>
                <a href="{{ route('cheques.create') }}" class="flex items-center p-3 hover:bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-xl mr-2">📄</span>
                    <span class="text-sm font-medium">New Cheque</span>
                </a>
                <a href="{{ route('collectors.index') }}" class="flex items-center p-3 hover:bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-xl mr-2">👥</span>
                    <span class="text-sm font-medium">Collectors</span>
                </a>
            </div>
        </div>

        <!-- User Info Card -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-blue-900">About You</h4>
                    <p class="text-blue-800 mt-2">
                        <strong>Name:</strong> {{ auth()->user()->name }} <br>
                        <strong>Email:</strong> {{ auth()->user()->email }} <br>
                        @if(auth()->user()->collector)
                            <strong>Role:</strong> Collector - {{ auth()->user()->collector->name }}
                        @else
                            <strong>Role:</strong> {{ implode(', ', auth()->user()->getRoleNames()->toArray()) ?: 'User' }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
