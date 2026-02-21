<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Profile;
use Database\Seeders\ConditionSeeder;

class MypageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }

    // ========================================
    // ID13: ユーザー情報一覧取得
    // ========================================

    public function test_プロフィールページに必要な情報が表示される(): void
    {
        $user = User::factory()->create(['name' => 'テストユーザー']);

        Profile::factory()->create([
            'user_id'           => $user->id,
            'profile_image_url' => 'https://example.com/avatar.jpg',
        ]);

        Item::factory()->create([
            'seller_id' => $user->id,
            'title'     => '出品した商品',
        ]);

        $purchasedItem = Item::factory()->create(['title' => '購入した商品']);
        Purchase::factory()->create([
            'item_id'  => $purchasedItem->id,
            'buyer_id' => $user->id,
            'amount'   => $purchasedItem->price,
        ]);

        $response = $this->actingAs($user)->get(route('mypage.index'));

        $response->assertOk();
        $response->assertSee('テストユーザー');
        $response->assertSee('avatar.jpg');
        $response->assertSee('出品した商品');

        $response = $this->actingAs($user)->get(route('mypage.index', ['tab' => 'purchased']));

        $response->assertOk();
        $response->assertSee('購入した商品');
    }
}
