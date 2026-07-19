<?php

namespace App\Http\Controllers;

use App\Support\Demo\SampleContent;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'featuredSpas' => SampleContent::featuredSpas(),
            'featuredTherapists' => SampleContent::featuredTherapists(),
            'areas' => SampleContent::areas(),
            'massageTypes' => SampleContent::massageTypes(),
            'articles' => SampleContent::latestArticles(),
            'latestReviews' => SampleContent::latestReviews(),
            'promos' => SampleContent::promos(),
            'wellnessFinds' => SampleContent::wellnessFinds(),
            'trendingSpas' => SampleContent::trendingSpas(),
            'trendingTherapists' => SampleContent::trendingTherapists(),
            'newListings' => SampleContent::newListings(),
            'updatedProfiles' => SampleContent::updatedProfiles(),
            'stats' => SampleContent::stats(),
            'quote' => SampleContent::quote(),
        ]);
    }
}
