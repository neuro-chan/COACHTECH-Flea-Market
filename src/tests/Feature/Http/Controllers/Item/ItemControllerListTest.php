<?php

namespace Tests\Feature\Http\Controllers\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Database\Seeders\ConditionSeeder;

class ItemControllerListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }

    // ========================================
    // ID4: 商品一覧取得
    // ========================================

    public function test_全商品が取得できる(): void
    {
        Item::factory()->create(['title' => '商品A']);
        Item::factory()->create(['title' => '商品B']);
        Item::factory()->create(['title' => '商品C']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('商品A');
        $response->assertSee('商品B');
        $response->assertSee('商品C');
    }


    public function test_購入済み商品にSoldが表示される(): void
    {
        $item = Item::factory()->create(['title' => '購入済み商品']);

        Purchase::factory()->create([
            'item_id' => $item->id,
            'amount'  => $item->price,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('購入済み商品');
        $response->assertSee('Sold');
    }


    public function test_自分が出品した商品は表示されない(): void
    {
        $user = User::factory()->create();

        Item::factory()->create([
            'seller_id' => $user->id,
            'title'     => '自分の出品商品',
        ]);

        Item::factory()->create([
            'title' => '他人の出品商品',
        ]);

        $response = $this->actingAs($user)->get('/?tab=recommend');

        $response->assertOk();
        $response->assertDontSee('自分の出品商品');
        $response->assertSee('他人の出品商品');
    }

    // ========================================
    // ID5: マイリスト一覧取得
    // ========================================

    public function test_いいねした商品だけが表示される(): void
    {
        $user = User::factory()->create();

        $likedItem    = Item::factory()->create(['title' => 'いいねした商品']);
        $notLikedItem = Item::factory()->create(['title' => 'いいねしていない商品']);

        $user->likes()->attach($likedItem->id);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertOk();
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }


    public function test_マイリストの購入済み商品にSoldが表示される(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['title' => '購入済み商品']);

        $user->likes()->attach($item->id);

        Purchase::factory()->create([
            'item_id' => $item->id,
            'amount'  => $item->price,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertOk();
        $response->assertSee('購入済み商品');
        $response->assertSee('Sold');
    }


    public function test_未認証の場合マイリストには何も表示されない(): void
    {
        Item::factory()->create(['title' => 'テスト商品']);

        $response = $this->get('/?tab=mylist');

        $response->assertOk();
        $response->assertDontSee('テスト商品');
    }

    // ========================================
    // ID6: 商品検索機能
    // ========================================

    public function test_商品名で部分一致検索ができる(): void
    {
        Item::factory()->create(['title' => '腕時計']);
        Item::factory()->create(['title' => 'ノートPC']);

        $response = $this->get('/?keyword=腕');

    $response->assertOk();
    $response->assertSee('腕時計');
    $response->assertDontSee('ノートPC');
}


    public function test_検索状態がマイリストでも保持されている(): void
{
    $user = User::factory()->create();

    $item1 = Item::factory()->create(['title' => '腕時計']);
    $item2 = Item::factory()->create(['title' => 'ノートPC']);

    $user->likes()->attach($item1->id);
    $user->likes()->attach($item2->id);

    // 手順1: ホームページで商品を検索
    $response = $this->actingAs($user)->get('/?keyword=腕');

    // 手順2: 検索結果が表示される
    $response->assertOk();
    $response->assertSee('腕時計');
    $response->assertDontSee('ノートPC');

    // 手順3: マイリストページに遷移（キーワードを引き継ぐ）
    $response = $this->actingAs($user)->get('/?tab=mylist&keyword=腕');

    $response->assertOk();
    // キーワードが保持されたまま検索結果が表示される
    $response->assertSee('腕時計');
    $response->assertDontSee('ノートPC');
}
}
