@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('messages.dashboard') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('messages.system_overview') }}</p>
        </div>


        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Customers -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a9 9 0 0118 0"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5">
                        <a href="{{ route('customers.index') }}" class="block bg-white rounded-lg shadow p-5 hover:shadow-md">
                            <p class="text-gray-600 text-sm font-medium">{{ __('messages.total_customers') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalCustomers }}</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Collectors -->
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
                        <a href="{{ route('collectors.index') }}" class="block bg-white rounded-lg shadow p-5 hover:shadow-md">
                            <p class="text-gray-600 text-sm font-medium">{{ __('messages.total_collectors') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalCollectors }}</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Collections -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-yellow-500 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5">
                        <a href="{{ route('collections.index') }}" class="block bg-white rounded-lg shadow p-5 hover:shadow-md">
                            <p class="text-gray-600 text-sm font-medium">{{ __('messages.total_collections') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ __('messages.egp') }} {{ number_format($totalCollections, 2) }}</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pending Cheques -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-red-500 text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5">
                        <a href="{{ route('cheques.index') }}" class="block bg-white rounded-lg shadow p-5 hover:shadow-md">
                            <p class="text-gray-600 text-sm font-medium">{{ __('messages.pending_cheques') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $pendingCheques }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Collections -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.recent_collections') }}</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.receipt_no') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.customers') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.collector_name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.amount') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentCollections as $collection)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-blue-600">
                                        <a href="{{ route('collections.show', $collection) }}">
                                            {{ $collection->receipt_no }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $collection->customer->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $collection->collector->name }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600">{{ __('messages.egp') }} {{ number_format($collection->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $collection->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-600">{{ __('messages.no_collections_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Collectors -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.top_collectors') }}</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($topCollectors as $collector)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $collector->name }}</p>
                                <p class="text-xs text-gray-600">{{ $collector->phone }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ __('messages.egp') }} {{ number_format($collector->collections_sum_amount ?? 0, 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-center text-gray-600">{{ __('messages.no_data_available') }}</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Overdue Cheques Alert -->
        @if($overdueCheques->count() > 0)
            <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M6 20h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-red-800">{{ __('messages.overdue_cheques') }}</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p class="mb-3">{{ $overdueCheques->count() }} {{ __('messages.overdue_cheques') }} {{ __('messages.require_attention') ?? '' }}</p>
                            <ul class="space-y-1">
                                @foreach($overdueCheques as $cheque)
                                    <li>
                                        <a href="{{ route('cheques.show', $cheque) }}" class="underline hover:text-red-900">
                                            {{ $cheque->customer->name }} - {{ __('messages.egp') }} {{ number_format($cheque->amount, 2) }} ({{ __('messages.due_date') }}: {{ $cheque->due_date->format('M d, Y') }})
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Collection Plans Section -->
        <div class="mt-8 bg-white rounded-lg shadow">
            <a href="{{ route('collection-plan-items.index') }}" class="block bg-white rounded-lg shadow p-5 hover:shadow-md">
                <p class="text-sm text-gray-500">{{ __('messages.items') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalCollectionPlanItems ?? 0 }}</p>
            </a>
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.active_collection_plans') }}</h2>
                <a href="{{ route('collection-plans.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded">
                    ➕ {{ __('messages.new_plan') }}
                </a>
            </div>

            @if($activePlans->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ __('messages.plan_name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ __('messages.collector_name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ __('messages.plan_date') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ __('messages.type') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ __('messages.items') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ __('messages.expected_amount') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($activePlans as $plan)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $plan->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $plan->collector->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $plan->date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $plan->collection_type === 'regular' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ ucfirst($plan->collection_type ?? 'regular') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $plan->items->count() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-blue-600">{{ __('messages.egp') }} {{ number_format($plan->getTotalExpectedAmount(), 2) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('collection-plans.show', $plan) }}" class="text-blue-600 hover:text-blue-800 font-medium">{{ __('messages.view') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-8 text-center text-gray-600">
                    <p>{{ __('messages.no_active_plans') }}</p>
                    <a href="{{ route('collection-plans.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">{{ __('messages.create_new_plan') }} →</a>
                </div>
            @endif
        </div>

        <!-- Users Management Grid -->
        <div class="mt-12">
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('admin.tables.show', 'users') }}" class="block bg-white rounded-lg shadow p-5 hover:shadow-md">
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.users_management') }}</h2>
                    <span class="text-sm text-gray-600">{{ __('messages.total_users') }}: {{ $totalUsers }}</span>
                </a>
            </div>

            @if ($users->count() > 0)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">{{ __('messages.name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">{{ __('messages.email') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">{{ __('messages.roles') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">{{ __('messages.status') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($user->roles->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($user->roles as $role)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                        @if($role->name === 'admin') bg-red-100 text-red-800
                                                        @elseif($role->name === 'collector') bg-blue-100 text-blue-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucfirst($role->name) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-500 text-xs">{{ __('messages.no_roles_assigned') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('messages.active') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm space-x-2">
                                        <button onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}')" class="text-blue-600 hover:text-blue-800 font-medium">{{ __('messages.edit_roles') }}</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white rounded-lg shadow px-6 py-8 text-center text-gray-600">
                    <p>{{ __('messages.no_users_found') }}</p>
                </div>
            @endif
        </div>

        <!-- Role Management Modal -->
        <div id="roleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('messages.assign_roles_to') }} <span id="userName"></span></h3>
                </div>
                <form id="roleForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-4 space-y-3">
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="roles[]" value="admin" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">{{ __('messages.admin') }}</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="roles[]" value="collector" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">{{ __('messages.collector') }}</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="roles[]" value="user" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">{{ __('messages.user') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-2 rounded-b-lg">
                        <button type="button" onclick="closeRoleModal()" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded">{{ __('messages.assign_roles') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openRoleModal(userId, userName) {
                document.getElementById('userName').textContent = userName;
                const form = document.getElementById('roleForm');
                form.action = `/users/${userId}/roles`;
                document.getElementById('roleModal').classList.remove('hidden');
            }

            function closeRoleModal() {
                document.getElementById('roleModal').classList.add('hidden');
            }
        </script>
    </div>
</div>
@endsection
