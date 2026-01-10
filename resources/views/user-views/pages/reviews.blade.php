@extends('layouts.app')
@section('title', 'Reviews')

@section('sidebar')
    @include('partials.aside')
@endsection

@section('reviews_content')
    <div class="bg-white rounded shadow mb-4 lg:hidden">
        <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
            Reviews
        </h4>

        {{-- Mobile Search Bar --}}
        <form action="{{ route('reviews') }}" method="GET" class="flex items-center gap-3 px-3 w-full mb-4">
            <input type="search" name="q" value="{{ request('q') }}"
                class="flex-1 border border-[#dcd9d9] py-2 px-3 rounded focus:outline-none focus:ring-1 focus:ring-[#F9A13D]"
                placeholder="Search reviews...">
            <button type="submit"
                class="bg-[#F9A13D] px-6 py-2 rounded text-white font-bold hover:bg-[#e8942b] transition-colors flex-none">
                Go
            </button>
        </form>

        <div class="px-3 space-y-2">
            @forelse ($reviews_list as $review)
                <article
                    class="flex flex-col p-1 bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-[#e0e0e0]">
                    {{-- Thumbnail --}}
                    <a href="{{ route('review-detail', $review->slug) }}" class="w-full">
                        <img src="{{ $review->cover_image_url }}" alt="{{ $review->title }}"
                            class="w-full h-auto object-cover" />
                    </a>
                    {{-- Content --}}
                    <div class="flex-1 flex flex-col justify-between gap-1">
                        <div>
                            <h3 class="text-[17px] md:text-[19px] font-semibold leading-snug text-[#444]">
                                <a href="{{ route('review-detail', $review->slug) }}" class="hover:text-[#F9A13D]">
                                    {{ $review->title }}
                                </a>
                            </h3>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 text-[12px] text-[#9c9c9c]">
                            <span class="flex items-center gap-1">
                                <i class="far fa-clock"></i>
                                <span>{{ $review->published_at->format('d F Y') }}</span>
                            </span>

                            <a href="{{ route('review-detail', $review->slug) }}#comments"
                                class="flex items-center gap-1 hover:text-[#F9A13D]">
                                <i class="far fa-comment"></i>
                                <span>{{ $review->comments_count }}</span>
                            </a>
                        </div>

                    </div>

                </article>
            @empty
                <div class="col-span-full bg-white p-10 text-center text-gray-500 italic">
                    No reviews found
                    @if(request('tag') || request('q'))
                        matching your criteria.
                    @else
                        yet.
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Mobile Custom Pagination --}}
        @if ($reviews_list->hasPages())
            <div class="flex items-center justify-center gap-2 py-6 px-2 bg-[#f6f6f6] border-t border-gray-200 mt-4 rounded-b">
                {{-- Scroll to Top --}}
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                    class="p-2 text-gray-400 hover:text-[#F9A13D] transition-colors">
                    <i class="fa-solid fa-angles-up"></i>
                </button>

                {{-- First Page --}}
                <a href="{{ $reviews_list->url(1) }}"
                    class="p-2 text-gray-400 hover:text-[#F9A13D] transition-colors {{ $reviews_list->onFirstPage() ? 'opacity-30 pointer-events-none' : '' }}">
                    <i class="fa-solid fa-backward-step"></i>
                </a>

                {{-- Previous Page --}}
                <a href="{{ $reviews_list->previousPageUrl() }}"
                    class="flex items-center justify-center w-9 h-9 rounded-full border border-gray-300 text-gray-500 hover:border-[#F9A13D] hover:text-[#F9A13D] transition-all {{ $reviews_list->onFirstPage() ? 'opacity-30 pointer-events-none' : '' }}">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>

                {{-- Current of Total --}}
                <div class="flex flex-col items-center px-2 min-w-[60px]">
                    <span class="font-bold text-base leading-tight">{{ $reviews_list->currentPage() }}</span>
                    <span class="text-[9px] text-gray-400 uppercase tracking-tighter">of {{ $reviews_list->lastPage() }}</span>
                </div>

                {{-- Next Page --}}
                <a href="{{ $reviews_list->nextPageUrl() }}"
                    class="flex items-center justify-center w-9 h-9 rounded-full border border-gray-300 text-gray-500 hover:border-[#F9A13D] hover:text-[#F9A13D] transition-all {{ !$reviews_list->hasMorePages() ? 'opacity-30 pointer-events-none' : '' }}">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>

                {{-- Last Page --}}
                <a href="{{ $reviews_list->url($reviews_list->lastPage()) }}"
                    class="p-2 text-gray-400 hover:text-[#F9A13D] transition-colors {{ !$reviews_list->hasMorePages() ? 'opacity-30 pointer-events-none' : '' }}">
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
    <section class="hidden lg:block w-full mb-6 md:h-[310px]">
        <div class="max-w-[1060px] mx-auto overflow-hidden h-full bg-black text-white">
            <div class="relative bg-cover bg-center h-full"
                style='background-image: url("{{ asset('user/images/reviews.jpg') }}");'>
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
                                        <a href="{{ route('reviews', ['tag' => $tag->slug]) }}"
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
                                Reviews
                            </h1>
                            <form method="GET" action="{{ route('reviews') }}"
                                class="flex flex-col sm:flex-row gap-2 sm:items-center">
                                <label class="flex-1 text-[12px] flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                                    <span class="font-semibold whitespace-nowrap text-white">
                                        Search for
                                    </span>
                                    <input type="text" name="q" maxlength="50" value="{{ request('q') }}"
                                        placeholder="Device, brand or keyword"
                                        class="flex-1 px-3 py-1.5 text-[13px] rounded bg-white/95 text-[#333] placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#F9A13D]" />
                                </label>

                                <button type="submit"
                                    class="self-start sm:self-auto px-4 py-1.5 text-[12px] font-semibold uppercase rounded bg-[#F9A13D] hover:bg-[#e8942b] text-white tracking-[0.15em]">
                                    Search
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="hidden lg:grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse ($reviews_list as $review)
            <article
                class="flex flex-col bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-[#e0e0e0]">

                {{-- Thumbnail --}}
                <a href="{{ route('review-detail', $review->slug) }}" class="w-full">
                    <img src="{{ $review->cover_image_url }}" alt="{{ $review->title }}"
                        class="w-full h-[180px] object-cover md:h-[200px]" />
                </a>

                {{-- Content --}}
                <div class="flex-1 p-4 flex flex-col justify-between gap-2">

                    <div>
                        <h3 class="text-[17px] md:text-[19px] font-semibold leading-snug text-[#444]">
                            <a href="{{ route('review-detail', $review->slug) }}" class="hover:text-[#F9A13D]">
                                {{ $review->title }}
                            </a>
                        </h3>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 text-[12px] text-[#9c9c9c] mt-1">
                        <span class="flex items-center gap-1">
                            <i class="far fa-clock"></i>
                            <span>{{ $review->published_at->format('d F Y') }}</span>
                        </span>

                        <a href="{{ route('review-detail', $review->slug) }}#comments"
                            class="flex items-center gap-1 hover:text-[#F9A13D]">
                            <i class="far fa-comment"></i>
                            <span>{{ $review->comments_count }}</span>
                        </a>
                    </div>

                </div>

            </article>
        @empty
            <div class="col-span-full bg-white p-10 text-center text-gray-500 italic">
                No reviews found
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
        {{ $reviews_list->links() }}
    </div>
@endsection