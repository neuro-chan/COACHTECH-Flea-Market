<?php

namespace Tests\Feature\Http\Controllers\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Category;
use Database\Seeders\ConditionSeeder;

class ItemControllerShowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }

    // ========================================
    // ID7: 商品詳細情報取得
    // ========================================

    public function test_商品詳細ページに必要な情報が表示される(): void
    {
    $category = Category::factory()->create([
        'category_name' => 'テストカテゴリ',
    ]);

    $item = Item::factory()->create([
        'title'       => 'テスト商品',
        'brand_name'  => 'テストブランド',
        'price'       => 10000,
        'description' => 'テスト商品説明',
    ]);

    $item->categories()->attach($category->id);

    $commenter = User::factory()->create(['name' => 'テストコメントユーザー']);
    Comment::factory()->create([
        'item_id'      => $item->id,
        'user_id'      => $commenter->id,
        'comment_text' => 'テストコメント',
    ]);

    $response = $this->get(route('items.show', $item));

    $response->assertOk();
    $response->assertSee('テスト商品');
    $response->assertSee('テストブランド');
    $response->assertSee('10,000');
    $response->assertSee('テスト商品説明');
    $response->assertSee('テストカテゴリ');
    $response->assertSee('テストコメントユーザー');
    $response->assertSee('テストコメント');
}


public function test_複数選択されたカテゴリが表示される(): void
{
    $category1 = Category::factory()->create(['category_name' => 'カテゴリA']);
    $category2 = Category::factory()->create(['category_name' => 'カテゴリB']);
    $category3 = Category::factory()->create(['category_name' => 'カテゴリC']);

    $item = Item::factory()->create();

    $item->categories()->attach([
        $category1->id,
        $category2->id,
        $category3->id,
    ]);

    $response = $this->get(route('items.show', $item));

    $response->assertOk();
    $response->assertSee('カテゴリA');
    $response->assertSee('カテゴリB');
    $response->assertSee('カテゴリC');
    }
}
