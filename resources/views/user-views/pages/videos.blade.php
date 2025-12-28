@extends('layouts.app')
@section('title', 'Videos')

@section('sidebar')
<x-popular-review :reviews="$popularReviews" />

@php
    $topDailyInterest = [
        ['name' => 'Xiaomi Poco F8 Ultra', 'value' => '35,721', 'url' => '#'],
        ['name' => 'OnePlus 15', 'value' => '24,173', 'url' => '#'],
        ['name' => 'Samsung Galaxy A56', 'value' => '23,603', 'url' => '#'],
        ['name' => 'Samsung Galaxy S25 Ultra', 'value' => '23,102', 'url' => '#'],
        ['name' => 'Apple iPhone 17 Pro Max', 'value' => '22,020', 'url' => '#'],
        ['name' => 'Xiaomi Poco F8 Pro', 'value' => '21,829', 'url' => '#'],
        ['name' => 'Xiaomi 17 Pro Max', 'value' => '17,405', 'url' => '#'],
        ['name' => 'Samsung Galaxy A17', 'value' => '16,797', 'url' => '#'],
        ['name' => 'Samsung Galaxy S25', 'value' => '14,365', 'url' => '#'],
        ['name' => 'Xiaomi Poco X7 Pro', 'value' => '14,085', 'url' => '#'],
    ];

    $topByFans = [
        ['name' => 'Xiaomi Poco F8 Ultra', 'value' => '18,421', 'url' => '#'],
        ['name' => 'Samsung Galaxy A56', 'value' => '15,603', 'url' => '#'],
        ['name' => 'Samsung Galaxy S25 Ultra', 'value' => '15,102', 'url' => '#'],
        ['name' => 'OnePlus 15', 'value' => '16,973', 'url' => '#'],
        ['name' => 'Apple iPhone 17 Pro Max', 'value' => '14,920', 'url' => '#'],
        ['name' => 'Xiaomi Poco F8 Pro', 'value' => '13,829', 'url' => '#'],
        ['name' => 'Xiaomi 17 Pro Max', 'value' => '11,405', 'url' => '#'],
        ['name' => 'Samsung Galaxy A17', 'value' => '10,797', 'url' => '#'],
        ['name' => 'Samsung Galaxy S25', 'value' => '9,365', 'url' => '#'],
        ['name' => 'Xiaomi Poco X7 Pro', 'value' => '9,085', 'url' => '#'],
    ];
@endphp

{{-- Top 10 by Daily Interest --}}
<x-ranking-table
    title="Top 10 by Daily Interest"
    value_column_label="Daily Hits"
    :items="$topDailyInterest"
    header_color="#a4c08d"
    even_row_color="#e8f5e9"
/>

{{-- Top 10 by Fans --}}
<x-ranking-table
    title="Top 10 by fans"
    value_column_label="Favorites"
    :items="$topByFans"
    header_color="#82a2bd"
    even_row_color="#e4eff6"
/>


@php
    $evNews = [
        [
            'title' => 'Electric car sales overtake gasoline and diesel in Europe, Tesla misses momentum',
            'img'   => 'https://st.arenaev.com/news/25/11/electric-car-sales-overtake-gasoline-and-diesel-in-europe/-344x215/arenaev_000.jpg',
            'url'   => 'https://www.arenaev.com/electric_car_sales_overtake_gasoline_and_diesel_in_europe_tesla_misses_momentum-news-5367.php',
        ],
        [
            'title' => 'Tesla launches 30-day free trial of FSD (Supervised) V14 in North America',
            'img'   => 'https://st.arenaev.com/news/25/11/tesla-fsd-v14-free-trial/-344x215/arenaev_000.jpg',
            'url'   => 'https://www.arenaev.com/tesla_launches_30day_free_trial_of_fsd_supervised_v14_in_north_america-news-5365.php',
        ],
        [
            'title' => 'UK electric car drivers to pay tax per mile starting 2028',
            'img'   => 'https://st.arenaev.com/news/25/11/uk-electric-car-drivers-to-pay-tax-per-mile/-344x215/arenaev_000.jpg',
            'url'   => 'https://www.arenaev.com/uk_electric_car_drivers_to_pay_tax_per_mile_starting_2028-news-5364.php',
        ],
        [
            'title' => 'Why solar panels on cars make no sense (at this point)',
            'img'   => 'https://st.arenaev.com/news/23/01/solar-panels-stupid/-344x215/arenaev_001.jpg',
            'url'   => 'https://www.arenaev.com/why_solar_panels_on_cars_are_beyond_stupid_at_this_point-news-1295.php',
        ],
    ];
@endphp
<x-news-list
    title="Electric vehicles"
    title_url="https://www.arenaev.com/"
    :items="$evNews"
    item_target="_blank"
/>

@endsection

@section('content')
@php
    $popularTags = [
        'Featured',
        'Android',
        'Samsung',
        'Nokia',
        'Sony',
        'Rumors',
        'Apple',
        'LG',
        'Xiaomi',
        'Motorola',
    ];
@endphp

<x-page-hero
    title="Videos"
    :tags=""
    background="{{ asset('user/images/video.jpg') }}"
    search_action="{{ route('videos') }}"
    query_param="q"
    tag_param="tag"
/>


{{-- Header / hero --}}
<div class="overflow-hidden w-full mb-6 md:h-[310px]">
    <div
        class="relative bg-cover bg-center h-full"
        style="background-image: url('{{ asset('user/images/video.jpg') }}');"
    >
        <div class="bg-black/55 h-full">
            <div class="px-4 h-full md:px-6 py-6 flex flex-col items-start justify-end text-center gap-2">
                <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-white drop-shadow-md">
                    Videos
                </h1>
            </div>
        </div>
    </div>
</div>


{{-- resources/views/userviews/videos/index.blade.php --}}

@php
    // Later: replace with DB-driven videos (YouTube IDs, titles, etc.)
    $videos = [
        [
            'id'    => 'x_q-gz_l5XM',
            'title' => 'Hands-on: flagship killer of the year?',
        ],
        [
            'id'    => 'Hbx8bLFc0IU',
            'title' => 'Camera review and low-light test',
        ],
        [
            'id'    => 'aIpxMkJd_V4',
            'title' => 'Battery endurance and charging deep dive',
        ],
        [
            'id'    => 'BD_HmUCUDGA',
            'title' => 'Display, speakers and gaming performance',
        ],
        [
            'id'    => '4uQIw98QeOI',
            'title' => 'Full review recap in under 10 minutes',
        ],
        [
            'id'    => '43ABU-eEOJE',
            'title' => 'Best phones of the month – roundup',
        ],
        [
            'id'    => 'VLtMPSbuX5g',
            'title' => 'Unboxing & first impressions',
        ],
        [
            'id'    => 'jwr0LTp1QZ0',
            'title' => 'Software, features & long-term verdict',
        ],
    ];

    // Your TikTok / YouTube channel links (replace with your own)
    $tiktokHandle = 'gsmarenateam';
    $youtubeChannelUrl = 'https://www.youtube.com/user/gsmarena07/videos';
@endphp

<section id="videos" class="max-w-[1060px] mx-auto bg-[#e7e4e4] my-5 flex flex-col gap-3 p-2 md:p-3">

    {{-- TikTok Creator Embed --}}
    <div class="w-full flex justify-center">
        <blockquote
            class="tiktok-embed w-full"
            data-unique-id="{{ $tiktokHandle }}"
            data-embed-type="creator"
            style="max-width: 780px; min-width: 288px;"
        >
            {{-- Fallback placeholder while TikTok loads --}}
            <div class="w-full bg-black/5 flex items-center justify-center min-h-[200px] text-[11px] text-gray-500">
                Loading TikTok videos…
            </div>
        </blockquote>
        <script async src="https://www.tiktok.com/embed.js"></script>
    </div>

    {{-- YouTube Videos List --}}
    <div class="space-y-4">
        @foreach ($videos as $video)
            <article class="bg-white border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                {{-- Responsive 16:9 iframe using padding-top hack (no plugins needed) --}}
                <div class="relative w-full pt-[56.25%]">
                    <iframe
                        class="absolute inset-0 w-full h-full"
                        src="https://www.youtube.com/embed/{{ $video['id'] }}"
                        title="{{ $video['title'] }}"
                        frameborder="0"
                        allowfullscreen
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    ></iframe>
                </div>

                @if (!empty($video['title']))
                    <div class="px-3 py-2 text-[13px] text-[#555]">
                        {{ $video['title'] }}
                    </div>
                @endif
            </article>
        @endforeach
    </div>

    {{-- “More videos / YouTube channel” CTA --}}
    <div class="flex flex-col items-center gap-1 mt-6 text-center">
        <a
            href="{{ $youtubeChannelUrl }}"
            target="_blank"
            class="inline-flex flex-col items-center gap-1 px-5 py-3 border border-[#F9A13D] text-[#F9A13D] rounded-md hover:bg-[#F9A13D] hover:text-white transition-colors"
        >
            <strong class="text-[12px] uppercase tracking-[0.22em]">
                More videos »
            </strong>
            <span class="text-[13px]">
                Visit our YouTube channel
            </span>
        </a>
    </div>

    {{-- Optional: "Compare photos" trigger --}}
    <div class="mt-6 flex justify-center">
        <button
            type="button"
            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-[12px] text-[#555] rounded-full border border-gray-300 hover:bg-gray-200 transition-colors"
        >
            <span>Click here to compare two photos</span>
            <i class="fa-solid fa-xmark text-[11px]"></i>
        </button>
    </div>
</section>

@endsection