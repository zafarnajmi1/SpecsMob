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
                $title = trim($videoData['title'] ?? '');
                $youtubeRaw = trim($videoData['youtube_id'] ?? '');
                $description = $videoData['description'] ?? null;
                $isPublished = !empty($videoData['is_published']);
                $brandId = $videoData['brand_id'] ?? null;
                $deviceId = $videoData['device_id'] ?? null;
                $thumbnailFile = $videoData['thumbnail'] ?? null;

                if ($title === '' || $youtubeRaw === '')
                    continue;

                $youtubeId = $this->extractYoutubeId($youtubeRaw);
                $videoId = $videoData['id'] ?? null;

                if ($videoId) {
                    $video = Video::findOrFail($videoId);
                } else {
                    $video = new Video();
                    $video->slug = Str::slug($title) . '-' . Str::random(6);
                    $video->author_id = auth()->id();
                }

                $video->title = $title;
                $video->youtube_id = $youtubeId;
                $video->description = $description;
                $video->is_published = $isPublished;
                $video->published_at = $isPublished ? ($video->published_at ?: now()) : null;

                if ($thumbnailFile instanceof UploadedFile) {
                    $video->thumbnail_url = $thumbnailFile->store('videos/thumbnails', 'public');
                }

                $video->save();

                // Sync brand & device
                if ($brandId)
                    $video->brands()->sync([$brandId]);
                if ($deviceId)
                    $video->devices()->sync([$deviceId => ['order' => $index]]);

                // Video Items (for backward/future compatibility with the multi-form)
                VideoItem::updateOrCreate(
                    ['video_id' => $video->id, 'youtube_id' => $youtubeId],
                    [
                        'title' => $title,
                        'description' => $description,
                        'brand_id' => $brandId,
                        'device_id' => $deviceId,
                        'is_published' => $isPublished,
                        'order' => $index
                    ]
                );

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
            'videos' => 'required|array|min:1',
            'videos.*.title' => 'required|string|max:255',
            'videos.*.youtube_id' => 'required|string|max:255',
            'videos.*.description' => 'nullable|string',
            'videos.*.is_published' => 'nullable|boolean',
            'videos.*.brand_id' => 'nullable|exists:brands,id',
            'videos.*.device_id' => 'nullable|exists:devices,id',
            'videos.*.thumbnail' => 'nullable|image|max:4096',

            // SEO (top-level)
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
            'videos' => 'required|array|min:1',
            'videos.*.id' => 'nullable|exists:videos,id',   // important for update
            'videos.*.title' => 'required|string|max:255',
            'videos.*.youtube_id' => 'required|string|max:255',
            'videos.*.description' => 'nullable|string',
            'videos.*.is_published' => 'nullable|boolean',
            'videos.*.brand_id' => 'nullable|exists:brands,id',
            'videos.*.device_id' => 'nullable|exists:devices,id',
            'videos.*.thumbnail' => 'nullable|image|max:4096',
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
        $brands = Brand::orderBy('name')->get();
        $video->load(['brands', 'devices', 'videoItems', 'seo']);

        return view('admin-views.video.edit', compact('video', 'brands'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        try {
            DB::beginTransaction();

            // Delete thumbnail if exists
            if ($video->thumbnail_url && Storage::disk('public')->exists($video->thumbnail_url)) {
                Storage::disk('public')->delete($video->thumbnail_url);
            }

            // Delete video items
            $video->videoItems()->delete();

            // Detach relationships
            $video->brands()->detach();
            $video->devices()->detach();

            // Delete SEO meta
            if ($video->seo) {
                $video->seo->delete();
            }

            $video->delete();

            DB::commit();

            ToastMagic::success('Video deleted successfully');
            return redirect()->route('admin.videos.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            ToastMagic::error('Failed to delete video: ' . $e->getMessage());
            return back();
        }
    }
    protected function extractYoutubeId($url)
    {
        if (strlen($url) === 11)
            return $url;
        preg_match("/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^\"&?\/\s]{11})/", $url, $match);
        return $match[1] ?? $url;
    }
}
