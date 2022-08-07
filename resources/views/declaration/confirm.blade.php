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
                <div class="card-header">{{ __('投稿確認') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('declaration.store') }}">
                        @csrf

                        <div class="row mb-5">
                            <label for="title" class="col-md-3  text-md-end">{{ __('タイトル') }}</label>

                            <div class="col-md-7">
                                <p class="confirm_content">{{ $request->title }}</p>
                                <input id="title" type="hidden" name="title" value="{{ $request->title }}">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label for="tag" class="col-md-3  text-md-end">{{ __('タグ') }}</label>

                            <div class="col-md-7">
                                <p class="confirm_content">{{ $request->tag }}</p>
                                <input id="tag" type="hidden" name="tag" value="{{ $request->tag }}">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label for="start_date" class="col-md-3  text-md-end">{{ __('開始日') }}</label>

                            <div class="col-md-7">
                                <p class="confirm_content">{{ $request->start_date }}</p>
                                <input id="start_date" type="hidden" name="start_date" value="{{ $request->start_date }}">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label for="end_date" class="col-md-3  text-md-end">{{ __('終了日') }}</label>

                            <div class="col-md-7">
                                <p class="confirm_content">{{ $request->end_date }}</p>
                                <input id="end_date" type="hidden" name="end_date" value="{{ $request->end_date }}">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label for="body" class="col-md-3  text-md-end">{{ __('内容') }}</label>

                            <div class="col-md-7">
                                <p class="confirm_content">{!! nl2br(e($request->body)) !!}</p>
                                <input id="body" type="hidden" name="body" value="{{ $request->body }}">
                            </div>
                        </div>



                        <div class="row mb-2">
                            <div class="col-md-5 offset-md-5">
                                <button type="submit" class="btn btn-outline-primary" style="width: 100px;">
                                    {{ __('Do it!') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="row mb-2">
                        <div class="col-md-5 offset-md-5">
                            <form method="post" action="{{ route('declaration.create') }}">
                                @csrf
                                <input id="title" type="hidden" name="title" value="{{ $request->title }}">
                                <input id="tag" type="hidden" name="tag" value="{{ $request->tag }}">
                                <input id="start_date" type="hidden" name="start_date" value="{{ $request->start_date }}">
                                <input id="end_date" type="hidden" name="end_date" value="{{ $request->end_date }}">
                                <input id="body" type="hidden" name="body" value="{{ $request->body }}">
                                <button class="btn btn-outline-danger" type="submit" style="width: 100px;">戻る</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
