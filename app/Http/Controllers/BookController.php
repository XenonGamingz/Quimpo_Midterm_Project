<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Book::with('category');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->latest()->paginate(10);
        $categories = Category::orderBy('name')->get();

        $totalBooks = Book::count();
        $totalCategories = Category::count();
        $totalBorrowed = Borrowing::count();

        return view('library.dashboard', compact('books','categories','totalBooks','totalCategories','totalBorrowed'));
    }

    public function borrow(Request $request, Book $book)
    {
        // create a borrow record
        Borrowing::create([
            'book_id' => $book->id,
            'user_id' => auth()->id(),
            'borrowed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Book borrowed successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn',
            'year' => 'nullable|integer',
            'category_id' => 'nullable|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('books', 'public');
        }

        Book::create($data);

        return redirect()->back()->with('success','Book added successfully.');
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'isbn' => "nullable|string|unique:books,isbn,{$book->id}",
            'year' => 'nullable|integer',
            'category_id' => 'nullable|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($book->photo) {
                Storage::disk('public')->delete($book->photo);
            }
            $data['photo'] = $request->file('photo')->store('books', 'public');
        }

        $book->update($data);

        return redirect()->back()->with('success','Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->back()->with('success','Book moved to trash successfully.');
    }

    public function trash()
    {
        $books = Book::onlyTrashed()->with('category')->latest()->paginate(10);
        return view('library.books_trash', compact('books'));
    }

    public function restore($id)
    {
        $book = Book::withTrashed()->findOrFail($id);
        $book->restore();
        return redirect()->back()->with('success','Book restored successfully.');
    }

    public function forceDelete($id)
    {
        $book = Book::withTrashed()->findOrFail($id);
        // Delete photo
        if ($book->photo) {
            Storage::disk('public')->delete($book->photo);
        }
        $book->forceDelete();
        return redirect()->back()->with('success','Book permanently deleted.');
    }

    public function exportPdf(Request $request)
    {
        $query = Book::with('category');

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->get();

        $content = view('library.books_pdf', compact('books'))->render();
        $filename = 'books_' . now()->format('Y-m-d_H-i-s') . '.html';
        return response($content)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
