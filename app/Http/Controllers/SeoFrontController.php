<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Device;
use App\Models\Article;
use App\Models\SeoSitemapSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SeoFrontController extends Controller
{
    public function robots()
    {
        $setting = SeoSitemapSetting::first();
        $content = $setting->robots_content ?? "User-agent: *\nAllow: /";

        return Response::make($content, 200, [
            'Content-Type' => 'text/plain'
        ]);
    }

    public function sitemap()
    {
        // Fetch active brands
        $brands = Brand::active()->get();

        // Fetch published devices (limit to reasonable number for basic sitemap or all if performance allows)
        // Given constraint "do not change architecture unless required", simple fetching is safer than implementing complex chunking now.
        $devices = Device::where('is_published', true)->orderBy('updated_at', 'desc')->get();

        // Fetch published articles
        $articles = Article::published()->orderBy('updated_at', 'desc')->get();

        return response()->view('seo.sitemap', [
            'brands' => $brands,
            'devices' => $devices,
            'articles' => $articles,
        ])->header('Content-Type', 'text/xml');
    }
}
