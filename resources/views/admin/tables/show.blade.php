@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
            <a href="{{ url()->previous() }}" class="text-sm text-blue-600">← Back</a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @forelse($columns as $col)
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">{{ ucwords(str_replace(['_','-'], ' ', $col)) }}</th>
                            @empty
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">No columns</th>
                            @endforelse
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($rows as $row)
                            <tr class="hover:bg-gray-50">
                                @foreach($columns as $col)
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        @php
                                            $value = data_get($row, $col);

                                            // Prepare a display-friendly string for various value types
                                            if ($value instanceof \Illuminate\Support\Carbon) {
                                                $display = $value->format('M d, Y H:i');
                                            } elseif (is_object($value)) {
                                                // Eloquent models / relations
                                                if (isset($value->name)) {
                                                    $display = $value->name;
                                                } elseif (isset($value->title)) {
                                                    $display = $value->title;
                                                } elseif (isset($value->id)) {
                                                    $display = $value->id;
                                                } elseif (method_exists($value, 'toArray')) {
                                                    $arr = $value->toArray();
                                                    $display = json_encode(array_slice($arr, 0, 3));
                                                } else {
                                                    $display = (string) $value;
                                                }
                                            } elseif (is_array($value)) {
                                                if (isset($value['name'])) {
                                                    $display = $value['name'];
                                                } elseif (isset($value['title'])) {
                                                    $display = $value['title'];
                                                } elseif (isset($value['id'])) {
                                                    $display = $value['id'];
                                                } else {
                                                    $display = json_encode(array_slice($value, 0, 3));
                                                }
                                            } else {
                                                $display = $value;
                                            }
                                        @endphp

                                        {{ $display }}
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 text-sm">
                                    {{-- Attempt to render resource show/edit/delete links if route exists --}}
                                    @php
                                        $showRoute = false;
                                        $editRoute = false;
                                        $deleteRoute = false;
                                        try {
                                            $showRoute = \Illuminate\Support\Facades\Route::has($table . '.show');
                                            $editRoute = \Illuminate\Support\Facades\Route::has($table . '.edit');
                                        } catch (\Exception $e) {
                                            $showRoute = false;
                                            $editRoute = false;
                                        }
                                    @endphp

                                    @if($showRoute)
                                        <a href="{{ route($table . '.show', $row->id ?? $row) }}" class="text-blue-600 hover:text-blue-800 mr-2">View</a>
                                    @endif

                                    @if($editRoute)
                                        <a href="{{ route($table . '.edit', $row->id ?? $row) }}" class="text-green-600 hover:text-green-800 mr-2">Edit</a>
                                    @endif

                                    @if(auth()->user()->hasRole('admin') && $showRoute)
                                        <form action="{{ route($table . '.destroy', $row->id ?? $row) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ max(1, count($columns) + 1) }}" class="px-6 py-8 text-center text-gray-600">No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
