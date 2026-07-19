<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\PasswordResetLink;
use App\Notifications\PasswordResetSuccessful;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use InteractsWithMongoUsers;

    private const CURRENT_PASSWORD = 'a current secure passphrase 2026';

    private const NEW_PASSWORD = 'a replacement secure passphrase 2026';

    protected function setUp(): void
    {
        parent::setUp();

        DB::connection('mongodb')->table('password_reset_tokens')->delete();
        Http::fake(['api.pwnedpasswords.com/*' => Http::response('', 200)]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        DB::connection('mongodb')->table('password_reset_tokens')->delete();

        parent::tearDown();
    }

    private function makeUser(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'username' => 'wellnessfan7',
            'email' => 'wellnessfan7@example.test',
            'password' => self::CURRENT_PASSWORD,
        ], $overrides));
    }

    /** @return array<string, string> */
    private function resetPayload(string $token, array $overrides = []): array
    {
        return array_merge([
            'token' => $token,
            'email' => 'wellnessfan7@example.test',
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ], $overrides);
    }

    public function test_password_recovery_pages_are_available_to_guests(): void
    {
        $this->get('/forgot-password')
            ->assertOk()
            ->assertSee(__('auth.password_request_title'));

        $this->get('/reset-password/example-token?email=wellnessfan7%40example.test')
            ->assertOk()
            ->assertSee(__('auth.password_reset_title'))
            ->assertSee('wellnessfan7@example.test');
    }

    public function test_verified_account_receives_a_reset_link(): void
    {
        Notification::fake();
        $user = $this->makeUser();

        $response = $this->post('/forgot-password', [
            'email' => ' WellnessFan7@Example.test ',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('password_reset_notice', __('auth.password_request_sent'));
        Notification::assertSentTo($user, PasswordResetLink::class);
    }

    public function test_unknown_email_receives_the_same_public_response(): void
    {
        Notification::fake();

        $response = $this->post('/forgot-password', [
            'email' => 'nobody@example.test',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('password_reset_notice', __('auth.password_request_sent'));
        Notification::assertNothingSent();
    }

    public function test_unverified_email_receives_the_same_response_without_a_reset_link(): void
    {
        Notification::fake();
        $user = User::factory()->unverified()->create([
            'username' => 'wellnessfan7',
            'email' => 'wellnessfan7@example.test',
        ]);

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertRedirect();
        $response->assertSessionHas('password_reset_notice', __('auth.password_request_sent'));
        Notification::assertNothingSent();
        $this->assertFalse(Password::tokenExists($user, 'any-token'));
    }

    public function test_recovery_requests_are_rate_limited(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/forgot-password', ['email' => 'nobody@example.test'])
                ->assertRedirect();
        }

        $this->post('/forgot-password', ['email' => 'nobody@example.test'])
            ->assertTooManyRequests();
    }

    public function test_reset_changes_the_password_rotates_remember_token_and_sends_security_notice(): void
    {
        Notification::fake();
        $user = $this->makeUser();
        $user->setRememberToken('the-old-remember-token');
        $user->save();
        $token = Password::createToken($user);

        $response = $this->post('/reset-password', $this->resetPayload($token));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('password_reset_notice', __('auth.password_reset_success'));

        $user->refresh();
        $this->assertTrue(Hash::check(self::NEW_PASSWORD, $user->password));
        $this->assertNotSame('the-old-remember-token', $user->getRememberToken());
        Notification::assertSentTo($user, PasswordResetSuccessful::class);
    }

    public function test_reset_token_is_single_use(): void
    {
        Notification::fake();
        $user = $this->makeUser();
        $token = Password::createToken($user);

        $this->post('/reset-password', $this->resetPayload($token))
            ->assertRedirect(route('login'));

        $secondPassword = 'another replacement passphrase 2026';
        $response = $this->post('/reset-password', $this->resetPayload($token, [
            'password' => $secondPassword,
            'password_confirmation' => $secondPassword,
        ]));

        $response->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check(self::NEW_PASSWORD, $user->fresh()->password));
    }

    public function test_new_reset_token_supersedes_the_previous_token(): void
    {
        $user = $this->makeUser();
        $firstToken = Password::createToken($user);
        $secondToken = Password::createToken($user);

        $this->assertFalse(Password::tokenExists($user, $firstToken));
        $this->assertTrue(Password::tokenExists($user, $secondToken));
    }

    public function test_expired_reset_token_is_rejected(): void
    {
        $user = $this->makeUser();
        $token = Password::createToken($user);
        Carbon::setTestNow(now()->addMinutes(61));

        $response = $this->post('/reset-password', $this->resetPayload($token));

        $response->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check(self::CURRENT_PASSWORD, $user->fresh()->password));
    }

    public function test_reset_enforces_the_project_password_policy(): void
    {
        $user = $this->makeUser();
        $token = Password::createToken($user);

        $response = $this->post('/reset-password', $this->resetPayload($token, [
            'password' => 'MassageNexus2026!MoreText',
            'password_confirmation' => 'MassageNexus2026!MoreText',
        ]));

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Password::tokenExists($user, $token));
        $this->assertTrue(Hash::check(self::CURRENT_PASSWORD, $user->fresh()->password));
    }
}
