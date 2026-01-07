<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Brand;
use App\Models\Device;
use App\Models\User;
use App\Models\Comment;
use App\Models\Review;
use App\Models\DeviceOpinion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Basic Stats
        $stats = [
            'totalUsers' => User::count(),
            'totalBrands' => Brand::count(),
            'totalDevices' => Device::count(),
            'totalArticles' => Article::count(),
            'totalComments' => Comment::count() + DeviceOpinion::count(),
            'totalReviews' => Review::count(),
        ];

        // Chart Data: User Registrations by Month (Profile Visits Proxy)
        $monthData = User::selectRaw('MONTH(created_at) as month, count(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->all();

        $visitsData = [];
        $hasVisitsData = false;
        for ($i = 1; $i <= 12; $i++) {
            $count = $monthData[$i] ?? 0;
            if ($count > 0)
                $hasVisitsData = true;
            $visitsData[] = $count;
        }

        // Dummy data if no real data
        // if (!$hasVisitsData) {
        //     $visitsData = [10, 25, 15, 40, 30, 55, 45, 70, 60, 85, 75, 100];
        // }

        // Visitors Profile by Country (Proxy)
        $countryDistribution = User::select('country', \DB::raw('count(*) as total'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // Dummy data for country distribution if none
        // if ($countryDistribution->isEmpty()) {
        //     $countryDistribution = collect([
        //         (object) ['country' => 'United States', 'total' => 450],
        //         (object) ['country' => 'Indonesia', 'total' => 320],
        //         (object) ['country' => 'United Kingdom', 'total' => 280],
        //         (object) ['country' => 'Germany', 'total' => 150],
        //         (object) ['country' => 'India', 'total' => 120],
        //     ]);
        // }

        // Latest Comments (Unified)
        $latestComments = Comment::with(['user', 'commentable'])
            ->latest()
            ->take(5)
            ->get();

        // Recent Users
        $recentUsers = User::latest()->take(3)->get();

        $data = [
            'stats' => $stats,
            'visitsData' => $visitsData,
            'countryDistribution' => $countryDistribution,
            'latestComments' => $latestComments,
            'recentUsers' => $recentUsers,
        ];

        // Admin sees all stats, manager sees limited stats
        if ($user->hasRole('admin')) {
            $data['systemStats'] = $this->getSystemStats();
        }

        return view('admin-views.dashboard', compact('data'));
    }

    private function getSystemStats()
    {
        // Admin-only statistics
        return [
            'storageUsage' => '...',
            'serverLoad' => '...',
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
