<?php

namespace App\Http\Controllers;

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
        $declarations = Declaration::withCount('do_it')->withCount('good_work')->latest()->paginate(20);
        $request->session()->forget('_old_input');
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

    public function confirm(Request $request)
    {
        // $this->dec_valid($request);
        return view('declaration.confirm', compact('request'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $declaration = new Declaration;
        $declaration->title = e($request->title);
        $declaration->body = e($request->body);
        $declaration->start_date = e($request->start_date);
        $declaration->end_date = e($request->end_date);
        $declaration->user_id = Auth::user()->id;
        $declaration->save();

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
        $request->session()->regenerateToken();

        $request->session()->forget('_old_input');

        return redirect()->route('declaration.show', ['id' => $declaration->id])->with('status', '投稿しました！');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $declaration = Declaration::whereId($id)->first();
        $comments = Declaration_comment::latest()->whereDeclarationId($id)->paginate(10);
        $count = Declaration_comment::whereDeclarationId($id)->count();
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
        if(! Gate::allows('declaration_gate', $declaration)){
            abort(403);
        }

        // タグを#ありの状態に加工
        $tags = "";
        foreach($declaration->tags as $tag){
            $tags .= "#"."$tag->name";
        }

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

        return view('declaration.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $declaration = Declaration::whereId($request->id)->first();
        if(! Gate::allows('declaration_gate', $declaration)){
            abort(403);
        }

        $request->session()->put([
            '_old_input' => [
                'id' => $request->input('id'),
                'title' => $request->input('title'),
                'tag' => $request->input('tags'),
                'start_date' => $request->input("start_date"),
                'end_date' => $request->input("end_date"),
                'body' => $request->input('body')
            ]
        ]);
        $this->dec_valid($request);

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
        $request->session()->regenerateToken();

        $request->session()->forget('_old_input');

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
        if(! Gate::allows('declaration_gate', $declaration)){
            abort(403);
        }

        $declaration->delete();

        $url = url()->previous();

        if (preg_match("/\?page\=/", $url)) {
            return redirect(url()->previous());
        } else {
            return redirect()->route('declaration.index')->with('status', '削除しました！');
        }
    }



    /************************************************************************************************************
     * Report
     *
     */
    public function report_create(Request $request, $id)
    {
        $declaration = Declaration::find($id);
        if(! Gate::allows('declaration_gate', $declaration)){
            abort(403);
        }
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

    public function report_confirm(Request $request)
    {
        $this->report_valid($request);
        return view('declaration.report.confirm', compact('request'));
    }

    public function report_store(Request $request)
    {
        $declaration = Declaration::find($request->declaration_id);
        if(! Gate::allows('declaration_gate', $declaration)){
            abort(403);
        }
        $report = new Report;
        $report->declaration_id = $request->declaration_id;
        $report->rate = $request->rate;
        $report->execution = $request->execution;
        $report->body = e($request->body);

        $report->save();

        // 二重送信防止
        $request->session()->regenerateToken();

        return redirect()->route('declaration.report.show', ['id' => $report->id])->with('status', '報告提出しました！');
    }

    public function report_show($id)
    {
        $report = Report::whereId($id)->first();
        $declaration = Declaration::whereId($report->declaration_id)->first();
        $comments = Report_comment::latest()->whereReportId($id)->paginate(10);
        $count = Report_comment::whereReportId($id)->count();
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
     * バリデーション
     *
     */
    public function dec_valid($request){
        // バリデーションルール
        $validateRules = [
            'title' => 'required|max:15',
            'start_date' =>  'required|date|after:yesterday',
            'end_date' => 'required|date|after:start_date',
            'body' => 'required|max:150',
        ];

        // バリデーションメッセージの日本語化
        $validateMessages = [
            "title.required" => "タイトルは必須入力です。",
            "start_date.required" => "開始日は必須入力です。",
            "end_date.required" => "終了日は必須入力です。",
            "body.required" => "内容は必須入力です。",
            "title.max" => "15文字以内でご入力ください。",
            "body.max" => "150文字以内でご入力ください。",
            "start_date.after" => "開始日は今日以降の日付を指定してください。",
            "end_date.after" => "終了日は開始日以降の日付を指定してください。"
        ];

        //バリデーションをインスタンス化
        $this->validate($request, $validateRules, $validateMessages);
    }

    public function report_valid($request){
        // バリデーションルール
        $validateRules = [
            'rate' => 'required',
            'body' => 'required|max:150',
        ];

        // バリデーションメッセージの日本語化
        $validateMessages = [
            "rate.required" => "自己評価は必須入力です。",
            "body.required" => "内容は必須入力です。",
            "body.max" => "150文字以内でご入力ください。",
        ];

        //バリデーションをインスタンス化
        $this->validate($request, $validateRules, $validateMessages);
    }
}
