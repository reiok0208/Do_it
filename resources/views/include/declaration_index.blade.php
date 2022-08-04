<!-- 完了メッセージ表示 -->
@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif
<div class="card">
    <div class="card-header">
        @if (preg_match("/user/", url()->current())){{ __('ユーザー宣言一覧') }} @if(!empty($sort))-{{ $sort }}-@endif
        @elseif(preg_match("/admin\/declaration/", url()->current())){{ __('凍結宣言一覧') }} @if(!empty($sort))-{{ $sort }}-@endif
        @else{{ __('全宣言一覧') }} @if(!empty($sort))-{{ $sort }}-@endif
        @endif
    </div>
    <div class="card-body">
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
                                    @if($dec->user_id == Auth::id())
                                        <li><a class="dropdown-item" href="{{ route('declaration.edit',['id'=>$dec->id]) }}">編集</a></li>
                                    @endif
                                    <li>
                                        @if ($dec->user_id == Auth::id() || Auth::user()->admin != 1)
                                            <form method="POST" action="{{ route('declaration.destroy',['id'=>$dec->id]) }}">
                                                @csrf
                                                @method('delete')
                                                <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">削除</button>
                                            </form>
                                        @elseif (Auth::user()->admin == 1 && $dec->del_flg == 0)
                                            <form method="POST" action="{{ route('admin.declaration.frozen',['id'=>$dec->id]) }}">
                                                @csrf
                                                <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">凍結</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.declaration.lift',['id'=>$dec->id]) }}">
                                                @csrf
                                                <button class="delete dropdown-item btn btn-link" style="text-decoration:none; color:black; border-radius:0;" type="submit">凍結解除</button>
                                            </form>
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
                                <a href="#" class="declaration__badge badge rounded-pill bg-secondary">{{$tag->name}}</a>
                            @endforeach
                        </div>
                        <div class="declaration__like col-md-3" style="position:relative; z-index:100;">
                            <!-- 認証ユーザーの場合 -->
                            @auth
                                <!-- 終了期間を過ぎていなければDo it!を表示、それ以外はGood work!を表示 -->
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
                        <div class="col">
                            <p class="declaration__date--bottom text-end">期間：{{ $dec->start_date->format('Y年m月d日') }}　〜　{{ $dec->end_date->format('Y年m月d日') }}</p>
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
{{ $declarations->links() }}
