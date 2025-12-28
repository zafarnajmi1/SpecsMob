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

</head>

<body class="bg-white font-['Arimo',Arial,sans-serif] text-[13px] overflow-x-hidden">
    <div id="app">
        <div>
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
                                <span class="absolute top-0.5 right-1 bg-[#ff6b6b] text-white text-[8px] px-1 py-[1px] rounded">
                                    NEW
                                </span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- MAIN WRAPPER --}}
        <div class="max-w-[1060px] mx-auto flex flex-col md:flex-row gap-5 pt-5 bg-white">
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
            @if(isset($device) && isset($device->id) && request()->routeIs('device-detail'))
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
                    resultsReviews.innerHTML = data.reviews.length ? '' : '<p class="text-[11px] text-gray-400 italic">No reviews found</p>';
                    data.reviews.forEach(review => {
                        resultsReviews.innerHTML += `
                            <a href="/reviews/show/${review.slug}" class="flex items-center gap-2 p-1 hover:bg-gray-50 rounded group border-b border-gray-50 last:border-0 pb-2">
                                <div class="w-10 h-10 bg-gray-100 rounded flex-none"><img src="${review.cover_image_url}" class="w-full h-full object-cover rounded"></div>
                                <span class="text-[11px] font-bold text-gray-700 line-clamp-2 group-hover:text-[#d50000]">${review.title}</span>
                            </a>
                        `;
                    });

                    // Render Devices
                    resultsDevices.innerHTML = data.devices.length ? '' : '<p class="text-[11px] text-gray-400 italic">No devices found</p>';
                    data.devices.forEach(device => {
                        resultsDevices.innerHTML += `
                            <a href="/devices/show/${device.slug}" class="flex items-center gap-2 p-1 hover:bg-gray-50 rounded group border-b border-gray-50 last:border-0 pb-2">
                                <div class="w-10 h-12 flex items-center justify-center p-1 bg-white border border-gray-100 rounded flex-none"><img src="${device.thumbnail_url}" class="max-h-full object-contain"></div>
                                <span class="text-[11px] font-bold text-gray-700 group-hover:text-[#d50000]">${device.name}</span>
                            </a>
                        `;
                    });

                    // Render News
                    resultsNews.innerHTML = data.news.length ? '' : '<p class="text-[11px] text-gray-400 italic">No news found</p>';
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