<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\StockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(CategoryController::class)->group(function () {
        Route::get('/category', 'index')->middleware('auth:sanctum');
        Route::post('/category/store', 'store')->middleware('auth:sanctum');
        Route::put('/category/update/{id}', 'update')->middleware('auth:sanctum');
        Route::delete('/category/delete/{id}', 'destroy')->middleware('auth:sanctum');
    });
Route::controller(BookController::class)->group(function () {
        Route::get('/book', 'index')->middleware('auth:sanctum');
        Route::post('/book/store', 'store')->middleware('auth:sanctum');
        Route::put('/book/update/{id}', 'update')->middleware('auth:sanctum');
        Route::delete('/book/delete/{id}', 'destroy')->middleware('auth:sanctum');
    });
Route::controller(StockController::class)->group(function () {
        Route::get('/stock', 'index')->middleware('auth:sanctum');
        Route::post('/stock/store', 'store')->middleware('auth:sanctum');
        Route::put('/stock/update/{id}', 'update')->middleware('auth:sanctum');
        Route::delete('/stock/delete/{id}', 'destroy')->middleware('auth:sanctum');
    });
Route::controller(BorrowController::class)->group(function () {
        Route::get('/borrow', 'index')->middleware('auth:sanctum');
        Route::post('/borrow/store', 'store')->middleware('auth:sanctum');
        Route::put('/borrow/update/{id}', 'update')->middleware('auth:sanctum');
        Route::delete('/borrow/delete/{id}', 'destroy')->middleware('auth:sanctum');
    });
Route::controller(HistoryController::class)->group(function () {
        Route::get('/history', 'index')->middleware('auth:sanctum');
        Route::post('/history/store', 'store')->middleware('auth:sanctum');
        Route::put('/history/update/{id}', 'update')->middleware('auth:sanctum');
        Route::delete('/history/delete/{id}', 'destroy')->middleware('auth:sanctum');
    });
Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
    });