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
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        $this->checkLogin();
        //ユーザー情報更新
        return DB::transaction(function () use($request){
            if(!empty($request->image)){
                $img = $request->image;
                $path = $img->store('img','public');
            }else{
                $path = null;
            }
            User::where('id',$request->UserId)
            ->lockForUpdate()
            //専有ロック
            ->update([
                'name'=> $request->name,
                'body'=> $request->body,
                'image'=> $path
            ]);
            return redirect('user')->with('status', __('情報の変更に成功しました'));
        });
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
