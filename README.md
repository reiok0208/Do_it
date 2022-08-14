<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Do_it!

## 環境
MAMP/MySQL/PHP

## 開発背景
人間は怠惰が大好きである。でも、そんな姿はダメだ！ You Can Do it!  
やることを宣言して仲間と応援、実行・達成して自分を高められるそんなサイトを作りました！  

## できること
- ログイン
- パスワードリセット
- ユーザ登録、編集、退会、フォロー・フォロワー機能
- 宣言閲覧、投稿、編集(宣言期間開始以降の編集は不可)、削除(宣言期間中の削除は不可)
- 宣言共有(Twitter)
- Do it!ボタン(宣言開始前)とGood work!ボタン(宣言報告後)、コメント機能
- 宣言のタグ付け、検索機能(宣言タイトル・宣言内容・タグから)
- 宣言の並び替え
- ユーザー一覧、ユーザー凍結・解除、宣言凍結・解除(管理者機能)

## 使い方
1.ダウンロード、解凍したらコマンドプロンプトでcdコマンドを使いアプリ直下まで移動してください。  

2.コマンドプロンプトで`composer install`してください。  

3.phpMyAdminにアプリ直下の ***do_it.sql*** をインポートしてください。(すぐに使用できるようにdb:seed済みですので追加は不要です)  
> データベース名:do_it   

4.コマンドプロンプトで`php artisan serve`してサーバーを起動してください。  

5.コマンドプロンプトに表示されたURLへ接続してください。  

## アカウント
### 管理者
メールアドレス： admin@gmail.com  
パスワード： admin  
  
### 一般ユーザー
メールアドレス： user@gmail.com  
パスワード： user  
