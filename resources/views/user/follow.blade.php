@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">@if(preg_match("/follows/", url()->current())) {{ __($user->name.'さんのフォロー') }} @else {{ __($user->name.'さんのフォロワー') }} @endif</div>

                <div class="card-body">
                    @foreach ($follows as $follow)
                        <div class="dec_box" style="position: relative;">
                            <div class="row">
                                <div class="col-md-2 text-end">
                                    @if ($follow->image != null)
                                        <img src="{{ Storage::url($follow->image) }}" alt="アイコン画像" width="30%">
                                    @else
                                        <img src="{{ asset('img/default_icon.png') }}" alt="アイコン画像" width="30%">
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p>{{ $follow->name }}</p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <a href="{{ route('user.follows',['id'=>$follow->id]) }}">{{ $follow->follows->count() }}フォロー</a>
                                            <a href="{{ route('user.followers',['id'=>$follow->id]) }}">{{ $follow->followers->count() }}フォロワー</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p>{!! $follow->body !!}</p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            @if ($follow->id != Auth::id())
                                                @if (App\Models\Relationship::where('following_user_id', \Auth::user()->id)->where('user_id', $follow->id)->first() == null)
                                                    <form method="POST" action="{{ route('user.follow',['id'=>$follow->id]) }}">
                                                        @csrf
                                                        <button class="btn btn-outline-primary" type="submit" style="position:relative; z-index:100;">フォローする</button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('user.unfollow',['id'=>$follow->id]) }}">
                                                        @csrf
                                                        <button class="btn btn-outline-danger" type="submit" style="position:relative; z-index:100;">フォロー解除する</button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('user.show',['id'=>$follow->id]) }}" class="link_box"></a>
                        </div>
                    @endforeach
                </div>
            </div>
            {{ $follows->links() }}
        </div>
    </div>
</div>
@endsection
