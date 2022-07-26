@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('宣言詳細') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="dec_box dec_show">
                        <div class="row">
                            <h2 class="col-md-5">{{ $declaration->title }}</h2>
                            <p class="col-md-3 text-end">
                                宣言者：<a href="{{ route('user.show',['id'=>$declaration->user->id]) }}">{{ $declaration->user->name }}</a>
                            </p>
                            <div class="col-md-4 text-end">
                                <p>宣言日：{{ $declaration->created_at->format('Y年m月d日') }}</p>
                                <p>更新日：{{ $declaration->updated_at->format('Y年m月d日') }}</p>
                            </div>
                        </div>
                        <p>{{ nl2br($declaration->body) }}</p>
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                @foreach($declaration->tags as $tag)
                                    <span class="badge rounded-pill bg-secondary">{{$tag->name}}</span>
                                @endforeach
                            </div>
                            <div class="col text-end">
                                <p>期間： {{ $declaration->start_date->format('Y年m月d日H時i分') }}</p>
                                <p> 〜 {{ $declaration->end_date->format('Y年m月d日H時i分') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">{{ __('コメント') }}</div>
                <div class="card-body">
                    @include('include.comment')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
