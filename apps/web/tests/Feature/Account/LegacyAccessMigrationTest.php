<?php

namespace Tests\Feature\Account;

use App\Models\User;
use App\Models\UserAccess;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class LegacyAccessMigrationTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();
        DB::connection('mongodb')->table('access_assignment')->delete();
        UserAccess::query()->delete();
    }

    protected function tearDown(): void
    {
        DB::connection('mongodb')->table('access_assignment')->delete();
        UserAccess::query()->delete();
        parent::tearDown();
    }

    public function test_legacy_id_shaped_founder_grant_is_backfilled_without_removal(): void
    {
        $user = User::factory()->create();
        DB::connection('mongodb')->table('access_assignment')->insert([
            'id' => 'legacyFounder001',
            'user_id' => (string) $user->getKey(),
            'role_workspace' => 'FND',
            'permission_code_list' => '[]',
            'scope_access' => 'GBL',
            'status_access_assignment' => 'ACT',
            'assigned_by_user_id' => (string) $user->getKey(),
            'assignment_reason' => 'Existing founder bootstrap grant.',
        ]);

        $migration = require database_path('migrations/2026_07_22_100100_backfill_legacy_access_assignment_ids.php');
        $migration->up();

        $grant = UserAccess::findOrFail('legacyFounder001');
        $this->assertSame('ACT', $grant->status_user_access);
        $this->assertSame('FND', $grant->role_workspace);
        $this->assertSame([], $grant->permission_code_list);
        $this->assertSame(1, DB::connection('mongodb')->table('access_assignment')->count());
    }

    public function test_legacy_user_receives_private_profile_and_preference_defaults(): void
    {
        DB::connection('mongodb')->table('user_main')->insert([
            'id' => 'legacyUser000001',
            'username' => 'legacy-user',
            'email' => 'legacy-user@example.test',
            'password' => 'hash-example-only',
            'bio' => 'A legacy profile biography retained during migration.',
            'is_marketing_email_opt_in' => true,
        ]);

        $migration = require database_path('migrations/2026_07_22_100200_backfill_legacy_user_profile_defaults.php');
        $migration->up();

        $user = (array) DB::connection('mongodb')->table('user_main')->where('id', 'legacyUser000001')->first();
        $this->assertSame('A legacy profile biography retained during migration.', $user['profile_biography']);
        $this->assertSame('PRV', $user['visibility_scope']);
        $this->assertSame('SYS', $user['appearance_preference']['color_mode']);
        $this->assertTrue($user['notification_preference']['is_marketing_email_opt_in']);
        $this->assertArrayNotHasKey('user_slug', $user);
    }

    public function test_finalization_removes_legacy_access_and_empty_duplicate_profile_fields(): void
    {
        DB::connection('mongodb')->table('user_main')->insert([
            'id' => 'sparseUser000001',
            'username' => 'sparse-user',
            'user_slug' => 'sparse-user',
            'bio' => null,
            'display_name' => null,
            'account_preference' => [],
            'access_summary' => [],
            'appearance_preference' => ['theme_mode' => 'DRK'],
        ]);
        DB::connection('mongodb')->table('access_assignment')->insert([
            'id' => 'legacySparse001',
            'user_id' => 'sparseUser000001',
            'role_workspace' => 'FND',
            'scope_access' => 'GBL',
            'status_access_assignment' => 'ACT',
        ]);
        DB::connection('mongodb')->table('user_access')->insert([
            '_id' => 'legacySparse001',
            'user_id' => 'sparseUser000001',
            'role_workspace' => 'FND',
            'scope_access' => 'GBL',
            'status_user_access' => 'ACT',
            'permission_code_list' => [],
        ]);

        $migration = require database_path('migrations/2026_07_22_100300_finalize_user_access_and_sparse_profile.php');
        $migration->up();

        $user = (array) DB::connection('mongodb')->table('user_main')->where('id', 'sparseUser000001')->first();
        $access = (array) DB::connection('mongodb')->table('user_access')->where('_id', 'legacySparse001')->first();
        $this->assertArrayHasKey('color_mode', $user['appearance_preference'], json_encode($user));
        $this->assertSame('DRK', $user['appearance_preference']['color_mode']);
        $this->assertArrayNotHasKey('theme_mode', $user['appearance_preference']);
        foreach (['user_slug', 'bio', 'display_name', 'account_preference', 'access_summary'] as $field) {
            $this->assertArrayNotHasKey($field, $user);
        }
        $this->assertArrayNotHasKey('permission_code_list', $access);
        $this->assertSame(0, DB::connection('mongodb')->table('access_assignment')->count());
    }
}
