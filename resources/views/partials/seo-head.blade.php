@php
    // 1. Resolve Global Settings (Cache these in production ideally)
    $globalSeo = \App\Models\SeoSetting::first();
    $globalSchema = \App\Models\SeoSchemaSetting::first();

    // 2. Resolve Current Object/SEO
    // Priority: Explicit $seo -> $article -> $device -> $page_seo (if exists)
    $currentSeo = $seo ?? null; // Variable passed from controller?
    $currentObject = null;
    $fallbackImage = null;

    if (!$currentSeo) {
        if (isset($article) && $article instanceof \App\Models\Article) {
            $currentSeo = $article->seo;
            $currentObject = $article;
            $fallbackImage = $article->thumbnail_url;
        } elseif (isset($device) && $device instanceof \App\Models\Device) {
            $currentSeo = $device->seo;
            $currentObject = $device;
            $fallbackImage = $device->thumbnail_url;
        } elseif (isset($review) && $review instanceof \App\Models\Review) {
            // If Review model exists and has SEO
            // $currentSeo = $review->seo; 
            // $currentObject = $review;
        }
    }

    // 3. Cascade Data Resolution

    // -- Title --
    $pageTitle = $currentSeo?->meta_title;
    if (!$pageTitle) {
        // Fallback to object names
        if ($currentObject instanceof \App\Models\Article) {
            $pageTitle = $currentObject->title;
        } elseif ($currentObject instanceof \App\Models\Device) {
            $pageTitle = $currentObject->name . ' - Full phone specifications';
        }
    }
    // Final fallback to Global Default
    $finalTitle = $pageTitle ?: ($globalSeo?->default_meta_title ?? config('app.name'));

    // Append Site Name if configured and not already present (optional logic)
    $siteName = $globalSeo?->site_name ?? config('app.name');
    if ($pageTitle && !str_contains($finalTitle, $siteName)) {
        // $finalTitle .= ' - ' . $siteName; // Uncomment if you want "Title - SiteName" pattern automatically
    }


    // -- Description --
    $pageDesc = $currentSeo?->meta_description;
    if (!$pageDesc) {
        if ($currentObject instanceof \App\Models\Article) {
            $pageDesc = Str::limit(strip_tags($currentObject->excerpt ?? $currentObject->body), 160);
        } elseif ($currentObject instanceof \App\Models\Device) {
            // GSMArena style description
            $pageDesc = $currentObject->name . " smartphone. Announced " . ($currentObject->announcement_date ? $currentObject->announcement_date->format('Y, F') : '') . ". Features " . ($currentObject->main_camera_short ? $currentObject->main_camera_short . " camera, " : "") . ($currentObject->battery_short ? $currentObject->battery_short . " battery, " : "") . ($currentObject->storage_short ? $currentObject->storage_short . " storage, " : "") . ($currentObject->ram_short ? $currentObject->ram_short . " RAM, " : "") . ($currentObject->chipset_short ?? '') . " chipset.";
        }
    }
    $finalDesc = $pageDesc ?: ($globalSeo?->default_meta_description ?? '');


    // -- Keywords --
    $finalKeywords = $currentSeo?->meta_keywords ?: ($globalSeo?->default_meta_keywords ?? '');


    // -- Canonical URL --
    $finalCanonical = $currentSeo?->canonical_url ?: url()->current();


    // -- Image --
    $pageImage = $currentSeo?->og_image ?? $currentSeo?->twitter_image;
    if (!$pageImage && $fallbackImage) {
        $pageImage = $fallbackImage;
    }
    // Ensure absolute URL
    if ($pageImage && !str_starts_with($pageImage, 'http')) {
        $pageImage = asset($pageImage);
    }
    $finalImage = $pageImage ?: ($globalSeo?->og_default_image ?? ($globalSeo?->twitter_default_image ?? ''));


    // -- Robots --
    $robots = $globalSeo?->robots_default ?? 'index,follow';
@endphp

<!-- Primary Meta Tags -->
<title>{{ $finalTitle }}</title>
<meta name="title" content="{{ $finalTitle }}">
<meta name="description" content="{{ $finalDesc }}">
@if($finalKeywords)
    <meta name="keywords" content="{{ $finalKeywords }}">
@endif
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $finalCanonical }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $finalCanonical }}">
<meta property="og:title" content="{{ $currentSeo?->og_title ?: ($globalSeo?->og_default_title ?: $finalTitle) }}">
<meta property="og:description"
    content="{{ $currentSeo?->og_description ?: ($globalSeo?->og_default_description ?: $finalDesc) }}">
@if($finalImage)
    <meta property="og:image" content="{{ $finalImage }}">
@endif
<meta property="og:site_name" content="{{ $globalSeo?->og_site_name ?: $siteName }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ $finalCanonical }}">
<meta property="twitter:title"
    content="{{ $currentSeo?->twitter_title ?: ($globalSeo?->twitter_default_title ?: $finalTitle) }}">
<meta property="twitter:description"
    content="{{ $currentSeo?->twitter_description ?: ($globalSeo?->twitter_default_description ?: $finalDesc) }}">
@if($finalImage)
    <meta property="twitter:image" content="{{ $finalImage }}">
@endif

<!-- Schema.org JSON-LD -->
@if(isset($currentSeo->json_ld) && $currentSeo->json_ld)
    <script type="application/ld+json">
            {!! $currentSeo->json_ld !!}
        </script>
@elseif($currentObject instanceof \App\Models\Article && isset($globalSchema->article_schema_template))
    @php
        $schema = $globalSchema->article_schema_template;
        $schema = str_replace(
            ['{title}', '{url}', '{image}', '{published_at}', '{author}'],
            [
                addslashes($currentObject->title),
                url()->current(),
                $finalImage,
                $currentObject->published_at?->toIso8601String() ?? now()->toIso8601String(),
                addslashes($currentObject->author->name ?? $siteName)
            ],
            $schema
        );
    @endphp
    <script type="application/ld+json">
            {!! $schema !!}
        </script>
@elseif($currentObject instanceof \App\Models\Device && isset($globalSchema->product_schema_template))
    @php
        $schema = $globalSchema->product_schema_template;
        $schema = str_replace(
            ['{device_name}', '{brand}', '{image}', '{description}'],
            [
                addslashes($currentObject->name),
                addslashes($currentObject->brand->name ?? ''),
                $finalImage,
                addslashes($finalDesc)
            ],
            $schema
        );
    @endphp
    <script type="application/ld+json">
            {!! $schema !!}
        </script>
@endif

<!-- Global Organization Schema -->
@if(isset($globalSchema->organization_schema))
    <script type="application/ld+json">
            {!! $globalSchema->organization_schema !!}
        </script>
@endif