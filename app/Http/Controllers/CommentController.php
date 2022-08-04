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
    public function declaration_comment_store(CommentValidationRequest $request)
    {

        $comment = new Declaration_comment;
        $comment->declaration_id = $request->declaration_id;
        $comment->user_id = Auth::user()->id;
        $comment->body = e($request->body);
        $comment->save();

        // 二重送信防止
        $request->session()->regenerateToken();

        return redirect()->back()->with('status', 'コメントを投稿しました！');
    }

    public function report_comment_store(CommentValidationRequest $request)
    {

        $comment = new Report_comment;
        $comment->report_id = $request->report_id;
        $comment->user_id = Auth::user()->id;
        $comment->body = e($request->body);
        $comment->save();

        // 二重送信防止
        $request->session()->regenerateToken();

        return redirect()->back()->with('status', 'コメントを投稿しました！');
    }



    public function declaration_comment_destroy($id)
    {
        $comment = Declaration_comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('status', 'コメントを削除しました！');
    }

    public function report_comment_destroy($id)
    {
        $comment = Report_comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('status', 'コメントを削除しました！');
    }

}
