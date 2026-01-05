<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Device;
use DB;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Storage;
use Validator;

class ReviewController extends Controller
{
    protected function saveReview(Request $request, Review $review): Review
    {
        DB::beginTransaction();

        try {
            $reviewData = $request->input('review', []);

            if (empty($reviewData)) {
                throw new \RuntimeException('No review data submitted.');
            }

            // ----- Brand & Device -----
            $review->brand_id = $reviewData['brand_id'] ?? null;
            $review->device_id = $reviewData['device_id'] ?? null;

            // ----- Title -----
            $reviewTitle = trim($reviewData['title'] ?? '');

            if ($reviewTitle === '') {
                $reviewTitle = 'Untitled review';
            }

            $review->title = $reviewTitle;

            // ----- Slug (unique) -----
            $baseSlug = Str::slug($reviewData['slug'] ?? $review->title);
            $slug = $baseSlug;
            $counter = 2;

            $slugQuery = Review::where('slug', $slug);
            if ($review->exists) {
                $slugQuery->where('id', '!=', $review->id);
            }

            while ($slugQuery->exists()) {
                $slug = $baseSlug . '-' . $counter++;
                $slugQuery = Review::where('slug', $slug);
                if ($review->exists) {
                    $slugQuery->where('id', '!=', $review->id);
                }
            }

            $review->slug = $slug;

            // ----- Body/content -----
            $review->body = $reviewData['body'] ?? '';

            // ----- Author / meta -----
            if (!$review->exists) {
                $review->author_id = auth()->id();
                $review->views_count = 0;
                $review->comments_count = 0;
            }

            // ----- Publish flags -----
            $isPublished = !empty($reviewData['is_published']);
            $publishedAtRaw = $reviewData['published_at'] ?? null;

            $review->is_published = $isPublished;

            if ($publishedAtRaw) {
                $review->published_at = Carbon::parse($publishedAtRaw);
            } elseif ($isPublished && !$review->published_at) {
                $review->published_at = now();
            } elseif (!$isPublished) {
                $review->published_at = null;
            }

            // ----- Cover image upload -----
            if ($request->hasFile('review.cover_image')) {
                $coverPath = $request
                    ->file('review.cover_image')
                    ->store('reviews', 'public');

                $review->cover_image_url = $coverPath;
            }

            // Save review
            $review->save();

            // 7. SEO Meta (polymorphic)
            $review->saveSeo($request);

            DB::commit();

            return $review;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function index()
    {
        $reviews = Review::with('author')->latest('created_at')->get();
        return view('admin-views.reviews.index', compact('reviews'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::latest()->get();
        $devices = Device::latest()->get();
        return view('admin-views.reviews.add', compact('brands', 'devices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate input
        $validator = Validator::make($request->all(), [
            'review.brand_id' => ['required', 'exists:brands,id'],
            'review.device_id' => ['required', 'exists:devices,id'],
            'review.title' => ['required', 'string', 'max:255'],
            'review.slug' => ['nullable', 'string', 'max:255'],
            'review.body' => ['required', 'string'],
            'review.published_at' => ['nullable', 'date'],
            'review.cover_image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $review = $this->saveReview($request, new Review());

            ToastMagic::success('Review created successfully');
            return redirect()->route('admin.reviews.index');
        } catch (\Throwable $e) {
            report($e);
            ToastMagic::error($e->getMessage());
            return redirect()->back()->withInput();
        }
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
    public function edit($id)
    {
        $review = Review::findOrFail($id);
        $brands = Brand::latest()->get();
        $devices = Device::latest()->get();

        return view('admin-views.reviews.edit', compact(
            'review',
            'brands',
            'devices'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        // (optionally validate here – you can reuse same rules)

        try {
            $this->saveReview($request, $review);

            ToastMagic::success('Review updated successfully');
            return redirect()->route('admin.reviews.index');
        } catch (\Throwable $e) {
            report($e);
            ToastMagic::error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        try {
            if ($review->thumbnail_url && Storage::disk('public')->exists($review->thumbnail_url)) {
                Storage::disk('public')->delete($review->thumbnail_url);
            }

            $review->delete();

            ToastMagic::success('Review deleted successfully');
            return back();
        } catch (\Throwable $e) {
            report($e);
            ToastMagic::error('Failed to delete review.');
            return back();
        }
    }
}
