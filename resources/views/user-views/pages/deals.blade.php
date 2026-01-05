@extends('layouts.app')

@section('title', 'Daily deals')

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
{{-- resources/views/userviews/deals/index.blade.php --}}

@php
    // Markets filter – later you can fetch from DB
    $markets = [
        ['id' => 1, 'name' => 'United States'],
        ['id' => 2, 'name' => 'United Kingdom'],
        ['id' => 5, 'name' => 'Europe'],
        ['id' => 4, 'name' => 'International'],
        ['id' => 6, 'name' => 'Canada'],
    ];

    // Example deals – replace with DB data
    $deals = [
        [
            'title'        => 'Samsung Galaxy Watch8',
            'specs_url'    => '#', // route('devices.show', ...)
            'image'        => 'https://m.media-amazon.com/images/I/616KEp7qQvL._AC_SX466_.jpg',
            'offer_url'    => 'https://www.amazon.com/dp/B0F7PZNZQD',
            'description'  => 'Samsung Galaxy Watch 8 (2025) 44mm Bluetooth Smartwatch, Cushion Design, Fitness Tracker, Sleep Coaching, Running Coach, Energy Score, Heart Rate Tracking, Graphite [US Version, 2 Yr Warranty]',
            'memory_label' => '32GB 2GB RAM',
            'store_logo'   => 'https://fdn.gsmarena.com/imgroot/static/stores/amazon-com1.png',
            'price_label'  => '$ 279.99',
            'discount_pct' => '16.7%',
            'history'      => [
                'currency' => '$',
                'previous' => '$ 309.99',
                'min'      => '$ 309.99',
                'max'      => '$ 379.99',
                'avg30'    => '$ 343.37',
                'raw'      => '2025-07-25,37999;2025-09-08,35999;2025-09-30,37999;2025-10-06,35999;2025-10-21,34999;2025-10-23,37999;2025-10-27,35278;2025-10-28,35033;2025-10-29,35032;2025-10-29,34262;2025-10-30,33896;2025-11-10,37999;2025-11-14,34999;2025-11-17,37999;2025-11-20,30999;2025-11-27,27999;',
            ],
        ],
        [
            'title'        => 'Apple iPhone 16e',
            'specs_url'    => '#',
            'image'        => 'https://m.media-amazon.com/images/I/31W+GSEQNiL._SL500_.jpg',
            'offer_url'    => 'https://www.amazon.co.uk/dp/B0DXRFK8VS',
            'description'  => 'Apple iPhone 16e 256GB: Built for Apple Intelligence, A18 Chip, long battery life, 48MP camera, 6.1" Super Retina XDR Display; White.',
            'memory_label' => '256GB 8GB RAM',
            'store_logo'   => 'https://fdn.gsmarena.com/imgroot/static/stores/amazon-uk1.png',
            'price_label'  => '£ 599.00',
            'discount_pct' => '7.7%',
            'history'      => [
                'currency' => '£',
                'previous' => '£ 649.00',
                'min'      => '£ 649.00',
                'max'      => '£ 649.00',
                'avg30'    => '£ 649.00',
                'raw'      => '2025-10-21,64900;2025-11-25,59900;',
            ],
        ],
    ];
@endphp

{{-- Header / hero --}}
<div class="overflow-hidden w-full mb-6 md:h-[310px]">
    <div
        class="relative bg-cover bg-center h-full"
        style="background-image: url('https://fdn.gsmarena.com/imgroot/static/headers/deals-hd-1.jpg');"
    >
        <div class="bg-black/55 h-full">
            <div class="px-4 h-full md:px-6 py-6 flex flex-col items-start justify-end text-center gap-2">
                <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-white drop-shadow-md">
                    Daily deals
                </h1>
            </div>
        </div>
    </div>
</div>

<section class="bg-[#e7e4e4] my-5 flex flex-col gap-3 p-2 md:p-3">

    {{-- Markets filter --}}
  <form
    method="GET"
    action="{{ url()->current() }}"
    class="flex items-start gap-3 w-full py-3"
>
    {{-- Dropdown Wrapper --}}
    <div class="relative w-[85%]">

        {{-- Dropdown Toggle --}}
        <button
            type="button"
            id="marketDropdownToggle"
            class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-left flex justify-between items-center"
        >
            <span class="text-[12px] font-semibold text-[#555] uppercase tracking-[0.18em]">
                Markets: {{ request('markets') ? 'Selected' : 'All' }}
            </span>

            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        {{-- Dropdown Panel with Checkboxes --}}
        <div
            id="marketDropdownPanel"
            class="hidden absolute z-50 left-0 right-0 mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-[220px] overflow-y-auto px-3 py-2"
        >
            @foreach ($markets as $market)
                <label class="flex items-center gap-2 py-1 text-[13px]">
                    <input
                        type="checkbox"
                        name="markets[]"
                        value="{{ $market['id'] }}"
                        @if(collect(request('markets', []))->contains($market['id'])) checked @endif
                    />
                    {{ $market['name'] }}
                </label>
            @endforeach
        </div>

    </div>

    {{-- Submit Button --}}
    <button
        type="submit"
        class="w-[15%] px-2 py-2 text-[12px] font-semibold uppercase tracking-[0.18em] border-2 border-gray-400  hover:bg-[#b20000] hover:text-white transition-colors"
    >
        Show
    </button>
</form>


    {{-- Info text --}}
    <div class="px-4 py-3 text-[13px] text-[#555]">
        We track recent prices for phones and gadgets and highlight standout offers we’ve spotted.
        Discounts are estimated from the average price over the last 30 days (or since listing for new items).
        Final price and availability can change – always confirm details with the seller before buying.
    </div>

    {{-- Deals list --}}
    <div class="space-y-6">
        @foreach ($deals as $deal)
            <article class="bg-white border border-gray-200 shadow-sm">
                {{-- Main row: image + info --}}
                <div class="flex flex-col md:flex-row gap-4 p-3 md:p-4">
                    {{-- Image --}}
                    <a
                        href="{{ $deal['offer_url'] }}"
                        target="_blank"
                        rel="noopener"
                        class="w-full md:w-[170px] flex-shrink-0 flex items-center justify-center bg-white"
                    >
                        <img
                            src="{{ $deal['image'] }}"
                            alt="{{ $deal['title'] }}"
                            class="max-h-[180px] object-contain"
                        />
                    </a>

                    {{-- Text / Deal details --}}
                    <div class="flex-1 flex flex-col gap-3">
                        {{-- Title + specs --}}
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <h3 class="text-[18px] md:text-[19px] font-semibold text-[#333]">
                                {{ $deal['title'] }}
                            </h3>
                        </div>

                        {{-- Description --}}
                        <p class="text-[13px] text-[#555] leading-snug">
                            <a
                                href="{{ $deal['offer_url'] }}"
                                target="_blank"
                                rel="noopener"
                                class="hover:text-[#F9A13D]"
                            >
                                {{ $deal['description'] }}
                            </a>
                        </p>

                        {{-- Deal row: memory / store / price / discount --}}
                        <div class="flex flex-wrap items-center gap-3 text-[13px]">
                            <a
                                href="{{ $deal['offer_url'] }}"
                                target="_blank"
                                rel="noopener"
                                class="px-2 py-1 rounded border border-gray-300 text-[#555] hover:border-[#F9A13D] hover:text-[#F9A13D] transition-colors"
                            >
                                {{ $deal['memory_label'] }}
                            </a>

                            <a
                                href="{{ $deal['offer_url'] }}"
                                target="_blank"
                                rel="noopener"
                                class="flex items-center"
                            >
                                <img
                                    src="{{ $deal['store_logo'] }}"
                                    alt="Store"
                                    class="h-5 object-contain"
                                />
                            </a>

                            <a
                                href="{{ $deal['offer_url'] }}"
                                target="_blank"
                                rel="noopener"
                                class="font-semibold text-[14px] text-[#2e7d32]"
                            >
                                {{ $deal['price_label'] }}
                            </a>

                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-[#e8f5e9] text-[#2e7d32] text-[11px] font-semibold">
                                {{ $deal['discount_pct'] }} off
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Price history (collapsible, no JS required) --}}
                <details
                    class="border-t border-gray-200 text-[12px] text-[#555]"
                    data-history="{{ $deal['history']['raw'] }}"
                    data-currency="{{ $deal['history']['currency'] }}"
                >
                    <summary class="flex items-center justify-end gap-2 px-4 py-2 cursor-pointer hover:bg-gray-50">
                        <span>Price history</span>
                        <i class="fa-solid fa-chevron-down text-[11px] text-gray-500"></i>
                    </summary>

                    <div class="px-4 pb-3 pt-1">
                        {{-- Placeholder for chart if you add JS later --}}
                        <div class="w-full h-[120px] bg-gray-100 rounded mb-3 flex items-center justify-center text-[11px] text-gray-500">
                            Price trend chart placeholder
                        </div>

                        {{-- Stats grid --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-[11px]">
                            <div class="flex flex-col">
                                <span class="text-gray-500">Previous</span>
                                <b class="text-[#333]">{{ $deal['history']['previous'] }}</b>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500">Min</span>
                                <b class="text-[#333]">{{ $deal['history']['min'] }}</b>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500">Max</span>
                                <b class="text-[#333]">{{ $deal['history']['max'] }}</b>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500">30-day avg</span>
                                <b class="text-[#333]">{{ $deal['history']['avg30'] }}</b>
                            </div>
                        </div>
                    </div>
                </details>
            </article>
        @endforeach
    </div>
</section>

@endsection

@push('scripts')
{{-- Vanilla JS to toggle dropdown --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("marketDropdownToggle");
    const panel = document.getElementById("marketDropdownPanel");

    toggle.addEventListener("click", () => {
        panel.classList.toggle("hidden");
    });

    document.addEventListener("click", function(e) {
        if (!toggle.contains(e.target) && !panel.contains(e.target)) {
            panel.classList.add("hidden");
        }
    });
});
</script>
@endpush