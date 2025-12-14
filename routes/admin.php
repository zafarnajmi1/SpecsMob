<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ArticleCommentController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DealController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\DeviceTypeController;
use App\Http\Controllers\Admin\OpinionController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\SpecCategoryController;
use App\Http\Controllers\Admin\SpecFieldController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\TagController;
use App\Models\Brand;

// Public admin login routes (no auth required)
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'store'])->name('admin.loginCheck');

// Protected admin routes (auth + admin.access required)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.access'])->group(function () {
    
    // Dashboard & Logout - accessible by both admin & manager
    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard')
         ->middleware('permission:access_dashboard');
         
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    // ==================== BRAND MANAGEMENT ====================
    Route::middleware(['permission:brand_view|brand_create|brand_edit|brand_delete'])->group(function () {
        Route::resource('brands', BrandController::class);
    });
    // Get devices for brand (for AJAX)
Route::get('/brands/{brand}/devices', function(Brand $brand) {
    $devices = $brand->devices()->select('id', 'name')->get();
    return response()->json($devices);
})->name('brands.devices');

    // ==================== DEVICE MANAGEMENT ====================
    Route::middleware(['permission:device_view|device_create|device_edit|device_delete'])->group(function () {
        Route::resource('devices', DeviceController::class);
        Route::get('test', [DeviceController::class, 'test'])->name('test');
    });

    // ==================== DEVICE TYPES ====================
    Route::middleware(['permission:devicetype_view|devicetype_create'])->group(function () {
        Route::resource('devicetypes', DeviceTypeController::class);
    });

    // ==================== PRICING SETUP ====================
    Route::middleware(['permission:country_view|country_create|country_edit|country_delete'])->group(function () {
        Route::resource('countries', CountryController::class);
    });

    Route::middleware(['permission:currency_view|currency_create|currency_edit|currency_delete'])->group(function () {
        Route::resource('currencies', CurrencyController::class);
    });

    Route::middleware(['permission:store_view|store_create|store_edit|store_delete'])->group(function () {
        Route::resource('stores', StoreController::class);
    });

    // ==================== CONTENT MANAGEMENT ====================
    // Articles (Unified for News, Blogs, Featured)
    Route::middleware(['permission:article_view|article_create|article_edit|article_delete'])->group(function () {
        Route::resource('articles', ArticleController::class);
        // Filter by type
        Route::get('articles/type/{type}', [ArticleController::class, 'indexByType'])->name('articles.type');
    });

    Route::prefix('admin/seo')->name('admin.seo.')->middleware(['auth', 'can:seo_manage'])->group(function () {
    });
    
    // SEO Management
    Route::prefix('seo')->name('seo.')->middleware(['permission:seo_manage'])->group(function () {
        Route::get('global', [SeoController::class, 'global'])->name('global');
        Route::post('global', [SeoController::class, 'saveGlobal'])->name('global.save');
    
        Route::get('schema', [SeoController::class, 'schema'])->name('schema');
        Route::post('schema', [SeoController::class, 'saveSchema'])->name('schema.save');
    
        Route::get('sitemap', [SeoController::class, 'sitemap'])->name('sitemap');
        Route::post('sitemap', [SeoController::class, 'saveSitemap'])->name('sitemap.save');
    });

    // ==================== REVIEW MANAGEMENT ====================
    Route::middleware(['permission:review_view|review_create|review_edit|review_delete'])->group(function () {
    Route::resource('reviews', ReviewController::class);
});

    // Tags Management
    Route::middleware(['permission:tag_view|tag_create|tag_edit|tag_delete'])->group(function () {
        Route::resource('tags', TagController::class);
    });

    // ==================== VIDEO MANAGEMENT ====================
    Route::middleware(['permission:video_view|video_create|video_edit|video_delete'])->group(function () {
        Route::resource('videos', VideoController::class);
    });

    // ==================== SPECIFICATIONS MANAGEMENT ====================
    Route::middleware(['permission:spec_category_manage'])->group(function () {
        Route::resource('spec-categories', SpecCategoryController::class);
    });

    Route::middleware(['permission:spec_field_manage'])->group(function () {
        Route::resource('spec-fields', SpecFieldController::class);
    });

    // ==================== REVIEWS & COMMENTS MODERATION ====================
    // Professional Reviews
    Route::middleware(['permission:review_manage'])->group(function () {
        Route::resource('reviews', ReviewController::class);
    });

    // User Opinions Moderation
    Route::prefix('opinions')->name('opinions.')->middleware('permission:opinion_moderate')->group(function () {
        Route::get('/', [OpinionController::class, 'index'])->name('index');
        Route::put('/{opinion}/approve', [OpinionController::class, 'approve'])->name('approve');
        Route::put('/{opinion}/reject', [OpinionController::class, 'reject'])->name('reject');
        Route::delete('/{opinion}', [OpinionController::class, 'destroy'])->name('destroy');
    });

    // Comments Moderation
    Route::prefix('comments')->name('comments.')->middleware('permission:comment_moderate')->group(function () {
        Route::get('/', [CommentController::class, 'index'])->name('index');
        Route::put('/{comment}/approve', [CommentController::class, 'approve'])->name('approve');
        Route::put('/{comment}/reject', [CommentController::class, 'reject'])->name('reject');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
    });

    // Article Comments Moderation
    Route::prefix('article-comments')->name('article-comments.')->middleware('permission:comment_moderate')->group(function () {
        Route::get('/', [ArticleCommentController::class, 'index'])->name('index');
        Route::put('/{comment}/approve', [ArticleCommentController::class, 'approve'])->name('approve');
        Route::delete('/{comment}', [ArticleCommentController::class, 'destroy'])->name('destroy');
    });

    // ==================== DEALS & OFFERS ====================
    Route::middleware(['permission:deal_view|deal_create|deal_edit|deal_delete'])->group(function () {
        Route::resource('deals', DealController::class);
        Route::get('deals/featured', [DealController::class, 'featured'])->name('deals.featured');
    });

    // ==================== ANALYTICS & REPORTS ====================
    Route::middleware(['permission:reports_view|analytics_view'])->group(function () {
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/overview', [AnalyticsController::class, 'overview'])->name('overview');
            Route::get('/devices', [AnalyticsController::class, 'devices'])->name('devices');
            Route::get('/articles', [AnalyticsController::class, 'articles'])->name('articles');
        });
        
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    // ==================== USER MANAGEMENT (Admin Only) ====================
    Route::middleware(['permission:user_view|user_create|user_edit|user_delete'])->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
    });

    // ==================== SYSTEM SETTINGS (Admin Only) ====================
    Route::middleware(['permission:settings_manage'])->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});