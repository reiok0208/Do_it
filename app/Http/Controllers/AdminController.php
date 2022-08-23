<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Declaration;
use App\Models\User;


class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(! Gate::allows('admin_gate', Auth::user())){
                abort(403);
            }
            $this->user = \Auth::user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index');
    }

    public function declaration_frozen_index(Request $request)
    {
        $declarations = Declaration::where('del_flg','1')->latest()->paginate(20);
        $request->session()->forget('_old_input');

        if ($declarations->isEmpty()){
            $request->session()->flash('record', 'Oops！凍結した宣言がありません！');
        }
        return view('admin.declaration', compact('declarations'));
    }

    public function declaration_frozen($id)
    {
        $declaration = Declaration::find($id);
        $declaration->del_flg = 1;
        $declaration->update();

        return redirect()->back()->with('status', '宣言を凍結しました！');

    }

    public function declaration_lift($id)
    {
        $declaration = Declaration::find($id);
        $declaration->del_flg = 0;
        $declaration->update();

        return redirect()->back()->with('status', '宣言を凍結解除しました！');
    }

    public function user_index()
    {
        $users = User::where('del_flg','0')->latest('id')->paginate(20);
        return view('admin.user', compact('users'));
    }

    public function user_frozen_index(Request $request)
    {
        $users = User::where('del_flg','1')->latest('id')->paginate(20);

        if ($users->isEmpty()){
            $request->session()->flash('record', 'Oops！凍結したユーザーがいません！');
        }
        return view('admin.user', compact('users'));
    }

    public function user_frozen($id)
    {
        $user = User::find($id);
        $user->del_flg = 1;
        $user->update();

        foreach($user->declaration as $dec){
            $dec->del_flg = 1;
            $dec->update();
        }


        return redirect()->back()->with('status', 'ユーザーを凍結しました！');

    }

    public function user_lift($id)
    {
        $user = User::find($id);
        $user->del_flg = 0;
        $user->update();

        foreach($user->declaration as $dec){
            $dec->del_flg = 0;
            $dec->update();
        }

        return redirect()->back()->with('status', 'ユーザーを凍結解除しました！');

    }

}
