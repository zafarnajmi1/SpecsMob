<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Brand;
use App\Models\Comment;
use App\Models\ComparisonStat;
use App\Models\Device;
use App\Models\DeviceOpinion;
use App\Models\DeviceStat;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function overview()
    {
        $stats = [
            'total_devices' => Device::count(),
            'total_brands' => Brand::count(),
            'total_articles' => Article::count(),
            'total_reviews' => Review::count(),
            'total_users' => User::count(),
            'total_comments' => Comment::count() + Article::sum('comments_count'),
            'total_hits' => DeviceStat::sum('total_hits'),
        ];

        // Recent Activity
        $recent_articles = Article::latest()->take(5)->get();
        $recent_opinions = DeviceOpinion::with(['user', 'device'])->latest()->take(5)->get();

        return view('admin-views.analytics.overview', compact('stats', 'recent_articles', 'recent_opinions'));
    }

    public function devices()
    {
        // Top 20 Devices by Daily Interest
        $top_daily = Device::with(['brand', 'stats'])
            ->join('device_stats', 'devices.id', '=', 'device_stats.device_id')
            ->orderByDesc('device_stats.daily_hits')
            ->take(20)
            ->get();

        // Top 20 Devices by All-time Hits
        $top_all_time = Device::with(['brand', 'stats'])
            ->join('device_stats', 'devices.id', '=', 'device_stats.device_id')
            ->orderByDesc('device_stats.total_hits')
            ->take(20)
            ->get();

        // Top 20 Devices by Fans
        $top_fans = Device::with(['brand', 'stats'])
            ->join('device_stats', 'devices.id', '=', 'device_stats.device_id')
            ->orderByDesc('device_stats.total_fans')
            ->take(20)
            ->get();

        // Popular Comparisons
        $popular_comparisons = ComparisonStat::with(['device1', 'device2'])
            ->orderByDesc('hits')
            ->take(15)
            ->get();

        return view('admin-views.analytics.devices', compact('top_daily', 'top_all_time', 'top_fans', 'popular_comparisons'));
    }

    public function articles()
    {
        // Most viewed articles
        $top_articles = Article::orderByDesc('views_count')->take(20)->get();

        // Most commented articles
        $discussed_articles = Article::orderByDesc('comments_count')->take(20)->get();

        // Most viewed reviews
        $top_reviews = Review::with('device')->orderByDesc('views_count')->take(20)->get();

        return view('admin-views.analytics.articles', compact('top_articles', 'discussed_articles', 'top_reviews'));
    }
}
