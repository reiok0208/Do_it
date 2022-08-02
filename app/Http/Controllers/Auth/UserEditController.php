<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\WithdrawalRequest;
use UserEdit_Operation_DB;

class UserEditController extends Controller
{
    private function checkLogin(){
        //ログインの有無をチェック
        if (!Auth::check()) {
            return \App::abort(404);
        }
    }


    public function UserEditForm(Request $request){
        //ユーザー編集画面を表示させるメソッド
        $auth = auth::user();
        $this->checkLogin();
        return view('auth.UserEdit',['auth'=>$auth]);
    }

    public function InfoUpdate(UpdateInfoRequest $request){
        //ユーザー情報を更新するメソッド
        $this->checkLogin();
        $UserEdit_Operation_DB = new UserEdit_Operation_DB();
        return $UserEdit_Operation_DB->InfoUpdate($request);
    }

    public function EmailUpdate(UpdateEmailRequest $request){
        //登録メールアドレスを更新するメソッド
        $this->checkLogin();
        $UserEdit_Operation_DB = new UserEdit_Operation_DB();
        return $UserEdit_Operation_DB->EmailUpdate($request);
    }

    public function PasswordChange(ChangePasswordRequest $request){
        //パスワードを変更するメソッド
        $this->checkLogin();
        $user = Auth::user();
        $UserEdit_Operation_DB = new UserEdit_Operation_DB();
        return $UserEdit_Operation_DB->PasswordChange($request,$user);
    }

    public function WithdrawalForm(){
        //退会フォームを表示させるメソッド
        $auth = auth::user();
        return view('auth.WithdrawalForm',['auth'=>$auth]);
    }

    public function Withdrawal(WithdrawalRequest $request){
        //退会処理を追加するメソッド
        $id = auth::id();
        $UserEdit_Operation_DB = new UserEdit_Operation_DB();
        return $UserEdit_Operation_DB->Withdrawal($request,$id);
    }
}
