<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TagController extends Controller
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

    public function index(Request $request)
    {
        $tags = Tag::latest('id')->paginate(20);

        return view('admin.tag', compact('tags'));
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);

        $tag->delete();

        return redirect()->back()->with('status', '削除しました！');
    }
}
