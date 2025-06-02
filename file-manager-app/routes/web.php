<?php
// routes/web.php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// File management routes
Route::get('/files', [FileController::class, 'index'])->name('files.index');
Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
Route::get('/files/download/{filename}', [FileController::class, 'download'])->name('files.download');
Route::get('/files/stream/{filename}', [FileController::class, 'stream'])->name('files.stream');
Route::delete('/files/delete/{filename}', [FileController::class, 'delete'])->name('files.delete');