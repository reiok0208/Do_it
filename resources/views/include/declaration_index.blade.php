<!-- 完了メッセージ表示 -->
@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif
<div class="card">
    <div class="card-header">
        @if (preg_match("/user/", url()->current())){{ __('ユーザー宣言一覧') }}
        @elseif(preg_match("/admin\/declaration/", url()->current())){{ __('凍結宣言一覧') }}
        @else{{ __('全宣言一覧') }}
            @if(!empty($sort) && !empty($search))　　ワード検索：{{ $search }}　並び替え：{{ $sort }}
            @elseif(!empty($sort))　　並び替え：{{ $sort }}
            @elseif(!empty($search))　　ワード検索：{{ $search }}
            @elseif(!empty($tag))　　タグ検索：{{ $tag }}
            @endif
        @endif
    </div>
    <div class="card-body">
        @if (session('record'))
            <div class="alert alert-light" role="alert">
                {{ session('record') }}
            </div>
        @endif
        @foreach ($declarations as $dec)
            @if ($dec->del_flg == 0 || !empty(Auth::user()->admin) == 1)
            <!-- del_flg == 0の宣言 -->
                <div class="declaration">
                    <div class="row">
                        <h2 class="declaration__title col-md-4">
                            {{ $dec->title }}
                            @if (!empty(Auth::user()->admin) == 1 && $dec->del_flg == 1)<span style="color:red;">(凍結中)</span>@endif
                        </h2>
                        <div class="declaration__date--top col-md-7 text-end">
                            <span>宣言者：<a href="{{ route('user.show',['id'=>$dec->user->id]) }}">{{ $dec->user->name }}</a>　</span>
                            <span>宣言日：{{ $dec->created_at->format('Y年m月d日') }}</span>
                        </div>
                        <!-- 編集ボタン、削除ボタンの権限分岐 -->
                        @if (Auth::user() && ($dec->user_id == Auth::id() || Auth::user()->admin == 1))
                            <div class="col-md-1 dropdown text-end" style="position:relative; z-index:100;">
                                <a class="btn" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">･･･</a>

                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <!-- 認証ユーザーの宣言か && (現在日 < 開始日) -->
                                    @if($dec->user_id == Auth::id() && strtotime(date('Y/m/d')) < strtotime($dec->start_date))
                                        <li><a class="dropdown-item" href="{{ route('declaration.edit',['id'=>$dec->id]) }}">編集</a></li>
                                    <!-- 認証ユーザーの宣言か && (現在日 > 開始日) -->
                                    @elseif($dec->user_id == Auth::id() && strtotime(date('Y/m/d')) > strtotime($dec->start_date))
                                        <p style="margin-left:16px;">開始日以降の<br>編集はできません</p>
                                    @endif
                                    <li>
                                        <!-- 認証ユーザーの宣言か && (開始日 > 現在日 || 現在日 > 終了日) -->
                                        @if ($dec->user_id == Auth::id() && (strtotime($dec->start_date) > strtotime(date('Y/m/d')) || strtotime(date('Y/m/d')) > strtotime($dec->end_date)))
                                            <form method="POST" action="{{ route('declaration.destroy',['id'=>$dec->id]) }}">
                                                @csrf
                                                @method('delete')
                                                <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">削除</button>
                                            </form>
                                        <!-- 認証ユーザーの宣言ではなく管理者かつdel_flg == 0の宣言 -->
                                        @elseif ($dec->user_id != Auth::id() && Auth::user()->admin == 1 && $dec->del_flg == 0)
                                            <form method="POST" action="{{ route('admin.declaration.frozen',['id'=>$dec->id]) }}">
                                                @csrf
                                                <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">凍結</button>
                                            </form>
                                        <!-- 認証ユーザーの宣言ではなく管理者かつdel_flg == 1の宣言 -->
                                        @elseif ($dec->user_id != Auth::id() && Auth::user()->admin == 1 && $dec->del_flg == 1)
                                            <form method="POST" action="{{ route('admin.declaration.lift',['id'=>$dec->id]) }}">
                                                @csrf
                                                <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">凍結解除</button>
                                            </form>
                                        <!-- 認証ユーザーの宣言か && (開始日 < 現在日 || 現在日 < 終了日) -->
                                        @elseif ($dec->user_id == Auth::id() && (strtotime($dec->start_date) < strtotime(date('Y/m/d')) || strtotime(date('Y/m/d')) < strtotime($dec->end_date)))
                                            <p style="margin-left:16px;">期間中の<br>削除はできません</p>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-11">
                            <p class="declaration__body">{!! $dec->body !!}</p>
                        </div>
                    </div>
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            @foreach($dec->tags as $tag)
                                @if (Request::routeIs('declaration.index'))
                                    {{ Request::session()->forget(['sort_by','search_by','tag_by']) }}
                                    <a href="/declaration/tag_by?tag_by={{ $tag->name }}" class="declaration__badge badge rounded-pill bg-secondary">{{$tag->name}}</a>
                                @else
                                    <span class="badge rounded-pill bg-secondary">{{$tag->name}}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="declaration__like col-md-4" style="position:relative; z-index:100;">
                            <!-- 認証ユーザーの場合 -->
                            @auth
                                <!-- レポートがnullであればDo it!を表示、それ以外はGood work!を表示 -->
                                @if (strtotime(date('Y/m/d')) < strtotime($dec->end_date))
                                    <!-- 認証ユーザーが宣言にDo it!を押しているか？ -->
                                    @if (!$dec->isDoItBy(Auth::user()))
                                        <span class="likes">
                                            <i class="fa-solid fa-hand-point-left fa-lg do-it-toggle" data-declaration-id="{{ $dec->id }}">
                                                <span class="do-it-counter">{{$dec->do_it_count}}</span> Do it!
                                            </i>

                                        </span>
                                    @else
                                        <span class="likes">
                                            <i class="fa-solid fa-hand-point-left fa-lg do-it-toggle liked" data-declaration-id="{{ $dec->id }}">
                                                <span class="do-it-counter">{{$dec->do_it_count}}</span> Do it!
                                            </i>
                                        </span>
                                    @endif
                                @else
                                    <!-- 認証ユーザーが宣言にGood work!を押しているか？ -->
                                    @if (!$dec->isGoodWorkBy(Auth::user()))
                                        <span class="likes">
                                            <i class="fa-solid fa-hands-clapping fa-lg good-work-toggle" data-declaration-id="{{ $dec->id }}">
                                                <span class="good-work-counter">{{$dec->good_work_count}}</span> Good work!
                                            </i>
                                        </span>
                                    @else
                                        <span class="likes">
                                            <i class="fa-solid fa-hands-clapping fa-lg good-work-toggle liked" data-declaration-id="{{ $dec->id }}">
                                                <span class="good-work-counter">{{$dec->good_work_count}}</span> Good work!
                                            </i>
                                        </span>
                                    @endif
                                @endif
                            @endauth
                            <!-- ゲストの場合 -->
                            @guest
                                @if (strtotime(date('Y/m/d')) < strtotime($dec->end_date))
                                    <span class="likes">
                                        <i class="fa-solid fa-hand-point-left fa-lg">
                                            <span class="do-it-counter">{{$dec->do_it_count}}</span> Do it!
                                        </i>
                                    </span>
                                @else
                                    <span class="likes">
                                        <i class="fa-solid fa-hands-clapping fa-lg">
                                            <span class="good-work-counter">{{$dec->good_work_count}}</span> Good work!
                                        </i>
                                    </span>
                                @endif
                            @endguest
                        </div>
                        <div class="col text-md-end text-center">
                            <p class="declaration__date--bottom">期間：{{ $dec->start_date->format('Y年m月d日') }}</p>
                            <p class="declaration__date--bottom">〜 {{ $dec->end_date->format('Y年m月d日') }}</p>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                    <a href="{{ route('declaration.show',['id'=>$dec->id]) }}" class="link_box"></a>
                </div>
            @else
                <!-- del_flg == 1の凍結された宣言 -->
                <div class="declaration">
                    <div class="row">
                        <h2 class="declaration__title col-md-4">{{ __('凍結された宣言') }}</h2>
                    </div>
                    <div class="row">
                        <div class="col-md-11">
                            <p class="declaration__body">{{ __('管理者によって凍結された宣言です') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
{{ $declarations->appends(request()->query())->links() }}
