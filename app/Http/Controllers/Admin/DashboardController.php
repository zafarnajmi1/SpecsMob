<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Mobile;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // $data = [
        //     'totalUsers' => User::count(),
        //     'totalBrands' => Brand::count(),
        //     'totalMobiles' => Mobile::count(),
        //     'totalBlogs' => Blog::count(),
        // ];

        $data=[];
        
        // Admin sees all stats, manager sees limited stats
        if ($user->hasRole('admin')) {
            $data['recentUsers'] = User::latest()->take(5)->get();
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
