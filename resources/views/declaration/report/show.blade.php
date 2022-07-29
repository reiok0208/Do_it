@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-center" style="margin-bottom:30px;">
                <a class="btn btn-outline-secondary rounded-0 col-md-2 text-center" href="{{ route('declaration.show',['id'=>$declaration->id]) }}">宣言詳細</a>
                <a class="btn btn-outline-secondary rounded-0 col-md-2 text-center disabled">宣言報告</a>
            </div>
            <div class="card">
                <div class="card-header">{{ __('宣言報告') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="dec_box dec_show">
                        <div class="row">
                            <h2 class="col-md-5">
                                @if($report->execution == 1)
                                    <span>できた</span>
                                @else
                                    <span>できなかった</span>
                                @endif
                            </h2>
                            <p class="col text-end">
                                宣言者：<a href="{{ route('user.show',['id'=>$declaration->user->id]) }}">{{ $declaration->user->name }}</a>
                            </p>
                        </div>
                        <div class="row">
                            <p>{{ $report->rate }}/5段階中</p>
                        </div>
                        <p>{!! $report->body !!}</p>
                    </div>
                </div>
            </div>

            @if (Auth::check())
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('report.comment.store') }}">
                    @csrf
                    <input id="report_id" type="hidden" name="report_id" value="{{ $report->id }}">

                    <div class="row mb-3">
                        <div class="col-md-10">
                            <textarea id="body" class="form-control @error('body') is-invalid @enderror" name="body" placeholder="コメント内容を入力してください" required style="background-color:white;" rows="5">{{ old('body') }}</textarea>

                            @error('body')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-2 align-self-end  text-end">
                            <button type="submit" class="btn btn-outline-primary">
                                {{ __('コメント投稿') }}
                            </button>
                        </div>
                    </div>
                </form>
            @endif

            <div class="card">
                <div class="card-header">{{ __('コメント') }}</div>
                <div class="card-body">
                    @include('include.comment')
                </div>
            </div>
            {{ $comments->links() }}
        </div>
    </div>
</div>
@endsection
