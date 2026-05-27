<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QizzController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminQuizController;
use App\Http\Controllers\AdminQuestionController;
use Illuminate\Http\Request;

Route::get('/', [QizzController::class, 'index'])->name('quiz.index');
Route::get('/quiz/{id}', [QizzController::class, 'show'])->name('quiz.show');
Route::post('/quiz/{id}/submit', [QizzController::class, 'submit'])->name('quiz.submit');
Route::get('/result/{id}', [QizzController::class, 'result'])->name('quiz.result');
Route::post('/login', [AuthController::class, 'Login'])->name('login');
Route::get('/login', function () {
    return view('login');
})->name('login.form')->middleware('guest');
Route::get('/admin', function () {
    return view('admin');
})->name('admin')->middleware('auth');
Route::get('/logout', [AuthController::class, 'Logout'])->name('logout');


//admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::post('/quizzes/{id}/random', [AdminQuizController::class, 'randomQuestions'])->name('quizzes.random');
    Route::resource('quizzes', AdminQuizController::class)->parameters(['quizzes' => 'id']);
    Route::resource('questions', AdminQuestionController::class)->parameters(['questions' => 'id']);
});