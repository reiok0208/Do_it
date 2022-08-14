<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserEdit_Operation_DB
{
    public function EmailUpdate($request){
        //メール情報更新
        return DB::transaction(function () use($request){
            User::where('id',$request->UserId)
            ->lockForUpdate()
            //専有ロック
            ->update(['email'=> $request->Email,]);

            User::where('id',$request->UserId)
            ->update(['email_verified_at' =>NULL]);
            //再度メール認証
            return redirect('user')->with('status', __('メールアドレスの変更に成功しました'));
        });
    }
    public function PasswordChange($request,$user){
        //パスワード変更
        return DB::transaction(function () use($request,$user){
            User::where('id',$request->UserId)
            ->lockForUpdate();
            $user->password = bcrypt($request->newPassword);
            $user->save();
            return redirect('user')->with('status', __('パスワードの変更に成功しました'));
        });
    }
    public function Withdrawal($request,$id){
        //退会
        return DB::transaction(function () use($request,$id){
            User::where('id',$id)
            ->lockForUpdate()
            ->delete();
            Auth::logout();
            return redirect('/')->with('status', __('退会できました。ご利用ありがとうございます'));
        });
    }

}
