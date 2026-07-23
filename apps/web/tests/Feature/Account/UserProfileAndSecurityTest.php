<?php

namespace Tests\Feature\Account;

use App\Models\User;
use App\Models\UserDevice;
use App\Models\UserSession;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class UserProfileAndSecurityTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();
        UserSession::query()->delete();
        UserDevice::query()->delete();
    }

    protected function tearDown(): void
    {
        UserSession::query()->delete();
        UserDevice::query()->delete();
        parent::tearDown();
    }

    public function test_public_profile_honors_overall_and_field_visibility(): void
    {
        $user = User::factory()->create([
            'username' => 'quietreader', 'visibility_scope' => 'PUB',
            'display_name' => 'Quiet Reader', 'gender_identity' => 'NB', 'type_handedness' => 'LH',
            'privacy_preference' => ['visibility_gender' => 'PUB', 'visibility_handedness' => 'PRV', 'type_birth_date_display' => 'AGE'],
        ]);

        $this->get('/user/'.$user->username)->assertOk()
            ->assertSee('Quiet Reader')->assertSee('Non-binary')->assertDontSee('Left-handed');

        $user->forceFill(['visibility_scope' => 'PRV'])->save();
        $this->get('/user/'.$user->username)->assertNotFound();
        $this->actingAs($user)->get('/user/'.$user->username)->assertOk();
    }

    public function test_profile_and_preferences_are_saved_in_user_main(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->put('/workspace/profile', [
            'display_name' => 'Calm Contributor', 'profile_biography' => 'I share careful notes about accessible wellness experiences.',
            'pronoun_text' => 'they/them', 'gender_identity' => 'NB', 'type_handedness' => 'AM',
            'visibility_scope' => 'UNL', 'visibility_gender' => 'PUB', 'visibility_handedness' => 'PRV', 'type_birth_date_display' => 'HID',
        ])->assertRedirect('/workspace/profile');

        $this->actingAs($user)->put('/workspace/setting', [
            'color_mode' => 'DRK', 'text_scale_percent' => 110, 'is_reduced_motion' => '1',
            'interface_language_id' => 3049, 'time_zone_id' => 255,
            'notification_channel_list' => ['WEB'], 'notification_category_list' => ['SEC'], 'digest_frequency' => 'DLY',
        ])->assertRedirect('/workspace/setting');

        $user->refresh();
        $this->assertSame('UNL', $user->visibility_scope);
        $this->assertSame('DRK', $user->appearance_preference['color_mode']);
        $this->assertTrue($user->appearance_preference['is_reduced_motion']);
        $this->assertSame(['WEB'], $user->notification_preference['notification_channel_list']);
    }

    public function test_password_login_creates_a_revocable_session_and_coarse_device(): void
    {
        $user = User::factory()->create(['username' => 'sessionuser', 'password' => 'a very long random passphrase 2026']);
        $this->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0) Chrome/130.0')->post('/login', [
            'identifier' => 'sessionuser', 'password' => 'a very long random passphrase 2026',
        ])->assertRedirect(route('home'));

        $session = UserSession::where('user_id', (string) $user->getKey())->firstOrFail();
        $device = UserDevice::where('user_id', (string) $user->getKey())->firstOrFail();
        $this->assertStringStartsWith('sha256:', $session->session_secret_hash);
        $this->assertStringNotContainsString(session()->getId(), $session->session_secret_hash);
        $this->assertSame('Chrome', $device->browser_summary);

        $this->delete(route('workspace.setting.session.destroy', $session))->assertRedirect(route('home'));
        $this->assertSame('REV', $session->refresh()->status_user_session);
        $this->assertGuest();
    }
}
