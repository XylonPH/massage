<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use InteractsWithMongoUsers;

    private function makePendingUser(): User
    {
        return User::factory()->unverified()->create([
            'username' => 'wellnessfan7',
            'email' => 'wellnessfan7@example.test',
        ]);
    }

    private function signedVerificationUrl(User $user): string
    {
        return URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]);
    }

    public function test_valid_signed_link_verifies_and_activates_the_account(): void
    {
        $user = $this->makePendingUser();

        $response = $this->get($this->signedVerificationUrl($user));

        $response->assertRedirect(route('login'));

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
        $this->assertSame('ACT', $user->status_account);
        $this->assertSame('ACT', $user->status_membership);
    }

    public function test_tampered_hash_is_rejected(): void
    {
        $user = $this->makePendingUser();

        $response = $this->get(URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $user->getKey(),
            'hash' => sha1('not-the-real-email'),
        ]));

        $response->assertRedirect(route('verification.notice'));

        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }

    public function test_expired_link_is_rejected(): void
    {
        $user = $this->makePendingUser();

        $url = URL::temporarySignedRoute('verification.verify', now()->subMinutes(5), [
            'id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]);

        $this->get($url)->assertStatus(403);

        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }

    public function test_resend_sends_a_new_link_for_a_pending_account(): void
    {
        Notification::fake();
        $user = $this->makePendingUser();

        $response = $this->post('/verify-email/resend', ['email' => $user->email]);

        $response->assertRedirect(route('verification.notice'));
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_resend_gives_the_same_response_for_an_unknown_email(): void
    {
        Notification::fake();

        $response = $this->post('/verify-email/resend', ['email' => 'nobody@example.test']);

        $response->assertRedirect(route('verification.notice'));
        $response->assertSessionHas('verify_notice', __('auth.verify_email_resend_sent'));
    }
}
