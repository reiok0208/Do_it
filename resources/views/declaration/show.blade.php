@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-center" style="margin-bottom:30px;">
                    <a class="btn btn-outline-secondary rounded-0 col-md-2 text-center disabled">宣言詳細</a>
                    <a class="btn btn-outline-secondary rounded-0 col-md-2 text-center" href="{{ route('declaration.report.create',['id'=>$declaration->id]) }}">宣言報告</a>
            </div>
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
                            <h2 class="col-md-4">{{ $declaration->title }}</h2>
                            <p class="col-md-3 text-end">
                                宣言者：<a href="{{ route('user.show',['id'=>$declaration->user->id]) }}">{{ $declaration->user->name }}</a>
                            </p>
                            <div class="col-md-4 text-end">
                                <p>宣言日：{{ $declaration->created_at->format('Y年m月d日') }}</p>
                                <p>更新日：{{ $declaration->updated_at->format('Y年m月d日') }}</p>
                            </div>
                            <div class="col-md-1 dropdown text-end" style="position:relative; z-index:100;">
                                <a class="btn" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">･･･</a>

                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <li><a class="dropdown-item" href="{{ route('declaration.edit',['id'=>$declaration->id]) }}">編集</a></li>
                                    <li>
                                        <form method="POST" action="{{ route('declaration.destroy',['id'=>$declaration->id]) }}">
                                            @csrf
                                            @method('delete')
                                            <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">削除</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <p>{!! $declaration->body !!}</p>
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                @foreach($declaration->tags as $tag)
                                    <span class="badge rounded-pill bg-secondary">{{$tag->name}}</span>
                                @endforeach
                            </div>
                            <div class="col text-end">
                                <p>期間： {{ $declaration->start_date->format('Y年m月d日') }}</p>
                                <p> 〜 {{ $declaration->end_date->format('Y年m月d日') }}</p>
                            </div>
                            <div class="col-md-1"></div>
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
