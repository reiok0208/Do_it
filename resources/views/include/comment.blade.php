<h2 style="font-size:18px;">　全{{ $count }}件</h2>
@foreach ($comments as $comment)
    <div class="comment">
        <div class="row">
            <p class="comment__title col-md-6">
                投稿者：<a href="{{ route('user.show',['id'=>$comment->user->id]) }}">{{ $comment->user->name }}</a>
            </p>
            <p class="comment__date col-md-5 text-end">投稿日：{{ $comment->created_at }}</p>

            @if (Auth::user() && $comment->user_id == Auth::id())
                <div class="col-md-1 dropdown text-end" style="margin:0;">
                    <a class="btn" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">･･･</a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li>
                            @if(preg_match('/report/',url()->current()))
                                <form method="POST" action="{{ route('report.comment.destroy',['id'=>$comment->id]) }}">
                            @else
                                <form method="POST" action="{{ route('declaration.comment.destroy',['id'=>$comment->id]) }}">
                            @endif
                                @csrf
                                @method('delete')
                                <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">削除</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
        <p class="comment__body">{!! $comment->body !!}</p>
    </div>
@endforeach
