<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', static function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', static function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(MovieController::class)->group(function () {
        Route::get('/movies', 'index')->name('movies');
        Route::get('/movies/create', 'create')->name('movies.create');
        Route::get('/movies/{movie}', 'show')->name('movies.show');
        Route::get('/movies/{movie}/edit', 'edit')->name('movies.edit');
    });
});


require __DIR__.'/auth.php';
