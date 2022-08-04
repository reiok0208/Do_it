@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('ユーザー詳細') }}</div>

                <div class="card-body">
                    <div class="user_box">
                        <div class='row justify-content-between'>
                            <div class="col-2">
                                @if ($user->image != null)
                                    <img src="{{ Storage::url($user->image) }}" alt="アイコン画像" width="85px">
                                @else
                                    <img src="{{ asset('img/default_icon.png') }}" alt="アイコン画像" width="85px">
                                @endif
                            </div>
                            <div class="col-6">
                                <p>ユーザー名：{{ $user->name }}</p>
                                @if (Auth::id() == $user->id)
                                    <p>メールアドレス：{{ $user->email }}</p>
                                @endif
                                <p>自己紹介：{{ $user->body }}</p>
                            </div>
                            <div class="col-4 text-end">
                                <div>
                                    <a href="{{ route('user.follows',['id'=>$user->id]) }}">{{ $user->follows->count() }}フォロー</a>
                                    <a href="{{ route('user.followers',['id'=>$user->id]) }}">{{ $user->followers->count() }}フォロワー</a>
                                </div>
                                @if ($user->id != Auth::id())
                                    @if ($followed == null)
                                        <form method="POST" action="{{ route('user.follow',['id'=>$user->id]) }}">
                                            @csrf
                                            <button class="btn btn-outline-primary" type="submit">フォロー</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('user.unfollow',['id'=>$user->id]) }}">
                                            @csrf
                                            <button class="btn btn-outline-danger" type="submit">フォロー解除</button>
                                        </form>
                                    @endif
                                @endif
                                @if (Auth::id() == $user->id)
                                    <a class="btn btn-outline-primary text-center w-30 user_edit" href="{{ route('user.edit') }}">ユーザー編集</a><br>
                                    @if (Auth::user()->admin == 0)
                                        <a class="btn btn-outline-danger text-center w-30" href="{{ route('user.delete') }}">ユーザー削除</a>
                                    @endif
                                @endif
                                @if(Auth::id() != $user->id && Auth::user()->admin == 1)
                                    @if ($user->del_flg == 0)
                                        <form method="POST" action="{{ route('admin.user.frozen',['id'=>$user->id]) }}">
                                            @csrf
                                            <button class="delete btn btn-outline-danger text-center w-30" type="submit">ユーザー凍結</button>
                                        </form>
                                    @elseif($user->del_flg == 1)
                                        <form method="POST" action="{{ route('admin.user.lift',['id'=>$user->id]) }}">
                                            @csrf
                                            <button class="delete btn btn-outline-primary text-center w-30" type="submit">ユーザー凍結解除</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- include/declaration_index.blade.php -->
            @include('include.declaration_index')

        </div>
    </div>
</div>
@endsection
