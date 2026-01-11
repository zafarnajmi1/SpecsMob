<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Brand;
use App\Models\Currency;
use App\Models\Device;
use App\Models\DeviceImage;
use App\Models\DeviceImageGroup;
use App\Models\Review;
use App\Models\Tag;
use App\Models\UserFavorite;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function toggleFan($id)
    {
        $device = Device::findOrFail($id);

        if (!$device->allow_fans) {
            return back()->with('error', 'Fans are not allowed for this device.');
        }

        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Please login to become a fan.');
        }

        $favorite = UserFavorite::where('user_id', auth()->id())
            ->where('device_id', $id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $device->stats()->decrement('total_fans');
            $message = 'You are no longer a fan of ' . $device->name;
        } else {
            UserFavorite::create([
                'user_id' => auth()->id(),
                'device_id' => $id
            ]);

            $stats = $device->stats()->firstOrCreate(['device_id' => $device->id]);
            $stats->incrementFan();

            $message = 'You are now a fan of ' . $device->name;
        }

        return back()->with('success', $message);
    }
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
        // Get 3 latest reviews for featured section
        $latestReviewsTop3 = Review::published()
            ->with(['device.brand', 'author'])
            ->withCount('comments')
            ->latest('published_at')
            ->take(3)
            ->get();

        $featuredReview = $latestReviewsTop3->first();
        $sideReviews = $latestReviewsTop3->skip(1);

        // Get 3 latest articles for featured section
        $latestArticlesTop3 = Article::published()
            ->with(['author'])
            ->withCount('comments')
            ->latest('published_at')
            ->take(3)
            ->get();

        $featuredArticle = $latestArticlesTop3->first();
        $sideArticles = $latestArticlesTop3->skip(1);

        $brands = Brand::active()->get();
        $mobileBrands = Brand::active()->take(12)->get();

        $latestReviews = Review::published()
            ->with(['device.brand'])
            ->withCount('comments')
            ->latest('published_at')
            ->skip(3) // Skip those shown in featured
            ->take(10)
            ->get();

        $latestArticles = Article::published()
            ->withCount('comments')
            ->latest('published_at')
            ->skip(3) // Skip those shown in featured
            ->take(10)
            ->get();

        $featuredReviews = Review::published()
            ->latest('published_at')
            ->take(8)
            ->get();

        $featuredArticles = Article::published()
            ->featured()
            ->latest('published_at')
            ->take(6)
            ->get();

        $latestDevices = Device::where('is_published', true)
            ->latest()
            ->take(7)
            ->get();

        $inStoreDevices = Device::where('is_published', true)
            ->where('release_status', Device::STATUS_RELEASED)
            ->latest()
            ->take(7)
            ->get();

        return view('home', compact(
            'featuredReview',
            'sideReviews',
            'featuredArticle',
            'sideArticles',
            'brands',
            'mobileBrands',
            'latestReviews',
            'latestArticles',
            'featuredReviews',
            'featuredArticles',
            'latestDevices',
            'inStoreDevices'
        ));
    }

    public function news(Request $request)
    {

        $query = Article::published()
            ->news()
            ->withCount('comments');

        // Search functionality
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('body', 'like', "%{$searchTerm}%");
            });
        }

        // Tag filtering
        if ($request->filled('tag')) {
            $tagValue = $request->tag;
            $query->where(function ($q) use ($tagValue) {
                $q->whereHas('tags', function ($t) use ($tagValue) {
                    $t->where('name', $tagValue)
                        ->orWhere('slug', $tagValue);
                })
                    ->orWhereHas('brand', function ($b) use ($tagValue) {
                        $b->where('name', $tagValue)
                            ->orWhere('slug', $tagValue);
                    });
            });
        }

        $news_articles = $query->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        $featuredArticles = Article::published()
            ->featured()
            ->latest('published_at')
            ->take(6)
            ->get();

        $tags = Tag::where('type', 'news')->get();

        return view('user-views.pages.news', compact('news_articles', 'tags', 'featuredArticles'));
    }

    public function tag_articles($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $news_articles = Article::published()
            ->whereHas('tags', function ($q) use ($slug) {
                $q->where('slug', $slug);
            })
            ->withCount('comments')
            ->latest('published_at')
            ->paginate(15);

        $featuredArticles = Article::published()
            ->featured()
            ->latest('published_at')
            ->take(6)
            ->get();

        $tags = Tag::where('is_popular', true)->get();

        return view('user-views.pages.news', compact('news_articles', 'tags', 'featuredArticles', 'tag'));
    }

    public function reviews(Request $request)
    {

        $tags = Tag::where('type', 'review')->get();

        $query = Review::published()->withCount('comments');

        // Search functionality
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('body', 'like', "%{$searchTerm}%")
                    ->orWhereHas('device', function ($dq) use ($searchTerm) {
                        $dq->where('name', 'like', "%{$searchTerm}%")
                            ->orWhereHas('brand', function ($bq) use ($searchTerm) {
                                $bq->where('name', 'like', "%{$searchTerm}%");
                            });
                    });
            });
        }

        // Tag filtering
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag)
                    ->orWhere('slug', $request->tag);
            });
        }

        $reviews_list = $query->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('user-views.pages.reviews', compact('tags', 'reviews_list'));
    }

    public function videos()
    {
        $videos = \App\Models\Video::where('is_published', true)
            ->orderByDesc('published_at')
            ->get();

        return view('user-views.pages.videos', compact('videos'));
    }

    public function featured(Request $request)
    {

        $tags = Tag::where('is_popular', true)->get();

        $query = Article::published()
            ->featured()
            ->withCount('comments');

        // Search functionality
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('body', 'like', "%{$searchTerm}%");
            });
        }

        // Tag filtering
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag)
                    ->orWhere('slug', $request->tag);
            });
        }

        $featured_articles = $query->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('user-views.pages.featured', compact('tags', 'featured_articles'));
    }

    public function deals(Request $request)
    {
        $query = \App\Models\Deal::where('is_active', true);

        if ($request->has('markets')) {
            $markets = $request->input('markets');
            if (is_array($markets) && count($markets) > 0) {
                // Determine if we are filtering by exact region strings
                $query->whereIn('region', $markets);
            }
        }

        $deals = $query->latest()->get();

        // Get distinct regions for the filter sidebar
        $availableMarkets = \App\Models\Deal::select('region')->distinct()->orderBy('region')->pluck('region');

        return view('user-views.pages.deals', compact('deals', 'availableMarkets'));
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

        return view('user-views.pages.contact');
    }
    public function tip_us()
    {

        return view('user-views.pages.tip-us');
    }

    public function handleContactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create([
            'type' => 'contact',
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }

    public function handleTipUsSubmit(Request $request)
    {
        $request->validate([
            'subject' => 'nullable|string|max:255',
            'share' => 'required|string',
            'captcha' => 'required|in:42',
        ], [
            'captcha.in' => 'The CAPTCHA answer is incorrect.'
        ]);

        ContactMessage::create([
            'type' => 'tip',
            'subject' => $request->subject,
            'message' => $request->share,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Thank you for your tip!');
    }

    public function phoneFinder()
    {
        $brands = Brand::orderBy('name')->get();
        return view('user-views.pages.phone-finder', compact('brands'));
    }

    public function phoneFinderResults(Request $request)
    {
        $query = Device::query()->where('is_published', true);

        // Filter by Brand
        if ($request->filled('brands')) {
            $brandIds = explode(',', $request->brands);
            $query->whereIn('brand_id', $brandIds);
        }

        // Filter by Device Type
        if ($request->filled('device_type')) {
            $type = $request->device_type;
            if ($type === 'phones') {
                $query->where('device_type_id', '!=', 4); // Assuming 4 is Tablet
            } elseif ($type === 'tablets') {
                $query->where('device_type_id', 4);
            }
        }

        // Filter by Year
        if ($request->filled('year_min')) {
            $query->whereYear('released_at', '>=', $request->year_min);
        }
        if ($request->filled('year_max')) {
            $query->whereYear('released_at', '<=', $request->year_max);
        }

        // Filter by RAM (using ram_short)
        if ($request->filled('ram_min') && is_numeric($request->ram_min) && $request->ram_min > 0) {
            $query->where('ram_short', 'REGEXP', '[0-9]+')
                ->whereRaw("CAST(ram_short AS UNSIGNED) >= ?", [$request->ram_min]);
        }

        // Filter by Storage (using storage_short)
        if ($request->filled('storage_min') && is_numeric($request->storage_min) && $request->storage_min > 0) {
            $query->where('storage_short', 'REGEXP', '[0-9]+')
                ->whereRaw("CAST(storage_short AS UNSIGNED) >= ?", [$request->storage_min]);
        }

        // Filter by Release Status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('release_status', $request->status);
        }

        // Filter by Price (if offers exist)
        if ($request->filled('price_min') || $request->filled('price_max')) {
            $query->whereHas('variants.offers', function ($q) use ($request) {
                if ($request->filled('price_min'))
                    $q->where('price', '>=', $request->price_min);
                if ($request->filled('price_max'))
                    $q->where('price', '<=', $request->price_max);
            });
        }

        // Filter by Display Size
        if ($request->filled('size_min')) {
            // If we had a display_size_inches column, we'd use it here.
            // For now, let's keep it safe.
        }

        // Ordering
        $orderField = $request->get('order', 'latest');
        if ($orderField == 'popular') {
            $query->leftJoin('device_stats', 'devices.id', '=', 'device_stats.device_id')
                ->orderByDesc('device_stats.daily_hits');
        } else {
            $query->latest('released_at')->latest('devices.id');
        }

        $devices = $query->select('devices.*')->paginate(24)->withQueryString();

        return view('user-views.pages.phone-finder-results-searched', compact('devices'));
    }

    public function review_detail($slug)
    {
        $review = Review::where('slug', $slug)
            ->with('device.brand') // Load device and brand relationships
            ->firstOrFail();

        $device = $review->device; // Get the device from the review

        $comments = $review->comments()
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->with([
                'user',
                'replies' => function ($query) {
                    $query->where('is_approved', true)->with('user');
                }
            ])
            ->latest()
            ->get();

        // Popular Articles (Top 5 by views)
        $popularArticles = Article::published()
            ->orderByDesc('views_count')
            ->take(5)
            ->get();
        $maxArticleViews = $popularArticles->max('views_count') ?: 1;

        // Popular Devices (Top 5 by daily_hits)
        $popularDevices = \App\Models\Device::select('devices.*')
            ->join('device_stats', 'devices.id', '=', 'device_stats.device_id')
            ->orderByDesc('device_stats.daily_hits')
            ->take(5)
            ->with('stats')
            ->get();

        $maxDeviceHits = $popularDevices->max(function ($device) {
            return $device->stats->daily_hits ?? 0;
        }) ?: 1;

        return view('user-views.pages.review-detail', compact(
            'review',
            'device',
            'comments',
            'popularArticles',
            'maxArticleViews',
            'popularDevices',
            'maxDeviceHits'
        ));
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

        $comments = $article->comments()
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->with([
                'user',
                'replies' => function ($query) {
                    $query->where('is_approved', true)->with('user');
                }
            ])
            ->latest()
            ->get();

        // Get related articles
        $relatedArticles = Article::published()
            ->where('type', $type)
            ->where('id', '!=', $article->id)
            ->withCount('comments')
            ->latest('published_at')
            ->take(3)
            ->get();

        // Get Recommended articles (featured)
        $recommendedArticles = Article::published()
            ->featured() // Use the featured scope
            ->with(['author'])
            ->withCount('comments')
            ->latest('published_at')
            ->take(6)
            ->get();

        // If no Recommended articles, get the latest published articles
        if ($recommendedArticles->isEmpty()) {
            $recommendedArticles = Article::published()
                ->with(['author'])
                ->withCount('comments')
                ->latest('published_at')
                ->take(6)
                ->get();
        }

        // Popular Articles (Top 5 by views)
        $popularArticles = Article::published()
            ->orderByDesc('views_count')
            ->take(5)
            ->get();
        $maxArticleViews = $popularArticles->max('views_count') ?: 1;

        // Popular Devices (Top 5 by daily_hits or total_hits, prioritizing trending)
        // Using a Join for performance to sort by stats
        $popularDevices = \App\Models\Device::select('devices.*')
            ->join('device_stats', 'devices.id', '=', 'device_stats.device_id')
            ->orderByDesc('device_stats.daily_hits') // Trending daily
            ->take(5)
            ->with('stats') // Eager load for access if needed
            ->get();

        // Calculate max hits for progress bar (using daily_hits as we sorted by it)
        $maxDeviceHits = $popularDevices->max(function ($device) {
            return $device->stats->daily_hits ?? 0;
        }) ?: 1;

        return view('user-views.pages.article-detail', compact(
            'article',
            'comments',
            'relatedArticles',
            'recommendedArticles',
            'popularArticles',
            'maxArticleViews',
            'popularDevices',
            'maxDeviceHits'
        ));
    }

    public function brands()
    {
        $brands = Brand::active()->withCount([
            'devices' => function ($query) {
                $query->where('is_published', true);
            }
        ])->orderBy('name')->get();
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


    public function brand_devices(Request $request, $slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        $query = $brand->devices()
            ->where('is_published', '1');

        // Search Filter
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        // Type Filter
        if ($request->filled('type')) {
            $query->where('device_type_id', $request->type);
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('release_status', $request->status);
        }

        // Year Filter
        if ($request->filled('year')) {
            $query->whereYear('released_at', $request->year);
        }

        $sort = $request->get('sort', 'popularity');
        if ($sort === 'popularity') {
            $query->orderBy('released_at', 'desc')->orderBy('id', 'desc');
        } else {
            $query->latest('created_at');
        }

        $devices = $query->paginate(11)->withQueryString();

        $deviceTypes = \App\Models\DeviceType::all();
        $years = \App\Models\Device::whereNotNull('released_at')
            ->selectRaw('YEAR(released_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

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

        return view('user-views.pages.brand-devices', compact('brand', 'devices', 'popularReviews', 'selectedDevices', 'deviceTypes', 'years'));
    }

    public function device_detail($slug)
    {
        $device = Device::where('slug', $slug)
            ->where('is_published', true)
            ->with(['brand', 'deviceType'])
            ->firstOrFail();

        // Load specifications
        $device->load(['specValues.field.category']);

        // Increment hit statistic
        $stats = $device->stats()->firstOrCreate(['device_id' => $device->id]);
        $stats->incrementHit();

        // Get opinions/comments
        $opinions = $device->comments()
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->with([
                'user',
                'replies' => function ($query) {
                    $query->where('is_approved', true)->with('user');
                }
            ])
            ->latest()
            ->get();

        // Recommended Articles - Latest articles related to this device or brand
        $recommendedArticles = Article::published()
            ->where(function ($query) use ($device) {
                $query->where('device_id', $device->id)
                    ->orWhere('brand_id', $device->brand_id);
            })
            ->latest('published_at')
            ->take(6)
            ->get();

        // If no device-specific articles, get general latest articles
        if ($recommendedArticles->isEmpty()) {
            $recommendedArticles = Article::published()
                ->latest('published_at')
                ->take(6)
                ->get();
        }

        // Latest Devices - Recent devices from the same brand
        $latestDevices = Device::where('brand_id', $device->brand_id)
            ->where('id', '!=', $device->id)
            ->where('is_published', true)
            ->latest('created_at')
            ->take(6)
            ->get();

        // In Store Devices - Devices from same brand with active offers
        $inStoreDevices = Device::where('brand_id', $device->brand_id)
            ->where('id', '!=', $device->id)
            ->where('is_published', true)
            ->whereHas('activeOffers')
            ->latest('created_at')
            ->take(6)
            ->get();

        return view('user-views.pages.device-detail', compact(
            'device',
            'opinions',
            'recommendedArticles',
            'latestDevices',
            'inStoreDevices'
        ));
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

        $commentsQuery = $device->comments()
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->with([
                'user',
                'replies' => function ($query) {
                    $query->where('is_approved', true)->with('user');
                }
            ]);

        if ($search = request('q')) {
            $commentsQuery->where('body', 'like', "%{$search}%");
        }

        $comments = $commentsQuery->latest()->paginate(10);

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

        $comments = $review->comments()
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->with([
                'user',
                'replies' => function ($query) {
                    $query->where('is_approved', true)->with('user');
                }
            ])
            ->latest()
            ->paginate(10);

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

        $comments = $article->comments()
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->with([
                'user',
                'replies' => function ($query) {
                    $query->where('is_approved', true)->with('user');
                }
            ])
            ->latest()
            ->paginate(10);


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


    public function device_comparison(Request $request, $slug = null, $id = null)
    {
        $device1 = null;
        $device2 = null;
        $device3 = null;

        // If called via /compare?devices=slug1,slug2,slug3
        if ($request->has('devices')) {
            $slugs = explode(',', $request->devices);
            $devices = Device::whereIn('slug', $slugs)
                ->where('is_published', true)
                ->with(['brand', 'specValues.field.category'])
                ->withCount('comments')
                ->take(3)
                ->get();

            $device1 = $devices->get(0);
            $device2 = $devices->get(1);
            $device3 = $devices->get(2);
        }
        // If called via /{slug}-compare-{id}
        elseif ($id) {
            $device1 = Device::where('id', $id)
                ->where('is_published', true)
                ->with(['brand', 'specValues.field.category'])
                ->withCount('comments')
                ->firstOrFail();

            if ($request->has('id2')) {
                $device2 = Device::where('id', $request->id2)
                    ->where('is_published', true)
                    ->with(['brand', 'specValues.field.category'])
                    ->withCount('comments')
                    ->first();
            }

            if ($request->has('id3')) {
                $device3 = Device::where('id', $request->id3)
                    ->where('is_published', true)
                    ->with(['brand', 'specValues.field.category'])
                    ->withCount('comments')
                    ->first();
            }
        }

        // Track popular comparisons
        if ($device1 && $device2) {
            \App\Models\ComparisonStat::incrementHit($device1->id, $device2->id);
        }
        if ($device1 && $device3) {
            \App\Models\ComparisonStat::incrementHit($device1->id, $device3->id);
        }
        if ($device2 && $device3) {
            \App\Models\ComparisonStat::incrementHit($device2->id, $device3->id);
        }

        return view('user-views.pages.device-comparison', compact('device1', 'device2', 'device3'));
    }

    public function search_devices(Request $request)
    {
        $query = trim($request->get('q'));
        if (!$query) {
            return response()->json([]);
        }

        $devices = Device::where('name', 'LIKE', "%{$query}%")
            ->where('is_published', true)
            ->limit(10)
            ->get(['id', 'name', 'slug', 'thumbnail_url']);

        return response()->json($devices);
    }

    public function liveSearch(Request $request)
    {
        $q = $request->get('q');

        // If query is empty, show recently viewed if IDs provided
        if (empty($q)) {
            $recentIds = $request->get('recent', []);
            if (!empty($recentIds)) {
                $recentDevices = Device::whereIn('id', $recentIds)
                    ->where('is_published', true)
                    ->get(['id', 'name', 'slug', 'thumbnail_url']);
                return response()->json(['recent' => $recentDevices]);
            }
            return response()->json([]);
        }

        $devices = Device::where('name', 'LIKE', "%{$q}%")
            ->where('is_published', true)
            ->limit(8)
            ->get(['id', 'name', 'slug', 'thumbnail_url']);

        $reviews = Review::where('title', 'LIKE', "%{$q}%")
            ->published()
            ->limit(8)
            ->get(['id', 'title', 'slug', 'cover_image_url']);

        $news = Article::where('title', 'LIKE', "%{$q}%")
            ->published()
            ->limit(8)
            ->get(['id', 'title', 'slug', 'thumbnail_url', 'type']);

        return response()->json([
            'devices' => $devices,
            'reviews' => $reviews,
            'news' => $news
        ]);
    }

    public function globalSearch(Request $request)
    {
        $q = $request->get('q');

        if (empty($q)) {
            return redirect()->route('home');
        }

        $devices = Device::where('name', 'LIKE', "%{$q}%")
            ->where('is_published', true)
            ->paginate(12, ['*'], 'devices_page')
            ->withQueryString();

        $news = Article::where('title', 'LIKE', "%{$q}%")
            ->published()
            ->paginate(12, ['*'], 'news_page')
            ->withQueryString();

        $reviews = Review::where('title', 'LIKE', "%{$q}%")
            ->published()
            ->paginate(12, ['*'], 'reviews_page')
            ->withQueryString();

        return view('user-views.pages.search-results', compact('q', 'devices', 'news', 'reviews'));
    }

    public function phone_finder_results()
    {
        $devices = Device::where('is_published', true)
            ->paginate(24);
        // ->withQueryString();
        return view('user-views.pages.phone-finder-results', compact('devices'));
    }
}