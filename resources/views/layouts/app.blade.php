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

<body class="bg-[#efebe9] font-['Arimo',Arial,sans-serif] text-[13px] overflow-x-hidden">
<div id="app">
        <div class="bg-[#9e9e9e]">
            <div class="bg-[#555555] max-w-[1060px] mx-auto px-4 py-1.5">

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
                    <div class="flex-1 flex justify-center">
                        <div class="w-full max-w-[350px]">
                            <div class="flex bg-[#9e9e9e] overflow-hidden">
                                <input type="text" placeholder="Search"
                                    class="flex-1 bg-transparent border-none px-3 py-2 text-white placeholder:text-white/70 focus:outline-none" />
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT SECTION --}}
                    <div class="flex items-center gap-3 flex-none">
                        @php
                            $icons = [
                                'fas fa-lightbulb',
                                'fab fa-youtube',
                                'fab fa-instagram',
                                'fas fa-rss',
                                'fas fa-car',
                                'fas fa-shopping-bag',
                            ];
                        @endphp

                        @foreach ($icons as $icon)
                            <a href="#"
                                class="flex flex-col items-center gap-1 text-white text-[11px] transition-all hover:bg-red-600 hover:py-1 hover:px-2 rounded">
                                <i class="{{ $icon }} text-[20px]"></i>
                            </a>
                        @endforeach
                        {{-- AUTH ICONS --}}
                        @guest
                            {{-- Login --}}
                            <a href="javascript:void(0)" id="login-toggle" title="Login"
                                class="flex flex-col items-center gap-1 text-white text-[11px] hover:bg-red-600 hover:py-1 hover:px-2 rounded">
                                <i class="fas fa-sign-in-alt text-[20px]"></i>
                            </a>

                            {{-- Register --}}
                            <a href="{{ route('register') }}" title="Sign Up"
                                class="flex flex-col items-center gap-1 text-white text-[11px] hover:bg-red-600 hover:py-1 hover:px-2 rounded">
                                <i class="fas fa-user-plus text-[20px]"></i>
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
                                class="flex flex-col items-center gap-1 text-white text-[11px] hover:bg-red-600 hover:py-1 hover:px-2 rounded">
                                <i class="fas fa-user text-[20px]"></i>
                            </a>
                            {{-- Logout (POST required) --}}
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" title="Logout"
                                    class="flex flex-col items-center gap-1 text-white text-[11px] hover:bg-red-600 hover:py-1 hover:px-2 rounded">
                                    <i class="fas fa-sign-out-alt text-[20px]"></i>
                                </button>
                            </form>
                        @endauth

                    </div>

                </div>
            </div>
        </div>


        {{-- NAV --}}
        <nav id="main-nav" class="md:block hidden">
            <ul class="max-w-[1060px] bg-[#dbd7d7] mx-auto flex flex-wrap">
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
                    <li class="flex-1 text-center min-w-[80px]">
                        <a href="{{ route($key) }}"
                            class="relative block py-3 px-2 text-[#555555] font-bold uppercase text-[12px] hover:bg-[#d50000] hover:text-white">
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

    @stack('scripts')
    {!! ToastMagic::scripts() !!}
</body>

</html>