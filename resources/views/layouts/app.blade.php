{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', config('app.name', 'Laravel'))
    </title>


    {!! ToastMagic::styles() !!}
    <!-- Tailwind CSS (Browser CDN v4) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Icons & Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Responsive CSS -->
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">

    <!-- GSMarena-Style Responsive Enhancements -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body class="bg-white font-['Arimo',Arial,sans-serif] text-[13px] overflow-x-hidden">
    <div id="app">
        <!-- Desktop & Laptop Header -->
        <div class="hidden lg:block">
            <div class="max-w-[1060px] mx-auto px-4 py-1.5">

                <div class="flex items-center justify-between gap-4">

                    {{-- LEFT SECTION --}}
                    <div class="flex items-center gap-4 flex-none">
                        <button id="menu-toggle" type="button"
                            class="bg-[#666666] text-white px-3 py-2 text-lg cursor-pointer md:hidden">
                            <i id="menu-icon" class="fa-solid fa-bars"></i>
                        </button>

                        <a href="#" class="text-white text-3xl w-[130px] font-black tracking-tight">
                            <img src="{{ asset('logo.png') }}" alt="">
                        </a>
                    </div>

                    {{-- CENTER SECTION --}}
                    <div class="flex-1 flex justify-center relative">
                        <div class="w-full max-w-[350px] relative">
                            <form action="{{ route('search.global') }}" method="GET" id="global-search-form">
                                <div class="flex overflow-hidden">
                                    <input type="search" name="q" id="search-input" placeholder="Search mobile phone..."
                                        autocomplete="off"
                                        class="flex-1 border focus border-gray-500 px-3 py-2 text-gray-500 placeholder:text-gray-500" />
                                </div>
                            </form>

                            {{-- Search Dropdown --}}
                            <div id="search-dropdown"
                                class="hidden absolute top-[100%] left-0 w-[600px] md:w-[800px] bg-white shadow-2xl z-[100] border-t-2 border-[#d50000] -translate-x-1/4 md:-translate-x-1/3 mt-1">
                                <div class="p-4">
                                    {{-- Recently Viewed (Initial State) --}}
                                    <div id="recently-viewed-section" class="hidden">
                                        <h3 class="text-xs font-bold text-gray-400 uppercase mb-3 border-b pb-1">
                                            Recently Viewed</h3>
                                        <div id="recent-list" class="grid grid-cols-4 md:grid-cols-6 gap-4"></div>
                                    </div>

                                    {{-- Live Search Results (Typing State) --}}
                                    <div id="search-results-section" class="hidden">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            {{-- Column 1: Reviews --}}
                                            <div>
                                                <h3
                                                    class="text-xs font-bold text-[#d50000] uppercase mb-2 flex items-center gap-2">
                                                    <i class="fas fa-star-half-alt"></i> Reviews
                                                </h3>
                                                <div id="results-reviews" class="flex flex-col gap-2"></div>
                                            </div>

                                            {{-- Column 2: Devices --}}
                                            <div>
                                                <h3
                                                    class="text-xs font-bold text-[#d50000] uppercase mb-2 flex items-center gap-2">
                                                    <i class="fas fa-mobile-alt"></i> Devices
                                                </h3>
                                                <div id="results-devices" class="flex flex-col gap-2"></div>
                                            </div>

                                            {{-- Column 3: News --}}
                                            <div>
                                                <h3
                                                    class="text-xs font-bold text-[#d50000] uppercase mb-2 flex items-center gap-2">
                                                    <i class="fas fa-newspaper"></i> News
                                                </h3>
                                                <div id="results-news" class="flex flex-col gap-2"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="search-loading" class="hidden py-10 text-center">
                                        <i class="fas fa-spinner fa-spin text-gray-300 text-2xl"></i>
                                    </div>

                                    <div id="search-empty" class="hidden py-4 text-center text-gray-400 italic">
                                        No results match your search.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT SECTION --}}
                    <div class="flex items-center gap-3 flex-none">
                        @php
                            $icons = [
                                'fa-brands fa-facebook-f',
                                'fab fa-instagram',
                                'fab fa-youtube',
                                'fa-brands fa-tiktok',
                            ];
                        @endphp

                        @foreach ($icons as $icon)
                            <a href="#"
                                class="flex flex-col w-[31px] h-[31px] rounded-full items-center justify-center text-white text-[11px] transition-all bg-[#F9A13D]">
                                <i class="{{ $icon }} text-[16px]"></i>
                            </a>
                        @endforeach
                        {{-- AUTH ICONS --}}
                        @guest
                            {{-- Login --}}
                            <a href="javascript:void(0)" id="login-toggle" title="Login"
                                class="flex flex-col w-[31px] h-[31px] rounded-full items-center justify-center text-white text-[11px] bg-[#F9A13D]">
                                <i class="fas fa-sign-in-alt text-[16px]"></i>
                            </a>

                            {{-- Register --}}
                            <a href="{{ route('register') }}" title="Sign Up"
                                class="flex flex-col w-[31px] h-[31px] rounded-full items-center justify-center text-white text-[11px] bg-[#F9A13D]">
                                <i class="fas fa-user-plus text-[16px]"></i>
                            </a>
                        @endguest

                        @guest
                            <span id="login-popup"
                                class="hidden absolute top-[60px] right-[40px] z-50 bg-white shadow-lg border rounded w-[260px] p-4">

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <p class="font-semibold text-sm mb-3">Login</p>

                                    <input type="email" name="email" placeholder="Email" required
                                        class="w-full border px-3 py-2 text-sm mb-2 rounded">

                                    <input type="password" name="password" placeholder="Your password" required
                                        class="w-full border px-3 py-2 text-sm mb-3 rounded">

                                    <button type="submit"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded text-sm font-semibold">
                                        Log in
                                    </button>
                                </form>

                                <a href="{{ route('password.request') }}"
                                    class="block text-xs text-center text-gray-500 mt-2 hover:underline">
                                    I forgot my password
                                </a>
                            </span>
                        @endguest


                        @auth
                            {{-- User Account --}}
                            <a href="{{ route('user.account') }}" title="Sign Up"
                                class="flex justify-center cursor-pointer items-center gap-1 text-white text-[11px] w-[31px] h-[31px] rounded-full bg-[#F9A13D]">
                                <i class="fas fa-user text-[16px]"></i>
                            </a>
                            {{-- Logout (POST required) --}}
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" title="Logout"
                                    class="flex justify-center cursor-pointer items-center gap-1 text-white text-[11px] w-[31px] h-[31px] rounded-full bg-[#F9A13D]">
                                    <i class="fas fa-sign-out-alt text-[16px]"></i>
                                </button>
                            </form>
                        @endauth

                    </div>

                </div>
            </div>
            {{-- NAV --}}
            <nav id="main-nav" class="md:block hidden bg-[#045cb4] text-white">
                <ul class="max-w-[1060px] mx-auto flex flex-wrap justify-center items-center">
                    @php
                        $menu = [
                            'home' => 'HOME',
                            'news' => 'NEWS',
                            'reviews' => 'REVIEWS',
                            'videos' => 'VIDEOS',
                            'featured' => 'FEATURED',
                            'phone-finder' => 'PHONE FINDER',
                            'deals' => 'DEALS',
                            'merch' => 'MERCH',
                            'coverage' => 'COVERAGE',
                            'contact' => 'CONTACT',
                        ];
                    @endphp

                    @foreach ($menu as $key => $item)
                        <li class="flex-1 text-center items-center min-w-[80px]">
                            <a href="{{ route($key) }}"
                                class="relative block py-3 px-2 font-bold uppercase text-[12px] hover:text-[#F9A13D]">
                                {{ $item }}
                                @if ($item === 'MERCH')
                                    <span
                                        class="absolute top-0.5 right-1 bg-[#ff6b6b] text-white text-[8px] px-1 py-[1px] rounded">
                                        NEW
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>

        <!-- Tablets & Mobile Header -->
        <div class="lg:hidden">
            <div class="bg-[#045cb4]">
                <div id="mobile-top-bar" class="flex items-center px-3 py-2 justify-between">
                    <!-- Left section -->
                    <div class="flex gap-4 items-center">
                        <button id="mobile-menu-btn" type="button"
                            class="bg-[#F9A13D] text-white px-3 text-lg cursor-pointer">
                            <i id="mobile-menu-icon" class="fa-solid fa-bars"></i>
                        </button>

                        <a href="#" class="text-white text-3xl w-[130px] font-black tracking-tight">
                            <img src="{{ asset('logo.png') }}" alt="">
                        </a>
                    </div>
                    <!-- Right section -->
                    <div class="flex gap-3">
                        @auth
                            {{-- User Account --}}
                            <a href="{{ route('user.account') }}" title="Sign Up"
                                class="text-white px-4 text-[11px] rounded-sm bg-[#F9A13D]">
                                {{ Auth::user()->name }}
                            </a>
                            {{-- Logout (POST required) --}}
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" title="Logout"
                                    class="flex justify-center cursor-pointer items-center gap-1 text-white text-[11px] w-[31px] h-[31px] rounded-full bg-[#F9A13D]">
                                    <i class="fas fa-sign-out-alt text-[16px]"></i>
                                </button>
                            </form>
                        @endauth

                        {{-- AUTH ICONS --}}
                        @guest
                            {{-- Login --}}
                            <a href="javascript:void(0)" id="login-toggle" title="Login"
                                class="flex flex-col w-[31px] h-[31px] rounded-full items-center justify-center text-white text-[11px] bg-[#F9A13D]">
                                <i class="fas fa-sign-in-alt text-[16px]"></i>
                            </a>

                            {{-- Register --}}
                            <a href="{{ route('register') }}" title="Sign Up"
                                class="flex flex-col w-[31px] h-[31px] rounded-full items-center justify-center text-white text-[11px] bg-[#F9A13D]">
                                <i class="fas fa-user-plus text-[16px]"></i>
                            </a>
                        @endguest

                        @guest
                            <span id="login-popup"
                                class="hidden absolute top-[60px] right-[40px] z-50 bg-white shadow-lg border rounded w-[260px] p-4">

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <p class="font-semibold text-sm mb-3">Login</p>

                                    <input type="email" name="email" placeholder="Email" required
                                        class="w-full border px-3 py-2 text-sm mb-2 rounded">

                                    <input type="password" name="password" placeholder="Your password" required
                                        class="w-full border px-3 py-2 text-sm mb-3 rounded">

                                    <button type="submit"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded text-sm font-semibold">
                                        Log in
                                    </button>
                                </form>

                                <a href="{{ route('password.request') }}"
                                    class="block text-xs text-center text-gray-500 mt-2 hover:underline">
                                    I forgot my password
                                </a>
                            </span>
                        @endguest
                    </div>
                </div>
                <div class="hidden flex-col justify-center relative" id="show-mobile-search">
                    <div class="w-full relative">
                        <form action="{{ route('search.global') }}" method="GET" id="global-search-form">
                            <div class="flex gap-3 overflow-hidden w-full px-5 py-2">
                                <input type="search" name="q" id="search-input" placeholder="Search mobile phone..."
                                    autocomplete="off"
                                    class="flex-1 border focus bg-white border-gray-500 px-3 py-2 text-gray-500 placeholder:text-gray-500" />
                                <button type="button" id="mobile-search-close"
                                    class="bg-gray-200 text-gray-600 px-3 rounded-sm font-bold">X</button>
                                <input type="submit" name="" id="search-submit" value="Go" autocomplete="off"
                                    class="border focus bg-[#F9A13D] rounded-sm px-1 text-white placeholder:text-gray-500" />
                            </div>
                        </form>

                        {{-- Search Dropdown --}}
                        <div id="mobile-search-dropdown"
                            class="hidden absolute left-0 w-full bg-white shadow-2xl z-[100] border-t-2 border-[#d50000] mt-1">
                            <div class="w-full">
                                {{-- Recently Viewed (Initial State) --}}
                                <div id="mobile-recently-viewed-section" class="hidden p-3 bg-gray-50">
                                    <h3
                                        class="text-xs font-bold text-gray-500 uppercase mb-3 border-b border-gray-200 pb-2">
                                        Recently Viewed</h3>
                                    <div id="mobile-recent-list" class="flex overflow-x-auto gap-3 pb-2 scrollbar-hide">
                                    </div>
                                </div>

                                {{-- Live Search Results (Typing State) --}}
                                <div id="mobile-search-results-section">
                                    {{-- Tab Headers --}}
                                    <div class="flex border-b border-gray-300">
                                        <button type="button" data-tab="devices"
                                            class="mobile-search-tab flex-1 py-3 text-xs font-bold uppercase bg-[#045cb4] text-white border-r border-white flex items-center justify-center gap-2">
                                            <i class="fas fa-mobile-alt"></i> Devices
                                        </button>
                                        <button type="button" data-tab="reviews"
                                            class="mobile-search-tab flex-1 py-3 text-xs font-bold uppercase bg-[#e0e0e0] text-gray-700 border-r border-white flex items-center justify-center gap-2">
                                            <i class="fas fa-star-half-alt"></i> Reviews
                                        </button>
                                        <button type="button" data-tab="news"
                                            class="mobile-search-tab flex-1 py-3 text-xs font-bold uppercase bg-[#e0e0e0] text-gray-700 flex items-center justify-center gap-2">
                                            <i class="fas fa-newspaper"></i> News
                                        </button>
                                    </div>

                                    {{-- Tab Content Areas --}}
                                    <div class="p-3">
                                        {{-- Devices Tab Content --}}
                                        <div id="mobile-tab-devices" class="mobile-tab-content">
                                            <div id="mobile-results-devices" class="flex flex-col gap-2"></div>
                                        </div>

                                        {{-- Reviews Tab Content --}}
                                        <div id="mobile-tab-reviews" class="mobile-tab-content hidden">
                                            <div id="mobile-results-reviews" class="flex flex-col gap-2"></div>
                                        </div>

                                        {{-- News Tab Content --}}
                                        <div id="mobile-tab-news" class="mobile-tab-content hidden">
                                            <div id="mobile-results-news" class="flex flex-col gap-2"></div>
                                        </div>
                                    </div>
                                </div>

                                <div id="mobile-search-loading" class="hidden py-10 text-center">
                                    <i class="fas fa-spinner fa-spin text-gray-300 text-2xl"></i>
                                </div>

                                <div id="mobile-search-empty" class="hidden py-4 text-center text-gray-400 italic">
                                    No results match your search.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="mobile-dropdown" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                <div class="bg-[#033d7c] px-5">
                    <div class="flex items-center justify-center gap-7 pt-5">
                        @php
                            $icons = [
                                'fa-brands fa-facebook-f',
                                'fab fa-instagram',
                                'fab fa-youtube',
                                'fa-brands fa-tiktok',
                            ];
                        @endphp

                        @foreach ($icons as $icon)
                            <a href="#"
                                class="flex flex-col w-[31px] h-[31px] rounded-full items-center justify-center text-white text-[11px] transition-all bg-[#F9A13D]">
                                <i class="{{ $icon }} text-[16px]"></i>
                            </a>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-3 gap-10 py-4 text-white">
                        <div class="flex flex-col items-start">
                            <a href="" class="uppercase">Home</a>
                            <a href="" class="uppercase">Compare</a>
                            <a href="" class="uppercase">Tip Us</a>
                            <a href="" class="uppercase">Privacy</a>
                        </div>
                        <div class="flex flex-col items-start border-l border-l-[#F9A13D] pl-5">
                            <a href="" class="uppercase">News</a>
                            <a href="" class="uppercase">Daily Deals</a>
                            <a href="" class="uppercase">Rumor Mill</a>
                            <a href="" class="uppercase">Merch</a>
                        </div>
                        <div class="flex flex-col items-start border-l border-l-[#F9A13D] pl-5">
                            <a href="" class="uppercase">Reviews</a>
                            <a href="" class="uppercase">Glossary</a>
                            <a href="" class="uppercase">Coverage</a>
                            <a href="" class="uppercase">Contact Us</a>
                        </div>
                    </div>
                    @php
                        $brands = App\Models\Brand::active()->orderBy('name')->get();
                        $limitedBrands = $brands->take(12); // 4 columns × 9 rows (like GSM)
                        $brandColumns = $limitedBrands->chunk(4);
                    @endphp
                    <div class="grid grid-cols-4 gap-10 py-4 text-white">
                        @foreach ($brandColumns as $column)
                            <div
                                class="flex flex-col items-start {{ !$loop->first ? 'border-l border-l-[#F9A13D] pl-5' : '' }}">
                                @foreach ($column as $brand)
                                    <a href="{{ route('brand.devices', ['slug' => $brand->slug]) }}" class="uppercase">
                                        {{ strtoupper($brand->name) }}
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

            <nav id="mobile-nav" class="bg-[#8ac4ff] z-10 relative text-white flex items-center justify-between px-3">
                @php
                    $menu = [
                        'home' => 'HOME',
                        'news' => 'NEWS',
                        'reviews' => 'REVIEWS',
                        'videos' => 'VIDEOS',
                    ];
                @endphp

                @foreach ($menu as $key => $item)
                    <a href="{{ route($key) }}"
                        class="relative block py-3 px-2 font-bold uppercase text-[12px] hover:text-[#F9A13D]">
                        {{ $item }}
                        @if ($item === 'MERCH')
                            <span class="absolute top-0.5 right-1 bg-[#ff6b6b] text-white text-[8px] px-1 py-[1px] rounded">
                                NEW
                            </span>
                        @endif
                    </a>
                @endforeach
                <button id="mobile-search-btn" type="button" class="bg-[#F9A13D] rounded-sm px-2 py-2"><i
                        class="fas fa-search"></i></button>
            </nav>
        </div>

        <!-- Mobile Slider (Drop-in) -->
        <div class="lg:hidden w-full flex justify-center bg-[#e9e9e9] py-8">
            <div class="relative overflow-hidden w-[320px] h-[100px] rounded">
                <div id="specMob-slider" class="flex transition-transform duration-500 ease-out">
                    @php($homeReviewSliders = \App\Models\HomeReviewSlider::latest()->get())
                    @foreach ($homeReviewSliders as $slider)
                        <a href="{{ $slider->review_link }}" target="_blank" class="flex-none w-[320px] h-[100px] block">
                            <img src="{{ Storage::url($slider->image) }}" class="w-full h-full object-cover"
                                alt="Review Slide">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Mobile Homepage SECTION -->
        {{-- Tablets & Mobile CONTENT & FOOTER --}}
        <div class="lg:hidden">
            @yield('news_content')

            @if(View::hasSection('mobile-home-section'))
                @yield('mobile-home-section')
            @else
                <div class="px-2">
                    @yield('content')
                </div>
            @endif

            {{-- Tablets & Mobile FOOTER --}}

            <footer class="mt-8 text-white">
                <div class="bg-[#cfcfcf] text-[#757575] flex items-center justify-between px-14 py-1">
                    <a href="{{ route('home') }}"
                        class="flex justify-center items-center font-bold uppercase text-[15px] hover:text-[#F9A13D]">
                        HOME
                    </a>
                    <a href="{{ route('news') }}"
                        class="flex justify-center items-center font-bold uppercase text-[15px] hover:text-[#F9A13D]">
                        news
                    </a>
                    <a href="{{ route('reviews') }}"
                        class="flex justify-center items-center font-bold uppercase text-[15px] hover:text-[#F9A13D]">
                        reviews
                    </a>
                    <a href="{{ route('brands.all') }}"
                        class="flex justify-center items-center font-bold uppercase text-[15px] hover:text-[#F9A13D]">
                        all phones
                    </a>
                </div>
                <div class="bg-black py-4">
                    <!-- Social Links -->
                    <div class="flex justify-center flex-wrap gap-6 mb-8">
                        @php(
                            $socials = [
                                ['icon' => 'fa-facebook-f', 'prefix' => 'fa-brands', 'url' => "https://www.facebook.com/profile.php?id=61573093576333"],
                                ['icon' => 'fa-instagram', 'prefix' => 'fa-brands', 'url' => "https://www.instagram.com/specs_mob/"],
                                ['icon' => 'fa-tiktok', 'prefix' => 'fa-brands', 'url' => "https://www.tiktok.com/@specs.mob?_t=ZS-8vTFUJxpFTO&_r=1"],
                                ['icon' => 'fa-youtube', 'prefix' => 'fa-brands', 'url' => "https://www.youtube.com/@SpecsMob"]
                            ]
                        )
                        @foreach ($socials as $social)
                            <a href="{{ $social['url'] }}"
                                class="w-10 h-10 rounded-full bg-[#F9A13D] flex items-center justify-center text-white text-lg">
                                <i class="{{ $social['prefix'] }} {{ $social['icon'] }}"></i>
                            </a>
                        @endforeach
                    </div>

                    <!-- Final info bar -->
                    <div class=" mt-4 text-center">
                        <p class="text-[11px] text-gray-300 mb-2">
                            © 2000-2026 SpecMob. All rights reserved.
                        </p>
                        <div class="flex justify-center gap-4 text-[10px] font-bold uppercase">
                            <a href="#" class="hover:text-white font-bold text-[14px]">Privacy</a>
                            <a href="#" class="hover:text-white font-bold text-[14px]">Terms</a>
                            <a href="#" class="hover:text-white font-bold text-[14px]">Glossary</a>
                            <a href="#" class="hover:text-white font-bold text-[14px]">Contact Us</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        {{-- MAIN WRAPPER --}}
        <div class="max-w-[1060px] hidden lg:flex mx-auto flex-col md:flex-row gap-5 pt-5 bg-white">
            {{-- SIDEBAR --}}
            <aside class="w-full md:w-[30%] px-4 md:px-0">
                {{-- ✅ COMMON SIDEBAR (shared on all pages) --}}
                @include('partials.sidebar-common')

                {{-- ✅ PAGE-SPECIFIC SIDEBAR (different per page) --}}
                @yield('sidebar')
            </aside>

            {{-- MAIN CONTENT --}}
            <main class="w-full md:w-[70%] overflow-x-auto">
                @yield('content')
            </main>
        </div>



    </div>


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        new Swiper(".featuredNewsSwiper", {
            slidesPerView: 2.5,
            spaceBetween: 8,
            autoHeight: true,

            scrollbar: {
                el: ".swiper-scrollbar",
                draggable: true,
            },

            breakpoints: {
                640: {
                    slidesPerView: 3.2
                },
                768: {
                    slidesPerView: 4
                },
                1024: {
                    slidesPerView: 5
                }
            }
        });
    </script>


    <!-- Mobile Slider (Drop-in) -->
    <script>
        let currentSlide = 0;

        const slider = document.getElementById("specMob-slider");
        const slides = slider.children;
        const totalSlides = slides.length;

        let slideWidth = slides[0].offsetWidth;

        function goToSlide(index) {
            slider.style.transform = `translateX(-${index * slideWidth}px)`;
        }

        // Auto Slide
        setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            goToSlide(currentSlide);
        }, 4000);

        // Swipe Support
        let startX = 0;

        slider.addEventListener("touchstart", e => {
            startX = e.touches[0].clientX;
        });

        slider.addEventListener("touchend", e => {
            let endX = e.changedTouches[0].clientX;

            if (startX - endX > 50) {
                currentSlide = (currentSlide + 1) % totalSlides;
            } else if (endX - startX > 50) {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            }

            goToSlide(currentSlide);
        });

        // Recalculate width on orientation change / resize (important for mobile)
        window.addEventListener("resize", () => {
            slideWidth = slides[0].offsetWidth;
            goToSlide(currentSlide);
        });
    </script>

    {{-- Menu toggle script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('menu-toggle');
            const nav = document.getElementById('main-nav');
            const icon = document.getElementById('menu-icon');

            if (!toggleBtn || !nav || !icon) return;

            toggleBtn.addEventListener('click', () => {
                nav.classList.toggle('hidden');

                if (icon.classList.contains('fa-bars')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-minus');
                } else {
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-bars');
                }
            });
        });
    </script>

    {{-- Login popup toggle script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('login-toggle');
            const popup = document.getElementById('login-popup');

            if (!toggle || !popup) return;

            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                popup.classList.toggle('hidden');
            });

            document.addEventListener('click', function (e) {
                if (!popup.contains(e.target) && e.target !== toggle) {
                    popup.classList.add('hidden');
                }
            });
        });
    </script>

    <!-- Mobile header -->
    <script>
        const btn = document.getElementById("mobile-menu-btn");
        const menu = document.getElementById("mobile-dropdown");
        const icon = document.getElementById("mobile-menu-icon");
        const nav = document.getElementById("mobile-nav");

        btn.addEventListener("click", () => {

            // If closed → open
            if (menu.classList.contains("max-h-0")) {
                menu.classList.remove("max-h-0");
                menu.classList.add("max-h-screen");
                nav.classList.add("opacity-0"); // hide nav visually
                nav.classList.add("pointer-events-none");

                icon.classList.remove("fa-bars");
                icon.classList.add("fa-minus");
            }

            // If open → close
            else {
                menu.classList.remove("max-h-screen");
                menu.classList.add("max-h-0");
                nav.classList.remove("opacity-0");
                nav.classList.remove("pointer-events-none");

                icon.classList.remove("fa-minus");
                icon.classList.add("fa-bars");
            }
        });
    </script>

    <!-- Mobile Search Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileSearchBtn = document.getElementById('mobile-search-btn');
            const mobileSearchSection = document.getElementById('show-mobile-search');
            const mobileSearchInput = mobileSearchSection?.querySelector('#search-input');
            const mobileSearchDropdown = document.getElementById('mobile-search-dropdown');
            const mobileResultsSection = document.getElementById('mobile-search-results-section');
            const mobileResultsDevices = document.getElementById('mobile-results-devices');
            const mobileResultsReviews = document.getElementById('mobile-results-reviews');
            const mobileResultsNews = document.getElementById('mobile-results-news');
            const mobileLoading = document.getElementById('mobile-search-loading');
            const mobileEmpty = document.getElementById('mobile-search-empty');

            const mobileSearchCloseBtn = document.getElementById('mobile-search-close');

            const mobileTopBar = document.getElementById('mobile-top-bar');

            // Recently Viewed Elements
            const mobileRecentSection = document.getElementById('mobile-recently-viewed-section');
            const mobileRecentList = document.getElementById('mobile-recent-list');

            let debounceTimer;
            let isSearchOpen = false;

            // --- FUNCTION: Render Mobile Recent Items ---
            function renderMobileRecent() {
                const viewedDevices = JSON.parse(localStorage.getItem('recently_viewed_devices') || '[]');

                if (viewedDevices.length > 0 && mobileRecentList) {
                    mobileRecentList.innerHTML = '';
                    viewedDevices.forEach(device => {
                        const div = document.createElement('div');
                        div.className =
                            'flex flex-col items-center min-w-[70px] w-[70px] cursor-pointer group';
                        div.onclick = () => window.location.href = `/devices/show/${device.slug}`;
                        div.innerHTML = `
                            <div class="h-16 w-16 flex items-center justify-center p-1 bg-white border border-gray-200 rounded-lg shadow-sm group-hover:border-[#d50000] transition-colors">
                                <img src="${device.img}" class="max-h-full max-w-full object-contain">
                            </div>
                            <span class="text-[9px] font-bold text-gray-600 mt-1 line-clamp-2 text-center leading-tight group-hover:text-[#d50000]">${device.name}</span>
                        `;
                        mobileRecentList.appendChild(div);
                    });

                    if (mobileSearchDropdown) mobileSearchDropdown.classList.remove('hidden');
                    if (mobileRecentSection) mobileRecentSection.classList.remove('hidden');
                    if (mobileResultsSection) mobileResultsSection.classList.add('hidden');
                    if (mobileLoading) mobileLoading.classList.add('hidden');
                    if (mobileEmpty) mobileEmpty.classList.add('hidden');
                } else {
                    // No recent items? Maybe keep dropdown hidden until typing
                    if (mobileRecentSection) mobileRecentSection.classList.add('hidden');
                }
            }

            // --- CLOSE SEARCH FUNCTION ---
            function closeMobileSearch() {
                isSearchOpen = false;

                // Show Top Bar
                if (mobileTopBar) mobileTopBar.classList.remove('hidden');

                // Hide Search Section
                if (mobileSearchSection) {
                    mobileSearchSection.classList.add('hidden');
                    mobileSearchSection.classList.remove('flex');
                }

                // Hide Dropdown
                if (mobileSearchDropdown) mobileSearchDropdown.classList.add('hidden');

                // Clear Input
                if (mobileSearchInput) mobileSearchInput.value = '';
            }

            // Tab switching functionality
            const mobileTabs = document.querySelectorAll('.mobile-search-tab');
            const mobileTabContents = document.querySelectorAll('.mobile-tab-content');

            mobileTabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    const tabName = this.getAttribute('data-tab');

                    // Remove active state from all tabs
                    mobileTabs.forEach(t => {
                        t.classList.remove('bg-[#045cb4]', 'text-white');
                        t.classList.add('bg-[#e0e0e0]', 'text-gray-700');
                    });

                    // Add active state to clicked tab
                    this.classList.remove('bg-[#e0e0e0]', 'text-gray-700');
                    this.classList.add('bg-[#045cb4]', 'text-white');

                    // Hide all tab contents
                    mobileTabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Show the selected tab content
                    document.getElementById(`mobile-tab-${tabName}`).classList.remove('hidden');
                });
            });

            if (mobileSearchBtn && mobileSearchSection) {
                // OPEN / TOGGLE Search
                mobileSearchBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    isSearchOpen = !isSearchOpen;

                    if (isSearchOpen) {
                        // Hide Top Bar
                        if (mobileTopBar) mobileTopBar.classList.add('hidden');

                        // Show search section
                        mobileSearchSection.classList.remove('hidden');
                        mobileSearchSection.classList.add('flex');

                        // Show Recent Items immediately if input is empty
                        if (!mobileSearchInput.value.trim()) {
                            renderMobileRecent();
                        }

                        // Focus on input
                        setTimeout(() => {
                            if (mobileSearchInput) mobileSearchInput.focus();
                        }, 100);
                    } else {
                        closeMobileSearch();
                    }
                });

                // CLOSE Button Handler
                if (mobileSearchCloseBtn) {
                    mobileSearchCloseBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        closeMobileSearch();
                    });
                }

                // Mobile search input handler
                if (mobileSearchInput) {
                    mobileSearchInput.addEventListener('input', function () {
                        const query = this.value.trim();
                        clearTimeout(debounceTimer);

                        if (query === '') {
                            // If input cleared, show recent items again
                            renderMobileRecent();
                            return;
                        } else {
                            // Hide recent items when typing
                            if (mobileRecentSection) mobileRecentSection.classList.add('hidden');
                        }

                        debounceTimer = setTimeout(() => performMobileSearch(query), 300);
                    });
                }
            }

            async function performMobileSearch(query) {
                if (!mobileSearchDropdown) return;

                mobileSearchDropdown.classList.remove('hidden');
                if (mobileResultsSection) mobileResultsSection.classList.add('hidden');
                if (mobileLoading) mobileLoading.classList.remove('hidden');
                if (mobileEmpty) mobileEmpty.classList.add('hidden');

                try {
                    const response = await fetch(`/live-search?q=${encodeURIComponent(query)}`);
                    const data = await response.json();

                    if (mobileLoading) mobileLoading.classList.add('hidden');

                    if (data.devices?.length === 0 && data.reviews?.length === 0 && data.news?.length === 0) {
                        if (mobileEmpty) mobileEmpty.classList.remove('hidden');
                        return;
                    }

                    if (mobileResultsSection) mobileResultsSection.classList.remove('hidden');

                    // Render Devices (Tab 1)
                    if (mobileResultsDevices) {
                        mobileResultsDevices.innerHTML = data.devices.length ? '' :
                            '<p class="text-[11px] text-gray-400 italic py-4 text-center">No devices found</p>';
                        data.devices.forEach(device => {
                            mobileResultsDevices.innerHTML += `
                                <a href="/devices/show/${device.slug}" class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded border-b border-gray-100 last:border-0">
                                    <div class="w-12 h-14 flex items-center justify-center p-1 bg-white border border-gray-200 rounded flex-none">
                                        <img src="${device.thumbnail_url}" class="max-h-full object-contain">
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-[12px] font-bold text-gray-800 block">${device.name}</span>
                                    </div>
                                </a>
                            `;
                        });
                    }

                    // Render Reviews (Tab 2)
                    if (mobileResultsReviews) {
                        mobileResultsReviews.innerHTML = data.reviews.length ? '' :
                            '<p class="text-[11px] text-gray-400 italic py-4 text-center">No reviews found</p>';
                        data.reviews.forEach(review => {
                            mobileResultsReviews.innerHTML += `
                                <a href="/reviews/show/${review.slug}" class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded border-b border-gray-100 last:border-0">
                                    <div class="w-12 h-12 bg-gray-100 rounded flex-none overflow-hidden">
                                        <img src="${review.cover_image_url}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-[12px] font-bold text-gray-800 line-clamp-2">${review.title}</span>
                                    </div>
                                </a>
                            `;
                        });
                    }

                    // Render News (Tab 3)
                    if (mobileResultsNews) {
                        mobileResultsNews.innerHTML = data.news.length ? '' :
                            '<p class="text-[11px] text-gray-400 italic py-4 text-center">No news found</p>';
                        data.news.forEach(article => {
                            mobileResultsNews.innerHTML += `
                                <a href="/articles/show/${article.slug}/${article.type}" class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded border-b border-gray-100 last:border-0">
                                    <div class="w-12 h-12 bg-gray-100 rounded flex-none overflow-hidden">
                                        <img src="${article.thumbnail_url}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-[12px] font-bold text-gray-800 line-clamp-2">${article.title}</span>
                                    </div>
                                </a>
                            `;
                        });
                    }

                } catch (error) {
                    console.error('Mobile search error:', error);
                    if (mobileLoading) mobileLoading.classList.add('hidden');
                }
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (mobileSearchDropdown &&
                    !mobileSearchDropdown.contains(e.target) &&
                    !mobileSearchSection?.contains(e.target) &&
                    e.target !== mobileSearchBtn) {
                    mobileSearchDropdown.classList.add('hidden');
                }
            });
        });
    </script>



    {{-- Global Search & Recently Viewed Tracking --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-input');
            const searchDropdown = document.getElementById('search-dropdown');
            const searchForm = document.getElementById('global-search-form');
            const recentSection = document.getElementById('recently-viewed-section');
            const recentList = document.getElementById('recent-list');
            const resultsSection = document.getElementById('search-results-section');
            const resultsReviews = document.getElementById('results-reviews');
            const resultsDevices = document.getElementById('results-devices');
            const resultsNews = document.getElementById('results-news');
            const loading = document.getElementById('search-loading');
            const empty = document.getElementById('search-empty');

            let debounceTimer;

            // --- TRACKING: Store recently viewed device ---
            @if (isset($device) && isset($device->id) && request()->routeIs('device-detail'))
                let viewedDevices = JSON.parse(localStorage.getItem('recently_viewed_devices') || '[]');
                const currentDevice = {
                    id: {{ $device->id }},
                    name: "{{ $device->name }}",
                    slug: "{{ $device->slug }}",
                    img: "{{ $device->thumbnail_url }}"
                };
                // Remove if already exists and add to front
                viewedDevices = viewedDevices.filter(d => d.id !== currentDevice.id);
                viewedDevices.unshift(currentDevice);
                // Keep only last 12
                if (viewedDevices.length > 12) viewedDevices.pop();
                localStorage.setItem('recently_viewed_devices', JSON.stringify(viewedDevices));
            @endif

            // --- SHOW RECENT ON FOCUS ---
            searchInput.addEventListener('focus', function () {
                const query = this.value.trim();
                if (query === '') {
                    showRecent();
                } else {
                    performSearch(query);
                }
            });

            // --- SEARCH ON INPUT ---
            searchInput.addEventListener('input', function () {
                const query = this.value.trim();
                clearTimeout(debounceTimer);

                if (query === '') {
                    showRecent();
                    return;
                }

                debounceTimer = setTimeout(() => performSearch(query), 300);
            });

            function showRecent() {
                const viewedDevices = JSON.parse(localStorage.getItem('recently_viewed_devices') || '[]');
                if (viewedDevices.length > 0) {
                    recentList.innerHTML = '';
                    viewedDevices.forEach(device => {
                        const div = document.createElement('div');
                        div.className = 'flex flex-col items-center text-center group cursor-pointer';
                        div.onclick = () => window.location.href = `/devices/show/${device.slug}`;
                        div.innerHTML = `
                            <div class="h-20 w-full flex items-center justify-center p-2 bg-gray-50 rounded group-hover:bg-gray-100 transition-all">
                                <img src="${device.img}" class="max-h-full object-contain">
                            </div>
                            <span class="text-[10px] font-bold text-gray-600 mt-1 line-clamp-1 group-hover:text-[#d50000]">${device.name}</span>
                        `;
                        recentList.appendChild(div);
                    });

                    searchDropdown.classList.remove('hidden');
                    recentSection.classList.remove('hidden');
                    resultsSection.classList.add('hidden');
                    loading.classList.add('hidden');
                    empty.classList.add('hidden');
                } else {
                    searchDropdown.classList.add('hidden');
                }
            }

            async function performSearch(query) {
                searchDropdown.classList.remove('hidden');
                recentSection.classList.add('hidden');
                resultsSection.classList.add('hidden');
                loading.classList.remove('hidden');
                empty.classList.add('hidden');

                try {
                    const response = await fetch(`/live-search?q=${encodeURIComponent(query)}`);
                    const data = await response.json();

                    loading.classList.add('hidden');

                    if (data.devices?.length === 0 && data.reviews?.length === 0 && data.news?.length === 0) {
                        empty.classList.remove('hidden');
                        return;
                    }

                    resultsSection.classList.remove('hidden');

                    // Render Reviews
                    resultsReviews.innerHTML = data.reviews.length ? '' :
                        '<p class="text-[11px] text-gray-400 italic">No reviews found</p>';
                    data.reviews.forEach(review => {
                        resultsReviews.innerHTML += `
                            <a href="/reviews/show/${review.slug}" class="flex items-center gap-2 p-1 hover:bg-gray-50 rounded group border-b border-gray-50 last:border-0 pb-2">
                                <div class="w-10 h-10 bg-gray-100 rounded flex-none"><img src="${review.cover_image_url}" class="w-full h-full object-cover rounded"></div>
                                <span class="text-[11px] font-bold text-gray-700 line-clamp-2 group-hover:text-[#d50000]">${review.title}</span>
                            </a>
                        `;
                    });

                    // Render Devices
                    resultsDevices.innerHTML = data.devices.length ? '' :
                        '<p class="text-[11px] text-gray-400 italic">No devices found</p>';
                    data.devices.forEach(device => {
                        resultsDevices.innerHTML += `
                            <a href="/devices/show/${device.slug}" class="flex items-center gap-2 p-1 hover:bg-gray-50 rounded group border-b border-gray-50 last:border-0 pb-2">
                                <div class="w-10 h-12 flex items-center justify-center p-1 bg-white border border-gray-100 rounded flex-none"><img src="${device.thumbnail_url}" class="max-h-full object-contain"></div>
                                <span class="text-[11px] font-bold text-gray-700 group-hover:text-[#d50000]">${device.name}</span>
                            </a>
                        `;
                    });

                    // Render News
                    resultsNews.innerHTML = data.news.length ? '' :
                        '<p class="text-[11px] text-gray-400 italic">No news found</p>';
                    data.news.forEach(article => {
                        resultsNews.innerHTML += `
                            <a href="/articles/show/${article.slug}/${article.type}" class="flex items-center gap-2 p-1 hover:bg-gray-50 rounded group border-b border-gray-50 last:border-0 pb-2">
                                <div class="w-10 h-10 bg-gray-100 rounded flex-none"><img src="${article.thumbnail_url}" class="w-full h-full object-cover rounded"></div>
                                <span class="text-[11px] font-bold text-gray-700 line-clamp-2 group-hover:text-[#d50000]">${article.title}</span>
                            </a>
                        `;
                    });

                } catch (error) {
                    console.error('Search error:', error);
                    loading.classList.add('hidden');
                }
            }

            // --- HIDE ON OUTSIDE CLICK ---
            document.addEventListener('click', function (e) {
                if (!searchDropdown.contains(e.target) && e.target !== searchInput) {
                    searchDropdown.classList.add('hidden');
                }
            });
        });

        // Global function to toggle reply forms
        function toggleReplyForm(commentId) {
            console.log('Toggling reply form for:', commentId);
            const form = document.getElementById(`reply-form-${commentId}`);

            if (form) {
                form.classList.toggle('hidden');
                if (!form.classList.contains('hidden')) {
                    const textarea = form.querySelector('textarea');
                    if (textarea) textarea.focus();
                }
            } else {
                // If form is missing, the user is likely a guest
                console.log('Reply form not found. User might be guest. Initiating login flow.');

                // Attempt to open the login popup
                const loginToggle = document.getElementById('login-toggle');
                if (loginToggle) {
                    loginToggle.click();
                    // Scroll to top to ensure popup is seen if needed, though popup is fixed/absolute
                    // window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    // Fallback to login page if popup button not found
                    console.log('Login toggle not found, redirecting to login page.');
                    window.location.href = "{{ route('login') }}";
                }
            }
        }
    </script>

    @stack('scripts')
    {!! ToastMagic::scripts() !!}
</body>

</html>