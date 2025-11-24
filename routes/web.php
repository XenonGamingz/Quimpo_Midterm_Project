<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Backwards-compatibility: some packages or views expect a named `home` route.
// Provide `/home` that redirects to the dashboard to avoid RouteNotFound exceptions.
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';

// Library system routes are available in `routes/library.php`.
// Mount them non-destructively under `/library` and use route name prefix `library.`
require __DIR__.'/library.php';
