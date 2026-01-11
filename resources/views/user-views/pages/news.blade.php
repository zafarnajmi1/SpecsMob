@extends('layouts.app')

@section('title', 'News')
@section('sidebar')
    @include('partials.aside')
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
                                    class="cursor-pointer self-start sm:self-auto px-4 py-1.5 text-[12px] font-semibold uppercase rounded bg-[#F9A13D] hover:bg-[#f7992d] text-white tracking-[0.15em]">
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