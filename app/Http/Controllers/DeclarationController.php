<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeclarationValidationRequest;
use App\Http\Requests\ReportValidationRequest;
use App\Http\Requests\SearchValidationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Declaration;
use App\Models\Declaration_comment;
use App\Models\Declaration_tag;
use App\Models\Report;
use App\Models\Report_comment;
use App\Models\Tag;
use App\Models\Do_it;
use App\Models\Good_work;
use Illuminate\Support\Facades\Gate;

class DeclarationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $declarations = Declaration::withCount('do_it')->withCount('good_work')->latest('id')->paginate(20);
        $request->session()->forget(['_old_input','record']);

        // 終了日が過ぎていてレポート未提出であったら詳細画面に遷移
        foreach($declarations as $dec){
            if($dec->user_id == Auth::id() && $dec->del_flg == 0 && $dec->report == null && strtotime(date('Y/m/d')) > strtotime($dec->end_date)){
                return redirect()->route('declaration.show', ['id' => $dec->id])->with('null_report', '宣言報告を提出してください');
            }
        }

        if ($declarations->isEmpty()){
            $request->session()->flash('record', 'Oops！宣言がありません！');
        }

        return view('declaration.index', compact('declarations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!preg_match("/declaration\/new/", url()->previous())) {
            $request->session()->forget('_old_input');
        }

        // oldメソッドの入力値を保持
        if(!empty($request->title)){
            $request->session()->put([
                '_old_input' => [
                    'title' => $request->title,
                    'tag' => $request->tag,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'body' => $request->body
                ]
            ]);
        }
        return view('declaration.create');
    }

    public function confirm(DeclarationValidationRequest $request)
    {
        return view('declaration.confirm', compact('request'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeclarationValidationRequest $request)
    {

        $declaration = new Declaration;
        $declaration->title = e($request->title);
        $declaration->body = e($request->body);
        $declaration->start_date = e($request->start_date);
        $declaration->end_date = e($request->end_date);
        $declaration->user_id = Auth::user()->id;
        $declaration->save();

        // 「#タグ#タグ」のような結合状態をpreg_match_allで分解し$match配列に入れる
        preg_match_all('/#([a-zA-Z0-9０-９ぁ-んァ-ヶー一-龠]+)/u', $request->tag, $match);
        $tags = [];
        // $match[1]から始める理由は[0]が#入りのタグであるから
        foreach ($match[1] as $tag) {
            // タグが新規か既存かを調べ新規であればcreateする
            $record = Tag::firstOrCreate(['name' => $tag]);
            array_push($tags, $record);
        };

        $tags_id = [];
        foreach ($tags as $tag) {
            // 宣言に使われたタグのidを取り出し配列に追加
            array_push($tags_id, $tag['id']);
        };
        // syncでタグと宣言を紐づける
        $declaration->tags()->sync($tags_id);

        // 二重送信防止
        if(preg_match("/confirm/", url()->previous())){
            $request->session()->regenerateToken();
            $request->session()->forget('_old_input');
        }

        return redirect()->route('declaration.show', ['id' => $declaration->id])->with('status', '投稿しました！');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $declaration = Declaration::whereId($id)->withCount('do_it')->withCount('good_work')->first();

        if($declaration->del_flg == 1){
            if(Auth::check() && Auth::user()->admin != 1){
                abort(403);
            }else if(Auth::guest()){
                abort(403);
            }
        }

        $comments = Declaration_comment::latest()->whereDeclarationId($id)->get();
        $count = Declaration_comment::whereDeclarationId($id)->count();

        $request->session()->forget(['comment','_old_input']);

        if ($comments->isEmpty()){
            $request->session()->flash('comment', 'コメントがありません！応援しましょう！');
        }
        return view('declaration.show', compact('declaration','comments','count'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $declaration = Declaration::whereId($id)->first();
        // Gate権限
        if(! Gate::allows('edit_gate', $declaration)){
            abort(403);
        }

        // タグを#ありの状態に加工
        $tags = "";
        foreach($declaration->tags as $tag){
            $tags .= "#"."$tag->name";
        }

        // バリデーション発火時に変更した値を保持する
        if(!preg_match("/edit/", url()->previous())){
            $request->session()->put([
                '_old_input' => [
                    'id' => $declaration->id,
                    'title' => $declaration->title,
                    'tag' => $tags,
                    'start_date' => $declaration->start_date->format('Y-m-d'),
                    'end_date' => $declaration->end_date->format('Y-m-d'),
                    'body' => $declaration->body
                ]
            ]);
        }

        return view('declaration.edit', compact('declaration'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeclarationValidationRequest $request)
    {
        $declaration = Declaration::whereId($request->id)->first();

        // Gate権限
        if(! Gate::allows('edit_gate', $declaration)){
            abort(403);
        }

        $declaration = Declaration::find($request->id);
        $declaration->title = e($request->title);
        $declaration->body = e($request->body);
        $declaration->start_date = e($request->start_date);
        $declaration->end_date = e($request->end_date);
        $declaration->update();

        preg_match_all('/#([a-zA-Z0-9０-９ぁ-んァ-ヶー一-龠]+)/u', $request->tag, $match);
        $tags = [];
        foreach ($match[1] as $tag) {
            $record = Tag::firstOrCreate(['name' => $tag]);
            array_push($tags, $record);
        };

        $tags_id = [];
        foreach ($tags as $tag) {
            array_push($tags_id, $tag['id']);
        };
        $declaration->tags()->sync($tags_id);

        // 二重送信防止
        if(preg_match("/edit/", url()->previous())){
            $request->session()->regenerateToken();
            $request->session()->forget('_old_input');
        }

        return redirect()->route('declaration.show', ['id' => $declaration->id])->with('status', '編集しました！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $declaration = Declaration::findOrFail($id);
        // Gate権限
        if(! Gate::allows('delete_gate', $declaration)){
            abort(403);
        }

        $declaration->delete();

        return redirect()->route('declaration.index')->with('status', '削除しました！');
    }



    /************************************************************************************************************
     * Report
     *
     */
    public function report_create(Request $request, $id)
    {
        $declaration = Declaration::find($id);
        // Gate権限
        if(! Gate::allows('report_gate', $declaration)){
            abort(403);
        }

        // 値の保持
        if(strtotime(date('Y/m/d')) > strtotime($declaration->end_date) || ($declaration->user_id == Auth::id())){
            $report = $declaration->report;
            if($report != null){
                return redirect()->route('declaration.report.show',['id' => $report->id] );
            }else{
                if(preg_match("/confirm/", url()->previous())){
                    $request->session()->put([
                        '_old_input' => [
                            'rate' => $request->rate,
                            'execution' => $request->execution,
                            'body' => $request->body,
                        ]
                    ]);
                }
                return view('declaration.report.create', compact('declaration'));
            }
        }else{
            return redirect()->route('declaration.show', ['id' => $declaration->id]);
        }
    }

    public function report_confirm(ReportValidationRequest $request)
    {
        return view('declaration.report.confirm', compact('request'));
    }

    public function report_store(Request $request)
    {
        $declaration = Declaration::find($request->declaration_id);
        // Gate権限
        if(! Gate::allows('report_gate', $declaration)){
            abort(403);
        }

        $report = new Report;
        $report->declaration_id = $request->declaration_id;
        $report->rate = $request->rate;
        $report->execution = $request->execution;
        $report->body = e($request->body);

        $report->save();

        // 二重送信防止
        if(preg_match("/confirm/", url()->previous())){
            $request->session()->regenerateToken();
        }

        return redirect()->route('declaration.report.show', ['id' => $report->id])->with('status', '報告提出しました！');
    }

    public function report_show(Request $request, $id)
    {
        $report = Report::whereId($id)->first();
        $declaration = Declaration::whereId($report->declaration_id)->first();

        if($declaration->del_flg == 1){
            if(Auth::check() && Auth::user()->admin != 1){
                abort(403);
            }else if(Auth::guest()){
                abort(403);
            }
        }

        $comments = Report_comment::latest()->whereReportId($id)->get();
        $count = Report_comment::whereReportId($id)->count();

        $request->session()->forget(['comment']);

        if ($comments->isEmpty()){
            $request->session()->flash('comment', 'コメントがありません！労いの言葉をかけましょう！');
        }
        return view('declaration.report.show', compact('report','declaration','comments','count'));
    }



    /************************************************************************************************************
     * いいね
     *
     */
    public function do_it(Request $request){
        $user_id = Auth::user()->id;
        $declaration_id = $request->declaration_id; //JSONから飛んできたdeclaration_id
        $already = Do_it::where('user_id', $user_id)->where('declaration_id', $declaration_id)->first(); //データがあるか取得してみる

        if (!$already) { //中身がなければ保存
            $like = new Do_it;
            $like->declaration_id = $declaration_id;
            $like->user_id = $user_id;
            $like->save();
        } else { //中身があれば削除
            Do_it::where('declaration_id', $declaration_id)->where('user_id', $user_id)->delete();
        }

        $do_it_count = Declaration::withCount('do_it')->findOrFail($declaration_id)->do_it_count; //更新された宣言のいいね数を取得
        $param = [
            'do_it_count' => $do_it_count,
        ];
        return response()->json($param); //JSONへ返却
    }

    public function good_work(Request $request){
        $user_id = Auth::user()->id;
        $declaration_id = $request->declaration_id; //JSONから飛んできたdeclaration_id
        $already = Good_work::where('user_id', $user_id)->where('declaration_id', $declaration_id)->first(); //データがあるか取得してみる

        if (!$already) { //中身がなければ保存
            $like = new Good_work;
            $like->declaration_id = $declaration_id;
            $like->user_id = $user_id;
            $like->save();
        } else { //中身があれば削除
            Good_work::where('declaration_id', $declaration_id)->where('user_id', $user_id)->delete();
        }

        $good_work_count = Declaration::withCount('good_work')->findOrFail($declaration_id)->good_work_count; //更新された宣言のいいね数を取得
        $param = [
            'good_work_count' => $good_work_count,
        ];
        return response()->json($param); //JSONへ返却
    }

    /************************************************************************************************************
     * Twitter共有
     *
     */
    public function twitter_share($declaration){
        $aryTwitter = [];
        $aryTwitter['text'] = $declaration->title."&brvbar; Do_it!";
        $aryTwitter['url'] = url()->current();

        if(!empty($declaration->tags[0])){
            foreach($declaration->tags as $tag){
                $array[] = $tag->name;
            }
            $implode = implode(',', $array);
            $tags = preg_replace("/\s/", "", $implode);
            $aryTwitter['hashtags'] = $tags;
        }


        $twitter_url = 'https://twitter.com/share?';
        $twitter_url .= implode('&', array_map(function($key, $value){return $key.'='.$value;}, array_keys($aryTwitter), array_values($aryTwitter)));
        echo $twitter_url;
    }

    /************************************************************************************************************
     * ソート
     *
     */
    public function sort_by(Request $request){
        if(empty($request->sort_by)){
            $sort = $request->session()->get('sort_by');
        }else{
            $request->session()->put('sort_by',$request->sort_by);
            $sort = $request->sort_by;
        }
        if($sort == "宣言が新しい順"){
            $declarations = Declaration::withCount('do_it')->withCount('good_work')->latest('id')->paginate(20);
        }else if($sort == "宣言が古い順"){
            $declarations = Declaration::withCount('do_it')->withCount('good_work')->oldest('id')->paginate(20);
        }else if($sort == "Do_it数順"){
            $declarations = Declaration::where('end_date','>',date("Y/m/d"))->withCount('do_it')->withCount('good_work')->orderBy('do_it_count', 'desc')->paginate(20);
        }else if($sort == "Good_work数順"){
            $declarations = Declaration::where('end_date','<',date("Y/m/d"))->withCount('do_it')->withCount('good_work')->orderBy('good_work_count', 'desc')->paginate(20);
        }else if($sort == "フォロー中"){
            $declarations = Declaration::whereIn('user_id', Auth::user()->follows()->pluck('user_id'))->withCount('do_it')->withCount('good_work')->latest('id')->paginate(20);
        }

        if ($declarations->isEmpty()){
            $request->session()->flash('record', 'Oops！宣言がありませんでした！');
        }

        return view('declaration.index', compact('declarations','sort'));

    }

    /************************************************************************************************************
     * 文字検索
     *
     */
    public function search_by(SearchValidationRequest $request){
        if(empty($request->search_by)){
            $search = $request->session()->get('search_by');
        }else{
            $request->session()->put('search_by',$request->search_by);
            $search = e($request->search_by) ?? '';
        }

        $pat = '%' . addcslashes($search, '%_\\') . '%';
        $declarations = Declaration::where('title', 'LIKE', $pat)
                                    ->orWhere('body', 'LIKE', $pat)
                                    ->orWhereHas('tags', function ($query) use ($pat){
                                        $query->where('name', 'LIKE', $pat);
                                    })->latest('created_at')->paginate(20);
        if ($declarations->isEmpty()){
            return redirect()->route('declaration.index')->with('record', 'Oops！検索条件にあった宣言がありませんでした！');
        }

        return view('declaration.index', compact('declarations','search'));

    }

    /************************************************************************************************************
     * タグ検索
     *
     */
    public function tag_by(Request $request){
        if(empty($request->tag_by)){
            $tag = $request->session()->get('tag_by');
        }else{
            $request->session()->put('tag_by',$request->tag_by);
            $tag = e($request->tag_by) ?? '';
        }

        $pat = addcslashes($tag, '%_\\');
        $declarations = Declaration::WhereHas('tags', function ($query) use ($pat){
                                        $query->where('name', $pat);
                                    })->latest('created_at')->paginate(20);

        return view('declaration.index', compact('declarations','tag'));

    }

}
