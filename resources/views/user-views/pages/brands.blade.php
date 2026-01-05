@extends('layouts.app')

@section('title', 'Brands')

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
    <x-ranking-table title="Top 10 by Daily Interest" value_column_label="Daily Hits" :items="$topDailyInterest"
        header_color="#a4c08d" even_row_color="#e8f5e9" />

    {{-- Top 10 by Fans --}}
    <x-ranking-table title="Top 10 by Fans" value_column_label="Favorites" :items="$topByFans" header_color="#82a2bd"
        even_row_color="#e4eff6" />

    @php
        $evNews = [
            [
                'title' => 'Electric car sales overtake gasoline and diesel in Europe, Tesla misses momentum',
                'img' => 'https://st.arenaev.com/news/25/11/electric-car-sales-overtake-gasoline-and-diesel-in-europe/-344x215/arenaev_000.jpg',
                'url' => 'https://www.arenaev.com/electric_car_sales_overtake_gasoline_and_diesel_in_europe_tesla_misses_momentum-news-5367.php',
            ],
            [
                'title' => 'Tesla launches 30-day free trial of FSD (Supervised) V14 in North America',
                'img' => 'https://st.arenaev.com/news/25/11/tesla-fsd-v14-free-trial/-344x215/arenaev_000.jpg',
                'url' => 'https://www.arenaev.com/tesla_launches_30day_free_trial_of_fsd_supervised_v14_in_north_america-news-5365.php',
            ],
            [
                'title' => 'UK electric car drivers to pay tax per mile starting 2028',
                'img' => 'https://st.arenaev.com/news/25/11/uk-electric-car-drivers-to-pay-tax-per-mile/-344x215/arenaev_000.jpg',
                'url' => 'https://www.arenaev.com/uk_electric_car_drivers_to_pay_tax_per_mile_starting_2028-news-5364.php',
            ],
            [
                'title' => 'Why solar panels on cars make no sense (at this point)',
                'img' => 'https://st.arenaev.com/news/23/01/solar-panels-stupid/-344x215/arenaev_001.jpg',
                'url' => 'https://www.arenaev.com/why_solar_panels_on_cars_are_beyond_stupid_at_this_point-news-1295.php',
            ],
        ];
    @endphp
    <x-news-list title="Electric vehicles" title_url="https://www.arenaev.com/" :items="$evNews" item_target="_blank" />
@endsection

@section('content')

    {{-- Hero Header --}}
    <div class="relative overflow-hidden w-full mb-10 rounded-b-[40px] shadow-2xl">
        <div class="relative h-[280px] md:h-[350px] bg-cover bg-center transition-transform duration-1000 hover:scale-105"
            style='background-image: url("{{ asset('user/images/all-brands.jpg') }}");'>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>

            <div class="relative h-full container mx-auto px-4 lg:px-6 flex flex-col justify-end pb-12">
                <div class="max-w-2xl">
                    <nav class="flex mb-4 text-blue-400 text-[10px] font-black uppercase tracking-widest gap-2">
                        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                        <span>/</span>
                        <span class="text-white">Brands</span>
                    </nav>
                    <h1
                        class="text-4xl md:text-6xl font-black text-white tracking-tighter leading-none mb-3 drop-shadow-2xl">
                        Explore <span class="text-blue-500">Brands</span>
                    </h1>
                    <p class="text-slate-300 text-sm md:text-base font-medium max-w-md line-clamp-2">
                        Discover the complete lineup of mobile technology from the world's leading manufacturers.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <section class="my-10 container mx-auto px-4 lg:px-6">
        <div class="mb-8 border-b border-gray-100 pb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-mobile-screen-button text-blue-600"></i> All Brands
                <span
                    class="ml-auto text-sm font-medium text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-100 italic">
                    {{ $brands->count() }} active brands in our database
                </span>
            </h2>
        </div>

        {{-- BRAND CARDS --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4 gap-6">
            @foreach ($brands as $brand)
                <a href="{{ route('brand.devices', $brand->slug) }}"
                    class="group flex flex-col items-center p-4 bg-white border border-gray-200 rounded-2xl hover:border-blue-500 hover:shadow-xl transition-all duration-300 w-40 h-40 justify-between">
                    <!-- Logo with Placeholder Fallback -->
                    <div
                        class="w-full h-16 mb-3 flex items-center justify-center rounded-xl bg-gray-50 overflow-hidden group-hover:bg-blue-50 transition-colors">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}"
                                class="w-12 h-12 object-contain">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-2xl">
                                {{ substr($brand->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <div class="text-center w-full">
                        <h3
                            class="text-[13px] font-bold text-gray-800 group-hover:text-blue-600 uppercase tracking-tight line-clamp-1">
                            {{ $brand->name }}
                        </h3>
                        <p class="text-[10px] font-semibold text-gray-400 mt-0.5">
                            {{ number_format($brand->devices_count) }} devices
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

@endsection