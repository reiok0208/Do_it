@extends('layouts.app')
@section('content')
<body>





@if (session('status'))
@endif
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">ユーザー情報編集</div>
                    <div class="card-body">

                        <form method="POST" action="/user/edit/info" enctype="multipart/form-data" style="border-bottom: solid 1px rgba(200,200,200);">
                        @csrf
                            <div class="form-group row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-end">ユーザー名変更</label>
                                <div class="col-md-6">
                                    <input id="name" name="name" value="{{$auth["name"]}}" class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="body" class="col-md-4 col-form-label text-end">自己紹介変更</label>
                                <div class="col-md-6">
                                    <textarea id="body" name="body" class="form-control @error('body') is-invalid @enderror" style="height:100px;">{{$auth["body"]}}</textarea>
                                    @error('body')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="image" class="col-md-4 col-form-label text-md-end">{{ __('アイコン画像') }}</label>

                                <div class="col-md-6">
                                    <input type="file" name="image" accept="image/png, image/jpeg" class="form-control">

                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <input type="hidden" name="UserId" value={{$auth["id"]}}>
                                <button dusk="view-button" class="btn btn-primary col-md-1 h-25">更新</button>
                            </div>
                        </form>

                        <form method="POST" action="/user/edit/email" style="border-bottom: solid 1px rgba(200,200,200);">
                        @csrf
                            <div class="form-group row mb-4 mt-4">
                                <label for="Email" class="col-md-4 col-form-label text-end">メールアドレス変更</label>
                                <div class="col-md-6">
                                    <input id="Email" name="Email" value="{{$auth["Email"]}}" class="form-control @error('Email') is-invalid @enderror">
                                    @error('Email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <input type="hidden" name="UserId" value={{$auth["id"]}}>
                                <button dusk="view-button" class="btn btn-primary col-md-1 h-25">更新</button>
                            </div>
                        </form>

                        <form method="POST" action="/user/edit/password">
                        @csrf
                            <div class="form-group row mb-4 mt-4">
                                <label for="password"  class="col-md-4 col-form-label text-end">現在のパスワードを入力</label>
                                <div class="col-md-6">
                                    <input  type="password" id="password"  name="CurrentPassword" class="form-control @error('CurrentPassword') is-invalid @enderror">
                                    @error('CurrentPassword')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-end">新規パスワードを入力</label>
                                <div class="col-md-6">
                                    <input type="password" id="password" name="newPassword" class="form-control @error('password') is-invalid @enderror" name="newPassword">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-end">新規パスワードを再入力</label>
                                <div class="col-md-6">
                                    <input  type="password" id="password" name="newPassword_confirmation" class="form-control @error('newPassword') is-invalid @enderror" name="newPassword">
                                    @error('newPassword')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <input type="hidden" name="UserId" value={{$auth["id"]}}>
                                <button dusk="view-button" class="btn btn-primary col-md-1 h-25">更新</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</body>
@endsection
