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
    Route::get('/new', [App\Http\Controllers\DeclarationController::class, 'create'])->name('create')->middleware('auth');
    Route::post('/new', [App\Http\Controllers\DeclarationController::class, 'create'])->name('create')->middleware('auth');
    Route::post('/new/confirm', [App\Http\Controllers\DeclarationController::class, 'confirm'])->name('confirm')->middleware('auth');
    Route::post('/store', [App\Http\Controllers\DeclarationController::class, 'store'])->name('store')->middleware('auth');
    Route::get('/show/{id}', [App\Http\Controllers\DeclarationController::class, 'show'])->name('show');
    Route::get('/edit/{id}', [App\Http\Controllers\DeclarationController::class, 'edit'])->name('edit')->middleware('auth');
    Route::post('/update', [App\Http\Controllers\DeclarationController::class, 'update'])->name('update')->middleware('auth');
    Route::delete('/delete/{id}', [App\Http\Controllers\DeclarationController::class, 'destroy'])->name('destroy')->middleware('auth');
    Route::group(['prefix' => 'report', 'as' => 'report.'], function(){
        Route::get('/{id}/new', [App\Http\Controllers\DeclarationController::class, 'report_create'])->name('create')->middleware('auth');
        Route::post('/{id}/new', [App\Http\Controllers\DeclarationController::class, 'report_create'])->name('create')->middleware('auth');
        Route::post('/new/confirm', [App\Http\Controllers\DeclarationController::class, 'report_confirm'])->name('confirm')->middleware('auth');
        Route::post('/store', [App\Http\Controllers\DeclarationController::class, 'report_store'])->name('store')->middleware('auth');
        Route::get('/show/{id}', [App\Http\Controllers\DeclarationController::class, 'report_show'])->name('show');
    });
});

Route::group(['prefix' => 'user', 'as' => 'user.'], function(){
    Route::get('/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('show');
});

// いいね関連
Route::post('/do_it', [App\Http\Controllers\DeclarationController::class, 'do_it'])->name('do_it')->middleware('auth');
Route::post('/good_work', [App\Http\Controllers\DeclarationController::class, 'good_work'])->name('good_work')->middleware('auth');

// コメント関連
Route::post('/declaration/comment/store', [App\Http\Controllers\CommentController::class, 'declaration_comment_store'])
->name('declaration.comment.store')->middleware('auth');

Route::post('/report/comment/store', [App\Http\Controllers\CommentController::class, 'report_comment_store'])
->name('report.comment.store')->middleware('auth');

Route::delete('/declaration/comment/delete/{id}', [App\Http\Controllers\CommentController::class, 'declaration_comment_destroy'])
->name('declaration.comment.destroy')->middleware('auth');

Route::delete('/report/comment/delete/{id}', [App\Http\Controllers\CommentController::class, 'report_comment_destroy'])
->name('report.comment.destroy')->middleware('auth');
