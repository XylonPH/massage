<?php

namespace App\Http\Middleware;

use App\Support\Workspace\WorkspaceAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWorkspacePermission
{
    public function __construct(private WorkspaceAccess $workspaceAccess)
    {
    }

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        abort_unless(
            $request->user() !== null && $this->workspaceAccess->can($request->user(), $permission),
            403,
        );

        return $next($request);
    }
}
