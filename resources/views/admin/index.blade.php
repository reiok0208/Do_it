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
                <div class="card-header">{{ __('管理者画面') }}</div>
                <div class="row mb-3 mt-5">
                    <a class="btn btn-outline-secondary col-md-6 offset-md-3" href="{{ route('admin.declaration.index') }}">凍結宣言管理</a>
                </div>
                <div class="row mb-3 mt-3">
                    <a class="btn btn-outline-secondary col-md-6 offset-md-3" href="#">宣言タグ管理</a>
                </div>
                <div class="row mb-5 mt-3">
                    <a class="btn btn-outline-secondary col-md-6 offset-md-3" href="{{ route('admin.user.index') }}">ユーザー管理</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
