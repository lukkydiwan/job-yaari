<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', [BlogController::class, 'index'])->name('home');
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{slug}', [BlogController::class, 'show'])->name('blogs.show');

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

        // Blog CRUD
        Route::resource('blogs', AdminBlogController::class)->except(['show']);

        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
});

// TEMP DEBUG
Route::get('/debug-session', function() {
    return response()->json([
        'session_id' => session()->getId(),
        'auth_check' => auth()->check(),
        'auth_user' => auth()->user()?->email,
        'session_all' => session()->all(),
        'is_https' => request()->isSecure(),
        'forwarded_proto' => request()->header('X-Forwarded-Proto'),
        'cookie_header' => request()->header('Cookie'),
    ]);
});

Route::get('/debug-login', function() {
    Auth::loginUsingId(1);
    session(['test' => 'hello']);
    session()->save();
    return response()->json([
        'logged_in' => auth()->check(),
        'session_id' => session()->getId(),
        'redirect_to' => route('admin.dashboard'),
    ]);
});


