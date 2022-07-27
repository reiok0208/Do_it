require('./bootstrap');

$(function () {
    $(".delete").on("click", function (e) {
        var result = window.confirm("本当に削除しますか？");

        if (!result) {
            e.preventDefault();
        }
    });
});
