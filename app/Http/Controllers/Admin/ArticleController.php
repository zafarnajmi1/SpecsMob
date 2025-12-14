<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Brand;
use App\Models\Device;
use App\Models\Tag;
use DB;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Validator;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        // $query = Article::with(['author', 'brand', 'device', 'tags'])
        //     ->latest();

        // // Filter by type
        // if ($request->has('type') && in_array($request->type, ['news', 'article', 'featured'])) {
        //     $query->where('type', $request->type);
        // }

        // // Search
        // if ($request->has('search')) {
        //     $search = $request->search;
        //     $query->where(function($q) use ($search) {
        //         $q->where('title', 'like', "%{$search}%")
        //           ->orWhere('excerpt', 'like', "%{$search}%")
        //           ->orWhere('body', 'like', "%{$search}%");
        //     });
        // }

        // $articles = $query->paginate(20);
        $articles = Article::latest()->get();
        return view('admin-views.articles.index', compact('articles'));
    }

    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        $devices = Device::with('brand')->orderBy('name')->get();
        // $tags = Tag::orderBy('name')->get();
        // $devices = De;
        $tags = [];

        return view('admin-views.articles.create', compact('brands', 'devices', 'tags'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug',
            'body' => 'required|string',
            'type' => 'required|in:news,article,featured',
            'brand_id' => 'nullable|exists:brands,id',
            'device_id' => 'nullable|exists:devices,id',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'published_at' => 'nullable|date',
            'thumbnail_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $data = [];
            if ($request->hasFile('thumbnail_url')) {
                $path = $request->file('thumbnail_url')->store('articles/thumbnails', 'public');
                $data['thumbnail_url'] = $path;
            }

            // Set author
            $data['author_id'] = auth()->id();

            // Handle draft
            if ($request->has('draft')) {
                $data['is_published'] = false;
            }

            $data['title'] = $request->title;
            $data['slug'] = $request->slug;
            $data['body'] = $request->body;
            $data['type'] = $request->type;
            $data['brand_id'] = $request->brand_id;
            $data['device_id'] = $request->device_id;
            $data['is_published'] = $request->is_published;
            $data['is_featured'] = $request->is_featured;
            $data['published_at'] = $request->published_at;
            // Create article
            $article = Article::create($data);

            // Sync tags
            if ($request->has('tags')) {
                $article->tags()->sync($request->tags);
            }

            
            // SEO Meta (polymorphic)
            $article->saveSeo($request);
            DB::commit();
            
            ToastMagic::success('Article created successfully!');
            return redirect()->route('admin.articles.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            ToastMagic::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit(Article $article)
{
    $brands = Brand::orderBy('name')->get();

    // Only devices for the selected brand (if any)
    $devices = $article->brand_id
        ? Device::where('brand_id', $article->brand_id)->orderBy('name')->get()
        : collect();

    $tags = Tag::orderBy('name')->get();

    return view('admin-views.articles.edit', compact('article', 'brands', 'devices', 'tags'));
}


  public function update(Request $request, Article $article)
{
    $validator = Validator::make($request->all(), [
        'title'         => 'required|string|max:255',
        'slug'          => 'required|string|max:255|unique:articles,slug,' . $article->id,
        'body'          => 'required|string',
        'type'          => 'required|in:news,article,featured',
        'brand_id'      => 'nullable|exists:brands,id',
        'device_id'     => 'nullable|exists:devices,id',
        'is_published'  => 'boolean',
        'is_featured'   => 'boolean',
        'allow_comments'=> 'boolean',
        'published_at'  => 'nullable|date',
        'thumbnail_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'tags'          => 'nullable|array',
        'tags.*'        => 'exists:tags,id',
    ]);

    if ($validator->fails()) {
        ToastMagic::error($validator->errors()->first());
        return redirect()->back()->withInput();
    }

    DB::beginTransaction();

    try {
        $data = [];

        // Update thumbnail if new file uploaded
        if ($request->hasFile('thumbnail_url')) {
            // Delete old thumbnail if exists
            if ($article->thumbnail_url && Storage::disk('public')->exists($article->thumbnail_url)) {
                Storage::disk('public')->delete($article->thumbnail_url);
            }

            $path = $request->file('thumbnail_url')->store('articles/thumbnails', 'public');
            $data['thumbnail_url'] = $path;
        }

        // Handle draft
        if ($request->has('draft')) {
            $data['is_published'] = false;
        }

        // Fill updated fields
        $data['title']        = $request->title;
        $data['slug']         = $request->slug;
        $data['body']         = $request->body;
        $data['type']         = $request->type;
        $data['brand_id']     = $request->brand_id;
        $data['device_id']    = $request->device_id;
        $data['is_published']  = $request->is_published;
        $data['is_featured']  = $request->is_featured;
        $data['published_at'] = $request->published_at;
        $data['updated_by']   = auth()->id();  // optional: track who updated

        // Update Article
        $article->update($data);

        // Sync tags
        if ($request->has('tags')) {
            $article->tags()->sync($request->tags);
        } else {
            $article->tags()->sync([]); // remove all tags if empty
        }

        // SEO polymorphic update
        $article->saveSeo($request);

        DB::commit();

        ToastMagic::success('Article updated successfully!');
        return redirect()->route('admin.articles.index');

    } catch (\Throwable $e) {
        DB::rollBack();
        report($e);
        ToastMagic::error($e->getMessage());
        return redirect()->back()->withInput();
    }
}


    public function destroy(Article $article)
    {
        // Delete thumbnail if exists
        if ($article->thumbnail_url) {
            Storage::disk('public')->delete($article->thumbnail_url);
        }

        $article->delete();

        ToastMagic::success('Article deleted successfully!');
        return redirect()->route('admin.articles.index');
    }
}