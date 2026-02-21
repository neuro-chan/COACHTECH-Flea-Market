<?php

namespace Tests\Feature\Http\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Tests\TestCase;
use App\Models\User;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    // ========================================
    // ID16: メール認証機能
    // ========================================

    public function test_会員登録後に認証メールが送信される(): void
    {
    Notification::fake();

    $this->post('/register', [
        'name'                  => 'テストユーザー',
        'email'                 => 'test@example.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $user = User::where('email', 'test@example.com')->firstOrFail();

    $this->assertNull($user->email_verified_at);

    Notification::assertSentTo($user, VerifyEmail::class);
    }


    public function test_「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // メール認証誘導画面を表示する
        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertOk();

        // 外部サイトの表示はFeatureテストの責務外と判断し、正しい遷移（link）が生成されているがどうかのテストで表現しています
        $response->assertSee('認証はこちらから', false);
        $response->assertSee('href="http://localhost:8025', false);
    }


    public function test_メール認証完了後にプロフィール設定画面に遷移する(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect('/mypage/profile');
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
