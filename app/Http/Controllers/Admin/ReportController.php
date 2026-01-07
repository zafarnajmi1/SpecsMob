<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Brand;
use App\Models\Comment;
use App\Models\Device;
use App\Models\DeviceStat;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // System wide summary report
        $summary = [
            'total_growth_labels' => [],
            'device_counts' => Device::selectRaw('count(*) as count, release_status')->groupBy('release_status')->get(),
            'user_stats' => [
                'total' => User::count(),
                'new_today' => User::whereDate('created_at', Carbon::today())->count(),
                'new_this_week' => User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            ],
            'content_stats' => [
                'news' => Article::where('type', 'news')->count(),
                'articles' => Article::where('type', 'article')->count(),
                'reviews' => Review::count(),
            ]
        ];

        // Distribution by Brand (Top 10)
        $brand_distribution = Brand::withCount('devices')->orderByDesc('devices_count')->take(10)->get();

        return view('admin-views.reports.index', compact('summary', 'brand_distribution'));
    }
}
