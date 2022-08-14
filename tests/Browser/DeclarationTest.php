<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Declaration;
use Illuminate\Foundation\Testing\WithFaker;

class DeclarationTest extends DuskTestCase
{
    use WithFaker; // Fakerを使用

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_宣言投稿、編集、削除()
    {
        $this->browse(function ($browser) {
            $browser->loginAs($this->user)
                    ->visit('/declaration')
                    ->clickLink('宣言投稿') //宣言投稿
                    ->type('title', 'テストタイトル')
                    ->type('tag', '#テストタグ')
                    ->keys('#start_date', '2099','{tab}','01','01')
                    ->keys('#end_date', '2099','{tab}','12','31')
                    ->type('body', 'テスト内容')
                    ->press('確認')
                    ->assertPathIs('/declaration/new/confirm')
                    ->press('Do it')
                    ->assertSee('投稿しました！','宣言詳細','テストタイトル','テストタグ','テスト内容')
                    ->clickLink('･･･') //宣言編集
                    ->clickLink('編集')
                    ->assertSee('宣言編集')
                    ->type('title', '編集テストタイトル')
                    ->type('tag', '#編集テストタグ')
                    ->type('body', '編集テスト内容')
                    ->press('編集')
                    ->assertSee('編集しました！','宣言詳細','編集テストタイトル','編集テストタグ','編集テスト内容')
                    ->clickLink('･･･') //宣言削除
                    ->press('削除')
                    ->waitForDialog($seconds = null)
                    ->assertDialogOpened('本当によろしいですか？')
                    ->acceptDialog()
                    ->assertPathIs('/declaration')
                    ->assertSee('削除しました！','全宣言一覧');
        });
    }



    public function test_宣言報告投稿、DoIt、GoodWorkボタンが表示、カウントするか()
    {
        $this->declaration_after = Declaration::factory()->create([
            'user_id' => $this->user->id,
            'start_date' => $this->faker->dateTimeBetween($startDate = 'now -2 week', $endDate = '-1 week'),
            'end_date' => $this->faker->dateTimeBetween($startDate = 'now  -5 day', $endDate = '-1 day'), // レポートが書ける期間に設定
        ]);

        $this->browse(function ($browser) {
            $browser->loginAs($this->user)
                    ->visit('/')
                    ->assertRouteIs('declaration.show',['id' => $this->declaration_after->id]) //宣言報告未提出の場合は強制的に宣言詳細へ遷移
                    ->assertSee('0 Do it') // 報告未提出はDoItボタンの表示、カウントするか
                    ->click('.likes')
                    ->waitForText('1 Do it')
                    ->clickLink('宣言報告')
                    ->assertRouteIs('declaration.report.create',['id' => $this->declaration_after->id]) //宣言報告を投稿
                    ->radio('rate', '3')
                    ->select('execution')
                    ->type('body', 'テスト報告内容')
                    ->press('確認')
                    ->assertRouteIs('declaration.report.confirm')
                    ->press('Good work')
                    ->assertSee('報告提出しました！','宣言報告','テスト報告内容')
                    ->clickLink('宣言詳細')
                    ->assertRouteIs('declaration.show',['id' => $this->declaration_after->id])
                    ->assertSee('0 Good work') // 報告提出後はGoodWorkボタンの表示、カウントするか
                    ->click('.likes')
                    ->waitForText('1 Good work');
        });
    }

    public function test_コメント投稿、削除()
    {
        $this->browse(function ($browser) {
            $browser->loginAs($this->user)
                    ->visitRoute('declaration.show',['id' => '5'])
                    ->assertSee('コメントがありません！応援しましょう！')
                    ->type('body', 'テストコメント内容')
                    ->press('コメント投稿')
                    ->waitForText($this->user->name,'全1件','投稿日','テストコメント内容')
                    ->clickLink('･･･') //宣言削除
                    ->press('削除')
                    ->waitForDialog($seconds = null)
                    ->assertDialogOpened('本当によろしいですか？')
                    ->acceptDialog()
                    ->assertRouteIs('declaration.show',['id' => '5'])
                    ->assertSee('コメントを削除しました！');
        });
    }
}
