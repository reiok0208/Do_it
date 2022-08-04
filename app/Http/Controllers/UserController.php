<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Declaration;
use App\Models\Relationship;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::whereId($id)->first();
        $declarations = Declaration::whereUserId($id)->withCount('do_it')->withCount('good_work')->latest()->paginate(20);
        $followed = Relationship::where('following_user_id', \Auth::user()->id)->where('user_id', $id)->first();
        return view('user.show', compact('user','declarations','followed'));
    }

    // ユーザーのフォロー・アンフォロー
    public function follow($id) {
        $follow = Relationship::create([
            'following_user_id' => \Auth::user()->id,
            'user_id' => $id,
        ]);
        return redirect()->back()->with('status', 'フォローしました！');
    }

    public function unfollow($id) {
        $follow = Relationship::where('following_user_id', \Auth::user()->id)->where('user_id', $id)->first();
        $follow->delete();

        return redirect()->back()->with('status', 'フォロー解除しました！');
    }



    // ログインユーザーのフォロー・フォロワー情報を取得
    public function  user_follows($id)
    {
        $user = User::whereId($id)->first();
        $follows = $user->follows()->latest()->paginate(20);
        return view('user.follow', compact('user','follows'));
    }

    public function  user_followers($id)
    {
        $user = User::whereId($id)->first();
        $follows = $user->followers()->latest()->paginate(20);
        return view('user.follow', compact('user','follows'));
    }

}
