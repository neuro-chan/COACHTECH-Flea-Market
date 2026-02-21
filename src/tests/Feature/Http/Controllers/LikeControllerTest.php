<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Database\Seeders\ConditionSeeder;

class LikeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }

    // ========================================
    // ID8: いいね機能
    // ========================================

    public function test_いいねアイコンを押下するといいねした商品として登録される(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->get(route('items.show', $item));

        $response = $this->actingAs($user)->post(route('item.like', $item));

        $response->assertRedirect();
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }


    public function test_いいね済みの場合アイコンの色が変化する(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->get(route('items.show', $item));

        $user->likes()->attach($item->id);

        $response = $this->actingAs($user)->get(route('items.show', $item));

        $response->assertOk();
        $response->assertSee('heart-logo-pink.png');
        $response->assertDontSee('heart-logo-default.png');
    }


    public function test_再度いいねアイコンを押下するといいねが解除される(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->get(route('items.show', $item));

        $user->likes()->attach($item->id);

        $response = $this->actingAs($user)->post(route('item.like', $item));

        $response->assertRedirect();
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
