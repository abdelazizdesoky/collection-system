@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Create User</h1>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full border rounded px-3 py-2">
                    @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full border rounded px-3 py-2">
                    @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" class="mt-1 block w-full border rounded px-3 py-2">
                    @error('password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="mt-1 block w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Roles</label>
                    <div class="mt-2 space-x-2">
                        @foreach($roles as $role)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="form-checkbox">
                                <span class="ml-2">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">Create User</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
