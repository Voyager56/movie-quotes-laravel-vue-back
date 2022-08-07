<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserEditController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/me', [AuthController::class, 'me'])->middleware('api');
Route::get('/email-verification/{token}', [AuthController::class, 'verify']);

Route::post('edit-profile', [UserEditController::class, 'editProfile'])->middleware('api');

Route::get('/quotes', [QuoteController::class, 'index'])->middleware('api');
Route::post('/quotes/add', [QuoteController::class, 'store'])->middleware('api');
Route::post('/likes/{quoteId}', [QuoteController::class, 'addLike']);

Route::get('/comments', [CommentsController::class, 'index']);
Route::post('/comment/{quoteId}', [CommentsController::class, 'store']);

Route::get('notifications', [NotificationController::class, 'index']);
Route::post('notifications/all', [NotificationController::class, 'destroyAll']);
Route::post('notifications/{id}', [NotificationController::class, 'destroy']);

Route::get('/genres', [GenreController::class, 'index']);

Route::get('movies', [MovieController::class, 'index']);
Route::get('movies/{id}', [MovieController::class, 'show']);
Route::post('movies', [MovieController::class, 'store']);
Route::get('movies/search', [MovieController::class, 'search']);

Route::get('/locale/{lang}', [LangController::class, 'index'])->name('locale');
