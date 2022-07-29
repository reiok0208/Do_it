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
    Route::post('/store', [App\Http\Controllers\DeclarationController::class, 'store'])->name('store');
    Route::get('/show/{id}', [App\Http\Controllers\DeclarationController::class, 'show'])->name('show');
    Route::get('/edit/{id}', [App\Http\Controllers\DeclarationController::class, 'edit'])->name('edit');
    Route::post('/update', [App\Http\Controllers\DeclarationController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [App\Http\Controllers\DeclarationController::class, 'destroy'])->name('destroy');
    Route::group(['prefix' => 'report', 'as' => 'report.'], function(){
        Route::get('/{id}/new', [App\Http\Controllers\DeclarationController::class, 'report_create'])->name('create');
        Route::post('/{id}/new', [App\Http\Controllers\DeclarationController::class, 'report_create'])->name('create');
        Route::post('/new/confirm', [App\Http\Controllers\DeclarationController::class, 'report_confirm'])->name('confirm');
        Route::post('/store', [App\Http\Controllers\DeclarationController::class, 'report_store'])->name('store');
        Route::get('/show/{id}', [App\Http\Controllers\DeclarationController::class, 'report_show'])->name('show');
    });
});

Route::group(['prefix' => 'user', 'as' => 'user.'], function(){
    Route::get('/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('show');
});

// いいね関連
Route::post('/do_it', [App\Http\Controllers\DeclarationController::class, 'do_it'])->name('do_it');
Route::post('/good_work', [App\Http\Controllers\DeclarationController::class, 'good_work'])->name('good_work');

// コメント関連
Route::post('/declaration/comment/store', [App\Http\Controllers\CommentController::class, 'declaration_comment_store'])
->name('declaration.comment.store');

Route::post('/report/comment/store', [App\Http\Controllers\CommentController::class, 'report_comment_store'])
->name('report.comment.store');

Route::delete('/declaration/comment/delete/{id}', [App\Http\Controllers\CommentController::class, 'declaration_comment_destroy'])
->name('declaration.comment.destroy');

Route::delete('/report/comment/delete/{id}', [App\Http\Controllers\CommentController::class, 'report_comment_destroy'])
->name('report.comment.destroy');
