<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSchemaSetting;
use App\Models\SeoSetting;
use App\Models\SeoSitemapSetting;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function global()
    {
        // $settings = SeoSetting::first();
        $settings = [];
        return view('admin-views.seo.global', compact('settings'));
    }

    public function saveGlobal(Request $request)
    {
        $data = $request->validate([
            'site_name'                => 'nullable|string|max:255',
            'default_meta_title'       => 'nullable|string|max:255',
            'default_meta_description' => 'nullable|string|max:255',
            'default_meta_keywords'    => 'nullable|string|max:255',
            'robots_default'           => 'required|string|max:50',
            'canonical_base_url'       => 'nullable|string|max:255',
            'og_site_name'             => 'nullable|string|max:255',
            'og_default_title'         => 'nullable|string|max:255',
            'og_default_description'   => 'nullable|string|max:255',
            'og_default_image'         => 'nullable|string|max:255',
            'twitter_default_title'    => 'nullable|string|max:255',
            'twitter_default_description' => 'nullable|string|max:255',
            'twitter_default_image'    => 'nullable|string|max:255',
        ]);

        SeoSetting::updateOrCreate(['id' => 1], $data);

        return back()->with('success', 'Global SEO settings updated.');
    }

    public function schema()
    {
        // $schemaSettings = SeoSchemaSetting::first();
        $schemaSettings = [];
        return view('admin-views.seo.schema', compact('schemaSettings'));
    }

    public function saveSchema(Request $request)
    {
        $data = $request->validate([
            'organization_schema'      => 'nullable|string',
            'article_schema_template'  => 'nullable|string',
            'product_schema_template'  => 'nullable|string',
        ]);

        SeoSchemaSetting::updateOrCreate(['id' => 1], $data);

        return back()->with('success', 'Schema settings updated.');
    }

    public function sitemap()
    {
        // $sitemapSettings = SeoSitemapSetting::first();
        $sitemapSettings = [];
        return view('admin-views.seo.sitemap', compact('sitemapSettings'));
    }

    public function saveSitemap(Request $request)
    {
        $data = $request->validate([
            'sitemap_url'      => 'nullable|string|max:255',
            'robots_content'   => 'nullable|string',
            'hreflang_en'      => 'nullable|string|max:255',
            'hreflang_en_pk'   => 'nullable|string|max:255',
            'hreflang_en_in'   => 'nullable|string|max:255',
        ]);

        SeoSitemapSetting::updateOrCreate(['id' => 1], $data);

        return back()->with('success', 'Sitemap & robots settings updated.');
    }
}
