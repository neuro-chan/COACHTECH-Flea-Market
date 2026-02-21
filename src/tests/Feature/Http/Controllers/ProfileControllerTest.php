<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    // ========================================
    // ID14: ユーザー情報変更
    // ========================================

    public function test_プロフィール編集画面に初期値が表示される(): void
    {
        $user = User::factory()->create(['name' => 'テストユーザー']);

        Profile::factory()->create([
            'user_id'           => $user->id,
            'profile_image_url' => 'https://example.com/avatar.jpg',
            'postal_code'       => '123-4567',
            'address'           => 'テスト住所',
            'building'          => 'テストビル',
        ]);

        $response = $this->actingAs($user)->get(route('mypage.profile.edit'));

        $response->assertOk();
        $response->assertSee('テストユーザー');
        $response->assertSee('avatar.jpg');
        $response->assertSee('123-4567');
        $response->assertSee('テスト住所');
    }
}
