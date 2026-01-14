@extends('layouts.app')

@section('content')
<div class="space-y-6">
  <div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold">Trashed Categories</h1>
    <a href="{{ route('library.categories.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded">Back to Categories</a>
  </div>

  <div class="bg-white p-4 rounded shadow">
    <!-- Desktop Table View -->
    <div class="hidden md:block">
      <div class="overflow-x-auto">
        <table class="w-full table-auto min-w-[640px]">
          <thead>
            <tr class="text-left">
              <th class="p-2">#</th>
              <th class="p-2">Name</th>
              <th class="p-2">Description</th>
              <th class="p-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($categories as $cat)
            <tr class="border-t hover:bg-gray-50">
              <td class="p-2 align-top">{{ $cat->id }}</td>
              <td class="p-2 align-top">{{ $cat->name }}</td>
              <td class="p-2 align-top">{{ $cat->description ?? 'N/A' }}</td>
              <td class="p-2 align-top">
                <div class="flex gap-2">
                  <form method="POST" action="{{ route('library.categories.restore', $cat) }}" class="inline">
                    @csrf
                    @method('POST')
                    <button class="px-3 py-1 rounded border text-green-600 text-sm" onclick="return confirm('Restore this category?');">Restore</button>
                  </form>
                  <form method="POST" action="{{ route('library.categories.force-delete', $cat) }}" class="inline" onsubmit="return confirm('Permanently delete this category? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1 rounded border text-red-600 text-sm">Delete Forever</button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4">
      @foreach($categories as $cat)
      <div class="border rounded p-4 bg-gray-50">
        <h3 class="font-semibold text-lg">{{ $cat->name }}</h3>
        <p class="text-sm text-gray-600">Description: {{ $cat->description ?? 'N/A' }}</p>
        <div class="mt-2 flex gap-2">
          <form method="POST" action="{{ route('library.categories.restore', $cat) }}" class="inline">
            @csrf
            @method('POST')
            <button class="px-3 py-1 rounded border text-green-600 text-sm" onclick="return confirm('Restore this category?');">Restore</button>
          </form>
          <form method="POST" action="{{ route('library.categories.force-delete', $cat) }}" class="inline" onsubmit="return confirm('Permanently delete this category? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button class="px-3 py-1 rounded border text-red-600 text-sm">Delete Forever</button>
          </form>
        </div>
      </div>
      @endforeach
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>
  </div>
</div>
@endsection