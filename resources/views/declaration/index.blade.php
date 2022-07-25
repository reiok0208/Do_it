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
                                <h2 class="col-md-5">{{ $dec->title }}</h2>
                                <p class="col-md-3 text-end">
                                    宣言者：<a href="{{ route('user.show',['id'=>$dec->user->id]) }}">{{ $dec->user->name }}</a>
                                </p>
                                <p class="col-md-4 text-end">宣言日：{{ $dec->created_at->format('Y年m月d日') }}</p>
                            </div>
                            <p>{{ nl2br($dec->body) }}</p>
                            <div class="row">
                                <p class="col-md-4"></p>
                                <div class="col">
                                    <p class="text-end">期間：　{{ $dec->start_date->format('Y年m月d日H時i分') }}</p>
                                    <p class="text-end"> 〜 {{ $dec->end_date->format('Y年m月d日H時i分') }}</p>
                                </div>
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
