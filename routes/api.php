<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;

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
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('tes', [QuizController::class, 'index']);

    Route::post('logout', [LoginController::class, 'logout']);
});
