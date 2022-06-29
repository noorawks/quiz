<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SummerNoteController;

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

Route::get('/home', function () {
    return redirect()->route('index');
});

Route::get('/', [FrontEndController::class, 'index'])->name('index');

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/quiz/{id}', [FrontEndController::class, 'quiz'])->name('quiz');
    Route::get('/quiz-ongoing/{id}', [FrontEndController::class, 'quizOngoing'])->name('quiz-ongoing');
    Route::post('/quiz-submit', [FrontEndController::class, 'quizSubmit'])->name('quiz-submit');

    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['admin']], function () {
        Route::get('/', function () {
            return redirect()->route('admin.quiz.index');
        });
        
        Route::resource('quiz', QuizController::class);
        
        Route::resource('question', QuestionController::class);
        Route::post('question/store/bulk', [QuestionController::class, 'storeBulk'])->name('question.store.bulk');

        Route::get('/download-questions-template', function () {
            return response()->download(public_path('questions-template.xlsx'));
        })->name('download-questions-template');

        Route::resource('option', OptionController::class);

        Route::resource('result', ResultController::class);

        Route::resource('user', UserController::class);
        Route::get('user/get-user/{id}', [UserController::class, 'getUser'])->name('user.get-user');
    });

});
