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
        $query = Article::with(['author', 'brand', 'device', 'tags'])
            ->latest();

        // Filter by type
        if ($request->has('type') && in_array($request->type, ['news', 'article', 'featured'])) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $articles = $query->paginate(20)->withQueryString();
        return view('admin-views.articles.index', compact('articles'));
    }

    /**
     * Redirect type-specific index to main index with type parameter
     */
    public function indexByType($type)
    {
        request()->merge(['type' => $type]);
        return $this->index(request());
    }

    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        $devices = collect(); // Devices will be loaded via AJAX
        $tags = Tag::orderBy('name')->get();

        return view('admin-views.articles.create', compact('brands', 'devices', 'tags'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:news,article,featured',
            'brand_id' => 'nullable|exists:brands,id',
            'device_id' => 'nullable|exists:devices,id',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'allow_comments' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'thumbnail_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back()->withInput();
        }

        DB::beginTransaction();
        try {
            $data = $request->only([
                'title',
                'body',
                'type',
                'brand_id',
                'device_id',
                'published_at'
            ]);

            $data['slug'] = Str::slug($request->title);
            $data['author_id'] = auth()->id();
            $data['is_published'] = $request->boolean('is_published');
            $data['is_featured'] = $request->boolean('is_featured');
            $data['allow_comments'] = $request->boolean('allow_comments', true);

            // Set published_at if not provided but marked as published
            if ($data['is_published'] && empty($data['published_at'])) {
                $data['published_at'] = now();
            }

            if ($request->hasFile('thumbnail_url')) {
                $path = $request->file('thumbnail_url')->store('articles/thumbnails', 'public');
                $data['thumbnail_url'] = $path;
            }

            // Create article
            $article = Article::create($data);

            // Sync tags
            $article->tags()->sync($this->processTags($request->tags));

            // SEO Meta (polymorphic)
            $article->saveSeo($request);

            DB::commit();

            ToastMagic::success('Article created successfully!');
            return redirect()->route('admin.articles.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            ToastMagic::error('Error creating article: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $article = Article::with('tags')->findOrFail($id);
        $brands = Brand::orderBy('name')->get();

        // Only devices for the selected brand (if any)
        $devices = $article->brand_id
            ? Device::where('brand_id', $article->brand_id)->orderBy('name')->get()
            : collect();

        $tags = Tag::orderBy('name')->get();

        return view('admin-views.articles.edit', compact('article', 'brands', 'devices', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:news,article,featured',
            'brand_id' => 'nullable|exists:brands,id',
            'device_id' => 'nullable|exists:devices,id',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'allow_comments' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'remove_thumbnail' => 'nullable|boolean',
            'thumbnail_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back()->withInput();
        }

        DB::beginTransaction();

        try {
            $data = $request->only([
                'title',
                'body',
                'type',
                'brand_id',
                'device_id',
                'published_at'
            ]);

            $data['slug'] = Str::slug($request->title);
            $data['is_published'] = $request->boolean('is_published');
            $data['is_featured'] = $request->boolean('is_featured');
            $data['allow_comments'] = $request->boolean('allow_comments');

            // Set published_at if marking as published for first time
            if ($data['is_published'] && empty($article->published_at) && empty($data['published_at'])) {
                $data['published_at'] = now();
            }

            // Handle thumbnail removal
            if ($request->boolean('remove_thumbnail')) {
                if ($article->thumbnail_url && Storage::disk('public')->exists($article->thumbnail_url)) {
                    Storage::disk('public')->delete($article->thumbnail_url);
                }
                $data['thumbnail_url'] = null;
            }

            // Update thumbnail if new file uploaded
            if ($request->hasFile('thumbnail_url')) {
                // Delete old thumbnail if exists
                if ($article->thumbnail_url && Storage::disk('public')->exists($article->thumbnail_url)) {
                    Storage::disk('public')->delete($article->thumbnail_url);
                }

                $path = $request->file('thumbnail_url')->store('articles/thumbnails', 'public');
                $data['thumbnail_url'] = $path;
            }

            // Update Article
            $article->update($data);

            // Sync tags
            $article->tags()->sync($this->processTags($request->tags));

            // SEO polymorphic update
            $article->saveSeo($request);

            DB::commit();

            ToastMagic::success('Article updated successfully!');
            return redirect()->route('admin.articles.index');

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            ToastMagic::error('Error updating article: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        try {
            // Delete thumbnail if exists
            if ($article->thumbnail_url && Storage::disk('public')->exists($article->thumbnail_url)) {
                Storage::disk('public')->delete($article->thumbnail_url);
            }

            // Remove tags
            $article->tags()->detach();

            $article->delete();

            ToastMagic::success('Article deleted successfully!');
        } catch (\Exception $e) {
            ToastMagic::error('Error deleting article: ' . $e->getMessage());
        }

        return redirect()->route('admin.articles.index');
    }

    /**
     * Helper to process tags (handle both IDs and new tag names)
     */
    protected function processTags($tagInputs)
    {
        if (empty($tagInputs))
            return [];

        $tagIds = [];
        foreach ($tagInputs as $tagInput) {
            // If it's numeric, assume it's an existing ID
            if (is_numeric($tagInput)) {
                $tagIds[] = $tagInput;
            } else {
                // Otherwise, it's a new tag name
                $tag = Tag::firstOrCreate(
                    ['name' => $tagInput],
                    ['slug' => Str::slug($tagInput)]
                );
                $tagIds[] = $tag->id;
            }
        }
        return $tagIds;
    }
}