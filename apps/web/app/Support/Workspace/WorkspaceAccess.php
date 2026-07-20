<?php

namespace App\Support\Workspace;

use App\Models\AccessAssignment;
use App\Models\User;
use Illuminate\Support\Collection;

class WorkspaceAccess
{
    /** @var array<string, Collection<int, AccessAssignment>> */
    private array $assignmentCache = [];

    public function can(User $user, string $permission, string $scope = 'GBL', ?string $scopeRecordId = null): bool
    {
        return $this->assignments($user)->contains(
            fn (AccessAssignment $assignment): bool => $this->assignmentMatchesScope($assignment, $scope, $scopeRecordId)
                && in_array($permission, $this->assignmentPermissions($assignment), true),
        );
    }

    public function canAccessPanel(User $user, string $panelId): bool
    {
        $permission = config("workspace.panel_permission_map.{$panelId}");

        return is_string($permission) && $this->can($user, $permission);
    }

    /** @return list<string> */
    public function scopedRecordIds(User $user, string $permission, string $scope): array
    {
        return $this->assignments($user)
            ->filter(fn (AccessAssignment $assignment): bool => $assignment->scope_access === $scope
                && filled($assignment->scope_record_id)
                && in_array($permission, $this->assignmentPermissions($assignment), true))
            ->pluck('scope_record_id')
            ->map(fn (mixed $id): string => (string) $id)
            ->unique()
            ->values()
            ->all();
    }

    /** @return list<array{key: string, url: string, title: string, description: string}> */
    public function administrativeAreas(User $user): array
    {
        return collect(config('workspace.administrative_area_list', []))
            ->filter(fn (array $area, string $panelId): bool => $this->canAccessPanel($user, $panelId))
            ->map(fn (array $area, string $panelId): array => [
                'key' => $panelId,
                'url' => $area['url'],
                'title' => __($area['title_key']),
                'description' => __($area['description_key']),
            ])
            ->values()
            ->all();
    }

    /** @return Collection<int, AccessAssignment> */
    private function assignments(User $user): Collection
    {
        $userId = (string) $user->getKey();

        return $this->assignmentCache[$userId] ??= AccessAssignment::query()
            ->where('user_id', $userId)
            ->effective()
            ->get();
    }

    /** @return list<string> */
    private function assignmentPermissions(AccessAssignment $assignment): array
    {
        $rolePermissions = config("workspace.role_permission_map.{$assignment->role_workspace}", []);

        return array_values(array_unique([
            ...array_filter($rolePermissions, 'is_string'),
            ...array_filter($assignment->permission_code_list ?? [], 'is_string'),
        ]));
    }

    private function assignmentMatchesScope(AccessAssignment $assignment, string $scope, ?string $scopeRecordId): bool
    {
        if ($assignment->scope_access === 'GBL') {
            return true;
        }

        return $assignment->scope_access === $scope
            && $scopeRecordId !== null
            && (string) $assignment->scope_record_id === $scopeRecordId;
    }
}
