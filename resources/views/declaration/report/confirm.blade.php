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
                <div class="card-header">{{ __('宣言報告確認') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('declaration.report.store') }}">
                        @csrf
                        <input id="declaration_id" type="hidden" name="declaration_id" value="{{ $request->declaration_id }}">

                        <div class="row mb-5">
                            <label for="rate" class="col-sm-3 col-12 text-sm-end text-center">{{ __('自己評価') }}</label>

                            <div class="col-sm-7 col-12 text-sm-start text-center">
                                <div class="confirm_content">
                                    <!-- 評価の数字を星にする -->
                                    @for ($i=0; $i < 5; $i++)
                                        @php
                                            if($i < $request->rate){
                                                echo "★";
                                            }else{
                                                echo "☆";
                                            }
                                        @endphp
                                    @endfor
                                </div>
                                <input id="rate" type="hidden" name="rate" value="{{ $request->rate }}">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label for="execution" class="col-sm-3 col-12 text-sm-end text-center">{{ __('できましたか？') }}</label>

                            <div class="col-sm-7 col-12 text-sm-start text-center">
                                <p class="confirm_content">
                                    @if($request->execution == 1)
                                        <span>できた</span>
                                    @else
                                        <span>できなかった</span>
                                    @endif
                                </p>
                                <input id="execution" type="hidden" name="execution" value="{{ $request->execution }}">
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label for="body" class="col-sm-3 col-12 text-sm-end text-center">{{ __('報告内容') }}</label>

                            <div class="col-sm-7 col-12 text-sm-start text-center">
                                <p class="confirm_content">{!! nl2br(e($request->body)) !!}</p>
                                <input id="body" type="hidden" name="body" value="{{ $request->body }}">
                            </div>
                        </div>



                        <div class="row mb-2">
                            <div class="col text-center">
                                <button type="submit" class="btn btn-outline-primary" style="width: 150px;">
                                    {{ __('Good work!') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="row mb-2">
                        <div class="col text-center">
                            <form method="post" action="{{ route('declaration.report.create',['id'=>$request->declaration_id]) }}">
                                @csrf
                                <input id="rate" type="hidden" name="rate" value="{{ $request->rate }}">
                                <input id="execution" type="hidden" name="execution" value="{{ $request->execution }}">
                                <input id="body" type="hidden" name="body" value="{{ $request->body }}">
                                <button class="btn btn-outline-danger" type="submit" style="width: 150px;">戻る</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
