<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchValidationRequest;
use App\Models\User;
use App\Models\Declaration;
use App\Models\Relationship;
use Illuminate\Support\Facades\Auth;

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
    public function show(Request $request, $id)
    {
        $user = User::whereId($id)->first();
        $declarations = Declaration::whereUserId($id)->sortable()->withCount('do_it')->withCount('good_work')->paginate(20);
        $followed = Relationship::where('following_user_id', \Auth::user()->id)->where('user_id', $id)->first();

        // 終了日が過ぎていてレポート未提出であったら詳細画面に遷移
        foreach($declarations as $dec){
            if($dec->user_id == Auth::id() && $dec->del_flg == 0 && $dec->report == null && strtotime(date('Y/m/d')) > strtotime($dec->end_date)){
                return redirect()->route('declaration.show', ['id' => $dec->id])->with('null_report', '宣言報告を提出してください');
            }
        }

        if ($declarations->isEmpty()){
            $request->session()->flash('record', 'Oops！宣言がありません！');
        }
        return view('user.show', compact('user','declarations','followed'));
    }

    // ユーザーのフォロー・アンフォロー
    public function follow(Request $request) {
        Relationship::create([
            'following_user_id' => Auth::id(), // フォローした人
            'user_id' => $request->user_id, // 上記の認証ユーザーにフォローされた人
        ]);

        $follow_count = Relationship::where('user_id', $request->user_id)->count();

        return response()->json($follow_count);
    }

    public function unfollow(Request $request) {
        $follow = Relationship::where('following_user_id', Auth::id())->where('user_id', $request->user_id)->first();
        $follow->delete();

        $follow_count = Relationship::where('user_id', $request->user_id)->count();

        return response()->json($follow_count);
    }



    // ログインユーザーのフォロー・フォロワー情報を取得
    public function  user_follows(Request $request, $id)
    {
        $user = User::whereId($id)->first();
        $follows = $user->follows()->latest()->paginate(20);

        if ($follows->isEmpty()){
            $request->session()->flash('follow', 'フォローがいません！切磋琢磨できる仲間をフォローしましょう！');
        }
        return view('user.follow', compact('user','follows'));
    }

    public function  user_followers(Request $request, $id)
    {
        $user = User::whereId($id)->first();
        $follows = $user->followers()->latest()->paginate(20);

        if ($follows->isEmpty()){
            $request->session()->flash('follow', 'フォロワーがいません！');
        }
        return view('user.follow', compact('user','follows'));
    }

    /************************************************************************************************************
     * ユーザー検索
     *
     */
    public function search_by(SearchValidationRequest $request){
        $pat = '%' . addcslashes(e($request->search_by), '%_\\') . '%';
        $users = User::where('name', 'LIKE', $pat)->get(['id', 'name']);

        return view('include.user_search', compact('users'));
    }

}
