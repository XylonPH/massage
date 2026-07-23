<?php

namespace App\Http\Controllers\Web\Workspace;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAccess;
use App\Models\UserSession;
use App\Services\UserSessionManager;
use App\Support\Workspace\WorkspaceAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SystemUserController extends Controller
{
    public function __construct(private readonly WorkspaceAccess $access) {}

    public function index(Request $request): View
    {
        $this->authorizePermission($request, 'user.view');
        $search = trim((string) $request->query('search'));
        $users = User::query()->when($search !== '', fn ($query) => $query->where(function ($query) use ($search): void {
            $query->where('username', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('display_name', 'like', "%{$search}%");
        }))->orderBy('username')->paginate(25)->withQueryString();

        return view('workspace.system.user-index', compact('users', 'search'));
    }

    public function show(Request $request, User $user): View
    {
        $this->authorizePermission($request, 'user.view');
        $assignments = UserAccess::where('user_id', (string) $user->getKey())->orderByDesc('created_at')->get();
        $sessions = UserSession::where('user_id', (string) $user->getKey())->orderByDesc('last_activity_at')->get();

        return view('workspace.system.user-show', compact('user', 'assignments', 'sessions'));
    }

    public function updateStatus(Request $request, User $user, UserSessionManager $sessions): RedirectResponse
    {
        $this->authorizePermission($request, 'user.manage');
        abort_if($request->user()->is($user), 422, __('user.cannot_suspend_self'));
        $validated = $request->validate(['status_account' => ['required', Rule::in(['ACT', 'SUS'])], 'reason' => ['required', 'string', 'min:10', 'max:500']]);
        $user->forceFill(['status_account' => $validated['status_account']])->save();
        if ($validated['status_account'] !== 'ACT') {
            $sessions->revokeAllFor($user, (string) $request->user()->getKey());
        }

        return back()->with('status', __('user.account_status_updated'));
    }

    public function storeAccess(Request $request, User $user): RedirectResponse
    {
        $this->authorizePermission($request, 'user.access.manage');
        $validated = $request->validate(['role_workspace' => ['required', Rule::in(array_keys(config('user.role_labels')))], 'grant_reason' => ['required', 'string', 'min:10', 'max:500'], 'is_role_public' => ['sometimes', 'boolean']]);
        $exists = UserAccess::where('user_id', (string) $user->getKey())->where('role_workspace', $validated['role_workspace'])->effective()->exists();
        abort_if($exists, 422, __('user.role_already_assigned'));
        UserAccess::create([
            'user_id' => (string) $user->getKey(), 'role_workspace' => $validated['role_workspace'],
            'scope_access' => 'GBL', 'status_user_access' => 'ACT', 'effective_at' => now(),
            'granted_by_user_id' => (string) $request->user()->getKey(), 'grant_reason' => $validated['grant_reason'],
            'is_role_public' => $request->boolean('is_role_public'), 'created_at' => now(), 'updated_at' => now(),
        ]);

        return back()->with('status', __('user.role_assigned'));
    }

    public function destroyAccess(Request $request, User $user, UserAccess $assignment): RedirectResponse
    {
        $this->authorizePermission($request, 'user.access.manage');
        abort_unless((string) $assignment->user_id === (string) $user->getKey(), 404);
        if ($assignment->role_workspace === 'FND') {
            abort_if($request->user()->is($user), 422, __('user.cannot_remove_own_founder'));
            abort_if(UserAccess::where('role_workspace', 'FND')->effective()->count() <= 1, 422, __('user.cannot_remove_last_founder'));
        }
        $validated = $request->validate(['revocation_reason' => ['required', 'string', 'min:10', 'max:500']]);
        $assignment->forceFill(['status_user_access' => 'REV', 'revoked_at' => now(), 'revoked_by_user_id' => (string) $request->user()->getKey(), 'revocation_reason' => $validated['revocation_reason']])->save();

        return back()->with('status', __('user.role_revoked'));
    }

    public function destroySession(Request $request, User $user, UserSession $session, UserSessionManager $manager): RedirectResponse
    {
        $this->authorizePermission($request, 'user.session.manage');
        abort_unless((string) $session->user_id === (string) $user->getKey(), 404);
        $manager->revoke($session, (string) $request->user()->getKey());

        return back()->with('status', __('user.session_revoked'));
    }

    private function authorizePermission(Request $request, string $permission): void
    {
        abort_unless($this->access->can($request->user(), $permission), 403);
    }
}
