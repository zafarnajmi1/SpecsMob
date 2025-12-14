<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Video;
use App\Models\VideoItem;
use Carbon\Carbon;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use DB;
use Illuminate\Http\UploadedFile;
use Storage;
use Str;
use Validator;

class VideoController extends Controller
{
    /**
     * Shared logic for create + update
     */
       protected function saveVideos(Request $request): array
{
    DB::beginTransaction();

    try {
        $videosInput = $request->input('videos', []);
        $savedVideos = [];

        foreach ($videosInput as $index => $videoData) {
            $title      = trim($videoData['title'] ?? '');
            $youtubeRaw = trim($videoData['youtube_id'] ?? '');
            $description = $videoData['description'] ?? null;
            $isPublished = !empty($videoData['is_published']);
            $brandId    = $videoData['brand_id']  ?? null;
            $deviceId   = $videoData['device_id'] ?? null;

            /** @var UploadedFile|null $thumbnailFile */
            $thumbnailFile = $videoData['thumbnail'] ?? null;

            // Skip incomplete rows
            if ($title === '' || $youtubeRaw === '') {
                continue;
            }

            // normalize YT id/url – for now just store raw
            $youtubeId = $youtubeRaw;

            // If you want update support, expect optional video id in form:
            // videos[0][id]
            $videoId = $videoData['id'] ?? null;

            if ($videoId) {
                // update existing
                $video = Video::findOrFail($videoId);
            } else {
                // create new
                $video = new Video();

                // Generate unique slug
                $baseSlug = Str::slug($title);
                $slug     = $baseSlug ?: Str::slug($title . '-' . Str::random(6));
                $counter  = 1;

                while (Video::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }

                $video->slug          = $slug;
                $video->author_id     = auth()->id();
                $video->views_count   = $video->views_count   ?? 0;
                $video->comments_count= $video->comments_count?? 0;
            }

            // Fill main fields
            $video->title        = $title;
            $video->youtube_id   = $youtubeId;
            $video->description  = $description;
            $video->is_published = $isPublished;
            $video->published_at = $isPublished
                ? ($video->published_at ?: now())
                : null;

            // Thumbnail upload (per-row)
            if ($thumbnailFile instanceof UploadedFile) {
                $thumbPath = $thumbnailFile->store('videos/thumbnails', 'public');
                $video->thumbnail_url = $thumbPath;
            }

            $video->save();

            // Brand pivot: assume one brand per video
            if ($brandId) {
                $video->brands()->sync([$brandId]);
            } else {
                $video->brands()->detach();
            }

            // Device pivot: assume one device per video with order = index
            if ($deviceId) {
                $video->devices()->sync([
                    $deviceId => ['order' => $index],
                ]);
            } else {
                $video->devices()->detach();
            }

            // SEO (same global SEO payload applied to each created/updated video)
            $video->saveSeo($request);

            $savedVideos[] = $video;
        }

        DB::commit();

        return $savedVideos;
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
        return view('admin-views.video.index', compact('videos'));
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
    $validator = Validator::make($request->all(), [
        'videos'                     => 'required|array|min:1',
        'videos.*.title'             => 'required|string|max:255',
        'videos.*.youtube_id'        => 'required|string|max:255',
        'videos.*.description'       => 'nullable|string',
        'videos.*.is_published'      => 'nullable|boolean',
        'videos.*.brand_id'          => 'nullable|exists:brands,id',
        'videos.*.device_id'         => 'nullable|exists:devices,id',
        'videos.*.thumbnail'         => 'nullable|image|max:4096',

        // SEO (top-level)
        'meta_title'                 => 'nullable|string|max:255',
        'meta_description'           => 'nullable|string|max:255',
        'meta_keywords'              => 'nullable|string|max:255',
        'canonical_url'              => 'nullable|string|max:255',
        'og_title'                   => 'nullable|string|max:255',
        'og_description'             => 'nullable|string|max:255',
        'og_image'                   => 'nullable|string|max:255',
        'twitter_title'              => 'nullable|string|max:255',
        'twitter_description'        => 'nullable|string|max:255',
        'twitter_image'              => 'nullable|string|max:255',
        'json_ld'                    => 'nullable|string',
    ]);

    if ($validator->fails()) {
        ToastMagic::error($validator->errors()->first());
        return redirect()->back()->withInput();
    }

    try {
        $this->saveVideos($request);

        ToastMagic::success('Videos created successfully');
        return redirect()->route('admin.videos.index');
    } catch (\Throwable $e) {
        report($e);
        ToastMagic::error($e->getMessage());
        return redirect()->back()->withInput();
    }
}


    public function update(Request $request, Video $video)
{
    // you can apply same validation rules as store()
    $validator = Validator::make($request->all(), [
        'videos'                     => 'required|array|min:1',
        'videos.*.id'                => 'nullable|exists:videos,id',   // important for update
        'videos.*.title'             => 'required|string|max:255',
        'videos.*.youtube_id'        => 'required|string|max:255',
        'videos.*.description'       => 'nullable|string',
        'videos.*.is_published'      => 'nullable|boolean',
        'videos.*.brand_id'          => 'nullable|exists:brands,id',
        'videos.*.device_id'         => 'nullable|exists:devices,id',
        'videos.*.thumbnail'         => 'nullable|image|max:4096',
        // same SEO rules...
    ]);

    if ($validator->fails()) {
        ToastMagic::error($validator->errors()->first());
        return redirect()->back()->withInput();
    }

    try {
        // We ignore $video here and let saveVideos handle create/update per-row via videos[*][id]
        $this->saveVideos($request);

        ToastMagic::success('Videos updated successfully');
        return redirect()->route('admin.videos.index');
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
