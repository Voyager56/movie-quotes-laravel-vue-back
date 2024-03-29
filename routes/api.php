<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')->group(function () {
	Route::post('edit-profile', [UserController::class, 'edit']);

	Route::controller(QuoteController::class)->group(function () {
		Route::get('/quotes', 'index')->name('quotes.index');
		Route::post('/quotes', 'store')->name('quotes.store');
		Route::get('/quotes/search', 'search')->name('quotes.search');
		Route::get('/quotes/{id}', 'show')->name('quotes.show');
		Route::put('/quotes/{quote}', 'update')->name('quotes.update');
		Route::delete('/quotes/{id}', 'destroy')->name('quotes.destroy');
		Route::post('/likes/{quoteId}', 'addLike')->name('quotes.like');
	});

	Route::controller(MovieController::class)->group(function () {
		Route::get('movies', 'index')->name('movies.index');
		Route::get('movies/search/', 'search')->name('movies.search');
		Route::put('movies/{movie}', 'update')->name('movies.update');
		Route::delete('movies/{id}', 'destroy')->name('movies.destroy');
		Route::get('movies/movie-search/', 'movieSearch')->name('movies.movie-search');
		Route::get('movies/{id}', 'show')->name('movies.show');
		Route::post('movies', 'store')->name('movies.store');
	});

	Route::controller(NotificationController::class)->group(function () {
		Route::get('notifications', 'index')->name('notifications.index');
		Route::post('notifications/all', 'destroyAll')->name('notifications.delete-all');
		Route::post('notifications/{notification}', 'destroy')->name('notifications.delete');
	});

	Route::get('/comments', [CommentsController::class, 'index']);
	Route::post('/comment/{quote}', [CommentsController::class, 'store']);

	Route::get('/genres', [GenreController::class, 'index']);

	Route::post('/logout', [AuthController::class, 'logout']);
	Route::post('/me', [AuthController::class, 'authorizedUser']);
});

Route::middleware('guest')->group(function () {
	Route::post('/login', [AuthController::class, 'login']);
	Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/email-verification/{token}', [AuthController::class, 'verify']);

Route::post('/forgot-password', [PasswordResetController::class, 'sendPasswordResetEmail']);
Route::post('/reset-password/{token}', [PasswordResetController::class, 'resetPassword']);
