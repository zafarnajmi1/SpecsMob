<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DeviceOpinionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/admin.php';



Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::controller(WebController::class)
    ->group(function () {

        Route::get('', 'home')->name('home');
        Route::get('news', 'news')->name('news');
        Route::get('/reviews', 'reviews')->name('reviews');
        Route::get('/videos', 'videos')->name('videos');
        Route::get('/featured', 'featured')->name('featured');
        Route::get('/phone-finder', 'phoneFinder')->name('phone-finder');
        Route::get('/phone-finder-results', 'phoneFinderResults')->name('phone-finder-results');
        Route::get('/deals', 'deals')->name('deals');
        Route::get('/merch', 'merch')->name('merch');
        Route::get('/coverage', 'coverage')->name('coverage');
        Route::get('/contact', 'contact')->name('contact');
        Route::get('/tip-us', 'tip_us')->name('tip-us');
        Route::post('/contact-submit', 'handleContactSubmit')->name('contact.submit');
        Route::post('/tip-us-submit', 'handleTipUsSubmit')->name('tip-us.submit');
        // Route::get('/reviews', 'review_detail')->name('reviews'); //dummy
    
        Route::get('/reviews/show/{slug}', 'review_detail')->name('review-detail');
        Route::get('/articles/show/{slug}/{type}', 'article_detail')->name('article-detail');

        Route::get('/brands', 'brands')->name('brands.all');
        Route::get('/brands/{slug}', 'brand_devices')->name('brand.devices');

        Route::get('/devices/show/{slug}', 'device_detail')->name('device-detail');
        Route::get('/{slug}-pictures-{id}', 'device_pictures')->where([
            'slug' => '[a-z0-9\-]+',
            'id' => '[0-9]+'
        ])->name('device.pictures');
        Route::get('/{slug}-reviews-{id}', 'device_opinions')->where([
            'slug' => '[a-z0-9\-]+',
            'id' => '[0-9]+'
        ])->name('device.opinions');
        Route::get('/{slug}-review-{id}', 'review_comments')->where([
            'slug' => '[A-Za-z0-9\-]+',
            'id' => '[0-9]+'
        ])->name('review.comments');
        Route::get('/{slug}-{id}/prices', 'device_prices')->where([
            'slug' => '[A-Za-z0-9\-]+',
            'id' => '[0-9]+'
        ])->name('device.prices');
        Route::get('/{slug}-comment-{id}', 'article_comments')->where([
            'slug' => '[A-Za-z0-9\-]+',
            'id' => '[0-9]+'
        ])->name('article.comments');
        Route::get('/article', 'contact')->name('articles.show'); //dummy
        Route::get('/tags/{slug}', 'tag_articles')->name('tags.show');
        Route::get('/compare', 'device_comparison')->name('device-comparison');
        Route::get('/{slug}-compare-{id}', 'device_comparison')->where([
            'slug' => '[A-Za-z0-9\-]+',
            'id' => '[0-9]+'
        ])->name('device.compare');
        Route::get('/search', 'globalSearch')->name('search.global');
        Route::get('/live-search', 'liveSearch')->name('search.live');
        Route::get('/search-devices', 'search_devices')->name('device.search');
        Route::post('/devices/{id}/fan', 'toggleFan')->name('device.fan');
    });

//Device opinions
Route::get('/{slug}-{id}', [DeviceOpinionController::class, 'device_opinion_post'])->where([
    'slug' => '[a-z0-9\-]+',
    'id' => '[0-9]+'
])->name('device.opinions.post');
Route::post('/{slug}-{id}/opinion', [DeviceOpinionController::class, 'store'])
    ->where([
        'slug' => '[a-z0-9\-]+',
        'id' => '[0-9]+'
    ])
    ->middleware('auth')
    ->name('device.opinions.store');


// Comments (both articles and reviews)
Route::get('/{slug}-{id}/{type}-comments', [CommentController::class, 'comment_post'])
    ->where(['slug' => '[A-Za-z0-9\-]+', 'id' => '[0-9]+'])
    ->name('comment.post');

// Store comment (both articles and reviews)
Route::post('/{slug}-{id}/{type}-comments', [CommentController::class, 'store'])
    ->where(['slug' => '[A-Za-z0-9\-]+', 'id' => '[0-9]+'])
    ->middleware('auth')
    ->name('comment.store');


// User dashboard (frontend)
Route::prefix('user')
    ->name('user.')
    ->middleware(['auth', 'role:user'])
    ->group(function () {

        Route::get('/account', [UserController::class, 'account'])
            ->name('account');
        Route::get('/{username}-posts', [UserController::class, 'posts'])
            ->where('username', '[A-Za-z0-9\-]+')
            ->name('posts');
        Route::get('/{username}-manage', [UserController::class, 'account_manage'])
            ->where('username', '[A-Za-z0-9\-]+')
            ->name('account.manage');
        Route::post('/{username}-avatar', [UserController::class, 'updateAvatar'])
            ->where('username', '[A-Za-z0-9\-]+')
            ->name('avatar.update');
        Route::post('/{username}-nickname', [UserController::class, 'updateNickname'])
            ->where('username', '[A-Za-z0-9\-]+')
            ->name('nickname.update');
        Route::post('/{username}-freeze', [UserController::class, 'freeze'])
            ->where('username', '[A-Za-z0-9\-]+')
            ->name('freeze');
        Route::delete('/{username}-delete', [UserController::class, 'destroy'])
            ->where('username', '[A-Za-z0-9\-]+')
            ->name('delete');
        Route::post('/{username}-download', [UserController::class, 'downloadData'])
            ->where('username', '[A-Za-z0-9\-]+')
            ->name('data.download');

        Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
    });
