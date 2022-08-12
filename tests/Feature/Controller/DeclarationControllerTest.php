<?php

namespace Tests\Feature\Controller;

use App\Models\Declaration;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class DeclarationControllerTest extends TestCase
{
    use WithFaker; // Fakerを使用
    use WithoutMiddleware; // ミドルウェアを無効化
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->another_user = User::factory()->create();
        $this->declaration_before = Declaration::factory()->create(['user_id' => $this->user->id]); // 編集削除ができる期間
        $this->declaration_after = Declaration::factory()->create([
            'user_id' => $this->user->id,
            'start_date' => $this->faker->dateTimeBetween($startDate = 'now -2 week', $endDate = '-1 week'),
            'end_date' => $this->faker->dateTimeBetween($startDate = 'now  -5 day', $endDate = '-1 day'), // レポートが書ける期間に設定
        ]);
    }

    public function test_宣言投稿テスト()
    {
        $this->withoutMiddleware([VerifyCsrfToken::class]);
        $this->withoutExceptionHandling();

        $response = $this
            ->actingAs($this->user)
            ->post('/declaration/store', [
                'title' => '投稿タイトルテスト',
                'body' => '投稿内容テスト',
                'start_date' => '2099/01/01',
                'end_date' => '2099/12/31'
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('declarations', ['title' => '投稿タイトルテスト']);
    }

    public function test_宣言編集テスト()
    {
        $this->withoutMiddleware([VerifyCsrfToken::class]);
        $this->withoutExceptionHandling();

        $response = $this
            ->actingAs($this->user)
            ->post(route('declaration.update',['id'=>$this->declaration_before->id]), [
                'title' => '更新タイトルテスト',
                'body' => '更新内容テスト',
                'start_date' => '2099/01/01',
                'end_date' => '2099/12/31'
            ]);
            //補足:セッション関連をコントローラーにifなしで直書きしていると「RuntimeException: Session store not set on request.」でコケた
        $response->assertStatus(302);
        $response->assertRedirect(route('declaration.show', ['id' => $this->declaration_before->id]));
    }

    public function test_宣言削除テスト()
    {
        $this->withoutMiddleware([VerifyCsrfToken::class]);
        $this->withoutExceptionHandling();

        $response = $this
            ->actingAs($this->user)
            ->delete(route('declaration.destroy',['id'=>$this->declaration_before->id]));
        $response->assertRedirect('/');
        $this->assertDeleted($this->declaration_before);
    }

    public function test_報告投稿テスト()
    {
        $this->withoutMiddleware([VerifyCsrfToken::class]);
        $this->withoutExceptionHandling();

        $response = $this
            ->actingAs($this->user)
            ->post(route('declaration.report.store',['id'=>$this->declaration_after->id]), [
                'declaration_id' => $this->declaration_after->id,
                'rate' => $this->faker->numberBetween(1,5),
                'execution' => $this->faker->numberBetween(0,1),
                'body' => '報告テスト'
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('reports', ['body' => '報告テスト']);
    }


}
