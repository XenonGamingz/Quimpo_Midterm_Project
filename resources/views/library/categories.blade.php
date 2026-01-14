@extends('layouts.app')

@section('content')
<div x-data="{ editModal:false, editId:null }" class="space-y-6">
  <div class="bg-white p-4 rounded shadow mb-6">
    <form method="POST" action="{{ route('library.categories.store') }}">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
          <label>Name*</label>
          <input name="name" class="w-full mt-1 rounded border p-2" value="{{ old('name') }}" />
          @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
          <label>Description</label>
          <input name="description" class="w-full mt-1 rounded border p-2" value="{{ old('description') }}" />
        </div>
      </div>
      <div class="mt-3">
        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Add Category</button>
      </div>
    </form>
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
              <th class="p-2">Books</th>
              <th class="p-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($categories as $cat)
            <tr class="border-t hover:bg-gray-50">
              <td class="p-2 align-top">{{ $cat->id }}</td>
              <td class="p-2 align-top">{{ $cat->name }}</td>
              <td class="p-2 align-top">{{ $cat->books_count }} books</td>
              <td class="p-2 align-top">
                <div class="flex gap-2">
                  <button @click="editModal=true; editId={{ $cat->id }}" class="px-3 py-1 rounded border text-sm">Edit</button>
                  <form method="POST" action="{{ route('library.categories.destroy', $cat) }}" class="inline" onsubmit="return confirm('Delete category? This will set category_id on books to null.');">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1 rounded border text-red-600 text-sm">Delete</button>
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
        <p class="text-sm text-gray-600">Books: {{ $cat->books_count }}</p>
        <p class="text-sm text-gray-600">Description: {{ $cat->description ?? 'N/A' }}</p>
        <div class="mt-2 flex gap-2">
          <button @click="editModal=true; editId={{ $cat->id }}" class="px-3 py-1 rounded border text-sm bg-blue-500 text-white">Edit</button>
          <form method="POST" action="{{ route('library.categories.destroy', $cat) }}" class="inline" onsubmit="return confirm('Delete category? This will set category_id on books to null.');">
            @csrf
            @method('DELETE')
            <button class="px-3 py-1 rounded border text-red-600 text-sm">Delete</button>
          </form>
        </div>
      </div>
      @endforeach
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>
  </div>

  <!-- Simple Edit Modal for Category -->
  <template x-if="editModal">
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
      <div class="bg-white p-4 rounded w-full max-w-md">
        <h3 class="font-semibold mb-2">Edit Category</h3>
        <form :action="`/library/categories/${editId}`" method="POST">
          @csrf
          @method('PUT')
          <div>
            <label>Name</label>
            <input name="name" x-bind:value="document.querySelector(`[data-cat-${editId}-name]`)?.value || ''" class="w-full mt-1 rounded border p-2" />
          </div>
          <div class="mt-2">
            <label>Description</label>
            <input name="description" x-bind:value="document.querySelector(`[data-cat-${editId}-desc]`)?.value || ''" class="w-full mt-1 rounded border p-2" />
          </div>
          <div class="mt-3 flex justify-end gap-2">
            <button type="button" @click="editModal=false" class="px-4 py-2 border rounded">Cancel</button>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
          </div>
        </form>
      </div>
    </div>
  </template>

  @foreach($categories as $c)
    <input type="hidden" data-cat-{{ $c->id }}-name value="{{ $c->name }}" />
    <input type="hidden" data-cat-{{ $c->id }}-desc value="{{ $c->description }}" />
  @endforeach
</div>
@endsection
