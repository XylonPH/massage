<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Support\Demo\SampleContent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromoController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category', 'all');

        $promos = SampleContent::promos();

        return view('promo.index', [
            'promos' => $promos,
            'activeCategory' => $category,
            'featuredSpas' => SampleContent::featuredSpas(),
        ]);
    }
}
