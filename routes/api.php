<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix'=>'file'], function () {
    Route::post('/', [FileController::class, 'index']);
    Route::post('/upload', [FileController::class, 'upload']);
    Route::post('/lists', [FileController::class, 'lists']);
    Route::get('/search', [FileController::class, 'searchFile']);
    Route::post('/store', [FileController::class, 'store']);
    Route::get('/newfile', [FileController::class, 'fileId']);
    Route::get('/{id}', [FileController::class, 'show']);
    Route::put('/{id}', [FileController::class, 'updateFile']);
    Route::delete('/{id}', [FileController::class, 'destroy']);
   
});

Route::group(['prefix'=>'event'], function () {
    Route::post('/', [EventController::class, 'index']);
    Route::get('/list', [EventController::class, 'list']);
    Route::post('/store', [EventController::class, 'store']);
    Route::get('/{id}', [EventController::class, 'show']);
    Route::post('/action', [EventController::class, 'action']);
    Route::delete('/{id}', [EventController::class, 'destroy']);
});
