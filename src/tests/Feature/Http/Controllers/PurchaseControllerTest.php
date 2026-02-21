<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Database\Seeders\ConditionSeeder;

class PurchaseControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }

    // ========================================
    // ID10: 商品購入機能
    // ========================================

    public function test_購入した商品は商品一覧画面にてSoldと表示される(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['title' => '購入済み商品']);

        $this->actingAs($user)->get(route('purchase.create', $item));

        // Stripeを経由せずに直接Purchaseレコードを作成する形のテストにしています
        Purchase::factory()->create([
            'item_id'  => $item->id,
            'buyer_id' => $user->id,
            'amount'   => $item->price,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('購入済み商品');
        $response->assertSee('Sold');
    }


    public function test_購入した商品がプロフィールの購入一覧に追加される(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['title' => '購入済み商品']);

        $this->actingAs($user)->get(route('purchase.create', $item));

        // Stripeを経由せずに直接Purchaseレコードを作成する形のテストにしています
        Purchase::factory()->create([
            'item_id'  => $item->id,
            'buyer_id' => $user->id,
            'amount'   => $item->price,
        ]);

        $response = $this->actingAs($user)->get(route('mypage.index', ['tab' => 'purchased']));

        $response->assertOk();
        $response->assertSee('購入済み商品');
    }
}
