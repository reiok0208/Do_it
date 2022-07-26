<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Declaration;
use App\Models\Declaration_comment;
use App\Models\Tag;

class DeclarationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $declarations = Declaration::paginate(20);
        return view('declaration.index', compact('declarations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->put([
            '_old_input' => [
                'title' => $request->title,
                'tag' => $request->tag,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'body' => $request->body
            ]
        ]);
        return view('declaration.create');
    }

    public function confirm(Request $request)
    {
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
        $declaration->tags()->attach($tags_id);

        // 二重送信防止
        $request->session()->regenerateToken();

        return redirect()->route('declaration.show', ['id' => $declaration->id]);
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
        $comments = Declaration_comment::whereDeclarationId($id)->get();
        $count = Declaration_comment::whereDeclarationId($id)->count();
        return view('declaration.show', compact('declaration','comments','count'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
