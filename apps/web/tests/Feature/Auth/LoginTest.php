<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use InteractsWithMongoUsers;

    private const PASSWORD = 'a very long random passphrase 2026';

    protected function setUp(): void
    {
        parent::setUp();

        RateLimiter::clear('wellnessfan7|127.0.0.1');
        RateLimiter::clear('wellnessfan7@example.test|127.0.0.1');
    }

    private function makeActiveUser(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'username' => 'wellnessfan7',
            'email' => 'wellnessfan7@example.test',
            'password' => self::PASSWORD,
        ], $overrides));
    }

    public function test_active_user_can_log_in_with_username(): void
    {
        $this->makeActiveUser();

        $response = $this->post('/login', [
            'identifier' => 'wellnessfan7',
            'password' => self::PASSWORD,
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
    }

    public function test_active_user_can_log_in_with_email(): void
    {
        $this->makeActiveUser();

        $response = $this->post('/login', [
            'identifier' => 'wellnessfan7@example.test',
            'password' => self::PASSWORD,
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
    }

    public function test_login_with_email_is_case_insensitive(): void
    {
        $this->makeActiveUser();

        $response = $this->post('/login', [
            'identifier' => 'WellnessFan7@Example.test',
            'password' => self::PASSWORD,
        ]);

        $this->assertAuthenticated();
    }

    public function test_wrong_password_is_rejected_with_generic_message(): void
    {
        $this->makeActiveUser();

        $response = $this->post('/login', [
            'identifier' => 'wellnessfan7',
            'password' => 'the wrong passphrase entirely',
        ]);

        $response->assertSessionHasErrors('identifier');
        $this->assertGuest();
    }

    public function test_unknown_identifier_is_rejected_with_generic_message(): void
    {
        $response = $this->post('/login', [
            'identifier' => 'nosuchuser',
            'password' => self::PASSWORD,
        ]);

        $response->assertSessionHasErrors('identifier');
        $this->assertGuest();
    }

    public function test_unverified_user_cannot_log_in(): void
    {
        $this->makeActiveUser([
            'status_account' => 'pending_email_verification',
            'email_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'identifier' => 'wellnessfan7',
            'password' => self::PASSWORD,
        ]);

        $response->assertSessionHasErrors('identifier');
        $this->assertGuest();
    }

    public function test_login_locks_out_after_too_many_attempts(): void
    {
        $this->makeActiveUser();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['identifier' => 'wellnessfan7', 'password' => 'wrong']);
        }

        $response = $this->post('/login', [
            'identifier' => 'wellnessfan7',
            'password' => self::PASSWORD,
        ]);

        $response->assertSessionHasErrors('identifier');
        $this->assertGuest();
    }

    public function test_remember_me_sets_a_recaller_cookie_and_token(): void
    {
        $user = $this->makeActiveUser();

        $response = $this->post('/login', [
            'identifier' => 'wellnessfan7',
            'password' => self::PASSWORD,
            'remember' => '1',
        ]);

        $response->assertCookie(Auth::getRecallerName());
        $this->assertNotNull($user->fresh()->getRememberToken());
    }

    public function test_logged_in_user_can_log_out(): void
    {
        $user = $this->makeActiveUser();

        $this->actingAs($user)->post('/logout');

        $this->assertGuest();
    }
}
