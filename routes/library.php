<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('library')
    ->name('library.')
    ->group(function () {
        Route::get('/', [BookController::class,'index'])->name('dashboard');

        Route::get('/books', [BookController::class,'index'])->name('books.index');
        Route::post('/books', [BookController::class,'store'])->name('books.store');
        Route::post('/books/{book}/borrow', [BookController::class,'borrow'])->name('books.borrow');
        Route::put('/books/{book}', [BookController::class,'update'])->name('books.update');
        Route::delete('/books/{book}', [BookController::class,'destroy'])->name('books.destroy');

        Route::get('/categories', [CategoryController::class,'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class,'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class,'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class,'destroy'])->name('categories.destroy');
    });
