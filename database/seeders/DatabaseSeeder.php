<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => '管理者',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'admin' => 1,
            'password' => Hash::make('admin'), // password
            'remember_token' => Str::random(10),
        ]);
        \App\Models\User::create([
            'name' => '一般ユーザー',
            'email' => 'user@gmail.com',
            'email_verified_at' => now(),
            'admin' => 0,
            'password' => Hash::make('user'), // password
            'remember_token' => Str::random(10),
        ]);
        \App\Models\User::factory(100)->create();
        \App\Models\Declaration::factory(100)->create();
        \App\Models\Tag::factory(50)->create();
        \App\Models\Declaration_tag::factory(300)->create();
        \App\Models\Relationship::factory(99)->create();
    }
}

/* テストに必要のないseed
\App\Models\Declaration_comment::factory(1000)->create();
\App\Models\Report::factory(100)->create();
\App\Models\Do_it::factory(1000)->create();
\App\Models\Good_work::factory(1000)->create();
\App\Models\Report_comment::factory(1000)->create();
*/
