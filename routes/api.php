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
	Route::post('/me', [AuthController::class, 'authorizedUser']);
	Route::post('edit-profile', [UserController::class, 'edit']);

	Route::get('/quotes', [QuoteController::class, 'index']);
	Route::post('/quotes/add', [QuoteController::class, 'store']);
	Route::get('/quotes/search', [QuoteController::class, 'search']);
	Route::get('/quotes/{id}', [QuoteController::class, 'show']);
	Route::post('/quotes/update/{id}', [QuoteController::class, 'update']);
	Route::delete('/quotes/delete/{id}', [QuoteController::class, 'destroy']);
	Route::post('/likes/{quoteId}', [QuoteController::class, 'addLike']);

	Route::get('movies', [MovieController::class, 'index']);
	Route::get('movies/search/', [MovieController::class, 'search']);
	Route::post('movies/update/{id}', [MovieController::class, 'update']);
	Route::delete('movies/delete/{id}', [MovieController::class, 'destroy']);
	Route::get('movies/movie-search/', [MovieController::class, 'movieSearch']);
	Route::get('movies/{id}', [MovieController::class, 'show']);
	Route::post('movies', [MovieController::class, 'store']);

	Route::get('notifications', [NotificationController::class, 'index']);
	Route::post('notifications/all', [NotificationController::class, 'destroyAll']);
	Route::post('notifications/{id}', [NotificationController::class, 'destroy']);

	Route::get('/comments', [CommentsController::class, 'index']);
	Route::post('/comment/{quoteId}', [CommentsController::class, 'store']);

	Route::get('/genres', [GenreController::class, 'index']);
	Route::post('/logout', [AuthController::class, 'logout']);
});
Route::get('/email-verification/{token}', [AuthController::class, 'verify']);

Route::middleware('guest')->group(function () {
	Route::post('/login', [AuthController::class, 'login']);
	Route::post('/register', [AuthController::class, 'register']);

	Route::post('/forgot-password', [PasswordResetController::class, 'sendPasswordResetEmail']);
	Route::post('/reset-password/{token}', [PasswordResetController::class, 'resetPassword']);
});
