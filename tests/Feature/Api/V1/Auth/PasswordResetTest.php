<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_password_reset_link()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/forgot-password', ['email' => $user->email]);

        $response->assertStatus(200);
        $response->assertJson(['message' => __('passwords.sent')]);
    }

    public function test_user_is_greeted_with_error_message_that_user_does_not_exist_with_the_email_provided()
    {
        $response = $this->postJson('/api/v1/auth/forgot-password', ['email' => 'nonexistent@example.com']);

        $response->assertStatus(422);
        $response->assertJson(['message' => __('passwords.user')]);
    }

    public function test_user_can_reset_password_with_valid_token()
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'email' => $user->email,
            'token' => $token,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => __('passwords.reset')]);

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_user_cannot_reset_password_if_passwords_do_not_match()
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'email' => $user->email,
            'token' => $token,
            'password' => 'new-password',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }
}
