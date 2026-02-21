<?php

namespace Tests\Feature\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    use RefreshDatabase;

    // ========================================
    // ID2: ログイン機能
    // ========================================

    public function test_メールアドレスが未入力の場合バリデーションエラーになる(): void
    {
        $this->get('/login');

        $response = $this->post('/login', [
            'email'    => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    
    public function test_パスワードが未入力の場合バリデーションエラーになる(): void
    {
        $this->get('/login');

        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    public function test_入力情報が間違っているとバリデーションエラーになる(): void
    {
        User::factory()->create([
            'email'    => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->get('/login');

        $response = $this->post('/login', [
            'email'    => 'notexisttest@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }


    public function test_メール認証済みユーザーは正しい情報でログインできる(): void
    {
        $user = User::factory()->create([
            'email'             => 'test@example.com',
            'password'          => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }


    public function test_メール未認証ユーザーはメール認証画面にリダイレクトされる(): void
    {
        $user = User::factory()->unverified()->create([
            'email'    => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(route('verification.notice')); // => /email/verify
    }
}
