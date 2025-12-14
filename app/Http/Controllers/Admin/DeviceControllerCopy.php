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
use App\Models\Seo;
use App\Models\SpecCategory;
use App\Models\SpecField;
use App\Models\Store;
use App\Models\Video;
use Carbon\Carbon;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;
use Str;
use Validator;

class DeviceControllerCopy extends Controller
{
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
            'variants' => 'required|array|min:1',
            'variants.*.label' => 'required|string|max:255',
            'variants.*.ram_gb' => 'nullable|integer|min:0',
            'variants.*.storage_gb' => 'nullable|integer|min:0',
            'variants.*.model_code' => 'nullable|string|max:100',
            'variants.*.is_active' => 'nullable|boolean',
            'variants.*.offers' => 'nullable|array',
            'variants.*.offers.*.store_id' => 'required|exists:stores,id',
            'variants.*.offers.*.country_id' => 'required|exists:countries,id',
            'variants.*.offers.*.currency_id' => 'required|exists:currencies,id',
            'variants.*.offers.*.price' => 'required|numeric|min:0',
            'variants.*.offers.*.url' => 'nullable|url',
            'variants.*.offers.*.status' => 'nullable|boolean',
            'variants.*.offers.*.in_stock' => 'nullable|boolean',
            'variants.*.offers.*.is_featured' => 'nullable|boolean',
            'primary_variant' => 'required|integer',
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

        // try {
        //     DB::beginTransaction();

        // 1. Device general Info
        $device = new Device();
        $device->brand_id = $request->brand_id;
        $device->device_type_id = $request->device_type_id;
        $device->name = $request->name;
        $device->slug = Str::slug($request->name);
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
        // Handle thumbnail upload (if any)
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('devices/thumbnails', 'public');
            $device->thumbnail_url = $path;  // make sure column exists in migration
        }
        $device->save();

        // 2. Device Specifications (spec_categories, spec_fields, device_spec_values)

        /*
         * |--------------------------------------------------------------------------
         * | Device Specifications (spec_categories, spec_fields, device_spec_values)
         * |--------------------------------------------------------------------------
         * |
         * | Request looks like:
         * | "spec_categories" => [
         * |   0 => [
         * |     "name" => "Network",
         * |     "fields" => [
         * |       0 => ["label" => "Technology", "type" => "string", "value" => "GSM / HSPA", "filterable" => "1"],
         * |       ...
         * |     ]
         * |   ],
         * |   1 => [...]
         * | ]
         */
        $specCategoriesInput = $request->input('spec_categories', []);
        foreach ($specCategoriesInput as $catIndex => $categoryData) {
            $categoryName = trim($categoryData['name'] ?? '');

            // Skip empty categories
            if ($categoryName === '') {
                continue;
            }

            // Generate a slug for the category
            $baseSlug = Str::slug($categoryName);
            $slug = $baseSlug;

            // Try to find existing category by slug OR create new
            $specCategory = SpecCategory::where('slug', $slug)->first();

            if (!$specCategory) {
                // Ensure slug uniqueness
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
                // Optionally update name/order if you want to keep them in sync
                $specCategory->name = $categoryName;
                $specCategory->order = $catIndex;
                $specCategory->save();
            }

            // Fields inside category
            $fieldsData = $categoryData['fields'] ?? [];

            foreach ($fieldsData as $fieldIndex => $fieldData) {
                $label = trim($fieldData['label'] ?? '');
                $type = $fieldData['type'] ?? 'string';
                $valueRaw = $fieldData['value'] ?? null;
                $isFilterable = !empty($fieldData['filterable']);

                // Skip fields with no label and no value
                if ($label === '' && ($valueRaw === null || $valueRaw === '')) {
                    continue;
                }

                // Find existing field in this category by label
                $specField = SpecField::where('spec_category_id', $specCategory->id)
                    ->where('label', $label)
                    ->first();

                if (!$specField) {
                    // Generate a unique key for spec_fields.key
                    $baseKey = Str::slug($specCategory->slug . ' ' . $label, '_');  // e.g., network_technology
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
                    // Optionally keep meta in sync
                    $specField->type = $type;
                    $specField->is_filterable = $isFilterable;
                    $specField->order = $fieldIndex;
                    $specField->save();
                }

                // If value is empty, skip creating DeviceSpecValue
                if ($valueRaw === null || $valueRaw === '') {
                    continue;
                }

                // Map value to correct value_* column
                $valueString = null;
                $valueNumber = null;
                $valueJson = null;

                switch ($type) {
                    case 'number':
                        if (is_numeric($valueRaw)) {
                            $valueNumber = (float) $valueRaw;
                        } else {
                            // Fallback: store as string if not numeric
                            $valueString = (string) $valueRaw;
                        }
                        break;

                    case 'json':
                        // Try decode as JSON first
                        $decoded = json_decode($valueRaw, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $valueJson = $decoded;
                        } else {
                            // Fallback: comma-separated list → array
                            $parts = array_filter(array_map('trim', explode(',', $valueRaw)));
                            $valueJson = $parts;
                        }
                        break;

                    case 'boolean':
                        // normalize various truthy/falsey text
                        $normalized = strtolower(trim($valueRaw));
                        if (in_array($normalized, ['1', 'true', 'yes', 'y'], true)) {
                            $valueString = 'Yes';
                        } elseif (in_array($normalized, ['0', 'false', 'no', 'n'], true)) {
                            $valueString = 'No';
                        } else {
                            // if random text (like from faker), just store as-is
                            $valueString = (string) $valueRaw;
                        }
                        break;

                    case 'text':
                    case 'string':
                    default:
                        $valueString = (string) $valueRaw;
                        break;
                }

                // Create or update device_spec_values
                DeviceSpecValue::updateOrCreate(
                    [
                        'device_id' => $device->id,
                        'spec_field_id' => $specField->id,
                    ],
                    [
                        'value_string' => $valueString,
                        'value_number' => $valueNumber,
                        'value_json' => $valueJson ? json_encode($valueJson) : null,
                        'unit' => null,  // You can later extend UI to include unit
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
        $variantsInput = $request->input('variants', []);
        $primaryVariantIndex = $request->input('primary_variant');
        foreach ($variantsInput as $index => $variantData) {
            $label = trim($variantData['label'] ?? '');

            // Skip empty variants with no label
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

            // primary variant
            $variant->is_primary = ((string) $primaryVariantIndex === (string) $index);
            // active status
            $variant->status = array_key_exists('is_active', $variantData)
                ? (bool) $variantData['is_active']
                : true;

            $variant->save();

            // Offers inside this variant
            $offersData = $variantData['offers'] ?? [];

            foreach ($offersData as $offerData) {
                // Minimum required
                $storeId = $offerData['store_id'] ?? null;
                $countryId = $offerData['country_id'] ?? null;
                $currencyId = $offerData['currency_id'] ?? null;
                $price = $offerData['price'] ?? null;

                // Skip incomplete offers
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

                // Initial price history snapshot
                DeviceOfferPriceHistory::create([
                    'device_offer_id' => $offer->id,
                    'price' => $offer->price,
                    'recorded_at' => now(),
                ]);
            }
        }

        // 4. Device Videos

        /*
         * |--------------------------------------------------------------------------
         * | 4. Device Videos
         * |--------------------------------------------------------------------------
         * | Request structure:
         * | "videos" => [
         * |   0 => [
         * |     "title" => "...",
         * |     "youtube_id" => "https://youtu.be/...",
         * |     "description" => "...",
         * |     "is_published" => "1" (optional)
         * |   ],
         * |   ...
         * | ]
         */
        $videosInput = $request->input('videos', []);
        foreach ($videosInput as $index => $videoData) {
            $title = trim($videoData['title'] ?? '');
            $youtubeRaw = trim($videoData['youtube_id'] ?? '');
            // Skip if title or YouTube field is empty
            if ($title === '' || $youtubeRaw === '') {
                continue;
            }
            // Generate unique slug
            $baseSlug = Str::slug($title);
            $slug = $baseSlug;
            $counter = 1;
            while (Video::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
            $isPublished = !empty($videoData['is_published']);
            $video = new Video();
            $video->title = $title;
            $video->slug = $slug;
            $video->youtube_id = $youtubeRaw;  // URL or ID, as per your migration comment
            $video->thumbnail_url = null;  // you can later fill this via a job / API
            $video->description = $videoData['description'] ?? null;
            $video->author_id = auth()->id();  // admin user; make sure admin is authenticated
            $video->is_published = $isPublished;
            $video->published_at = $isPublished ? now() : null;
            $video->views_count = 0;
            $video->comments_count = 0;
            $video->save();
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

        // 5. Device Image Groups & Images
        /*
         * |--------------------------------------------------------------------------
         *         | Device Image Groups & Images
         *         |--------------------------------------------------------------------------
         *         "image_groups" => [
         *   0 => [
         *     "title" => "...",
         *     "group_type" => "...",
         *     "order" => "10",
         *     "images" => [
         *       0 => [
         *         "caption" => "...",
         *         "order"   => "8",
         *         "image"   => UploadedFile
         *       ],
         *       ...
         *     ]
         *   ],
         *   ...
         * ]
         */
        $imageGroupsInput = $request->all()['image_groups'] ?? [];
        foreach ($imageGroupsInput as $groupIndex => $groupData) {
            $title = trim($groupData['title'] ?? '');
            $groupType = trim($groupData['group_type'] ?? '');
            $groupOrder = isset($groupData['order']) && $groupData['order'] !== ''
                ? (int) $groupData['order']
                : $groupIndex;

            // Skip groups with no title and no images
            $imagesData = $groupData['images'] ?? [];
            if ($title === '' && empty($imagesData)) {
                continue;
            }

            // Build slug (unique per device)
            $baseSlug = Str::slug($title !== '' ? $title : ($groupType !== '' ? $groupType : "group-{$groupIndex}"));
            $slug = $baseSlug;
            $counter = 1;

            while (
                $slug !== null &&
                DeviceImageGroup::where('device_id', $device->id)->where('slug', $slug)->exists()
            ) {
                $slug = $baseSlug . '-' . $counter++;
            }

            // Create group
            $group = new DeviceImageGroup();
            $group->device_id = $device->id;
            $group->title = $title !== '' ? $title : 'Image Group ' . ($groupIndex + 1);
            $group->slug = $slug;
            $group->group_type = $groupType !== '' ? $groupType : null;
            $group->order = $groupOrder;
            $group->save();

            // Save images in this group
            foreach ($imagesData as $imgIndex => $imgData) {
                $file = $imgData['image'] ?? null;
                $caption = trim($imgData['caption'] ?? '');
                $imgOrder = isset($imgData['order']) && $imgData['order'] !== ''
                    ? (int) $imgData['order']
                    : $imgIndex;

                // Must have file
                if (!$file || !($file instanceof \Illuminate\Http\UploadedFile)) {
                    continue;
                }

                // Store file in public disk under /devices/{device_id}/images
                $path = $file->store("devices/{$device->id}/images", 'public');

                $image = new DeviceImage();
                $image->device_image_group_id = $group->id;
                $image->image_url = $path;
                $image->caption = $caption !== '' ? $caption : null;
                $image->order = $imgOrder;
                $image->save();
            }
        }

        // 6. Device Review

        /*
         * |--------------------------------------------------------------------------
         * | Device Review (device_reviews + device_review_sections)
         * |--------------------------------------------------------------------------
         * | Request:
         * | "review" => [
         * |   "is_published" => "1",
         * |   "title"        => "...",
         * |   "slug"         => "...",
         * |   "summary"      => "...",
         * |   "published_at" => "2020-04-09T16:52",
         * |   "cover_image"  => UploadedFile
         * | ]
         * |
         * | "review_sections" => [
         * |   0 => ["title" => "...", "slug" => "...", "body" => "<p>..</p>", "order" => "49"],
         * |   1 => [...]
         * | ]
         */
        $reviewData = $request->input('review', []);
        $reviewSections = $request->input('review_sections', []);

        // Decide if we should create a review at all
        $hasReviewContent =
            trim($reviewData['title'] ?? '') !== '' ||
            trim($reviewData['slug'] ?? '') !== '' ||
            trim($reviewData['summary'] ?? '') !== '' ||
            $request->hasFile('review.cover_image') ||
            !empty($reviewSections);

        if ($hasReviewContent) {
            $reviewTitle = trim($reviewData['title'] ?? '');
            $slugInput = trim($reviewData['slug'] ?? '');

            // Fallback slug: from title, or device name + "-review"
            if ($slugInput === '') {
                $slugInput = $reviewTitle !== ''
                    ? $reviewTitle
                    : ($device->name . ' review');
            }

            $baseSlug = Str::slug($slugInput);
            $slug = $baseSlug ?: Str::slug($device->name . '-review');

            // Ensure global unique slug (migration: unique())
            $counter = 1;
            while (DeviceReview::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $isPublished = !empty($reviewData['is_published']);
            $publishedAtRaw = $reviewData['published_at'] ?? null;

            $review = new DeviceReview();
            $review->device_id = $device->id;
            $review->title = $reviewTitle !== '' ? $reviewTitle : $device->name . ' review';
            $review->slug = $slug;
            $review->summary = $reviewData['summary'] ?? null;
            $review->author_id = auth()->id();  // assuming admin logged in
            $review->is_published = $isPublished;
            $review->views_count = 0;
            $review->comments_count = 0;

            if ($publishedAtRaw) {
                $review->published_at = Carbon::parse($publishedAtRaw);
            } elseif ($isPublished) {
                $review->published_at = now();
            } else {
                $review->published_at = null;
            }

            // Cover image upload
            if ($request->hasFile('review.cover_image')) {
                $coverPath = $request
                    ->file('review.cover_image')
                    ->store("devices/{$device->id}/reviews", 'public');

                $review->cover_image_url = $coverPath;
            }

            $review->save();

            // Save review sections
            foreach ($reviewSections as $index => $sectionData) {
                $secTitle = trim($sectionData['title'] ?? '');
                $secBody = $sectionData['body'] ?? '';

                // Skip completely empty sections
                if ($secTitle === '' && $secBody === '') {
                    continue;
                }

                $secSlugInput = trim($sectionData['slug'] ?? '');
                if ($secSlugInput === '') {
                    $secSlugInput = $secTitle !== '' ? $secTitle : "section-{$index}";
                }

                $secBaseSlug = Str::slug($secSlugInput);
                $secSlug = $secBaseSlug ?: "section-{$index}";

                // Ensure uniqueness per review (unique: device_review_id + slug)
                $secCounter = 1;
                while (
                    DeviceReviewSection::where('device_review_id', $review->id)
                        ->where('slug', $secSlug)
                        ->exists()
                ) {
                    $secSlug = $secBaseSlug . '-' . $secCounter++;
                }

                $order = isset($sectionData['order']) && $sectionData['order'] !== ''
                    ? (int) $sectionData['order']
                    : $index;

                $section = new DeviceReviewSection();
                $section->device_review_id = $review->id;
                $section->title = $secTitle !== '' ? $secTitle : 'Section ' . ($index + 1);
                $section->slug = $secSlug;
                $section->order = $order;
                $section->body = $secBody;  // HTML from Summernote
                $section->save();
            }
        }

        // 7. SEO Meta (polymorphic)
        $device->saveSeo($request);

        // DB::commit();

        ToastMagic::success('Device created successfully');
        return redirect()->route('admin.devices.index');
        // } catch (\Throwable $e) {
        //     DB::rollBack();
        //     report($e);

        //     // ToastMagic::error('Something went wrong while saving the device.');
        //     ToastMagic::error($e->getMessage());
        //     return redirect()->back();
        // }
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
    public function edit(Device $device)
    {
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
            'variants.offers',
            'imageGroups.images',
            'reviews.sections',
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
    public function update(Request $request, Device $device)
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
            // Device variants + offer price (keep same rules as store)
            'variants' => 'required|array|min:1',
            'variants.*.label' => 'required|string|max:255',
            'variants.*.ram_gb' => 'nullable|integer|min:0',
            'variants.*.storage_gb' => 'nullable|integer|min:0',
            'variants.*.model_code' => 'nullable|string|max:100',
            'variants.*.is_active' => 'nullable|boolean',
            'variants.*.offers' => 'nullable|array',
            'variants.*.offers.*.store_id' => 'required|exists:stores,id',
            'variants.*.offers.*.country_id' => 'required|exists:countries,id',
            'variants.*.offers.*.currency_id' => 'required|exists:currencies,id',
            'variants.*.offers.*.price' => 'required|numeric|min:0',
            'variants.*.offers.*.url' => 'nullable|url',
            'variants.*.offers.*.status' => 'nullable|boolean',
            'variants.*.offers.*.in_stock' => 'nullable|boolean',
            'variants.*.offers.*.is_featured' => 'nullable|boolean',
            'primary_variant' => 'required|integer',
            // Device Images
            'image_groups' => 'nullable|array',
            'image_groups.*.title' => 'required|string|max:255',
            'image_groups.*.group_type' => 'required|string|max:100',
            'image_groups.*.order' => 'nullable|integer|min:0',
            'image_groups.*.images' => 'nullable|array',
            'image_groups.*.images.*.caption' => 'nullable|string|max:500',
            'image_groups.*.images.*.order' => 'nullable|integer|min:0',
            'image_groups.*.images.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Review
            'review.title' => 'nullable|string|max:255',
            'review.slug' => 'nullable|string|max:255',
            'review.summary' => 'nullable|string|max:255',
            'review.published_at' => 'nullable|date',
            'review.cover_image' => 'nullable|image|max:4096',
            // SEO
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
            DB::beginTransaction();

            /*
             * |--------------------------------------------------------------------------
             * | 1. Update Device General Info
             * |--------------------------------------------------------------------------
             */
            $device->brand_id = $request->brand_id;
            $device->device_type_id = $request->device_type_id;
            $device->name = $request->name;
            $device->slug = Str::slug($request->name);  // or keep old slug if you prefer
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

            // Thumbnail upload (optional replace)
            if ($request->hasFile('thumbnail')) {
                // Optionally: delete old file if you want
                if ($device->thumbnail_url && Storage::disk('public')->exists($device->thumbnail_url)) {
                    Storage::disk('public')->delete($device->thumbnail_url);
                }

                $path = $request->file('thumbnail')->store('devices/thumbnails', 'public');
                $device->thumbnail_url = $path;
            }

            $device->save();

            /*
             * |--------------------------------------------------------------------------
             * | 2. Update Specifications (DeviceSpecValue)
             * |--------------------------------------------------------------------------
             * | Strategy: simple & safe
             * |   - Delete all existing spec values for this device
             * |   - Rebuild them from the incoming form (same logic as store())
             * |--------------------------------------------------------------------------
             */
            DeviceSpecValue::where('device_id', $device->id)->delete();

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

            DB::commit();

            ToastMagic::success('Device updated successfully');
            return redirect()->route('admin.devices.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            ToastMagic::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
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
