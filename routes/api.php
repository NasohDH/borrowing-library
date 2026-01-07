<?php

use App\Http\Controllers\Api\Admin\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('categories' , ['App\Http\Controllers\Api\CategoryController' , 'index']);
Route::get('categories' , [CategoryController::class,  'index']);
Route::post('categories' , [CategoryController::class,  'store']);
Route::put('categories/{identifier}' , [CategoryController::class,  'update']);
Route::delete('categories/{id}' , [CategoryController::class,  'destroy']);

Route::prefix('admin')->group(function () {
    Route::apiResource('authors', AuthorController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);
});
Route::apiResource('books' , BookController::class);
