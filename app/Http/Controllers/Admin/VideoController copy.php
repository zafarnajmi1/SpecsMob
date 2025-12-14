<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Video;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use DB;
use Storage;
use Str;
use Validator;

class VideoController extends Controller
{
    /**
     * Shared logic for create + update
     */
    protected function saveVideo(Request $request, Video $video): Video
    {
        DB::beginTransaction();

        try {
            $video->brand_id = $request->brand_id;


            $videosInput = $request->input('videos', []);

            // On update: clear existing pivot links for this device
            // (we don't delete videos themselves, they are global content)
            DB::table('video_devices')->where('device_id', $device->id)->delete();

            foreach ($videosInput as $index => $videoData) {
                $title = trim($videoData['title'] ?? '');
                $youtubeRaw = trim($videoData['youtube_id'] ?? '');
                $description = $videoData['description'] ?? null;
                $isPublished = !empty($videoData['is_published']);

                // Skip if required fields are missing
                if ($title === '' || $youtubeRaw === '') {
                    continue;
                }

                // You can normalize youtube ID here if you want (extract v=, etc.)
                $youtubeId = $youtubeRaw;  // for now just store what user entered

                // Try to reuse existing video by youtube_id (so same YouTube link isn't duplicated)
                $video = Video::where('youtube_id', $youtubeId)->first();

                if (!$video) {
                    // Generate unique slug for this title
                    $baseSlug = Str::slug($title);
                    $slug = $baseSlug;
                    $counter = 1;

                    while (Video::where('slug', $slug)->exists()) {
                        $slug = $baseSlug . '-' . $counter++;
                    }

                    $video = new Video();
                    $video->title = $title;
                    $video->slug = $slug;
                    $video->youtube_id = $youtubeId;  // full URL or ID
                    $video->thumbnail_url = null;  // can be filled later by job/API
                    $video->description = $description;
                    $video->author_id = auth()->id();  // admin user
                    $video->is_published = $isPublished;
                    $video->published_at = $isPublished ? now() : null;
                    $video->views_count = 0;
                    $video->comments_count = 0;
                    $video->save();
                } else {
                    // Optional: keep metadata in sync when editing device
                    $video->title = $title;
                    $video->description = $description;
                    $video->is_published = $isPublished;
                    $video->published_at = $isPublished
                        ? ($video->published_at ?: now())
                        : null;
                    $video->save();
                }

                // Link video to this device (video_devices pivot)
                DB::table('video_devices')->insert([
                    'video_id' => $video->id,
                    'device_id' => $device->id,
                    'order' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Optionally link video to brand as well (video_brands pivot)
                DB::table('video_brands')->insertOrIgnore([
                    'video_id' => $video->id,
                    'brand_id' => $device->brand_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 7. SEO Meta (polymorphic)
            $video->saveSeo($request);

            DB::commit();

            return $video;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::latest()->get();
        return view('admin-views.videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::with('devices')->get();
        return view('admin-views.video.add', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
        // 1. Validate input
        $validator = Validator::make($request->all(), [
            'brand_id' => 'required|exists:brands,id',
            'device_id' => 'required|exists:devices,id',
            'videos' => 'required|array|min:1',
            'videos.*.title' => 'required|string|max:255',
            'videos.*.youtube_id' => 'required|string|max:255',
            'videos.*.description' => 'nullable|string',
            'videos.*.is_published' => 'sometimes|boolean',
            // SEO validation
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|string|max:255',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:255',
            'og_image' => 'nullable|string|max:255',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:255',
            'twitter_image' => 'nullable|string|max:255',
            'json_ld' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            ToastMagic::error($validator->errors()->first());
            return redirect()->back();
        }
        try {
            $video = $this->saveDevice($request, new Video());

            ToastMagic::success('Video created successfully');
            return redirect()->route('admin.videos.index');
        } catch (\Throwable $e) {
            report($e);
            ToastMagic::error($e->getMessage());
            return redirect()->back();
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
    public function edit(Video $video)
    {
        $brands = Brand::latest()->get();
        $devices = Video::latest()->get();
        $video->load([
            'videos',
            'seo'
        ]);

        return view('admin-views.videos.edit', compact(
            'device',
            'brands'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        // (optionally validate here – you can reuse same rules)

        try {
            $this->saveDevice($request, $video);

            ToastMagic::success('Video updated successfully');
            return redirect()->route('admin.videos.index');
        } catch (\Throwable $e) {
            report($e);
            ToastMagic::error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $device)
    {
        try {
            if ($device->thumbnail_url && Storage::disk('public')->exists($device->thumbnail_url)) {
                Storage::disk('public')->delete($device->thumbnail_url);
            }

            $device->delete();

            ToastMagic::success('Video deleted successfully');
            return back();
        } catch (\Throwable $e) {
            report($e);
            ToastMagic::error('Failed to delete device.');
            return back();
        }
    }
}
