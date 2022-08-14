<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\Declaration;

class AdminTest extends DuskTestCase
{
    use WithFaker; // Fakerを使用

    public function setUp(): void
    {
        parent::setUp();
        $this->admin_user = User::factory()->create(['name' => 'テスト管理者','admin' => '1']);
        $this->another_user = User::factory()->create(['password' => Hash::make('password')]);
        $this->declaration = Declaration::factory()->create(['user_id' => $this->another_user->id]);
    }

    public function test_ユーザー凍結、凍結されたユーザーはログインできない()
    {
        $this->browse(function ($browser) {
            $browser->loginAs($this->admin_user)
                    ->visitRoute('admin.index') //管理者画面にいける
                    ->assertRouteIs('admin.index')
                    ->visitRoute('user.show',['id' => $this->another_user->id])
                    ->assertRouteIs('user.show',['id' => $this->another_user->id])
                    ->press('ユーザー凍結')
                    ->waitForDialog($seconds = null)
                    ->assertDialogOpened('本当によろしいですか？')
                    ->acceptDialog()
                    ->waitForText('ユーザーを凍結しました！','ユーザー凍結解除')
                    ->clickLink('テスト管理者')
                    ->clickLink('ログアウト');

            $browser->visitRoute('root')
                    ->clickLink('ログイン')
                    ->type('email', $this->another_user->email)
                    ->type('password', 'password')
                    ->press('ログイン')
                    ->assertPathIs('/login')
                    ->waitForText('凍結されたユーザーのためログインできません');
        });
    }

    public function test_宣言凍結、解除、凍結した宣言は管理者以外は見れない()
    {
        $this->browse(function ($browser) {
            $browser->loginAs($this->admin_user)
                    ->visitRoute('declaration.show',['id' => $this->declaration->id])
                    ->assertRouteIs('declaration.show',['id' => $this->declaration->id])
                    ->clickLink('･･･')
                    ->press('凍結')
                    ->waitForDialog($seconds = null)
                    ->assertDialogOpened('本当によろしいですか？')
                    ->acceptDialog()
                    ->waitForText('宣言を凍結しました！')
                    ->assertRouteIs('declaration.show',['id' => $this->declaration->id]) //管理者は凍結した宣言の詳細を見れる
                    ->assertSee('宣言詳細','宣言者','宣言日','更新日','期間','Do it');

            $browser->loginAs($this->another_user)
                    ->visitRoute('declaration.index')
                    ->assertSee('凍結された宣言','管理者によって凍結された宣言です')
                    ->visitRoute('declaration.show',['id' => $this->declaration->id]) //一般ユーザーは凍結した宣言詳細を見れない
                    ->assertSee('403');

            $browser->loginAs($this->admin_user)
                    ->visitRoute('declaration.show',['id' => $this->declaration->id])
                    ->assertRouteIs('declaration.show',['id' => $this->declaration->id])
                    ->clickLink('･･･')
                    ->press('凍結解除')
                    ->waitForDialog($seconds = null)
                    ->assertDialogOpened('本当によろしいですか？')
                    ->acceptDialog()
                    ->waitForText('宣言を凍結解除しました！')
                    ->assertRouteIs('declaration.show',['id' => $this->declaration->id]) //管理者は凍結解除した宣言詳細を見れる
                    ->assertSee('宣言詳細','宣言者','宣言日','更新日','期間','Do it');

            $browser->loginAs($this->another_user)
                    ->visitRoute('declaration.show',['id' => $this->declaration->id]) //一般ユーザーは凍結解除した宣言詳細を見れる
                    ->assertSee('宣言詳細','宣言者','宣言日','更新日','期間','Do it');
        });
    }
}
