<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;

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

Route::get('users/login', [UserController::class, 'login']);

Route::get('users/user_rented_books/{id}', [UserController::class, 'user_rented_books'])->middleware('jwt');

Route::post('users/rent_book', [UserController::class, 'rent_book'])->middleware('jwt');

Route::put('users/return_book', [UserController::class, 'return_book'])->middleware('jwt');

Route::apiResource('books',BookController::class);

Route::apiResource('users', UserController::class);

Route::resource('books', BookController::class)->only([
    'index', 'show', 'store', 'update', 'destroy'
    ])->middleware('jwt');
    
Route::resource('users', UserController::class)->only([
    'index', 'show', 'update', 'destroy'
    ])->middleware('jwt');
    