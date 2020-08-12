<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('login/github', 'GithubLogin@main')->name('login.github');

Route::get('login/twitch', 'TwitchLogin@main')->name('login.twitch');

Route::get('logout', function() {
    Auth::logout();
    return redirect('/');
})->name('logout');
