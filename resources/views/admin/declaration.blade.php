@extends('layouts.app')

@section('content')

<!-- 管理画面ではいいねボタンは不必要のため非表示 -->
<style>
    .declaration__like{
        display: none;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('include.declaration_index')
        </div>
    </div>
</div>
@endsection
