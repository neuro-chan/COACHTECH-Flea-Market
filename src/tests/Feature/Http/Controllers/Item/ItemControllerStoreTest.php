<?php

namespace Tests\Feature\Http\Controllers\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Database\Seeders\ConditionSeeder;

class ItemControllerStoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }

    // ========================================
    // ID15: 出品商品情報登録
    // ========================================

    public function test_商品出品画面にて必要な情報が保存できる(): void
    {
        $user     = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user)->get(route('items.create'));

        Storage::fake('public');

        $response = $this->actingAs($user)->post(route('items.store'), [
            'title'        => 'テスト商品',
            'description'  => 'テスト商品説明',
            'item_image'   => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
            'category_ids' => [$category->id],
            'condition_id' => 1,
            'price'        => 1000,
            'brand_name'   => 'テストブランド',
        ]);

        $response->assertRedirectContains('/');
        $this->assertDatabaseHas('items', [
            'seller_id'   => $user->id,
            'title'       => 'テスト商品',
            'description' => 'テスト商品説明',
            'price'       => 1000,
            'brand_name'  => 'テストブランド',
        ]);
    }
}
