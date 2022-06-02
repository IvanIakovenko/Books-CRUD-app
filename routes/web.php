<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\BooksController;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', function () {
    return view('layouts.index');
});

Route::get('/categories', [CategoriesController::class, 'index']);
Route::post('/addCategory', [CategoriesController::class, 'store']);
Route::post('delCategory', [CategoriesController::class, 'destroy']);
Route::post('/updCategory/{category}', [CategoriesController::class, 'update']);

Route::get('/books', [BooksController::class, 'index']);
Route::post('/books', [BooksController::class, 'index']);
Route::post('/addBook', [BooksController::class, 'store']);
Route::post('/editBook/{id}', [BooksController::class, 'edit']);
Route::post('/search', [BooksController::class, 'search']);
Route::post('/sort', [BooksController::class, 'sort']);
Route::get('/edit/{id}', [BooksController::class, 'editIndex']);
Route::post('/delete/{id}', [BooksController::class, 'destroy']);




Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
