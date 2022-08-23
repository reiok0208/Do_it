@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-center" style="margin-bottom:30px;">
                <a class="declaration-show__nav btn btn-outline-secondary rounded-0 col-lg-2 col-4 text-center disabled">宣言詳細</a>
                @if ($declaration->report != null)
                    <a class="declaration-show__nav btn btn-outline-secondary rounded-0 col-lg-2 col-4 text-center end_date" href="{{ route('declaration.report.show',['id'=>$declaration->report->id]) }}">宣言報告</a>
                @else
                    @if(Auth::user() && $declaration->user_id == Auth::id())
                        <a class="declaration-show__nav btn btn-outline-secondary rounded-0 col-lg-2 col-4 text-center end_date" href="{{ route('declaration.report.create',['id'=>$declaration->id]) }}">宣言報告(未提出)</a>
                    @else
                        <a class="declaration-show__nav btn btn-outline-secondary rounded-0 col-lg-2 col-4 text-center end_date disabled">宣言報告(未提出)</a>
                    @endif
                @endif
            </div>
            @if (session('null_report'))
                <div class="alert alert-danger" role="alert">
                    {{ session('null_report') }}
                </div>
            @endif
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">{{ __('宣言詳細') }}</div>

                <div class="card-body">

                    <div class="declaration-show">
                        <div class="row">
                            <h2 class="declaration-show__title col-lg-5">{{ $declaration->title }}</h2>
                            <p class="declaration-show__user col" style="position:relative; z-index:200;">
                                宣言者：<a href="{{ route('user.show',['id'=>$declaration->user->id]) }}">{{ $declaration->user->name }}</a>
                            </p>
                            <div class="declaration-show__date--top col text-end">
                                <p>宣言日：{{ $declaration->created_at->format('Y年m月d日') }}</p>
                                <p>更新日：{{ $declaration->updated_at->format('Y年m月d日') }}</p>
                            </div>
                            <!-- 編集ボタン、削除ボタンの権限分岐 -->
                            @if (Auth::user() && ($declaration->user_id == Auth::id() || Auth::user()->admin == 1))
                                <div class="col-md-1 dropdown text-end" style="position:relative; z-index:100;">
                                    <a class="btn" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">･･･</a>

                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        @if($declaration->user_id == Auth::id() && strtotime(date('Y/m/d')) < strtotime($declaration->start_date))
                                            <li><a class="dropdown-item" href="{{ route('declaration.edit',['id'=>$declaration->id]) }}">編集</a></li>
                                        @elseif($declaration->user_id == Auth::id() && strtotime(date('Y/m/d')) > strtotime($declaration->start_date))
                                            <p style="margin-left:16px;">開始日以降の<br>編集はできません</p>
                                        @endif
                                        <li>
                                            @if ($declaration->user_id == Auth::id() && (strtotime($declaration->start_date) > strtotime(date('Y/m/d')) || strtotime(date('Y/m/d')) > strtotime($declaration->end_date)))
                                                <form method="POST" action="{{ route('declaration.destroy',['id'=>$declaration->id]) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">削除</button>
                                                </form>
                                            @elseif ($declaration->user_id != Auth::id() && Auth::user()->admin == 1 && $declaration->del_flg == 0)
                                                <form method="POST" action="{{ route('admin.declaration.frozen',['id'=>$declaration->id]) }}">
                                                    @csrf
                                                    <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">凍結</button>
                                                </form>
                                            @elseif ($declaration->user_id != Auth::id())
                                                <form method="POST" action="{{ route('admin.declaration.lift',['id'=>$declaration->id]) }}">
                                                    @csrf
                                                    <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">凍結解除</button>
                                                </form>
                                            @else
                                                <p style="margin-left:16px;">期間中の<br>削除はできません</p>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <div class="col-md-1"></div>
                            @endif
                        </div>
                        <p class="declaration-show__body">{!! $declaration->body !!}</p>
                        <div class="row align-items-end">
                            <div class="col-lg-5">
                                <!-- タグ検索 -->
                                @foreach($declaration->tags as $tag)
                                    @if (Request::routeIs('declaration.show'))
                                        {{ Request::session()->forget(['sort_by','search_by','tag_by']) }}
                                        <a href="/declaration/tag_by?tag_by={{ $tag->name }}" class="declaration__badge badge rounded-pill bg-secondary">{{$tag->name}}</a>
                                    @else
                                        <span class="badge rounded-pill bg-secondary">{{$tag->name}}</span>
                                    @endif
                                @endforeach
                            </div>
                            <div class="declaration-show__like col" style="position:relative; z-index:100;">
                                <!-- 認証ユーザーの場合 -->
                                @auth
                                    <!-- レポートがnullであればDo it!を表示、それ以外はGood work!を表示 -->
                                    @if (strtotime(date('Y/m/d')) < strtotime($declaration->end_date))
                                        <!-- 認証ユーザーが宣言にDo it!を押しているか？ -->
                                        @if (!$declaration->isDoItBy(Auth::user()))
                                            <span class="likes">
                                                <i class="fa-solid fa-hand-point-left fa-lg do-it-toggle" data-declaration-id="{{ $declaration->id }}">
                                                    <span class="do-it-counter">{{$declaration->do_it_count}}</span> Do it!
                                                </i>

                                            </span>
                                        @else
                                            <span class="likes">
                                                <i class="fa-solid fa-hand-point-left fa-lg do-it-toggle liked" data-declaration-id="{{ $declaration->id }}">
                                                    <span class="do-it-counter">{{$declaration->do_it_count}}</span> Do it!
                                                </i>
                                            </span>
                                        @endif
                                    @else
                                        <!-- 認証ユーザーが宣言にGood work!を押しているか？ -->
                                        @if (!$declaration->isGoodWorkBy(Auth::user()))
                                            <span class="likes">
                                                <i class="fa-solid fa-hands-clapping fa-lg good-work-toggle" data-declaration-id="{{ $declaration->id }}">
                                                    <span class="good-work-counter">{{$declaration->good_work_count}}</span> Good work!
                                                </i>

                                            </span>
                                        @else
                                            <span class="likes">
                                                <i class="fa-solid fa-hands-clapping fa-lg good-work-toggle liked" data-declaration-id="{{ $declaration->id }}">
                                                    <span class="good-work-counter">{{$declaration->good_work_count}}</span> Good work!
                                                </i>
                                            </span>
                                        @endif
                                    @endif
                                @endauth
                                <!-- ゲストの場合 -->
                                @guest
                                    @if (strtotime(date('Y/m/d')) < strtotime($declaration->end_date))
                                        <span class="likes">
                                            <i class="fa-solid fa-hand-point-left fa-lg">
                                                <span class="do-it-counter">{{$declaration->do_it_count}}</span> Do it!
                                            </i>
                                        </span>
                                    @else
                                        <span class="likes">
                                            <i class="fa-solid fa-hands-clapping fa-lg">
                                                <span class="good-work-counter">{{$declaration->good_work_count}}</span> Good work!
                                            </i>
                                        </span>
                                    @endif
                                @endguest
                            </div>
                            <div class="declaration-show__date--bottom col text-end">
                                <p>期間： {{ $declaration->start_date->format('Y年m月d日') }}</p>
                                <p> 〜 {{ $declaration->end_date->format('Y年m月d日') }}</p>
                            </div>
                            <div class="declaration__share col-md-1 text-end">
                                @inject('DeclarationController', 'App\Http\Controllers\DeclarationController')
                                <a href="{{ $DeclarationController->twitter_share($declaration) }}" target="_blank" rel="noopener noreferrer">
                                    <i class="fa-brands fa-twitter fa-2x" style="color: #1D9CF1;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (Auth::check() && $declaration->report == null)
                <input id="id" type="hidden" name="id" value="{{ $declaration->id }}">

                <div class="row">
                    <div class="col-md-10">
                        <textarea id="body" class="form-control @error('body') is-invalid @enderror" name="body" placeholder="コメント内容を入力してください" style="background-color:white;" rows="5">{{ old('body') }}</textarea>
                    </div>
                    <div class="comment__button col-md-2 align-self-end">
                        <button type="submit" class="comment__ajax btn btn-outline-primary">
                            {{ __('コメント投稿') }}
                        </button>
                    </div>
                </div>
                <div class="row mb-3 mx-1">
                    <span class="comment__error invalid-feedback" role="alert" style="display: inline;"></span>
                    <div class="comment__success mt-3 alert alert-success" role="alert" style="display:none;">コメント投稿しました！</div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">{{ __('コメント') }}</div>
                <div class="comment__delete card-body">
                    @include('include.comment')
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // reportが書ける期間かどうかjavascriptに判定を送る
    const end_date = @json(strtotime(date('Y/m/d')) > strtotime($declaration->end_date));
</script>
@endsection
