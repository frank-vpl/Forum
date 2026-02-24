<?php

use App\Http\Controllers\Auth\EmailChangeController;
use App\Http\Controllers\Auth\GoogleOAuthController;
use App\Http\Controllers\Auth\XOAuthController;
use App\Http\Controllers\UserBlockController;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    $stats = [
        'users' => User::count(),
        'posts' => Post::count(),
        'comments' => Comment::count(),
    ];

    return view('home', compact('stats'));
});

Route::get('/home', function () {
    $stats = [
        'users' => User::count(),
        'posts' => Post::count(),
        'comments' => Comment::count(),
    ];

    return view('home', compact('stats'));
})->name('home');

Route::view('dashboard', 'dashboard')->name('dashboard');

// Notifications
Route::view('/notifications', 'pages.notifications-container')
    ->middleware(['auth', ...when(config('auth.require_email_verification'), ['verified'], []), 'not.banned'])
    ->name('notifications.index');

// Forum: create new post
Route::view('/new', 'pages.forum.new-container')
    ->middleware(['auth', ...when(config('auth.require_email_verification'), ['verified'], []), 'not.banned'])
    ->name('forum.new');

// Forum: show post
Route::get('/forum/{id}', function (int $id) {
    return view('pages.forum.show-container', ['id' => $id]);
})->name('forum.show');

// User profile and posts
Route::get('/user/{id}', function (int $id) {
    return view('pages.users.profile-container', ['id' => $id]);
})->name('user.show');

Route::post('/user/{id}/block', [UserBlockController::class, 'block'])
    ->middleware(['auth', ...when(config('auth.require_email_verification'), ['verified'], []), 'not.banned'])
    ->name('user.block');

Route::delete('/user/{id}/block', [UserBlockController::class, 'unblock'])
    ->middleware(['auth', ...when(config('auth.require_email_verification'), ['verified'], []), 'not.banned'])
    ->name('user.unblock');

// Public users directory page
Route::get('/users', function () {
    return view('pages.users.users-list-container');
})->name('users.index');

// Blocked users page
Route::get('/blocks', function () {
    return view('pages.users.blocked-users-container');
})->middleware(['auth', ...when(config('auth.require_email_verification'), ['verified'], []), 'not.banned'])->name('users.blocks');

// Premium
Route::view('/premium', 'pages.premium-container')->name('premium.index');

// Google OAuth
Route::get('/auth/google', [GoogleOAuthController::class, 'redirect'])->name('oauth.google.redirect');
Route::get('/callback/google', [GoogleOAuthController::class, 'callback'])->name('oauth.google.callback');

// X OAuth
Route::get('/redirect/x', [XOAuthController::class, 'redirect'])->name('oauth.x.redirect');
Route::get('/callback/x', [XOAuthController::class, 'callback'])->name('oauth.x.callback');

// Email change verification
Route::get('/email/change/verify', [EmailChangeController::class, 'verify'])->name('email.change.verify');

// About
Route::view('/about', 'about')->name('about');

// Privacy
Route::view('/privacy', 'privacy')->name('privacy');

// Terms
Route::view('/terms', 'terms')->name('terms');

// FAQ
Route::view('/faq', 'faq')->name('faq');

require __DIR__.'/settings.php';

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
