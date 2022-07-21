<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuoteController;
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

    Route::get("/quotes", [QuoteController::class, "index"])->middleware('api');

    Route::get('/comments', [CommentsController::class, 'index']);
    Route::post('/comment/{quoteId}', [CommentsController::class, 'store']);


    Route::get('/locale/{lang}', [LangController::class, "index"])->name('locale');



