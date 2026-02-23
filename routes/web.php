<?php

use App\Http\Controllers\Auth\GoogleOAuthController;
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

// Public users directory page
Route::get('/users', function () {
    return view('pages.users.users-list-container');
})->name('users.index');

// Premium
Route::view('/premium', 'pages.premium-container')->name('premium.index');

// Google OAuth
Route::get('/auth/google', [GoogleOAuthController::class, 'redirect'])->name('oauth.google.redirect');
Route::get('/callback/google', [GoogleOAuthController::class, 'callback'])->name('oauth.google.callback');

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
