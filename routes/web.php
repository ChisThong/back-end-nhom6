<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QizzController;

Route::get('/', [QizzController::class, 'index']);
Route::get('/thi/{id}', [QizzController::class, 'show']);
Route::post('/submit/{id}', [QizzController::class, 'submit'])->name('submit-quiz');
Route::get('/ket-qua', [QizzController::class, 'ketQua'])->name('ket-qua');
