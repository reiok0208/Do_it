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
                    @if (session('follow'))
                        <div class="alert alert-light" role="alert">
                            {{ session('follow') }}
                        </div>
                    @endif
                    @foreach ($follows as $follow)
                        <div class="follow" style="position: relative;">
                            <div class="row">
                                <div class="col-lg-2 offset-lg-1 col-1 text-end">
                                    @if ($follow->image != null)
                                        <img src="{{ Storage::url($follow->image) }}" alt="アイコン画像" width="50px">
                                    @else
                                        <img src="{{ asset('img/default_icon.png') }}" alt="アイコン画像" width="50px">
                                    @endif
                                </div>
                                <div class="col-lg-8 col-11">
                                    <div class="row">
                                        <div class="col-lg-6 col-7">
                                            <p class="follow__name">{{ $follow->name }}</p>
                                        </div>
                                        <div class="follow__follow col-lg-4 col-5 text-end">
                                            <a href="{{ route('user.follows',['id'=>$follow->id]) }}">{{ $follow->follows->count() }}フォロー</a>
                                            <a href="{{ route('user.followers',['id'=>$follow->id]) }}">{{ $follow->followers->count() }}フォロワー</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-7 col-7">
                                            <p class="follow__body">{!! $follow->body !!}</p>
                                        </div>
                                        <div class="col-lg-3 col-5 text-end">
                                            @if ($follow->id != Auth::id())
                                                @if (App\Models\Relationship::where('following_user_id', \Auth::user()->id)->where('user_id', $follow->id)->first() == null)
                                                    <form method="POST" action="{{ route('user.follow',['id'=>$follow->id]) }}">
                                                        @csrf
                                                        <button class="follow__button btn btn-outline-primary btn-sm" type="submit" style="position:relative; z-index:100;">フォロー</button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('user.unfollow',['id'=>$follow->id]) }}">
                                                        @csrf
                                                        <button class="follow__button btn btn-outline-danger btn-sm" type="submit" style="position:relative; z-index:100;">フォロー解除</button>
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
