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
                <div class="card-header">{{ __('タグ管理') }}</div>

                <div class="card-body">
                    @if (session('record'))
                        <div class="alert alert-light" role="alert">
                            {{ session('record') }}
                        </div>
                    @endif
                    @foreach ($tags as $tag)
                        <div class="follow" style="position: relative;">
                            <div class="row">
                                <div class="col-10 offset-1">
                                    <div class="row">
                                        <div class="follow__name col-10">
                                            {{ "ID:".$tag->id."　タグ名：".$tag->name }}
                                        </div>
                                        <div class="user__other col-2 dropdown text-end" style="position: relative; z-index:100;">
                                            <a class="btn" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">･･･</a>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <li>
                                                    <form method="POST" action="{{ route('admin.tag.destroy',['id'=>$tag->id]) }}">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">削除</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            {{ $tags->links() }}
        </div>
    </div>
</div>
@endsection
