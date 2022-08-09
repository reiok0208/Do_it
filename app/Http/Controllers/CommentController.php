<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentValidationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Declaration_comment;
use App\Models\Report_comment;

class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function declaration_comment_index(Request $request)
    {
        $comments = Declaration_comment::latest()->whereDeclarationId($request->id)->get();
        $count = Declaration_comment::whereDeclarationId($request->id)->count();

        return view('include.comment', compact('comments','count'));
    }

    public function report_comment_index(Request $request)
    {
        $comments = Report_comment::latest()->whereReportId($request->id)->get();
        $count = Report_comment::whereReportId($request->id)->count();

        return view('include.comment', compact('comments','count'));
    }

    public function declaration_comment_store(CommentValidationRequest $request)
    {

        $comment = new Declaration_comment;
        $comment->declaration_id = $request->id;
        $comment->user_id = Auth::user()->id;
        $comment->body = e($request->body);
        $comment->save();

        return response()->json($request->id);
    }

    public function report_comment_store(CommentValidationRequest $request)
    {

        $comment = new Report_comment;
        $comment->report_id = $request->id;
        $comment->user_id = Auth::user()->id;
        $comment->body = e($request->body);
        $comment->save();

        return response()->json($request->id);
    }



    public function declaration_comment_destroy($id)
    {
        $comment = Declaration_comment::findOrFail($id);

        if(Auth::user()->admin == 0 && $comment->user_id != Auth::id()){
            abort(403);
        }else if(Auth::user()->admin == 1 || $comment->user_id == Auth::id()){ //管理者には全コメント物理削除権限あり
            $comment->delete();
            return redirect()->back()->with('status', 'コメントを削除しました！');
        }
    }

    public function report_comment_destroy($id)
    {
        $comment = Report_comment::findOrFail($id);

        if(Auth::user()->admin == 0 && $comment->user_id != Auth::id()){
            abort(403);
        }else if(Auth::user()->admin == 1 || $comment->user_id == Auth::id()){ //管理者には全コメント物理削除権限あり
            $comment->delete();
            return redirect()->back()->with('status', 'コメントを削除しました！');
        }
    }

}
