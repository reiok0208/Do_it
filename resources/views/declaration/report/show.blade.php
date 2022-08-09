@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-center" style="margin-bottom:30px;">
                <a class="btn btn-outline-secondary rounded-0 col-md-2 text-center" href="{{ route('declaration.show',['id'=>$declaration->id]) }}">宣言詳細</a>
                <a class="btn btn-outline-secondary rounded-0 col-md-2 text-center disabled">宣言報告</a>
            </div>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">{{ __('宣言報告') }}</div>

                <div class="card-body">
                    <div class="report">
                        <div class="row">
                            <h2 class="col-7 col-md-9">
                                @if($report->execution == 1)
                                    <span>できた</span>
                                @else
                                    <span>できなかった</span>
                                @endif
                            </h2>
                            <p class="col">
                                宣言者：<a href="{{ route('user.show',['id'=>$declaration->user->id]) }}">{{ $declaration->user->name }}</a>
                            </p>
                        </div>
                        <div class="row mb-5">
                            <p>{{ $report->rate }}/5段階中</p>
                        </div>
                        <p>{!! $report->body !!}</p>
                    </div>
                </div>
            </div>

            @if (Auth::check())
                <input id="id" type="hidden" name="id" value="{{ $report->id }}">

                <div class="row">
                    <div class="col-md-10">
                        <textarea id="body" class="form-control @error('body') is-invalid @enderror" name="body" placeholder="コメント内容を入力してください" style="background-color:white;" rows="5">{{ old('body') }}</textarea>
                    </div>
                    <div class="comment__button col-md-2 align-self-end">
                        <button type="submit" class="comment__ajax btn btn-outline-primary">
                            {{ __('コメント投稿') }}
                        </button>
                    </div>
                </div>
                <div class="row mb-3 mx-1">
                    <span class="comment__error invalid-feedback" role="alert" style="display: inline;"></span>
                    <div class="comment__success mt-3 alert alert-success" role="alert" style="display:none;">コメント投稿しました！</div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">{{ __('コメント') }}</div>
                <div class="comment__delete card-body">
                    @include('include.comment')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
