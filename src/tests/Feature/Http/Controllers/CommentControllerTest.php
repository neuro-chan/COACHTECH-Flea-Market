<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Database\Seeders\ConditionSeeder;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }


    // ========================================
    // ID9: コメント送信機能
    // ========================================

    public function test_ログイン済みユーザーはコメントを送信できる(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('item.comment.store', $item), [
            'comment_text' => 'テストコメント',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'user_id'      => $user->id,
            'item_id'      => $item->id,
            'comment_text' => 'テストコメント',
        ]);
    }


    public function test_未ログインユーザーはコメントを送信できない(): void
    {
        $item = Item::factory()->create();

        $response = $this->post(route('item.comment.store', $item), [
            'comment_text' => 'テストコメント',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('comments', [
            'comment_text' => 'テストコメント',
        ]);
    }


    public function test_コメントが未入力の場合バリデーションメッセージが表示される(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('item.comment.store', $item), [
            'comment_text' => '',
        ]);

        $response->assertSessionHasErrors([
            'comment_text' => 'コメントを入力してください',
        ]);
    }


    public function test_コメントが255文字以上の場合バリデーションメッセージが表示される(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('item.comment.store', $item), [
            'comment_text' => str_repeat('あ', 256),
        ]);

        $response->assertSessionHasErrors([
            'comment_text' => 'コメントは255文字以内で入力してください',
        ]);
    }
}
