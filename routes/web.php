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

// Declaration及びReport関連
// ゲスト操作可能
Route::group(['prefix' => 'declaration', 'as' => 'declaration.'], function(){
    Route::get('/', [App\Http\Controllers\DeclarationController::class, 'index'])->name('index');
    Route::get('/sort_by', [App\Http\Controllers\DeclarationController::class, 'sort_by'])->name('sort_by');
    Route::get('/search_by', [App\Http\Controllers\DeclarationController::class, 'search_by'])->name('search_by');
    Route::get('/tag_by', [App\Http\Controllers\DeclarationController::class, 'tag_by'])->name('tag_by');
    Route::get('/show/{id}', [App\Http\Controllers\DeclarationController::class, 'show'])->name('show');
    Route::group(['prefix' => 'report', 'as' => 'report.'], function(){
        Route::get('/show/{id}', [App\Http\Controllers\DeclarationController::class, 'report_show'])->name('show');
    });
});
// 認証ユーザー操作可能
Route::group(['middleware' => ['auth'], 'prefix' => 'declaration', 'as' => 'declaration.'], function(){
    Route::get('/new', [App\Http\Controllers\DeclarationController::class, 'create'])->name('create');
    Route::post('/new', [App\Http\Controllers\DeclarationController::class, 'create'])->name('create');
    Route::post('/new/confirm', [App\Http\Controllers\DeclarationController::class, 'confirm'])->name('confirm');
    Route::post('/store', [App\Http\Controllers\DeclarationController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [App\Http\Controllers\DeclarationController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [App\Http\Controllers\DeclarationController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [App\Http\Controllers\DeclarationController::class, 'destroy'])->name('destroy');
    Route::group(['prefix' => 'report', 'as' => 'report.'], function(){
        Route::get('/{id}/new', [App\Http\Controllers\DeclarationController::class, 'report_create'])->name('create');
        Route::post('/{id}/new', [App\Http\Controllers\DeclarationController::class, 'report_create'])->name('create');
        Route::post('/new/confirm', [App\Http\Controllers\DeclarationController::class, 'report_confirm'])->name('confirm');
        Route::post('/store', [App\Http\Controllers\DeclarationController::class, 'report_store'])->name('store');
    });
});


// いいね関連
Route::post('/do_it', [App\Http\Controllers\DeclarationController::class, 'do_it'])->name('do_it')->middleware('auth');
Route::post('/good_work', [App\Http\Controllers\DeclarationController::class, 'good_work'])->name('good_work')->middleware('auth');


// コメント関連
Route::get('/declaration/comment/index', [App\Http\Controllers\CommentController::class, 'declaration_comment_index'])
->name('declaration.comment.index')->middleware('auth');
Route::get('/report/comment/index', [App\Http\Controllers\CommentController::class, 'report_comment_index'])
->name('report.comment.index')->middleware('auth');
Route::post('/declaration/comment/store', [App\Http\Controllers\CommentController::class, 'declaration_comment_store'])
->name('declaration.comment.store')->middleware('auth');
Route::post('/report/comment/store', [App\Http\Controllers\CommentController::class, 'report_comment_store'])
->name('report.comment.store')->middleware('auth');
Route::delete('/declaration/comment/delete/{id}', [App\Http\Controllers\CommentController::class, 'declaration_comment_destroy'])
->name('declaration.comment.destroy')->middleware('auth');
Route::delete('/report/comment/delete/{id}', [App\Http\Controllers\CommentController::class, 'report_comment_destroy'])
->name('report.comment.destroy')->middleware('auth');


// ユーザー関連
Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function(){
    Route::get('/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('show');
    Route::get('/{id}/follows', [App\Http\Controllers\UserController::class, 'user_follows'])->name('follows');
    Route::get('/{id}/followers', [App\Http\Controllers\UserController::class, 'user_followers'])->name('followers');
    Route::post('/search_by', [App\Http\Controllers\UserController::class, 'search_by'])->name('search_by');
    Route::post('/follow', [App\Http\Controllers\UserController::class, 'follow'])->name('follow');
    Route::post('/unfollow', [App\Http\Controllers\UserController::class, 'unfollow'])->name('unfollow');
});
// ユーザー編集関連(パッケージ生成)
Route::group(['middleware' => ['auth']], function() {
    Route::get('/user', 'App\Http\Controllers\Auth\UserEditController@UserEditForm')->name('user.edit');
    Route::post('/user/edit/info','App\Http\Controllers\Auth\UserEditController@InfoUpdate');
    Route::post('/user/edit/email','App\Http\Controllers\Auth\UserEditController@EmailUpdate');
    Route::post('/user/edit/password','App\Http\Controllers\Auth\UserEditController@PasswordChange');
    Route::get('/user/edit/delete','App\Http\Controllers\Auth\UserEditController@WithdrawalForm')->name('user.delete');
    Route::post('/user/edit/Withdrawal','App\Http\Controllers\Auth\UserEditController@Withdrawal');
});


//管理者関連
Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('index');
    Route::get('/declaration/frozen', [App\Http\Controllers\AdminController::class, 'declaration_frozen_index'])->name('declaration.frozen.index');
    Route::post('/declaration/frozen/{id}', [App\Http\Controllers\AdminController::class, 'declaration_frozen'])->name('declaration.frozen');
    Route::post('/declaration/lift/{id}', [App\Http\Controllers\AdminController::class, 'declaration_lift'])->name('declaration.lift');
    Route::get('/user', [App\Http\Controllers\AdminController::class, 'user_index'])->name('user.index');
    Route::get('/user/frozen', [App\Http\Controllers\AdminController::class, 'user_frozen_index'])->name('user.frozen.index');
    Route::post('/user/frozen/{id}', [App\Http\Controllers\AdminController::class, 'user_frozen'])->name('user.frozen');
    Route::post('/user/lift/{id}', [App\Http\Controllers\AdminController::class, 'user_lift'])->name('user.lift');
    Route::get('/tag', [App\Http\Controllers\TagController::class, 'index'])->name('tag.index');
    Route::delete('/tag/delete/{id}', [App\Http\Controllers\TagController::class, 'destroy'])->name('tag.destroy');
});
