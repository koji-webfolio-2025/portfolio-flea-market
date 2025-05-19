<?php

namespace Tests\Feature;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_register_without_name_shows_error_message()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ]);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    /** @test */
    public function test_register_without_email_shows_error_message()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    /** @test */
    public function test_register_without_password_shows_error_message()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'name' => 'テスト太郎',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    /** @test */
    public function test_register_with_short_password_shows_error_message()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'name' => 'テスト太郎',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    /** @test */
    public function test_register_with_mismatched_password_confirmation_shows_error_message()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'name' => 'テスト太郎',
            'password' => 'password1234',
            'password_confirmation' => 'differentpass',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードと一致しません']);
    }

    /** @test */
    public function test_register_with_all_valid_inputs_redirects_to_login()
    {
        // Notification::fake() で通知を実際に送信しないようにする
        Notification::fake();

        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'name' => 'テスト太郎',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ]);

        /// ユーザーが作成されたことを確認
        $user = \App\Models\User::first();
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'テスト太郎',
        ]);

        // メール認証用の通知が送信されたことを確認
        Notification::assertSentTo($user, VerifyEmail::class);

        // メール認証リンクを手動で生成
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify', now()->addMinutes(60), ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->get($verifyUrl);

        // ログイン画面にリダイレクトされることを確認
        $response->assertRedirect('/login');

    }
}
