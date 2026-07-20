<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Symfony\Component\Uid\UuidV4;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use InteractsWithMongoUsers;

    private const VALID_PASSWORD = 'a very long random passphrase 2026';

    protected function setUp(): void
    {
        parent::setUp();

        // Password::uncompromised() calls the HaveIBeenPwned range API; fake
        // it as "not found" so tests stay deterministic and offline-safe.
        Http::fake(['api.pwnedpasswords.com/*' => Http::response('', 200)]);
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'username' => 'wellnessfan7',
            'email' => 'wellnessfan7@example.test',
            'password' => self::VALID_PASSWORD,
            'password_confirmation' => self::VALID_PASSWORD,
            'birth_date' => '1990-05-15',
            'terms_accepted' => '1',
        ], $overrides);
    }

    public function test_application_uuid_generation_avoids_the_incompatible_ramsey_runtime(): void
    {
        $uuid = Str::uuid();

        $this->assertInstanceOf(UuidV4::class, $uuid);
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            (string) $uuid,
        );
    }

    public function test_valid_registration_creates_a_pending_user_and_sends_verification(): void
    {
        Notification::fake();

        $response = $this->post('/register', $this->validPayload());

        $response->assertRedirect(route('verification.notice'));
        $this->assertGuest();

        $user = User::where('username', 'wellnessfan7')->first();
        $this->assertNotNull($user);
        $this->assertSame('PND', $user->status_account);
        $this->assertSame('PEL', $user->status_membership);
        $this->assertNotNull($user->terms_accepted_at);
        $this->assertNotNull($user->privacy_acknowledged_at);
        $this->assertTrue(strlen($user->_id) === 16);
        $this->assertNotSame(self::VALID_PASSWORD, $user->password);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_reserved_username_is_rejected(): void
    {
        $response = $this->post('/register', $this->validPayload(['username' => 'admin']));

        $response->assertSessionHasErrors('username');
        $this->assertNull(User::where('username', 'admin')->first());
    }

    public function test_reserved_username_leetspeak_variant_is_rejected(): void
    {
        $response = $this->post('/register', $this->validPayload(['username' => 'adm1n2026']));

        $response->assertSessionHasErrors('username');
    }

    public function test_username_with_uppercase_is_rejected(): void
    {
        $response = $this->post('/register', $this->validPayload(['username' => 'Xylon2026']));

        $response->assertSessionHasErrors('username');
    }

    public function test_short_password_is_rejected(): void
    {
        $response = $this->post('/register', $this->validPayload([
            'password' => 'short pass',
            'password_confirmation' => 'short pass',
        ]));

        $response->assertSessionHasErrors('password');
    }

    public function test_password_matching_username_is_rejected(): void
    {
        $response = $this->post('/register', $this->validPayload([
            'password' => 'wellnessfan7wellnessfan7',
            'password_confirmation' => 'wellnessfan7wellnessfan7',
        ]));

        $response->assertSessionHasErrors('password');
    }

    public function test_obvious_password_pattern_is_rejected(): void
    {
        $response = $this->post('/register', $this->validPayload([
            'password' => 'MassageNexus2026!MoreText',
            'password_confirmation' => 'MassageNexus2026!MoreText',
        ]));

        $response->assertSessionHasErrors('password');
    }

    public function test_under_eighteen_birth_date_is_rejected(): void
    {
        $response = $this->post('/register', $this->validPayload([
            'birth_date' => now()->subYears(17)->toDateString(),
        ]));

        $response->assertSessionHasErrors('birth_date');
    }

    public function test_terms_must_be_accepted(): void
    {
        $response = $this->post('/register', $this->validPayload(['terms_accepted' => null]));

        $response->assertSessionHasErrors('terms_accepted');
    }

    public function test_duplicate_username_is_rejected(): void
    {
        User::factory()->create(['username' => 'wellnessfan7', 'email' => 'other@example.test']);

        $response = $this->post('/register', $this->validPayload());

        $response->assertSessionHasErrors('username');
        $this->withViewErrors(['username' => 'The username has already been taken.']);
        $this->view('auth.register')
            ->assertSee('id="username-error"', false)
            ->assertSee('data-server-validation-error', false);
    }

    public function test_duplicate_email_is_case_insensitive(): void
    {
        User::factory()->create(['username' => 'otheruser', 'email' => 'wellnessfan7@example.test']);

        $response = $this->post('/register', $this->validPayload(['email' => 'WellnessFan7@Example.test']));

        $response->assertSessionHasErrors('email');
    }
}
