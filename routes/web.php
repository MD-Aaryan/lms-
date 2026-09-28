<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CirculationController;
use App\Http\Controllers\MyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // "/" sends each role to its own home page.
    Route::get('/', fn () => redirect()->route(auth()->user()->isAdmin() ? 'books.index' : 'my'));

    Route::get('/my', [MyController::class, 'index'])->name('my');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('books', BookController::class)->except('show');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/circulation', [CirculationController::class, 'index'])->name('circulation.index');
    Route::post('/circulation', [CirculationController::class, 'store'])->name('circulation.store');
    Route::post('/circulation/{issue}/return', [CirculationController::class, 'returnBook'])->name('circulation.return');
});

require __DIR__.'/auth.php';
