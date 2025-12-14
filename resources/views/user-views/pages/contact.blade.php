@extends('layouts.app')

@section('title', 'Contact')

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

{{-- Hero / header --}}
<div class="overflow-hidden w-full mb-6 md:h-[310px]">
    <div
        class="relative bg-cover bg-center h-full"
        style="background-image: url('https://fdn.gsmarena.com/imgroot/static/headers/contact-hlr.jpg');"
    >
        <div class="bg-black/25 h-full">
            <div class="px-4 md:px-6 py-6 h-full flex flex-col justify-end gap-2">
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white drop-shadow-md">
                    Contact us
                </h1>
            </div>
        </div>
    </div>
</div>

<section class=" my-5 flex flex-col gap-4 p-3 md:p-4">

    {{-- Intro / feedback card --}}
    <div class="bg-white px-4 py-4 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed space-y-3">
        <h2 class="text-lg font-semibold text-[#333] mb-1">
            We value your feedback
        </h2>

        <p>
            We’re happy to hear from you when something on the site needs attention or you have ideas
            that can help us improve.
        </p>

        <p class="font-semibold text-[13px] text-[#333]">
            Reach out to us if, for example:
        </p>

        <ul class="list-disc pl-5 space-y-1">
            <li>You noticed an error or outdated detail in a device specification.</li>
            <li>You have information about a phone or device that’s missing from our database.</li>
            <li>You’ve come across a broken link or a page that doesn’t load as expected.</li>
            <li>You’d like to suggest a new feature or UX improvement for the site.</li>
        </ul>

        <p class="mt-3 font-semibold text-[13px] text-[#333]">
            Before contacting us, please keep in mind:
        </p>

        <ul class="list-disc pl-5 space-y-1">
            <li>We don’t sell phones or accessories and can’t provide purchase invoices.</li>
            <li>We can’t quote local prices for specific countries or shops.</li>
            <li>We don’t handle SIM unlocking or carrier-unlock requests.</li>
            <li>We can’t offer personalised “which phone should I buy?” consultations.</li>
        </ul>

        <p class="mt-3">
            For tips about news, leaks, or interesting stories, please use our
            <a
                href="{{ url('/tip-us') }}"
                class="font-semibold text-[#b00020] hover:underline"
            >
                Tip us form
            </a>
            so the information reaches the right team directly.
        </p>
    </div>

    {{-- Direct contact block --}}
    <div class="bg-white px-4 py-4 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed">
        <h2 class="text-lg font-semibold text-[#333] mb-2">
            General contact
        </h2>

        <p class="mb-2">
            For general questions about the site, corrections, or technical issues, you can email us at:
        </p>

        <p class="text-[14px] font-semibold">
            <a
                href="mailto:support@yourdomain.com"
                class="text-[#b00020] hover:underline break-all"
            >
                support@yourdomain.com
            </a>
        </p>

        <p class="mt-3 text-[12px] text-[#777]">
            Please include as much detail as possible (URL, device name, screenshot if relevant) so we can
            investigate and respond more efficiently.
        </p>
    </div>

    {{-- Advertising block --}}
    <div class="bg-white px-4 py-4 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed">
        <h2 class="text-lg font-semibold text-[#333] mb-2">
            Advertising & partnerships
        </h2>

        <p>
            If you run an online store, service, or brand and are interested in advertising or partnership
            opportunities, we’d be happy to discuss formats and options.
        </p>

        <p class="mt-2">
            You can find more details on our dedicated advertising page:
        </p>

        <p class="mt-1">
            <a
                href="{{ url('/advertising') }}"
                class="font-semibold text-[#b00020] hover:underline"
            >
                Learn more about advertising
            </a>
        </p>
    </div>

    {{-- Optional: Simple contact form (you can wire this to a controller later) --}}
    <div class="bg-white px-4 py-5 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed">
        <h2 class="text-lg font-semibold text-[#333] mb-3">
            Send us a message
        </h2>

        <form method="POST" action="{{ route('contact.submit') }}" class="space-y-3">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[12px] font-semibold text-[#555] mb-1">
                        Your name
                    </label>
                    <input
                        type="text"
                        name="name"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#b00020]"
                        required
                    >
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#555] mb-1">
                        Email address
                    </label>
                    <input
                        type="email"
                        name="email"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#b00020]"
                        required
                    >
                </div>
            </div>

            <div>
                <label class="block text-[12px] font-semibold text-[#555] mb-1">
                    Subject
                </label>
                <input
                    type="text"
                    name="subject"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#b00020]"
                >
            </div>

            <div>
                <label class="block text-[12px] font-semibold text-[#555] mb-1">
                    Message
                </label>
                <textarea
                    name="message"
                    rows="4"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#b00020] resize-y"
                    required
                ></textarea>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="px-4 py-2 text-[12px] font-semibold uppercase tracking-[0.18em]
                           bg-[#b00020] text-white hover:bg-[#8e0019] transition-colors"
                >
                    Send message
                </button>
            </div>
        </form>
    </div>
</section>

@endsection