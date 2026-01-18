<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Device;
use App\Models\DeviceImage;
use App\Models\DeviceImageGroup;
use App\Models\DeviceOffer;
use App\Models\DeviceOfferPriceHistory;
use App\Models\DeviceReview;
use App\Models\DeviceReviewSection;
use App\Models\DeviceSpecValue;
use App\Models\DeviceType;
use App\Models\DeviceVariant;
use App\Models\Review;
use App\Models\Seo;
use App\Models\SpecCategory;
use App\Models\SpecField;
use App\Models\Store;
use App\Models\Video;
use Carbon\Carbon;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Storage;
use Str;
use Validator;

class DeviceController extends Controller
{
    /**
     * Shared logic for create + update
     */
    protected function saveDevice(Request $request, Device $device): Device
    {
        DB::beginTransaction();

        try {
            /*
             * |--------------------------------------------------------------------------
             * | 1. Device general Info
             * |--------------------------------------------------------------------------
             */
            $device->brand_id = $request->brand_id;
            $device->device_type_id = $request->device_type_id;
            $device->name = $request->name;

            // keep slug on update if you want stable URLs
            $device->slug = $device->exists
                ? $device->slug
                : Str::slug($request->name);

            $device->description = $request->description;
            $device->announcement_date = $request->announcement_date;
            $device->release_status = $request->release_status;
            $device->released_at = $request->released_at;
            $device->os_short = $request->os_short;
            $device->chipset_short = $request->chipset_short;
            $device->storage_short = $request->storage_short;
            $device->ram_short = $request->ram_short;
            $device->main_camera_short = $request->main_camera_short;
            $device->battery_short = $request->battery_short;
            $device->color_short = $request->color_short;
            $device->weight_grams = $request->weight_grams;
            $device->dimensions = $request->dimensions;
            $device->is_published = $request->boolean('is_published');
            $device->allow_opinions = $request->boolean('allow_opinions');
            $device->allow_fans = $request->boolean('allow_fans');

            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('devices/thumbnails', 'public');
                $device->thumbnail_url = $path;
            }

            $device->save();

            /*
             * |--------------------------------------------------------------------------
             * | 2. Device Specifications (spec_categories, spec_fields, device_spec_values)
             * |--------------------------------------------------------------------------
             */

            // On update, clear existing spec values for this device
            if ($device->wasRecentlyCreated === false) {
                DeviceSpecValue::where('device_id', $device->id)->delete();
            }

            $specCategoriesInput = $request->input('spec_categories', []);

            foreach ($specCategoriesInput as $catIndex => $categoryData) {
                $categoryName = trim($categoryData['name'] ?? '');

                if ($categoryName === '') {
                    continue;
                }

                $baseSlug = Str::slug($categoryName);
                $slug = $baseSlug;

                $specCategory = SpecCategory::where('slug', $slug)->first();

                if (!$specCategory) {
                    $counter = 1;
                    while (SpecCategory::where('slug', $slug)->exists()) {
                        $slug = $baseSlug . '-' . $counter++;
                    }

                    $specCategory = SpecCategory::create([
                        'name' => $categoryName,
                        'slug' => $slug,
                        'order' => $catIndex,
                    ]);
                } else {
                    $specCategory->name = $categoryName;
                    $specCategory->order = $catIndex;
                    $specCategory->save();
                }

                $fieldsData = $categoryData['fields'] ?? [];

                foreach ($fieldsData as $fieldIndex => $fieldData) {
                    $label = trim($fieldData['label'] ?? '');
                    $type = $fieldData['type'] ?? 'string';
                    $valueRaw = $fieldData['value'] ?? null;
                    $isFilterable = !empty($fieldData['filterable']);

                    if ($label === '' && ($valueRaw === null || $valueRaw === '')) {
                        continue;
                    }

                    $specField = SpecField::where('spec_category_id', $specCategory->id)
                        ->where('label', $label)
                        ->first();

                    if (!$specField) {
                        $baseKey = Str::slug($specCategory->slug . ' ' . $label, '_');
                        $key = $baseKey;
                        $kCounter = 1;

                        while (SpecField::where('key', $key)->exists()) {
                            $key = $baseKey . '_' . $kCounter++;
                        }

                        $specField = SpecField::create([
                            'spec_category_id' => $specCategory->id,
                            'key' => $key,
                            'label' => $label,
                            'type' => $type,
                            'is_filterable' => $isFilterable,
                            'order' => $fieldIndex,
                        ]);
                    } else {
                        $specField->type = $type;
                        $specField->is_filterable = $isFilterable;
                        $specField->order = $fieldIndex;
                        $specField->save();
                    }

                    if ($valueRaw === null || $valueRaw === '') {
                        continue;
                    }

                    $valueString = null;
                    $valueNumber = null;
                    $valueJson = null;

                    switch ($type) {
                        case 'number':
                            if (is_numeric($valueRaw)) {
                                $valueNumber = (float) $valueRaw;
                            } else {
                                $valueString = (string) $valueRaw;
                            }
                            break;

                        case 'json':
                            $decoded = json_decode($valueRaw, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $valueJson = $decoded;
                            } else {
                                $parts = array_filter(array_map('trim', explode(',', $valueRaw)));
                                $valueJson = $parts;
                            }
                            break;

                        case 'boolean':
                            $normalized = strtolower(trim($valueRaw));
                            if (in_array($normalized, ['1', 'true', 'yes', 'y'], true)) {
                                $valueString = 'Yes';
                            } elseif (in_array($normalized, ['0', 'false', 'no', 'n'], true)) {
                                $valueString = 'No';
                            } else {
                                $valueString = (string) $valueRaw;
                            }
                            break;

                        case 'text':
                        case 'string':
                        default:
                            $valueString = (string) $valueRaw;
                            break;
                    }

                    DeviceSpecValue::updateOrCreate(
                        [
                            'device_id' => $device->id,
                            'spec_field_id' => $specField->id,
                        ],
                        [
                            'value_string' => $valueString,
                            'value_number' => $valueNumber,
                            'value_json' => $valueJson ? json_encode($valueJson) : null,
                            'unit' => null,
                        ]
                    );
                }
            }

            // 3. Device Variants + Offers + Price History

            /*
             * |--------------------------------------------------------------------------
             * |  Device Variants & Offers
             * |    (device_variants, device_offers, device_offer_price_histories)
             * |--------------------------------------------------------------------------
             * | Request example:
             * | "variants" => [
             * |   0 => [
             * |     "label" => "128GB 8GB RAM",
             * |     "ram_gb" => "8",
             * |     "storage_gb" => "128",
             * |     "model_code" => "SM-XXXX",
             * |     "is_active" => "1",
             * |     "offers" => [
             * |       0 => [
             * |         "store_id" => "5",
             * |         "country_id" => "3",
             * |         "currency_id" => "4",
             * |         "price" => "198",
             * |         "url" => "https://...",
             * |         "status" => "1",
             * |         "in_stock" => "1",
             * |         "is_featured" => "1"
             * |       ],
             * |       ...
             * |     ]
             * |   ],
             * |   1 => [...]
             * | ],
             * | "primary_variant" => "1"
             */
            // On update: remove all old variants (offers + price history cascade via FK)
            DeviceVariant::where('device_id', $device->id)->delete();

            $variantsInput = $request->input('variants', []);
            $primaryVariantIndex = $request->input('primary_variant');

            foreach ($variantsInput as $index => $variantData) {
                $label = trim($variantData['label'] ?? '');

                if ($label === '') {
                    continue;
                }

                $variant = new DeviceVariant();
                $variant->device_id = $device->id;
                $variant->label = $label;
                $variant->ram_gb = $variantData['ram_gb'] !== null && $variantData['ram_gb'] !== ''
                    ? (int) $variantData['ram_gb']
                    : null;
                $variant->storage_gb = $variantData['storage_gb'] !== null && $variantData['storage_gb'] !== ''
                    ? (int) $variantData['storage_gb']
                    : null;
                $variant->model_code = $variantData['model_code'] ?? null;

                $variant->is_primary = ((string) $primaryVariantIndex === (string) $index);
                $variant->status = !empty($variantData['is_active']);

                $variant->save();

                $offersData = $variantData['offers'] ?? [];

                foreach ($offersData as $offerData) {
                    $storeId = $offerData['store_id'] ?? null;
                    $countryId = $offerData['country_id'] ?? null;
                    $currencyId = $offerData['currency_id'] ?? null;
                    $price = $offerData['price'] ?? null;

                    if (!$storeId || !$countryId || !$currencyId || $price === null || $price === '') {
                        continue;
                    }

                    $offer = new DeviceOffer();
                    $offer->device_variant_id = $variant->id;
                    $offer->store_id = (int) $storeId;
                    $offer->country_id = (int) $countryId;
                    $offer->currency_id = (int) $currencyId;
                    $offer->url = $offerData['url'] ?? null;
                    $offer->price = (float) $price;
                    $offer->in_stock = !empty($offerData['in_stock']);
                    $offer->is_featured = !empty($offerData['is_featured']);
                    $offer->status = isset($offerData['status'])
                        ? (bool) $offerData['status']
                        : true;

                    $offer->save();

                    DeviceOfferPriceHistory::create([
                        'device_offer_id' => $offer->id,
                        'price' => $offer->price,
                        'recorded_at' => now(),
                    ]);
                }
            }

            /*
             * |--------------------------------------------------------------------------
             * | 4. Device Videos
             * |--------------------------------------------------------------------------
             * | Request structure:
             * | "videos" => [
             * |   0 => [
             * |     "title"       => "...",
             * |     "youtube_id"  => "https://youtu.be/...",
             * |     "description" => "...",
             * |     "is_published"=> "1" (optional)
             * |   ],
             * |   ...
             * | ]
             */

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

            /*
             * |--------------------------------------------------------------------------
             * | 5. Device Image Groups & Images
             * |--------------------------------------------------------------------------
             * | "image_groups" => [
             * |   0 => [
             * |     "title"      => "...",
             * |     "group_type" => "...",
             * |     "order"      => "10",
             * |     "images" => [
             * |       0 => [
             * |         "caption" => "...",
             * |         "order"   => "8",
             * |         "image"   => UploadedFile
             * |       ],
             * |       ...
             * |     ]
             * |   ],
             * |   ...
             * | ]
             */

            // On UPDATE: completely replace image groups for this device
            // (for store(), there are no old rows so this is harmless)
            // Instead of deleting everything:
            $existingGroups = $device->imageGroups()->with('images')->get()->keyBy('id');

            // Incoming group data
            $imageGroupsInput = $request->all()['image_groups'] ?? [];

            $newGroupIds = [];

            foreach ($imageGroupsInput as $groupIndex => $groupData) {

                $groupId = $groupData['id'] ?? null; // Hidden field for existing group id
                $title = trim($groupData['title'] ?? '');
                $groupType = trim($groupData['group_type'] ?? '');
                $groupOrder = $groupData['order'] ?? $groupIndex;

                // -----------------------------
                // A. UPDATE OR CREATE GROUP
                // -----------------------------
                if ($groupId && isset($existingGroups[$groupId])) {
                    // Update existing group
                    $group = $existingGroups[$groupId];
                    $group->title = $title;
                    $group->group_type = $groupType;
                    $group->order = $groupOrder;
                    $group->save();
                } else {
                    // Create new group
                    $group = new DeviceImageGroup();
                    $group->device_id = $device->id;
                    $group->title = $title;
                    $group->group_type = $groupType;
                    $group->order = $groupOrder;
                    $group->slug = Str::slug($title . '-' . uniqid());
                    $group->save();
                }

                $newGroupIds[] = $group->id;

                // -----------------------------
                // B. IMAGES
                // -----------------------------
                $incomingImages = $groupData['images'] ?? [];
                $existingImages = $group->images->keyBy('id');

                foreach ($incomingImages as $imgIndex => $imgData) {

                    $imageId = $imgData['id'] ?? null;

                    // IMAGE DELETE OPTION — implement if needed
                    if (!empty($imgData['remove']) && $imageId) {
                        $existingImages[$imageId]->delete();
                        continue;
                    }

                    // New file uploaded
                    if (isset($imgData['image']) && $imgData['image'] instanceof UploadedFile) {
                        $path = $imgData['image']->store("devices/{$device->id}/images", 'public');

                        // If updating existing image
                        if ($imageId && isset($existingImages[$imageId])) {
                            $image = $existingImages[$imageId];
                        } else {
                            $image = new DeviceImage();
                            $image->device_image_group_id = $group->id;
                        }

                        $image->image_url = $path;
                        $image->caption = $imgData['caption'] ?? null;
                        $image->order = $imgData['order'] ?? $imgIndex;
                        $image->save();

                    } else if ($imageId && isset($existingImages[$imageId])) {
                        // No new file, update metadata only
                        $image = $existingImages[$imageId];
                        $image->caption = $imgData['caption'] ?? null;
                        $image->order = $imgData['order'] ?? $imgIndex;
                        $image->save();
                    }

                }
            }

            // -----------------------------
// C. DELETE GROUPS NOT IN FORM
// -----------------------------
            $device->imageGroups()
                ->whereNotIn('id', $newGroupIds)
                ->delete();


            /*
             * |--------------------------------------------------------------------------
             * | 6. Device Review Sections (device_review_sections)
             * |--------------------------------------------------------------------------
             * | "review" => [
             * |     "title" => "...",
             * |     "slug"  => "...",
             * |     "body"  => "<p>...</p>",
             * |     "published_at" => "2004-08-10T07:39"
             * | ]
             */
            $reviewData = $request->input('review', []);

            $hasReviewContent =
                !empty(trim($reviewData['title'] ?? '')) ||
                !empty(trim($reviewData['slug'] ?? '')) ||
                !empty(trim($reviewData['body'] ?? '')) ||
                $request->hasFile('review.cover_image');

            $existingReview = Review::where('device_id', $device->id)->first();

            if (!$hasReviewContent) {
                if ($existingReview) {
                    $existingReview->delete();
                }
            } else {
                $review = $existingReview ?: new Review();
                $review->device_id = $device->id;
                $review->brand_id = $device->brand_id;

                $reviewTitle = trim($reviewData['title'] ?? '');

                if ($reviewTitle === '') {
                    $reviewTitle = $device->name . ' review';
                }

                $review->title = $reviewTitle;

                $baseSlug = Str::slug($reviewData['slug'] ?? $reviewTitle);
                $slug = $baseSlug;
                $counter = 2;

                while (Review::where('slug', $slug)->where('id', '!=', $review->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }

                $review->slug = $slug;

                $review->body = $reviewData['body'] ?? '';

                $isPublished = !empty($reviewData['is_published']);
                $publishedAtRaw = $reviewData['published_at'] ?? null;

                $review->is_published = $isPublished;

                if ($publishedAtRaw) {
                    $review->published_at = Carbon::parse($publishedAtRaw);
                } elseif ($isPublished) {
                    $review->published_at = now();
                } else {
                    $review->published_at = null;
                }

                if ($request->hasFile('review.cover_image')) {
                    $coverPath = $request
                        ->file('review.cover_image')
                        ->store('reviews', 'public');
                    $review->cover_image_url = $coverPath;
                }

                $review->save();
            }

            // 7. SEO Meta (polymorphic)
            $device->saveSeo($request);

            DB::commit();

            return $device;
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
        $devices = Device::latest()->get();
        return view('admin-views.devices.index', compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::latest()->get();
        $deviceTypes = DeviceType::latest()->get();
        $stores = Store::all();
        $countries = Country::all();
        $currencies = Currency::all();
        return view('admin-views.devices.add', compact('brands', 'deviceTypes', 'stores', 'countries', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate input
        $validator = Validator::make($request->all(), [
            'brand_id' => 'required|exists:brands,id',
            'device_type_id' => 'required|exists:device_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'announcement_date' => 'nullable|date',
            'released_at' => 'nullable|date',
            'weight_grams' => 'nullable|numeric',
            'dimensions' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            // Device variants + offer price
            'variants' => 'sometimes|nullable|array',
            'variants.*.label' => 'sometimes|required_with:variants|string|max:255',
            'variants.*.ram_gb' => 'nullable|integer|min:0',
            'variants.*.storage_gb' => 'nullable|integer|min:0',
            'variants.*.model_code' => 'nullable|string|max:100',
            'variants.*.is_active' => 'nullable|boolean',
            'variants.*.offers' => 'nullable|array',
            'variants.*.offers.*.store_id' => 'sometimes|required_with:variants|exists:stores,id',
            'variants.*.offers.*.country_id' => 'sometimes|required_with:variants|exists:countries,id',
            'variants.*.offers.*.currency_id' => 'sometimes|required_with:variants|exists:currencies,id',
            'variants.*.offers.*.price' => 'sometimes|required_with:variants|numeric|min:0',
            'variants.*.offers.*.url' => 'nullable|url',
            'variants.*.offers.*.status' => 'nullable|boolean',
            'variants.*.offers.*.in_stock' => 'nullable|boolean',
            'variants.*.offers.*.is_featured' => 'nullable|boolean',
            'primary_variant' => 'sometimes|nullable|integer',
            // Device Images
            'image_groups' => 'nullable|array',
            'image_groups.*.title' => 'required|string|max:255',
            'image_groups.*.group_type' => 'required|string|max:100',
            'image_groups.*.order' => 'nullable|integer|min:0',
            'image_groups.*.images' => 'nullable|array',
            'image_groups.*.images.*.caption' => 'nullable|string|max:500',
            'image_groups.*.images.*.order' => 'nullable|integer|min:0',
            'image_groups.*.images.*.image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Review (optional) - keep soft
            'review.title' => 'nullable|string|max:255',
            'review.slug' => 'nullable|string|max:255',
            'review.summary' => 'nullable|string|max:255',
            'review.published_at' => 'nullable|date',
            'review.cover_image' => 'nullable|image|max:4096',
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
            $device = $this->saveDevice($request, new Device());

            ToastMagic::success('Device created successfully');
            return redirect()->route('admin.devices.index');
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
        $device = Device::findOrFail($id);
        $brands = Brand::latest()->get();
        $deviceTypes = DeviceType::latest()->get();
        $stores = Store::all();
        $countries = Country::all();
        $currencies = Currency::all();

        $device->load([
            'specValues' => function ($query) {
                $query->whereHas('field');  // This will now work correctly
            },
            'specValues.field.category',
            'allVariants.offers',
            'imageGroups.images',
            'videos',
            'seo'
        ]);

        return view('admin-views.devices.edit', compact(
            'device',
            'brands',
            'deviceTypes',
            'stores',
            'countries',
            'currencies'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'brand_id' => 'required|exists:brands,id',
            'device_type_id' => 'required|exists:device_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'announcement_date' => 'nullable|date',
            'released_at' => 'nullable|date',
            'weight_grams' => 'nullable|numeric',
            'dimensions' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            // Device variants + offer price
            'variants' => 'sometimes|nullable|array',
            'variants.*.label' => 'sometimes|required_with:variants|string|max:255',
            'variants.*.ram_gb' => 'nullable|integer|min:0',
            'variants.*.storage_gb' => 'nullable|integer|min:0',
            'variants.*.model_code' => 'nullable|string|max:100',
            'variants.*.is_active' => 'nullable|boolean',
            'variants.*.offers' => 'nullable|array',
            'variants.*.offers.*.store_id' => 'sometimes|required_with:variants|exists:stores,id',
            'variants.*.offers.*.country_id' => 'sometimes|required_with:variants|exists:countries,id',
            'variants.*.offers.*.currency_id' => 'sometimes|required_with:variants|exists:currencies,id',
            'variants.*.offers.*.price' => 'sometimes|required_with:variants|numeric|min:0',
            'variants.*.offers.*.url' => 'nullable|url',
            'variants.*.offers.*.status' => 'nullable|boolean',
            'variants.*.offers.*.in_stock' => 'nullable|boolean',
            'variants.*.offers.*.is_featured' => 'nullable|boolean',
            'primary_variant' => 'sometimes|nullable|integer',
            // Device Images
            'image_groups' => 'nullable|array',
            'image_groups.*.title' => 'required|string|max:255',
            'image_groups.*.group_type' => 'required|string|max:100',
            'image_groups.*.order' => 'nullable|integer|min:0',
            'image_groups.*.images' => 'nullable|array',
            'image_groups.*.images.*.caption' => 'nullable|string|max:500',
            'image_groups.*.images.*.order' => 'nullable|integer|min:0',
            // Review (optional) - keep soft
            'review.title' => 'nullable|string|max:255',
            'review.slug' => 'nullable|string|max:255',
            'review.summary' => 'nullable|string|max:255',
            'review.published_at' => 'nullable|date',
            'review.cover_image' => 'nullable|image|max:4096',
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
            return redirect()->back()->withInput();
        }

        try {
            $this->saveDevice($request, $device);

            ToastMagic::success('Device updated successfully');
            return redirect()->route('admin.devices.index');
        } catch (\Throwable $e) {
            report($e);
            ToastMagic::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        try {
            if ($device->thumbnail_url && Storage::disk('public')->exists($device->thumbnail_url)) {
                Storage::disk('public')->delete($device->thumbnail_url);
            }

            $device->delete();

            ToastMagic::success('Device deleted successfully');
            return back();
        } catch (\Throwable $e) {
            report($e);
            ToastMagic::error('Failed to delete device.');
            return back();
        }
    }
}
