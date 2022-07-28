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
                <div class="card-header">{{ __('宣言報告投稿') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('declaration.report.confirm') }}">
                        @csrf
                        <input id="declaration_id" type="hidden" name="declaration_id" value="{{ $declaration->id }}">

                        <div class="row mb-3">
                            <label for="title" class="col-md-3 col-form-label text-md-end">{{ __('自己評価') }}</label>

                            <div class="col-md-7">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="rate" id="rate1" value="1" {{ old('rate') == '1' ? 'checked' : '' }}>
                                    <label for="rate1" class="form-check-label">1</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="rate" id="rate2" value="2" {{ old('rate') == '2' ? 'checked' : '' }}>
                                    <label for="rate2" class="form-check-label">2</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="rate" id="rate3" value="3" {{ old('rate') == '3' ? 'checked' : '' }}>
                                    <label for="rate3" class="form-check-label">3</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="rate" id="rate4" value="4" {{ old('rate') == '4' ? 'checked' : '' }}>
                                    <label for="rate4" class="form-check-label">4</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="rate" id="rate5" value="5" {{ old('rate') == '5' ? 'checked' : '' }}>
                                    <label for="rate5" class="form-check-label">5</label>
                                </div>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tag" class="col-md-3 col-form-label text-md-end">{{ __('できましたか？') }}</label>

                            <div class="col-md-7">
                                <select id="execution" class="form-control" name="execution">
                                    <option value='0'>できなかった</option>
                                    <option value='1'>できた</option>
                                </select>

                                @error('tag')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="body" class="col-md-3 col-form-label text-md-end">{{ __('報告内容') }}</label>

                            <div class="col-md-7">
                                <textarea id="body" class="form-control @error('body') is-invalid @enderror" name="body" placeholder="報告内容を入力" required>{{ old('body') }}</textarea>

                                @error('body')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="row mb-0">
                            <div class="col-md-5 offset-md-5">
                                <button type="submit" class="btn btn-outline-primary" style="width: 100px;">
                                    {{ __('確認') }}
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
