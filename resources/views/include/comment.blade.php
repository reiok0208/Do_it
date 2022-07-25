<h2 style="font-size:20px;">コメント：{{ $count }}件</h2>
@foreach ($comments as $comment)
    <div class="comment_box">
        <div class="row">
            <p class="col-md-7">
                投稿者：<a href="{{ route('user.show',['id'=>$comment->user->id]) }}">{{ $comment->user->name }}</a>
            </p>
            <p class="col-md-5 text-end">投稿日：{{ $comment->created_at }}</p>
        </div>
        <p>{{ nl2br($comment->body) }}</p>
    </div>
@endforeach
