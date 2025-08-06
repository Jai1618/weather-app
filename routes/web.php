<?php
use Illuminate\Support\Facades\Route;

// Redirect root to login page if not logged in
Route::get('/', function () {
    return redirect()->route('login');
});

// The main dashboard view. Auth middleware from Breeze handles protection.
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Include the default auth routes from Breeze
require __DIR__.'/auth.php';