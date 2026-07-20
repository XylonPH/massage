<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate;

class AuthenticateWorkspacePanel extends Authenticate
{
    protected function redirectTo($request): ?string
    {
        return route('login');
    }
}
