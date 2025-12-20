<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Brand;
use App\Models\Currency;
use App\Models\Device;
use App\Models\DeviceImage;
use App\Models\DeviceImageGroup;
use App\Models\Review;
use Illuminate\Http\Request;

class WebController extends Controller
{
    // public function home()
    // {
    //     $brands = Brand::active()->get();

    //     $reviews = Review::published()
    //         ->with(['device.brand', 'author']) // Eager load relationships
    //         ->latest('published_at')
    //         ->take(6) // Limit for homepage
    //         ->get();

    //     $articles = Article::published()
    //         ->latest('published_at')
    //         ->withCount('comments')
    //         ->take(10)
    //         ->get();

    //     return view('home', compact('articles', 'reviews', 'brands'));
    // }

    // public function home()
    // {
    //     $featuredReview = Review::published()
    //         ->with(['device.brand', 'author'])
    //         ->withCount('comments') // ✅ Add this
    //         ->latest('published_at')
    //         ->first();

    //     $sideReviews = Review::published()
    //         ->with(['device.brand', 'author'])
    //         ->withCount('comments') // ✅ Add this
    //         ->where('id', '!=', $featuredReview->id ?? 0)
    //         ->latest('published_at')
    //         ->take(2)
    //         ->get();

    //     $featuredArticle = Article::published()
    //         ->with(['author'])
    //         ->withCount('comments') // ✅ Add this
    //         ->latest('published_at')
    //         ->first();

    //     $sideArticles = Article::published()
    //         ->with(['author'])
    //         ->withCount('comments') // ✅ Add this
    //         ->where('id', '!=', $featuredArticle->id ?? 0)
    //         ->latest('published_at')
    //         ->take(2)
    //         ->get();

    //     $brands = Brand::active()->get();

    //     $latestReviews = Review::published()
    //         ->with(['device.brand'])
    //         ->withCount('comments') // ✅ Add this
    //         ->latest('published_at')
    //         ->take(10)
    //         ->get();

    //     $latestArticles = Article::published()
    //         ->withCount('comments') // ✅ Add this
    //         ->latest('published_at')
    //         ->take(10)
    //         ->get();

    //     return view('home', compact(
    //         'featuredReview',
    //         'sideReviews',
    //         'featuredArticle',
    //         'sideArticles',
    //         'brands',
    //         'latestReviews',
    //         'latestArticles'
    //     ));
    // }

    public function home()
    {
        $featuredReview = Review::published()
            ->with(['device.brand', 'author'])
            ->withCount('comments')
            ->latest('published_at')
            ->first();

        $sideReviews = Review::published()
            ->with(['device.brand', 'author'])
            ->withCount('comments')
            ->where('id', '!=', $featuredReview->id ?? 0)
            ->latest('published_at')
            ->take(2)
            ->get();

        // Get featured article (with type 'featured' or fallback to latest)
        $featuredArticle = Article::published()
            ->featured() // Use the featured scope
            ->with(['author'])
            ->withCount('comments')
            ->latest('published_at')
            ->first();

        // If no featured article, get the latest published article
        if (!$featuredArticle) {
            $featuredArticle = Article::published()
                ->with(['author'])
                ->withCount('comments')
                ->latest('published_at')
                ->first();
        }

        // Get side articles (exclude the featured one, get latest news)
        $sideArticles = Article::published()
            ->news() // Get news type articles
            ->with(['author'])
            ->withCount('comments')
            ->where('id', '!=', $featuredArticle->id ?? 0)
            ->latest('published_at')
            ->take(2)
            ->get();

        $brands = Brand::active()->get();

        $latestReviews = Review::published()
            ->with(['device.brand'])
            ->withCount('comments')
            ->latest('published_at')
            ->take(10)
            ->get();

        $latestArticles = Article::published()
            ->withCount('comments')
            ->latest('published_at')
            ->take(10)
            ->get();

        return view('home', compact(
            'featuredReview',
            'sideReviews',
            'featuredArticle',
            'sideArticles',
            'brands',
            'latestReviews',
            'latestArticles'
        ));
    }

    public function news()
    {
        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.news', compact('popularReviews'));
    }

    public function reviews()
    {
        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.reviews', compact('popularReviews'));
    }
    public function videos()
    {
        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.videos', compact('popularReviews'));
    }

    public function featured()
    {
        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.featured', compact('popularReviews'));
    }
    public function deals()
    {
        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.deals', compact('popularReviews'));
    }

    public function coverage()
    {
        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.coverage', compact('popularReviews'));
    }

    public function contact()
    {
        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.contact', compact('popularReviews'));
    }

    public function phoneFinder()
    {
        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.phone-finder', compact('popularReviews'));
    }

    public function review_detail($slug)
    {
        $review = Review::where('slug', $slug)->firstOrFail();
        // $comments = $review->comments()->where('is_approved', 'approved')->get();
        $comments = collect([
            [
                'username' => 'Ahmad',
                'location' => 'Pakistan',
                'avatar_color' => '#8ca6c6',
                'created_at' => now()->subDays(1),
                'content' => 'The Nothing Phone (3a) Lite is actually amazing for the price. The battery life is much better than expected.',
            ],
            [
                'username' => 'TechGuru',
                'location' => 'India',
                'avatar_color' => '#cc8899',
                'created_at' => now()->subDays(2),
                'content' => 'Camera performance is average in low light. But for daylight shots, it performs great!',
            ],
        ])->map(function ($item) {
            return (object) $item;  // convert to object
        });
        return view('user-views.pages.review-detail', compact('review', 'comments'));
    }


    public function article_detail($slug, $type)
    {
        // Validate type parameter
        $validTypes = ['news', 'article', 'featured'];
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $article = Article::where('type', $type)
            ->where('slug', $slug)
            ->published() // Only show published articles
            ->with(['author', 'brand', 'device', 'tags', 'comments'])
            ->withCount('comments')
            ->firstOrFail();

        // Increment view count
        $article->increment('views_count');

        $comments = $article->comments()->latest()->get();

        // If no comments, use mock data
        if ($comments->isEmpty()) {
            $comments = collect([
                [
                    'username' => 'Ahmad',
                    'location' => 'Pakistan',
                    'avatar_color' => '#8ca6c6',
                    'created_at' => now()->subDays(1),
                    'content' => 'The Nothing Phone (3a) Lite is actually amazing for the price. The battery life is much better than expected.',
                ],
                [
                    'username' => 'TechGuru',
                    'location' => 'India',
                    'avatar_color' => '#cc8899',
                    'created_at' => now()->subDays(2),
                    'content' => 'Camera performance is average in low light. But for daylight shots, it performs great!',
                ],
            ])->map(function ($item) {
                return (object) $item;
            });
        }

        // Get related articles
        $relatedArticles = Article::published()
            ->where('type', $type)
            ->where('id', '!=', $article->id)
            ->withCount('comments')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('user-views.pages.article-detail', compact('article', 'comments', 'relatedArticles'));
    }

    public function brands()
    {
        $brands = Brand::active()->get();
        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.brands', compact('brands', 'popularReviews'));
    }

    // public function brand_devices($slug)
    // {
    //     $brand = Brand::where('slug', $slug)->firstOrFail();

    //     // Get devices with sorting
    //     $devices = $brand->devices()
    //         ->where('is_published', 1);

    //     // Apply sorting
    //     $sort = request()->get('sort', 'release');
    //     if ($sort === 'popularity') {
    //         $devices = $devices->orderBy('views_count', 'desc');
    //     } else {
    //         $devices = $devices->latest('released_at');
    //     }

    //     $devices = $devices->get();

    //     $popularReviews = [
    //         [
    //             'title' => 'OnePlus 15 review',
    //             'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
    //             'url' => '/review/oneplus-15',
    //         ],
    //         [
    //             'title' => 'Realme GT 8 Pro review',
    //             'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
    //             'url' => '/review/realme-gt-8-pro',
    //         ],
    //         [
    //             'title' => 'Oppo Find X9 Pro review',
    //             'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
    //             'url' => '/review/oppo-find-x9-pro',
    //         ],
    //     ];

    //     return view('user-views.pages.brand-devices', compact('brand', 'devices', 'popularReviews'));
    // }


    public function brand_devices($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        $devices = $brand->devices()
            ->where('is_published', '1');

        $sort = request()->get('sort', 'release');
        if ($sort === 'popularity') {
            $devices = $devices->orderBy('views_count', 'desc');
        } else {
            $devices = $devices->latest('released_at');
        }

        $devices = $devices->paginate(15);  // 👈 Pagination added

        $selectedDevices = [];

        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.brand-devices', compact('brand', 'devices', 'popularReviews', 'selectedDevices'));
    }

    public function device_detail($slug)
    {
        $device = Device::where('slug', $slug)
            ->where('is_published', true)
            ->with(['brand', 'deviceType'])
            ->firstOrFail();

        // Load specifications
        $device->load(['specValues.field.category']);

        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        $opinions = collect([
            [
                'username' => 'Ahmad',
                'location' => 'Pakistan',
                'avatar_color' => '#8ca6c6',
                'created_at' => now()->subDays(1),
                'content' => 'The Nothing Phone (3a) Lite is actually amazing for the price. The battery life is much better than expected.',
            ],
            [
                'username' => 'TechGuru',
                'location' => 'India',
                'avatar_color' => '#cc8899',
                'created_at' => now()->subDays(2),
                'content' => 'Camera performance is average in low light. But for daylight shots, it performs great!',
            ],
        ])->map(function ($item) {
            return (object) $item;  // convert to object
        });

        // return $device;
        return view('user-views.pages.device-detail', compact('device', 'popularReviews', 'opinions'));
    }

    public function device_pictures(string $slug, int $id)
    {
        $device = Device::where('id', $id)->where('slug', $slug)
            ->where('is_published', true)
            ->with([
                'imageGroups.images' // <-- eager load everything
            ])
            ->firstOrFail();

        if ($slug !== $device->slug) {
            return redirect()
                ->route('device.pictures', [
                    'slug' => $device->slug,
                    'id' => $device->id
                ], 301);
        }

        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.device-pictures', compact('device', 'popularReviews'));
    }

    public function device_opinions(string $slug, int $id)
    {
        $device = Device::where('id', $id)->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        if ($slug !== $device->slug) {
            return redirect()
                ->route('device.opinions', [
                    'slug' => $device->slug,
                    'id' => $device->id
                ], 301);
        }

        $comments = collect([
            [
                'username' => 'Ahmad',
                'location' => 'Pakistan',
                'avatar_color' => '#8ca6c6',
                'created_at' => now()->subDays(1),
                'content' => 'The Nothing Phone (3a) Lite is actually amazing for the price. The battery life is much better than expected.',
            ],
            [
                'username' => 'TechGuru',
                'location' => 'India',
                'avatar_color' => '#cc8899',
                'created_at' => now()->subDays(2),
                'content' => 'Camera performance is average in low light. But for daylight shots, it performs great!',
            ],
        ])->map(function ($item) {
            return (object) $item;  // convert to object
        });

        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.device-opinions', compact('device', 'popularReviews', 'comments'));
    }


    public function device_prices(string $slug, int $id)
    {
        $device = Device::where('id', $id)
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with([
                'variants' => function ($query) {
                    $query->where('device_variants.status', true)  // Specify table name
                        ->orderBy('storage_gb')
                        ->orderBy('ram_gb');
                },
                // Use 'offers' relationship
                'offers' => function ($query) {
                    $query->where('device_offers.status', true)  // Specify table name
                        ->where('device_offers.in_stock', true)
                        ->with([
                            'variant',
                            'country',
                            'currency',
                            'store'
                        ]);
                }
            ])
            ->firstOrFail();

        if ($slug !== $device->slug) {
            return redirect()
                ->route('device.prices', [
                    'slug' => $device->slug,
                    'id' => $device->id
                ], 301);
        }

        // Get all currencies for dropdown
        $currencies = Currency::whereIn('iso_code', ['USD', 'EUR', 'GBP', 'INR', 'AED', 'JPY', 'CAD'])
            ->orderBy('iso_code')
            ->get();

        return view('user-views.pages.device-prices', compact('device', 'currencies'));
    }

    public function review_comments(string $slug, int $id)
    {
        $review = Review::where('id', $id)->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        if ($slug !== $review->slug) {
            return redirect()
                ->route('review.comments', [
                    'slug' => $review->slug,
                    'id' => $review->id
                ], 301);
        }

        $comments = collect([
            [
                'username' => 'Ahmad',
                'location' => 'Pakistan',
                'avatar_color' => '#8ca6c6',
                'created_at' => now()->subDays(1),
                'content' => 'The Nothing Phone (3a) Lite is actually amazing for the price. The battery life is much better than expected.',
            ],
            [
                'username' => 'TechGuru',
                'location' => 'India',
                'avatar_color' => '#cc8899',
                'created_at' => now()->subDays(2),
                'content' => 'Camera performance is average in low light. But for daylight shots, it performs great!',
            ],
        ])->map(function ($item) {
            return (object) $item;  // convert to object
        });

        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.review-comment', compact('review', 'popularReviews', 'comments'));
    }

    public function article_comments(string $slug, int $id)
    {
        $article = Article::where('id', $id)->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        if ($slug !== $article->slug) {
            return redirect()
                ->route('article.comments', [
                    'slug' => $article->slug,
                    'id' => $article->id
                ], 301);
        }

        $comments = collect([
            [
                'username' => 'Ahmad',
                'location' => 'Pakistan',
                'avatar_color' => '#8ca6c6',
                'created_at' => now()->subDays(1),
                'content' => 'The Nothing Phone (3a) Lite is actually amazing for the price. The battery life is much better than expected.',
            ],
            [
                'username' => 'TechGuru',
                'location' => 'India',
                'avatar_color' => '#cc8899',
                'created_at' => now()->subDays(2),
                'content' => 'Camera performance is average in low light. But for daylight shots, it performs great!',
            ],
        ])->map(function ($item) {
            return (object) $item;  // convert to object
        });

        $popularReviews = [
            [
                'title' => 'OnePlus 15 review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oneplus-15/-347x151/gsmarena_000.jpg',
                'url' => '/review/oneplus-15',
            ],
            [
                'title' => 'Realme GT 8 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/realme-gt-8-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/realme-gt-8-pro',
            ],
            [
                'title' => 'Oppo Find X9 Pro review',
                'img' => 'https://fdn.gsmarena.com/imgroot/reviews/25/oppo-find-x9-pro/-347x151/gsmarena_001.jpg',
                'url' => '/review/oppo-find-x9-pro',
            ],
        ];

        return view('user-views.pages.article-comment', compact('article', 'popularReviews', 'comments'));
    }


}