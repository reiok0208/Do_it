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
                <div class="card-header">{{ __('宣言編集') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('declaration.update',['id'=>$declaration->id]) }}">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ old('id') }}">

                        <div class="row mb-3">
                            <label for="title" class="col-md-3 col-form-label text-md-end">{{ __('タイトル') }}</label>

                            <div class="col-md-7">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" autofocus placeholder="宣言タイトルを入力してください">

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tag" class="col-md-3 col-form-label text-md-end">{{ __('タグ') }}</label>

                            <div class="col-md-7">
                                <input id="tag" type="text" class="form-control @error('tag') is-invalid @enderror" name="tag" value="{{ old('tag') }}" placeholder="タグを入力してください　例：#投稿#勉強">

                                @error('tag')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="start_date" class="col-md-3 col-form-label text-md-end">{{ __('開始日') }}</label>

                            <div class="col-md-7">
                                <input id="start_date" type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}">

                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="end_date" class="col-md-3 col-form-label text-md-end">{{ __('終了日') }}</label>

                            <div class="col-md-7">
                                <input id="end_date" type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date') }}">

                                @error('end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="body" class="col-md-3 col-form-label text-md-end">{{ __('内容') }}</label>

                            <div class="col-md-7">
                                <textarea id="body" class="form-control @error('body') is-invalid @enderror" name="body" placeholder="宣言内容を入力してください" rows="10">{{ old('body') }}</textarea>

                                @error('body')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="row mb-0">
                            <div style="text-align: center;">
                                <button type="submit" class="btn btn-outline-primary" style="width: 100px;">
                                    {{ __('編集') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
