@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('全宣言一覧') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @foreach ($declarations as $dec)
                        <div class="dec_box">
                            <div class="row">
                                <h2 class="col-md-4">{{ $dec->title }}</h2>
                                <div class="col-md-7 text-end">
                                    <span>宣言者：<a href="{{ route('user.show',['id'=>$dec->user->id]) }}">{{ $dec->user->name }}</a>　</span>
                                    <span>宣言日：{{ $dec->created_at->format('Y年m月d日') }}</span>
                                </div>

                                <div class="col-md-1 dropdown text-end" style="position:relative; z-index:100;">
                                    <a class="btn" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">･･･</a>

                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <li><a class="dropdown-item" href="{{ route('declaration.edit',['id'=>$dec->id]) }}">編集</a></li>
                                        <li>
                                            <form method="POST" action="{{ route('declaration.destroy',['id'=>$dec->id]) }}">
                                                @csrf
                                                @method('delete')
                                                <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">削除</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <p>{!! $dec->body !!}</p>
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    @foreach($dec->tags as $tag)
                                        <span class="badge rounded-pill bg-secondary">{{$tag->name}}</span>
                                    @endforeach
                                </div>
                                <div class="col">
                                    <p class="text-end">期間：{{ $dec->start_date->format('Y年m月d日') }}　〜　{{ $dec->end_date->format('Y年m月d日') }}</p>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <a href="{{ route('declaration.show',['id'=>$dec->id]) }}" class="link_box"></a>
                        </div>
                    @endforeach
                </div>
            </div>
            {{ $declarations->links() }}
        </div>
    </div>
</div>
@endsection
