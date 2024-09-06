<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/posts/index', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}/detail', [PostController::class, 'show'])->name('posts.show');