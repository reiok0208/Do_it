<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class UserTest extends DuskTestCase
{
    use WithFaker; // Fakerを使用

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['password' => Hash::make('password')]);
        $this->another_user = User::factory()->create();
    }

    public function test_ユーザー編集、削除()
    {
        $this->browse(function ($browser) {
            $browser->loginAs($this->user)
                    ->visitRoute('user.show',['id' => $this->user->id])
                    ->assertRouteIs('user.show',['id' => $this->user->id])
                    ->clickLink('ユーザー編集')
                    ->assertRouteIs('user.edit')
                    ->type('name', '編集テスト名')
                    ->type('body', '編集テスト自己紹介')
                    ->press('#info_submit')
                    ->assertSee('情報の変更に成功しました')
                    ->clickLink('マイページ')
                    ->assertSee('ユーザー詳細','編集テスト名','編集テスト自己紹介');
        });
    }

    public function test_メールアドレス編集()
    {
        $this->browse(function ($browser) {
            $browser->loginAs($this->user)
                    ->visitRoute('user.show',['id' => $this->user->id])
                    ->assertRouteIs('user.show',['id' => $this->user->id])
                    ->clickLink('ユーザー編集')
                    ->assertRouteIs('user.edit')
                    ->type('Email', 'test12345@email.com')
                    ->press('#email_submit')
                    ->assertSee('メールアドレスの変更に成功しました')
                    ->clickLink('マイページ')
                    ->assertSee('ユーザー詳細','test@gmail.com');
        });
    }

    public function test_パスワード変更()
    {
        $this->browse(function ($browser) {
            $browser->loginAs($this->user)
                    ->visitRoute('user.show',['id' => $this->user->id])
                    ->assertRouteIs('user.show',['id' => $this->user->id])
                    ->clickLink('ユーザー編集')
                    ->assertRouteIs('user.edit')
                    ->type('CurrentPassword', 'password')
                    ->type('newPassword', 'passwordTest')
                    ->type('newPassword_confirmation', 'passwordTest')
                    ->press('#password_submit')
                    ->assertSee('パスワードの変更に成功しました')
                    ->clickLink('マイページ')
                    ->assertSee('ユーザー詳細');
        });
    }

    public function test_フォローする、フォローされる()
    {
        $this->browse(function ($browser) {
            $browser->loginAs($this->user)
                    ->visitRoute('user.show',['id' => $this->user->id])
                    ->assertRouteIs('user.show',['id' => $this->user->id])
                    ->assertSee('0フォロー')
                    ->visitRoute('user.show',['id' => $this->another_user->id])
                    ->assertRouteIs('user.show',['id' => $this->another_user->id])
                    ->assertSee('ユーザー詳細','0フォロワー')
                    ->press('.follow-toggle')
                    ->waitForText('ユーザー詳細','1フォロワー')
                    ->visitRoute('user.show',['id' => $this->user->id])
                    ->assertRouteIs('user.show',['id' => $this->user->id])
                    ->assertSee('1フォロー');

            $browser->loginAs($this->another_user)
                    ->visitRoute('user.show',['id' => $this->another_user->id])
                    ->assertRouteIs('user.show',['id' => $this->another_user->id])
                    ->assertSee('ユーザー詳細','1フォロワー');
        });
    }

    public function test_ユーザー削除()
    {
        $this->browse(function ($browser) {
            $browser->loginAs($this->user)
                    ->visitRoute('user.show',['id' => $this->user->id])
                    ->assertRouteIs('user.show',['id' => $this->user->id])
                    ->clickLink('ユーザー削除')
                    ->assertRouteIs('user.delete')
                    ->type('CurrentPassword', 'password')
                    ->press('削除')
                    ->waitForDialog($seconds = null)
                    ->assertDialogOpened('本当によろしいですか？')
                    ->acceptDialog()
                    ->assertRouteIs('root')
                    ->assertSee('退会できました。ご利用ありがとうございます');
            $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
        });
    }

}
