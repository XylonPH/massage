<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\ServiceProfileController;
use App\Http\Controllers\SpaProfileController;
use App\Http\Controllers\TherapistProfileController;
use App\Http\Controllers\Web\Public\ArticleController as PublicArticleController;
use App\Http\Controllers\Web\Public\ReviewController as PublicReviewController;
use App\Http\Controllers\Web\Workspace\ArticleController as WorkspaceArticleController;
use App\Http\Controllers\Web\Workspace\HomeController as WorkspaceHomeController;
use App\Http\Controllers\Web\Workspace\ListingController as WorkspaceListingController;
use App\Http\Controllers\Web\Workspace\ProfileController as WorkspaceProfileController;
use App\Http\Controllers\Web\Workspace\ReviewController as WorkspaceReviewController;
use App\Http\Controllers\Web\Workspace\SettingController as WorkspaceSettingController;
use App\Http\Middleware\EnsureActiveMember;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/spa/{establishment_slug}', [SpaProfileController::class, 'show'])->name('spa.show');
Route::get('/spa/{establishment_slug}/review', [WorkspaceReviewController::class, 'createSpa'])
    ->middleware(['auth', 'verified', EnsureActiveMember::class])
    ->name('spa.review.create');
Route::post('/spa/{establishment_slug}/review', [WorkspaceReviewController::class, 'storeSpa'])
    ->middleware(['auth', 'verified', EnsureActiveMember::class, 'throttle:10,1'])
    ->name('spa.review.store');

Route::get('/therapist/{therapist_slug}', [TherapistProfileController::class, 'show'])->name('therapist.show');
Route::get('/therapist/{therapist_slug}/review', [WorkspaceReviewController::class, 'createTherapist'])
    ->middleware(['auth', 'verified', EnsureActiveMember::class])
    ->name('therapist.review.create');
Route::post('/therapist/{therapist_slug}/review', [WorkspaceReviewController::class, 'storeTherapist'])
    ->middleware(['auth', 'verified', EnsureActiveMember::class, 'throttle:10,1'])
    ->name('therapist.review.store');

Route::get('/service/{service_slug}', [ServiceProfileController::class, 'show'])->name('service.show');
Route::prefix('article')->name('article.')->group(function () {
    Route::get('/', [PublicArticleController::class, 'index'])->name('index');
    Route::get('/category', [PublicArticleController::class, 'categoryIndex'])->name('category.index');
    Route::get('/category/{article_category_slug}', [PublicArticleController::class, 'category'])->name('category.show');
    Route::get('/tag', [PublicArticleController::class, 'tagIndex'])->name('tag.index');
    Route::get('/tag/{tag_slug}', [PublicArticleController::class, 'tag'])->name('tag.show');
    Route::get('/author', [PublicArticleController::class, 'authorIndex'])->name('author.index');
    Route::get('/author/{author_slug}', [PublicArticleController::class, 'author'])->name('author.show');
    Route::get('/neural-agent', [PublicArticleController::class, 'neuralAgentIndex'])->name('neural-agent.index');
    Route::get('/neural-agent/{neural_agent_slug}', [PublicArticleController::class, 'neuralAgent'])->name('neural-agent.show');
    Route::get('/audience', [PublicArticleController::class, 'audienceIndex'])->name('audience.index');
    Route::get('/audience/{audience_slug}', [PublicArticleController::class, 'audience'])->name('audience.show');
    Route::get('/archive', [PublicArticleController::class, 'archive'])->name('archive');
    Route::get('/search', [PublicArticleController::class, 'search'])->name('search');
    Route::get('/{article_slug}', [PublicArticleController::class, 'show'])->name('show');
});

Route::prefix('review')->name('review.')->group(function () {
    Route::get('/', [PublicReviewController::class, 'index'])->name('index');
    Route::get('/spa', [PublicReviewController::class, 'spas'])->name('spa');
    Route::get('/therapist', [PublicReviewController::class, 'therapists'])->name('therapist');
    Route::get('/{review_slug}', [PublicReviewController::class, 'show'])->name('show');
});

Route::get('/legal/terms', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/legal/privacy', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/legal/cookie', [LegalController::class, 'cookies'])->name('legal.cookies');

// Planned public sections that are not built yet render a shared
// coming-soon page so header navigation never leads to a 404.
Route::view('/directory/{path?}', 'coming-soon', ['sectionKey' => 'navigation.directory'])
    ->where('path', '.*')
    ->name('directory.index');

foreach ([
    'campus' => 'navigation.campus',
    'promo' => 'navigation.promos',
] as $path => $sectionKey) {
    Route::view("/{$path}", 'coming-soon', ['sectionKey' => $sectionKey])->name(str_replace('-', '_', $path).'.index');
}

foreach ([
    'claim' => 'navigation.claim',
    'help' => 'navigation.help',
] as $path => $sectionKey) {
    Route::view("/{$path}/{subpath?}", 'coming-soon', ['sectionKey' => $sectionKey])
        ->where('subpath', '.*')
        ->name($path.'.index');
}

Route::prefix('workspace')
    ->name('workspace.')
    ->middleware(['auth', 'verified', EnsureActiveMember::class])
    ->group(function () {
        Route::redirect('/', '/workspace/home');
        Route::get('/home', [WorkspaceHomeController::class, 'index'])->name('home');
        Route::get('/profile', [WorkspaceProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [WorkspaceProfileController::class, 'update'])->middleware('throttle:20,1')->name('profile.update');
        Route::get('/setting', [WorkspaceSettingController::class, 'edit'])->name('setting.edit');
        Route::put('/setting', [WorkspaceSettingController::class, 'update'])->middleware('throttle:20,1')->name('setting.update');
        Route::get('/listing/spa', [WorkspaceListingController::class, 'spaIndex'])->name('listing.spa');
        Route::get('/listing/therapist', [WorkspaceListingController::class, 'therapistIndex'])->name('listing.therapist');
    });

Route::prefix('workspace/article')
    ->name('workspace.article.')
    ->middleware(['auth', 'verified', EnsureActiveMember::class])
    ->group(function () {
        Route::get('/', [WorkspaceArticleController::class, 'index'])->name('index');
        Route::get('/draft', [WorkspaceArticleController::class, 'drafts'])->name('draft');
        Route::get('/submitted', [WorkspaceArticleController::class, 'submitted'])->name('submitted');
        Route::get('/published', [WorkspaceArticleController::class, 'published'])->name('published');
        Route::get('/new', [WorkspaceArticleController::class, 'create'])->name('create');
        Route::post('/', [WorkspaceArticleController::class, 'store'])->middleware('throttle:20,1')->name('store');
        Route::get('/{article}/edit', [WorkspaceArticleController::class, 'edit'])->name('edit');
        Route::put('/{article}', [WorkspaceArticleController::class, 'update'])->middleware('throttle:30,1')->name('update');
        Route::post('/{article}/submit', [WorkspaceArticleController::class, 'submit'])->middleware('throttle:10,1')->name('submit');
        Route::get('/{article}/revision', [WorkspaceArticleController::class, 'revisions'])->name('revisions');
    });

Route::prefix('workspace/review')
    ->name('workspace.review.')
    ->middleware(['auth', 'verified', EnsureActiveMember::class])
    ->group(function () {
        Route::get('/', [WorkspaceReviewController::class, 'index'])->name('index');
        Route::get('/draft', [WorkspaceReviewController::class, 'drafts'])->name('draft');
        Route::get('/submitted', [WorkspaceReviewController::class, 'submitted'])->name('submitted');
        Route::get('/published', [WorkspaceReviewController::class, 'published'])->name('published');
        Route::get('/{review}/edit', [WorkspaceReviewController::class, 'edit'])->name('edit');
        Route::put('/{review}', [WorkspaceReviewController::class, 'update'])->middleware('throttle:20,1')->name('update');
        Route::post('/{review}/submit', [WorkspaceReviewController::class, 'submit'])->middleware('throttle:10,1')->name('submit');
    });

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('register.store');
    Route::get('/register/check-username', [RegisterController::class, 'checkUsername'])
        ->middleware('throttle:30,1')
        ->name('register.check-username');

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])
        ->middleware('throttle:5,1')
        ->name('password.update');
});

Route::post('/logout', [LogoutController::class, 'store'])->middleware('auth')->name('logout');

Route::get('/verify-email', [EmailVerificationController::class, 'notice'])->name('verification.notice');
Route::get('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware('signed')
    ->name('verification.verify');
Route::post('/verify-email/resend', [EmailVerificationController::class, 'resend'])
    ->middleware('throttle:6,1')
    ->name('verification.resend');
