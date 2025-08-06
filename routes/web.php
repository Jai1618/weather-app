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

Route::get('/test-broadcast', function() {
    // We'll send a notification to user with ID 1 (Super Admin)
    event(new App\Events\RealTimeNotification('This is a test notification!', 'info', 1));
    return 'Event broadcasted!';
});
// Include the default auth routes from Breeze
require __DIR__.'/auth.php';