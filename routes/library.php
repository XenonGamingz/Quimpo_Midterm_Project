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
        Route::get('/books/trash', [BookController::class,'trash'])->name('books.trash');
        Route::post('/books/{book}/restore', [BookController::class,'restore'])->name('books.restore');
        Route::get('/books/{id}/restore', [BookController::class,'restore'])->name('books.restore.get');
        Route::delete('/books/{book}/force-delete', [BookController::class,'forceDelete'])->name('books.force-delete');
        Route::get('/books/{id}/force-delete', [BookController::class,'forceDelete'])->name('books.force-delete.get');
        Route::get('/books/export-pdf', [BookController::class,'exportPdf'])->name('books.export-pdf');

        Route::get('/categories', [CategoryController::class,'index'])->name('categories.index');
        Route::get('/categories/trash', [CategoryController::class,'trash'])->name('categories.trash');
        Route::post('/categories/{category}/restore', [CategoryController::class,'restore'])->name('categories.restore');
        Route::get('/categories/{id}/restore', [CategoryController::class,'restore'])->name('categories.restore.get');
        Route::delete('/categories/{category}/force-delete', [CategoryController::class,'forceDelete'])->name('categories.force-delete');
        Route::get('/categories/{id}/force-delete', [CategoryController::class,'forceDelete'])->name('categories.force-delete.get');
        Route::post('/categories', [CategoryController::class,'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class,'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class,'destroy'])->name('categories.destroy');
    });
