require('./bootstrap');

$(function () {
    $(document).on("click", ".delete", function (e) { //$(document)から指定することでAjaxのhtml遅延読み込み後もイベントとして認識される
        var result = window.confirm("本当によろしいですか？");

        if (!result) {
            e.preventDefault();
        }
    });
});

// 終了日前の宣言報告ページは遷移禁止
$('.end_date').on('click', function(e){
    if(!end_date){
        alert('終了日を過ぎていません。\n報告は終了日を過ぎたら閲覧・入力できます。');
        e.preventDefault();
    }
});


$(function () {
    $(".sort_by_select").change(function() {
        $('.sort_by_form').submit();
    });
});


// JSON
// いいね関連非同期
// 発火後コントローラーへ飛び処理したのちここへ返ってくる(非同期のためviewは介さない)
$(function () {
    let like = $('.do-it-toggle');
    let likeId;
    like.on('click', function () {
        let $this = $(this);
        likeId = $this.data('declaration-id'); //data-declaration-idの値(declaration_id)
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') // フォームの@csrfと同じ
            },
            url: '/do_it',
            method: 'POST',
            data: {
                'declaration_id': likeId //declarationのidをコントローラーへ送り$requestで受け取れる
            },
        })
        //通信成功時
        .done(function (data) {
            $this.toggleClass('liked'); //アイコンに赤色をつける
            $this.children('.do-it-counter').html(data.do_it_count); //返ってきたいいね数を.like-counterに反映
        })
        //通信失敗時
        .fail(function () {
            console.log('fail');
        });
    });
});

$(function () {
    let like = $('.good-work-toggle');
    let likeId;
    like.on('click', function () {
        let $this = $(this);
        likeId = $this.data('declaration-id'); //data-declaration-idの値(declaration_id)
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') // フォームの@csrfと同じ
            },
            url: '/good_work',
            method: 'POST',
            data: {
                'declaration_id': likeId //declarationのidをコントローラーへ送り$requestで受け取れる
            },
        })
        //通信成功時
        .done(function (data) {
            $this.toggleClass('liked'); //アイコンに赤色をつける
            $this.children('.good-work-counter').html(data.good_work_count); //返ってきたいいね数を.like-counterに反映
        })
        //通信失敗時
        .fail(function () {
            console.log('fail');
        });
    });
});

//コメント関連非同期 Declaration,report共用
$(function () {
    let comment = $('.comment__ajax');
    comment.on('click', function () {
        if (location.pathname.match(/\/declaration\/report\/show\//)){
            var post_url = '/report/comment/store';
            var get_url = '/report/comment/index';
        }else{
            var post_url = '/declaration/comment/store';
            var get_url = '/declaration/comment/index';
        }
        let body = document.getElementById('body').value;
        let id = document.getElementById('id').value;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') // フォームの@csrfと同じ
            },
            url: post_url,
            method: 'POST',
            data: {
                'id': id,
                'body': body
            },
        })
        //通信が成功したとき
        .then((data) => {
            $(".comment__delete").empty();
            $.ajax({
                url: get_url,
                method: 'GET',
                data: {
                    'id': data
                },
            })
            .then((data) => {
                $(".comment__error").empty();
                $(".comment__delete").append(data);
                $('textarea').val("");
                $(".comment__success").css("display","block")
            })
        })
        //通信が失敗したとき
        .fail((error) => {
            $(".comment__success").css("display","none");
            $(".comment__error").text(error.responseJSON.errors.body);
        });
    });
});

// フォロー関連非同期
$(function () {
    let follow = $('.follow-toggle');
    let followId;
    follow.on('click', function () {
        let $this = $(this);
        if($this.attr("class").match(/unfollow/)){
            follow_url = '/user/unfollow';
        }else{
            follow_url = '/user/follow';
        }
        followId = $this.data('follow-id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') // フォームの@csrfと同じ
            },
            url: follow_url,
            method: 'POST',
            data: {
                'user_id' : followId
            },
        })
        //通信成功時
        .done(function (data) {
            if ($this.text() == 'フォロー') {
                if (location.pathname.match(/follow/)){
                    $this.parent().parent().prev().children('.follow__follow').children('.follow__count').text(data+"フォロワー");
                }else{
                    $('.follow__count').text(data+"フォロワー");
                }
                $this.removeClass('follow__button btn-outline-primary');
                $this.addClass('unfollow__button btn-outline-danger');
                $this.text('フォロー解除');
            }else{
                if (location.pathname.match(/follow/)){
                    $this.parent().parent().prev().children('.follow__follow').children('.follow__count').text(data+"フォロワー");
                }else{
                    $('.follow__count').text(data+"フォロワー");
                }
                $this.removeClass('unfollow__button btn-outline-danger');
                $this.addClass('follow__button btn-outline-primary');
                $this.text('フォロー');
            }
        })
        //通信失敗時
        .fail(function () {
            console.log('fail');
        });
    });
});
