<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Artisan;

class LoginTest extends DuskTestCase
{
    protected static $db = false;

    protected static function initDB()
    {
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }

    public function setUp(): void
    {
        parent::setUp();
        if (!static::$db) {
            static::$db = true;
            static::initDB();
        }
    }

    public function test_新規作成、ログアウト、ログイン()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                    ->clickLink('新規登録')
                    ->type('name', 'テストユーザ')
                    ->type('email', 'test@gmail.com')
                    ->type('password', 'password')
                    ->type('password_confirmation', 'password')
                    ->press('登録')
                    ->assertPathIs('/declaration')
                    ->clickLink('テストユーザ')
                    ->clickLink('ログアウト')
                    ->clickLink('ログイン')
                    ->type('email', 'test@gmail.com')
                    ->type('password', 'password')
                    ->press('ログイン')
                    ->assertPathIs('/declaration');
        });
    }
}
