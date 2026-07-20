<?php

namespace App\Http\Controllers\Web\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\Review\Review;
use App\Support\Workspace\WorkspaceAccess;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request, WorkspaceAccess $workspaceAccess): View
    {
        $user = $request->user();

        return view('workspace.home', [
            'user' => $user,
            'reviewCount' => Review::query()->where('created_by_user_id', (string) $user->getKey())->count(),
            'articleCount' => Article::query()->where('created_by_user_id', (string) $user->getKey())->count(),
            'administrativeAreas' => $workspaceAccess->administrativeAreas($user),
        ]);
    }
}
