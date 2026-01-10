@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">View User</h1>
                <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-900 font-medium">Back</a>
            </div>

            <div class="p-6 space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <p class="text-gray-900">{{ $user->name }}</p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>

                <!-- Roles -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Roles</label>
                    <div class="flex flex-wrap gap-2">
                        @forelse($user->roles as $role)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($role->name) }}
                            </span>
                        @empty
                            <span class="text-gray-500">No roles assigned</span>
                        @endforelse
                    </div>
                </div>

                <!-- Collector -->
                @if($user->collector)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Collector</label>
                        <p class="text-gray-900">{{ $user->collector->name }}</p>
                    </div>
                @endif

                <!-- Created At -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Created At</label>
                    <p class="text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</p>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Edit
                    </a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
