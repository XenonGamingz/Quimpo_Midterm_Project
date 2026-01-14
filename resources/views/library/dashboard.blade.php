@extends('layouts.app')

@section('content')
<div x-data="{ editModal:false, editId:null }" class="space-y-6">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="p-4 bg-white rounded shadow">
      <div class="text-sm text-gray-500">Total Books</div>
      <div class="text-2xl font-bold">{{ $totalBooks }}</div>
    </div>
    <div class="p-4 bg-white rounded shadow">
      <div class="text-sm text-gray-500">Total Categories</div>
      <div class="text-2xl font-bold">{{ $totalCategories }}</div>
    </div>
    <div class="p-4 bg-white rounded shadow">
      <div class="text-sm text-gray-500">Borrows</div>
      <div class="text-2xl font-bold">{{ $totalBorrowed ?? 0 }}</div>
    </div>
  </div>

  <!-- Search and Filter -->
  <div class="bg-white p-4 rounded shadow mb-6">
    <form method="GET" action="{{ route('library.dashboard') }}" class="flex flex-col sm:flex-row gap-3">
      <input name="search" value="{{ request('search') }}" placeholder="Search by title, author, ISBN" class="flex-1 rounded border p-2" />
      <select name="category_id" class="rounded border p-2">
        <option value="">All Categories</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
        @endforeach
      </select>
      <button class="px-4 py-2 bg-blue-600 text-white rounded">Search</button>
      <a href="{{ route('library.dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded">Clear</a>
      <a href="{{ route('library.books.export-pdf', request()->query()) }}" class="px-4 py-2 bg-green-600 text-white rounded">Export PDF</a>
    </form>
  </div>

  <!-- Add New Book Form -->
  <div class="bg-white p-4 rounded shadow mb-6">
    <form method="POST" action="{{ route('library.books.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        <div>
          <label class="text-sm">Title*</label>
          <input name="title" value="{{ old('title') }}" class="w-full mt-1 rounded border p-2" />
          @error('title')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="text-sm">Author</label>
          <input name="author" value="{{ old('author') }}" class="w-full mt-1 rounded border p-2" />
          @error('author')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="text-sm">ISBN</label>
          <input name="isbn" value="{{ old('isbn') }}" class="w-full mt-1 rounded border p-2" />
          @error('isbn')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="text-sm">Year</label>
          <input name="year" value="{{ old('year') }}" type="number" class="w-full mt-1 rounded border p-2" />
          @error('year')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="text-sm">Category</label>
          <select name="category_id" class="w-full mt-1 rounded border p-2">
            <option value="">-- None --</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
          </select>
          @error('category_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="text-sm">Cover Photo</label>
          <input name="photo" type="file" accept="image/*" class="w-full mt-1 rounded border p-2" />
          @error('photo')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="mt-3 flex gap-2">
        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Add Book</button>
      </div>
    </form>
  </div>

  <!-- Books Table -->
  <div class="bg-white p-4 rounded shadow">
    <!-- Desktop Table View -->
    <div class="hidden md:block">
      <div class="overflow-x-auto">
        <table class="w-full table-auto min-w-[640px]">
          <thead>
            <tr class="text-left">
              <th class="p-2">#</th>
              <th class="p-2">Cover</th>
              <th class="p-2">Title</th>
              <th class="p-2">Author</th>
              <th class="p-2">Category</th>
              <th class="p-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($books as $book)
            <tr class="border-t hover:bg-gray-50">
              <td class="p-2 align-top">{{ $book->id }}</td>
              <td class="p-2 align-top">
                @if($book->photo)
                  <img src="{{ asset('storage/' . $book->photo) }}" alt="Cover" class="w-12 h-16 object-cover rounded">
                @else
                  <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Image</div>
                @endif
              </td>
              <td class="p-2 align-top">{{ $book->title }}</td>
              <td class="p-2 align-top">{{ $book->author ?? 'N/A' }}</td>
              <td class="p-2 align-top">{{ $book->category->name ?? 'N/A' }}</td>
              <td class="p-2 align-top">
                <div class="flex flex-wrap items-center gap-2">
                  <button @click="editModal=true; editId={{ $book->id }}" class="px-3 py-1 rounded border text-sm">Edit</button>

                  <form method="POST" action="{{ route('library.books.destroy', $book) }}" class="inline" onsubmit="return confirm('Delete book?');">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1 rounded border text-red-600 text-sm">Delete</button>
                  </form>

                  <form method="POST" action="{{ route('library.books.borrow', $book) }}" class="inline">
                    @csrf
                    <button class="px-3 py-1 rounded border text-indigo-600 text-sm">Borrow</button>
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
      @foreach($books as $book)
      <div class="border rounded p-4 bg-gray-50">
        <div class="flex gap-3">
          <div class="flex-shrink-0">
            @if($book->photo)
              <img src="{{ asset('storage/' . $book->photo) }}" alt="Cover" class="w-16 h-20 object-cover rounded">
            @else
              <div class="w-16 h-20 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Image</div>
            @endif
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-lg">{{ $book->title }}</h3>
            <p class="text-sm text-gray-600">Author: {{ $book->author ?? 'N/A' }}</p>
            <p class="text-sm text-gray-600">Category: {{ $book->category->name ?? 'N/A' }}</p>
            <p class="text-sm text-gray-600">ISBN: {{ $book->isbn ?? 'N/A' }}</p>
            <p class="text-sm text-gray-600">Year: {{ $book->year ?? 'N/A' }}</p>
            <div class="mt-2 flex flex-wrap gap-2">
              <button @click="editModal=true; editId={{ $book->id }}" class="px-3 py-1 rounded border text-sm bg-blue-500 text-white">Edit</button>
              <form method="POST" action="{{ route('library.books.destroy', $book) }}" class="inline" onsubmit="return confirm('Delete book?');">
                @csrf
                @method('DELETE')
                <button class="px-3 py-1 rounded border text-red-600 text-sm">Delete</button>
              </form>
              <form method="POST" action="{{ route('library.books.borrow', $book) }}" class="inline">
                @csrf
                <button class="px-3 py-1 rounded border text-indigo-600 text-sm">Borrow</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div class="mt-4">{{ $books->links() }}</div>
  </div>

  <!-- Edit Modal (simple implementation) -->
  <template x-if="editModal">
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
      <div class="bg-white p-6 rounded w-full max-w-3xl">
        <h3 class="font-semibold mb-2">Edit Book</h3>

        <form :action="`/library/books/${editId}`" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
              <label>Title</label>
              <input name="title" x-bind:value="document.querySelector(`[data-b-${editId}-title]`)?.value || ''" class="w-full mt-1 rounded border p-2" />
            </div>
            <div>
              <label>Author</label>
              <input name="author" x-bind:value="document.querySelector(`[data-b-${editId}-author]`)?.value || ''" class="w-full mt-1 rounded border p-2" />
            </div>
            <div>
              <label>ISBN</label>
              <input name="isbn" x-bind:value="document.querySelector(`[data-b-${editId}-isbn]`)?.value || ''" class="w-full mt-1 rounded border p-2" />
            </div>
            <div>
              <label>Year</label>
              <input name="year" type="number" x-bind:value="document.querySelector(`[data-b-${editId}-year]`)?.value || ''" class="w-full mt-1 rounded border p-2" />
            </div>
            <div>
              <label>Category</label>
              <select name="category_id" class="w-full mt-1 rounded border p-2">
                <option value="">-- None --</option>
                @foreach($categories as $c)
                  <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label>Cover Photo</label>
              <input name="photo" type="file" accept="image/*" class="w-full mt-1 rounded border p-2" />
            </div>
          </div>

          <div class="mt-3 flex justify-end gap-2">
            <button type="button" @click="editModal=false" class="px-4 py-2 border rounded">Cancel</button>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
          </div>
        </form>
      </div>
    </div>
  </template>
</div>

<!-- Hidden pre-filled inputs for modal population (a simple trick) -->
@foreach($books as $b)
  <input type="hidden" data-b-{{ $b->id }}-title value="{{ $b->title }}" />
  <input type="hidden" data-b-{{ $b->id }}-author value="{{ $b->author }}" />
  <input type="hidden" data-b-{{ $b->id }}-isbn value="{{ $b->isbn }}" />
  <input type="hidden" data-b-{{ $b->id }}-year value="{{ $b->year }}" />
@endforeach

@endsection
