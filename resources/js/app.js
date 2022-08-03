require('./bootstrap');

$(function () {
    $(".delete").on("click", function (e) {
        var result = window.confirm("本当によろしいですか？");

        if (!result) {
            e.preventDefault();
        }
    });
});


// JSON
// いいね関連
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


// 終了日前の宣言報告ページは遷移禁止
$('.end_date').on('click', function(e){
    if(!end_date){
        alert('終了日を過ぎていません。\n報告は終了日を過ぎたら閲覧・入力できます。');
        e.preventDefault();
    }
});
