<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    public function show(Request $request, string $username): View
    {
        $user = User::where('username', $username)->firstOrFail();
        $isOwner = $request->user()?->is($user) ?? false;
        abort_unless($isOwner || in_array($user->visibility_scope, ['PUB', 'UNL'], true), 404);

        $roles = UserAccess::where('user_id', (string) $user->getKey())
            ->effective()->where('is_role_public', true)->orderBy('public_role_order')->get();

        return view('user.show', compact('user', 'roles', 'isOwner'));
    }
}
