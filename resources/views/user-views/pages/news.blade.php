@extends('layouts.app')

@section('title', 'News')
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
    <x-ranking-table title="Top 10 by fans" value_column_label="Favorites" :items="$topByFans" header_color="#82a2bd"
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

@section('news_content')
    <div class="bg-white rounded shadow mb-4 lg:hidden">
        <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
            News
        </h4>

        {{-- Mobile Search Bar --}}
        <form action="{{ route('news') }}" method="GET" class="flex items-center gap-3 px-3 w-full mb-4">
            <input type="search" name="q" value="{{ request('q') }}"
                class="flex-1 border border-[#dcd9d9] py-2 px-3 rounded focus:outline-none focus:ring-1 focus:ring-[#F9A13D]"
                placeholder="Search news...">
            <button type="submit"
                class="bg-[#F9A13D] px-6 py-2 rounded text-white font-bold hover:bg-[#e8942b] transition-colors flex-none">
                Go
            </button>
        </form>

        <div class="px-3">
            @forelse ($news_articles as $article)
                <x-article :article="$article" />
            @empty
                <div class="bg-white p-10 text-center text-gray-500 italic">
                    No news articles found
                    @if (request('tag') || request('q'))
                        matching your criteria.
                    @else
                        yet.
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Mobile Custom Pagination --}}
        @if ($news_articles->hasPages())
            <div class="flex items-center justify-center gap-2 py-6 px-2 bg-[#f6f6f6] border-t border-gray-200 mt-4 rounded-b">
                {{-- Scroll to Top --}}
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                    class="p-2 text-gray-400 hover:text-[#F9A13D] transition-colors">
                    <i class="fa-solid fa-angles-up"></i>
                </button>

                {{-- First Page --}}
                <a href="{{ $news_articles->url(1) }}"
                    class="p-2 text-gray-400 hover:text-[#F9A13D] transition-colors {{ $news_articles->onFirstPage() ? 'opacity-30 pointer-events-none' : '' }}">
                    <i class="fa-solid fa-backward-step"></i>
                </a>

                {{-- Previous Page --}}
                <a href="{{ $news_articles->previousPageUrl() }}"
                    class="flex items-center justify-center w-9 h-9 rounded-full border border-gray-300 text-gray-500 hover:border-[#F9A13D] hover:text-[#F9A13D] transition-all {{ $news_articles->onFirstPage() ? 'opacity-30 pointer-events-none' : '' }}">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>

                {{-- Current of Total --}}
                <div class="flex flex-col items-center px-2 min-w-[60px]">
                    <span class="font-bold text-base leading-tight">{{ $news_articles->currentPage() }}</span>
                    <span class="text-[9px] text-gray-400 uppercase tracking-tighter">of {{ $news_articles->lastPage() }}</span>
                </div>

                {{-- Next Page --}}
                <a href="{{ $news_articles->nextPageUrl() }}"
                    class="flex items-center justify-center w-9 h-9 rounded-full border border-gray-300 text-gray-500 hover:border-[#F9A13D] hover:text-[#F9A13D] transition-all {{ !$news_articles->hasMorePages() ? 'opacity-30 pointer-events-none' : '' }}">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>

                {{-- Last Page --}}
                <a href="{{ $news_articles->url($news_articles->lastPage()) }}"
                    class="p-2 text-gray-400 hover:text-[#F9A13D] transition-colors {{ !$news_articles->hasMorePages() ? 'opacity-30 pointer-events-none' : '' }}">
                    <i class="fa-solid fa-forward-step"></i>
                </a>

                {{-- Scroll to Bottom --}}
                <button onclick="window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'})"
                    class="p-2 text-gray-400 hover:text-[#F9A13D] transition-colors">
                    <i class="fa-solid fa-angles-down"></i>
                </button>
            </div>
        @endif
    </div>
@endsection

@section('content')
    <section class="w-full mb-6 md:h-[310px] hidden lg:block">
        <div class="max-w-[1060px] mx-auto overflow-hidden h-full bg-black text-white">
            <div class="relative bg-cover bg-center h-full"
                style="background-image: url('https://fdn.gsmarena.com/imgroot/static/headers/news-hlr.jpg');">
                {{-- Dark overlay for contrast --}}
                <div class="bg-black/55 h-full">
                    <div class="px-4 md:px-6 pt-4 pb-6 space-y-4 h-full flex flex-col justify-between">

                        {{-- Top: Popular tags row --}}
                        <div class="relative border-b border-white/15 pb-3">
                            <span
                                class="inline-flex items-center text-[11px] font-semibold uppercase tracking-[0.18em] bg-black/50 px-3 py-1 rounded-full mr-3">
                                Popular tags
                            </span>

                            <ul class="mt-2 md:mt-0 inline-flex flex-wrap gap-2 align-middle">
                                @foreach ($tags as $tag)
                                    <li>
                                        <a href="{{ route('news', ['tag' => $tag->slug]) }}"
                                            class="inline-block text-[11px] px-3 py-1 rounded-full bg-black/40 border border-white/15 hover:bg-[#F9A13D] hover:border-[#F9A13D] transition-colors">
                                            {{ $tag->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Bottom: Search band --}}
                        <div class="border-t border-white/15 pt-3">
                            <h1 class="text-3xl mb-3 md:text-4xl font-bold tracking-tight drop-shadow-md">
                                @if(request('tag'))
                                    Articles tagged "{{ request('tag') }}"
                                @elseif(request('q'))
                                    Search results for "{{ request('q') }}"
                                @else
                                    News
                                @endif
                            </h1>
                            <form method="GET" action="{{ route('news') ?? url()->current() }}"
                                class="flex flex-col sm:flex-row gap-2 sm:items-center">
                                <label class="flex-1 text-[12px] flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                                    <span class="font-semibold whitespace-nowrap">
                                        Search for
                                    </span>
                                    <input type="text" name="q" maxlength="50" value="{{ request('q') }}"
                                        placeholder="Device, brand or keyword"
                                        class="flex-1 px-3 py-1.5 text-[13px] rounded bg-white/95 text-[#333] placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#F9A13D]" />
                                </label>

                                <button type="submit"
                                    class="self-start sm:self-auto px-4 py-1.5 text-[12px] font-semibold uppercase rounded bg-[#F9A13D] hover:bg-[#b20000] text-white tracking-[0.15em]">
                                    Search
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="articles hidden lg:flex bg-[#e7e4e4] my-5 flex-col gap-3 p-2 md:p-3">
        @forelse ($news_articles as $article)
            <x-article :article="$article" />
        @empty
            <div class="bg-white p-10 text-center text-gray-500 italic">
                No news articles found
                @if(request('tag') || request('q'))
                    matching your criteria.
                @else
                    yet.
                @endif
            </div>
        @endforelse
    </div>

    {{-- Desktop Standard Pagination (Hidden on mobile) --}}
    <div class="mt-6 mb-10 px-2 lg:px-0 hidden lg:block">
        {{ $news_articles->links() }}
    </div>
@endsection