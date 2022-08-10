@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- 並び替え -->
            <form class="sort_by_form" method="get" action="{{ route('declaration.sort_by') }}">
                <div class="form-group row mb-3 text-end">
                    <label for="sort_by" class="col-form-label col-md-2 offset-md-5 col-lg-3 offset-lg-5">並び替え<br>絞り込み</label>
                    <div class="col-md-3 col-lg-3" style="position:relative; top:12px;">
                        <select class="sort_by_select form-select" name="sort_by" aria-label="Default select example" style="background-color: white;">
                            <option selected>選択してください</option>
                            <option value="宣言が新しい順">宣言が新しい順</option>
                            <option value="宣言が古い順">宣言が古い順</option>
                            <option value="Do_it数順">Do_it数順</option>
                            <option value="Good_work数順">Good_work数順</option>
                            @if (Auth::check())
                                <option value="フォロー中">フォロー中</option>
                            @endif
                        </select>
                    </div>
                </div>
            </form>
            <!-- 宣言検索 -->
            <form method="get" action="{{ route('declaration.search_by') }}">
                <div class="form-group row mb-3 text-end">
                    <label for="search_by" class="col-form-label col-md-2 offset-md-5 col-lg-3 offset-lg-5">宣言検索</label>
                    <div class="col-md-3 col-lg-3">
                        <input type="text" id="search_by" name="search_by" class="form-control @error('search_by') is-invalid @enderror" placeholder="タイトル、内容、タグから検索"  style="background-color: white;">
                        @error('search_by')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-1 text-right h-100">
                        <button class="btn btn-primary h-25 mt-1 mt-md-0" style="width: 70px;">検索</button>
                    </div>
                </div>
            </form>
            <!-- include/declaration_index.blade.php -->
            @include('include.declaration_index')
        </div>
    </div>
</div>
@endsection
