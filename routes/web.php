<?php

use App\Http\Controllers\Auth\GoogleOAuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('home');
})->name('home');

Route::view('dashboard', 'dashboard')->name('dashboard');

// Notifications
Route::view('/notifications', 'pages.notifications-container')
    ->middleware(['auth', 'verified', 'not.banned'])
    ->name('notifications.index');

// Forum: create new post
Route::view('/new', 'pages.forum.new-container')
    ->middleware(['auth', 'verified', 'not.banned'])
    ->name('forum.new');

// Forum: show post
Route::get('/forum/{id}', function (int $id) {
    return view('pages.forum.show-container', ['id' => $id]);
})->name('forum.show');

// Forum: my posts
Route::view('/my', 'pages.forum.my-list-container')
    ->middleware(['auth', 'verified', 'not.banned'])
    ->name('forum.my');

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

require __DIR__.'/settings.php';
