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
                <div class="card-header">{{ __('全ユーザー管理') }}</div>

                <div class="card-body">
                    @foreach ($users as $user)
                        <div class="dec_box" style="position: relative;">
                            <div class="row mb-3">
                                <div class="col-md-2 text-end">
                                    @if ($user->image != null)
                                        <img src="{{ Storage::url($user->image) }}" alt="アイコン画像" width="60px">
                                    @else
                                        <img src="{{ asset('img/default_icon.png') }}" alt="アイコン画像" width="60px">
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-8">
                                            {{ "ID:".$user->id."　ユーザー名：".$user->name }}
                                            <span style="color:red;">@if ($user->del_flg == 1)(凍結中)@endif</span>
                                        </div>
                                        @if ($user->admin == 0)
                                            <div class="col-md-1 dropdown text-end" style="position:relative; z-index:100;">
                                                <a class="btn" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">･･･</a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    <li>
                                                        @if ($user->del_flg == 0)
                                                            <form method="POST" action="{{ route('admin.user.frozen',['id'=>$user->id]) }}">
                                                                @csrf
                                                                <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">凍結</button>
                                                            </form>
                                                        @elseif($user->del_flg == 1)
                                                            <form method="POST" action="{{ route('admin.user.lift',['id'=>$user->id]) }}">
                                                                @csrf
                                                                <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">凍結解除</button>
                                                            </form>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p>{!! $user->body !!}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('user.show',['id'=>$user->id]) }}" class="link_box"></a>
                        </div>
                    @endforeach
                </div>
            </div>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
