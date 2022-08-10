@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('ユーザー詳細') }}</div>

                <div class="card-body">
                    <div class="user-show">
                        <div class='row justify-content-between'>
                            <div class="col-2">
                                @if ($user->image != null)
                                    <img class="user-show__image" src="{{ Storage::url($user->image) }}" alt="アイコン画像" width="85px">
                                @else
                                    <img class="user-show__image" src="{{ asset('img/default_icon.png') }}" alt="アイコン画像" width="85px">
                                @endif
                            </div>
                            <div class="user-show__text col-6">
                                <p>ユーザー名：{{ $user->name }}</p>
                                @if (Auth::id() == $user->id)
                                    <p>メールアドレス：{{ $user->email }}</p>
                                @endif
                                <p>自己紹介：{{ $user->body }}</p>
                            </div>
                            <div class="col-4 text-end">
                                <div class="user-show__follow mb-3">
                                    <a href="{{ route('user.follows',['id'=>$user->id]) }}">{{ $user->follows->count() }}フォロー</a>
                                    <a class="follow__count" href="{{ route('user.followers',['id'=>$user->id]) }}">{{ $user->followers->count() }}フォロワー</a>
                                </div>
                                @if ($user->id != Auth::id())
                                    @if ($followed == null)
                                        <button class="follow__button btn btn-outline-primary btn-sm follow-toggle mb-1" data-follow-id="{{ $user->id }}" style="position:relative; z-index:100;">フォロー</button>
                                    @else
                                        <button class="unfollow__button btn btn-outline-danger btn-sm follow-toggle mb-1" data-follow-id="{{ $user->id }}" style="position:relative; z-index:100;">フォロー解除</button>
                                    @endif
                                @endif
                                @if (Auth::id() == $user->id)
                                    <a class="user-show__button btn btn-outline-primary text-center btn-sm w-30 user_edit mb-1" href="{{ route('user.edit') }}">ユーザー編集</a><br>
                                    @if (Auth::user()->admin == 0)
                                        <a class="user-show__button btn btn-outline-danger text-center btn-sm w-30 mb-1" href="{{ route('user.delete') }}">ユーザー削除</a>
                                    @endif
                                @endif
                                @if(Auth::id() != $user->id && Auth::user()->admin == 1)
                                    @if ($user->del_flg == 0)
                                        <form method="POST" action="{{ route('admin.user.frozen',['id'=>$user->id]) }}">
                                            @csrf
                                            <button class="user-show__button delete btn btn-outline-danger btn-sm text-center w-30 mb-1" type="submit">ユーザー凍結</button>
                                        </form>
                                    @elseif($user->del_flg == 1)
                                        <form method="POST" action="{{ route('admin.user.lift',['id'=>$user->id]) }}">
                                            @csrf
                                            <button class="user-show__button delete btn btn-outline-primary btn-sm text-center w-30 mb-1" type="submit">ユーザー凍結解除</button>
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
