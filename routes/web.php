<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\DeclarationController::class, 'index'])->name('root');

Auth::routes();

Route::group(['prefix' => 'declaration', 'as' => 'declaration.'], function(){
    Route::get('/', [App\Http\Controllers\DeclarationController::class, 'index'])->name('index');
    Route::get('/new', [App\Http\Controllers\DeclarationController::class, 'create'])->name('create');
    Route::post('/new', [App\Http\Controllers\DeclarationController::class, 'create'])->name('create');
    Route::post('/new/confirm', [App\Http\Controllers\DeclarationController::class, 'confirm'])->name('confirm');
    Route::post('/', [App\Http\Controllers\DeclarationController::class, 'store'])->name('store');
    Route::get('/{id}', [App\Http\Controllers\DeclarationController::class, 'show'])->name('show');
});

Route::group(['prefix' => 'user', 'as' => 'user.'], function(){
    Route::get('/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('show');
});
