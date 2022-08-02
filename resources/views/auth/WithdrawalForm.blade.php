@extends('layouts.app')
@section('content')
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">アカウント削除</div>
                <div class="card-body">

                    <form method="post" action="/user/edit/Withdrawal">
                    @csrf
                            <div class="form-group row mb-3">
                                <label for="password"  class="col-md-4 col-form-label text-end">現在のパスワード</label>
                                <div class="col-md-5">
                                    <input type="password"  name="CurrentPassword" class="form-control @error('CurrentPassword') is-invalid @enderror">
                                    @error('CurrentPassword')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <input type="hidden" name="UserId" value={{$auth["id"]}}>
                            </div>
                            <div class="form-group row">
                                <button class="btn btn-primary col-md-2 offset-md-5 h-25 delete">削除</button>
                            </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>



</body>
@endsection
