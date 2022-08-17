<span>検索結果:</span>
@foreach ($users as $user)
    <a href="{{ route('user.show',['id'=>$user->id]) }}">{{ $user->name }}</a><br>
@endforeach

@if($users->isEmpty())
    検索結果なし
@endif
