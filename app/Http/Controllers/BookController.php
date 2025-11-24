<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $books = Book::with('category')->latest()->paginate(10);
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
        ]);

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
        ]);

        $book->update($data);

        return redirect()->back()->with('success','Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->back()->with('success','Book deleted successfully.');
    }
}
