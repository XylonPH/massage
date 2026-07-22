<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Support\Demo\SampleContent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DirectoryController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->query('type', 'all');
        $areaFilter = $request->query('area');
        $serviceFilter = $request->query('service');

        return view('directory.index', [
            'activeTab' => $tab,
            'selectedArea' => $areaFilter,
            'selectedService' => $serviceFilter,
            'featuredSpas' => SampleContent::featuredSpas(),
            'featuredTherapists' => SampleContent::featuredTherapists(),
            'trendingSpas' => SampleContent::trendingSpas(),
            'areas' => SampleContent::areas(),
            'massageTypes' => SampleContent::massageTypes(),
            'stats' => SampleContent::stats(),
        ]);
    }
}
