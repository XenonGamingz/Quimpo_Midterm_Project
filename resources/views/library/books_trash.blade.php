@extends('layouts.app')

@section('content')
<div class="space-y-6">
  <div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold">Trashed Books</h1>
    <a href="{{ route('library.dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded">Back to Books</a>
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
              <td class="p-2 align-top">{{ $book->title }}</td>
              <td class="p-2 align-top">{{ $book->author ?? 'N/A' }}</td>
              <td class="p-2 align-top">{{ $book->category->name ?? 'N/A' }}</td>
              <td class="p-2 align-top">
                <div class="flex flex-wrap items-center gap-2">
                  <form method="POST" action="{{ route('library.books.restore', $book) }}" class="inline">
                    @csrf
                    @method('POST')
                    <button class="px-3 py-1 rounded border text-green-600 text-sm" onclick="return confirm('Restore this book?');">Restore</button>
                  </form>

                  <form method="POST" action="{{ route('library.books.force-delete', $book) }}" class="inline" onsubmit="return confirm('Permanently delete this book? This action cannot be undone.');">
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
      @foreach($books as $book)
      <div class="border rounded p-4 bg-gray-50">
        <h3 class="font-semibold text-lg">{{ $book->title }}</h3>
        <p class="text-sm text-gray-600">Author: {{ $book->author ?? 'N/A' }}</p>
        <p class="text-sm text-gray-600">Category: {{ $book->category->name ?? 'N/A' }}</p>
        <div class="mt-2 flex gap-2">
          <form method="POST" action="{{ route('library.books.restore', $book) }}" class="inline">
            @csrf
            @method('POST')
            <button class="px-3 py-1 rounded border text-green-600 text-sm" onclick="return confirm('Restore this book?');">Restore</button>
          </form>
          <form method="POST" action="{{ route('library.books.force-delete', $book) }}" class="inline" onsubmit="return confirm('Permanently delete this book? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button class="px-3 py-1 rounded border text-red-600 text-sm">Delete Forever</button>
          </form>
        </div>
      </div>
      @endforeach
    </div>

    <div class="mt-4">{{ $books->links() }}</div>
  </div>
</div>
@endsection