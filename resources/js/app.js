require('./bootstrap');

$(function () {
    $(".delete").on("click", function (e) {
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
