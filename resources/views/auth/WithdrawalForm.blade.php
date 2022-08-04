@extends('layouts.app')
@section('content')
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">アカウント削除</div>
                <div class="card-body row">
                    <div class="offset-lg-1 offset-xl-2 mb-5 mt-3" style="color: red; font-size: 18px;">
                        <p>1.削除後、アカウントの復元はできません。</p>
                        <p>2.あなたの宣言、コメント、FF等の記録は全て削除されます。</p>
                        <p>3.宣言の投稿、ユーザーの閲覧等ができなくなります。</p>
                    </div>
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
                        <div style="text-align: center;">
                            <button class="btn btn-primary delete" style="width: 100px;">削除</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>



</body>
@endsection
