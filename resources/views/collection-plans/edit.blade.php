@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Collection Plan</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('collection-plans.update', $collectionPlan) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Plan Name *</label>
                <input type="text" name="name" value="{{ old('name', $collectionPlan->name) }}" placeholder="e.g., Weekly Cash Collection" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Collector *</label>
                <select name="collector_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach ($collectors as $collector)
                        <option value="{{ $collector->id }}" {{ old('collector_id', $collectionPlan->collector_id) == $collector->id ? 'selected' : '' }}>{{ $collector->name }}</option>
                    @endforeach
                </select>
                @error('collector_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Plan Date *</label>
                <input type="date" name="plan_date" value="{{ old('plan_date', $collectionPlan->date->toDateString()) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('plan_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Collection Type *</label>
                <select name="collection_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="regular" {{ old('collection_type', $collectionPlan->collection_type) == 'regular' ? 'selected' : '' }}>Regular</option>
                    <option value="special" {{ old('collection_type', $collectionPlan->collection_type) == 'special' ? 'selected' : '' }}>Special</option>
                </select>
                @error('collection_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Additional Notes (Optional)</label>
                <input type="text" name="type" value="{{ old('type', $collectionPlan->type) }}" placeholder="e.g., Cash collection from showroom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                <a href="{{ route('collection-plans.show', $collectionPlan) }}" class="flex-1 bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-center">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
