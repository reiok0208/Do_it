@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form method="get" action="">
                <div class="form-group row mb-3 text-end">
                    <label for="Email" class="col-form-label col-md-1 offset-md-8">並び替え</label>
                    <div class="col-md-2">
                        <select class="form-select" aria-label="Default select example">
                            <option selected>selected</option>
                            <option value="#"></option>
                        </select>
                    </div>
                </div>
            </form>
            <form method="get" action="">
                <div class="form-group row mb-3 text-end">
                    <label for="Email" class="col-form-label col-md-1 offset-md-8">宣言検索</label>
                    <div class="col-md-2">
                        <input type="text" id="search" name="search" class="form-control">
                    </div>
                    <button class="btn btn-primary col-md-1 h-25" style="width: 80px;">検索</button>
                </div>
            </form>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">{{ __('凍結宣言一覧') }}</div>

                <div class="card-body">
                    @foreach ($declarations as $dec)

                        <div class="declaration">
                            <div class="row">
                                <h2 class="declaration__title col-md-4">{{ $dec->title }}</h2>
                                <div class="declaration__date--top col-md-7 text-end">
                                    <span>宣言者：<a href="{{ route('user.show',['id'=>$dec->user->id]) }}">{{ $dec->user->name }}</a>　</span>
                                    <span>宣言日：{{ $dec->created_at->format('Y年m月d日') }}</span>
                                </div>

                                <div class="col-md-1 dropdown text-end" style="position:relative; z-index:100;">
                                    <a class="btn" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">･･･</a>

                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <li>
                                            <form method="POST" action="{{ route('admin.declaration.lift',['id'=>$dec->id]) }}">
                                                @csrf
                                                <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">凍結解除</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-11">
                                    <p class="declaration__body">{!! $dec->body !!}</p>
                                </div>
                            </div>
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    @foreach($dec->tags as $tag)
                                        <a href="#" class="declaration__badge badge rounded-pill bg-secondary">{{$tag->name}}</a>
                                    @endforeach
                                </div>

                                <div class="col">
                                    <p class="declaration__date--bottom text-end">期間：{{ $dec->start_date->format('Y年m月d日') }}　〜　{{ $dec->end_date->format('Y年m月d日') }}</p>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
            {{ $declarations->links() }}
        </div>
    </div>
</div>
@endsection
