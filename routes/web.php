<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Book;
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

Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/books/view/{id}', [BookController::class, 'view'])->name('books.view')->middleware('book-check');
Route::get('/books/download/{id}', [BookController::class, 'download'])->name('books.download')->middleware(['auth', 'admin']);




Auth::routes();


Route::middleware(['auth', 'user-role:user'])->group(function () {
  Route::get('/user/{user}', [AccountsController::class, 'userAccount'])->name('user.show');
  Route::get('/user/{user}/edit', [AccountsController::class, 'editProfile'])->name('user.edit');
  Route::put('/user/{user}', [AccountsController::class, 'updateProfile'])->name('user.update');
  Route::get('/user/books/{user}', [AccountsController::class, 'myBooks'])->name('user.books');
  Route::get('/user/credits/{user}', [AccountsController::class, 'myCredits'])->name('user.credits');
  Route::get('/user/buy-credits/{user}', [AccountsController::class, 'buyCredits'])->name('user.buyCredits');
  Route::post('/books/{book}/borrow-book', [BookController::class, 'borrow'])->name('books.borrow');
  Route::post('/books/{book}/return-book', [BookController::class, 'return'])->name('books.return');
  Route::post('/books/{book}/extend-book', [BookController::class, 'extend'])->name('books.extend');
});


Route::middleware(['auth', 'user-role:admin'])->group(function () {
  Route::get('/books/create/{id}', [BookController::class, 'create'])->name('books.create');
  Route::get('/users', [AccountsController::class, 'index'])->name('users.index');
  Route::get('/admin/{user}', [AccountsController::class, 'adminAccount'])->name('admin.show');
  Route::post('/user/{user}/make-admin', [AccountsController::class, 'makeAdmin'])->name('user.makeAdmin');
  Route::delete('/user/{user}/delete', [AccountsController::class, 'deleteUser'])->name('user.delete');
  Route::post('/books/{book}/change-status', [BookController::class, 'status'])->name('books.status');
  // Use only one definition for resource routes to avoid conflicts
  Route::resource('/books', BookController::class, ['except' => ['show', 'index']]);
  Route::resource('/authors', AuthorController::class, ['except' => ['show', 'index']]);
  Route::post('/books/{book}/file-upload', [BookController::class, 'upload'])->name('books.upload');
  // Route for handling CSRF token refresh to prevent 419 errors
  Route::post('/refresh-csrf', function() {
      return response()->json(['token' => csrf_token()]);
  })->name('refresh.csrf');
});

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');

// Image upload routes
Route::post('/images/upload', [App\Http\Controllers\ImageController::class, 'upload'])->name('images.upload')->middleware('auth');
Route::post('/images/delete', [App\Http\Controllers\ImageController::class, 'delete'])->name('images.delete')->middleware('auth');

// PHP Info route - restricted to admin users only
Route::get('/phpinfo', function () {
    ob_start();
    phpinfo();
    $phpinfo = ob_get_clean();

    // Extract the body content only
    $bodyStart = strpos($phpinfo, '<body>');
    $bodyEnd = strpos($phpinfo, '</body>', $bodyStart);
    $phpinfoBody = substr($phpinfo, $bodyStart, $bodyEnd - $bodyStart);

    return response()->view('phpinfo', ['phpinfo' => $phpinfoBody]);
})->middleware(['auth', 'user-role:admin'])->name('phpinfo');
