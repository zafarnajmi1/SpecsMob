@extends('layouts.app')

@section('title', 'Search Results for: ' . $q)

@section('content')
    {{-- Hero / Header --}}
    <div class="overflow-hidden w-full md:h-[310px]">
        <div class="relative bg-cover bg-center h-full"
            style='background-image: url("{{ asset('user/images/phonefinder.jpg') }}");'>
            <div class="bg-black/25 h-full">
                <div class="px-4 md:px-6 py-6 h-full flex flex-col justify-end gap-2">
                    <h1 class="text-[32px] md:text-[40px] font-bold tracking-tight text-white drop-shadow-md">
                        Search Results for "{{ $q }}"
                    </h1>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Sections --}}
    <div class="bg-[#f2f2f2] border border-gray-300 py-5">

        {{-- Section 1: Matched Devices / Specs --}}
        <section class="mb-8">
            <h4 class="border-l-[11px] border-[#accf83] text-[#777] font-[500] text-[23px] px-4 py-3">
                Specs
            </h4>
            @if($devices->count() > 0)
                <div class="px-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4">
                        @foreach($devices as $device)
                            <a href="{{ route('device-detail', $device->slug) }}"
                                class="relative bg-white shadow-sm overflow-hidden group cursor-pointer border border-[#eee] hover:border-[#F9A13D] hover:shadow-md transition-all duration-300 text-center block rounded">

                                <!-- Device Image -->
                                <div class="block h-[170px] flex items-center justify-center px-2 py-4 bg-white">
                                    <img src="{{ $device->thumbnail_url }}" alt="{{ $device->name }}" title="{{ $device->name }}"
                                        class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-500"
                                        onerror="this.src='{{ asset('images/default-device.png') }}'">
                                </div>

                                <!-- Device Name -->
                                <div class="py-3 border-t border-[#f8f8f8] transition-all duration-300 group-hover:bg-[#F9A13D]">
                                    <strong class="text-[#555] font-bold text-[14px] group-hover:text-white px-2 line-clamp-1">
                                        {{ $device->name }}
                                    </strong>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-center">
                        {{ $devices->links() }}
                    </div>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-mobile-alt text-5xl text-gray-200 mb-4 block"></i>
                    <p class="text-gray-400 italic">No devices matched your search.</p>
                </div>
            @endif
        </section>

        {{-- Section 2: Matched Reviews --}}
        <section class="mb-8">
            <h4 class="border-l-[11px] border-[#92bfce] text-[#777] font-[500] text-[23px] px-4 py-3">
                Reviews
            </h4>

            @if($reviews->count() > 0)
                <div class="px-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($reviews as $review)
                            <article
                                class="flex flex-col bg-white overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-[#eee] rounded group">
                                {{-- Thumbnail --}}
                                <a href="{{ route('review-detail', $review->slug) }}"
                                    class="relative w-full h-[180px] overflow-hidden bg-gray-50">
                                    <img src="{{ $review->cover_image_url }}" alt="{{ $review->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                        onerror="this.src='{{ asset('user/images/default-preview.png') }}'" />
                                </a>

                                {{-- Content --}}
                                <div class="p-4 flex flex-col justify-center">
                                    <h3
                                        class="text-[17px] mb-8 font-bold leading-tight text-[#444] group-hover:text-[#F9A13D] transition-colors line-clamp-2">
                                        <a href="{{ route('review-detail', $review->slug) }}">
                                            {{ $review->title }}
                                        </a>
                                    </h3>

                                    <div class="flex items-center gap-4 text-[11px] text-gray-400 border-t border-[#f9f9f9]">
                                        <span class="flex items-center gap-1.5">
                                            <i class="far fa-calendar-alt"></i>
                                            <span>{{ $review->published_at ? $review->published_at->format('M d, Y') : 'Recently' }}</span>
                                        </span>
                                        <span class="flex items-center gap-1.5">
                                            <i class="far fa-comment-dots"></i>
                                            <span>{{ $review->comments_count }}</span>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-center">
                        {{ $reviews->links() }}
                    </div>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-file-alt text-5xl text-gray-200 mb-4 block"></i>
                    <p class="text-gray-400 italic">No reviews found for this query.</p>
                </div>
            @endif
        </section>

        {{-- Section 3: Tech News --}}
        <section class="mb-8">
            <h4 class="border-l-[11px] border-[#ed9b9d] text-[#777] font-[500] text-[23px] px-4 py-3">
                News
            </h4>

            @if($news->count() > 0)
                <div class="px-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($news as $article)
                            <a href="{{ route('article-detail', ['slug' => $article->slug, 'type' => $article->type]) }}"
                                class="group block">
                                <article
                                    class="flex flex-col bg-white overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-[#eee] rounded-md h-full">
                                    {{-- Image --}}
                                    <div class="w-full h-[180px] overflow-hidden bg-gray-100">
                                        <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                            onerror="this.src='{{ asset('user/images/default-preview.png') }}'" />
                                    </div>

                                    {{-- Text content --}}
                                    <div class="flex-1 p-4 flex flex-col justify-between">
                                        <div class="mb-3">
                                            <h3
                                                class="text-[16px] font-bold leading-tight text-[#333] group-hover:text-[#F9A13D] transition-colors line-clamp-2">
                                                {{ $article->title }}
                                            </h3>
                                        </div>

                                        <div
                                            class="flex items-center justify-between text-[11px] font-semibold text-[#9c9c9c] border-t border-[#f7f7f7] pt-3">
                                            <div class="flex gap-3">
                                                <span class="flex items-center gap-1">
                                                    <i class="far fa-clock"></i>
                                                    <span>{{ $article->published_at ? $article->published_at->diffForHumans() : 'Recently' }}</span>
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <i class="far fa-comment"></i>
                                                    <span>{{ $article->comments_count ?? $article->comments->count() }}</span>
                                                </span>
                                            </div>
                                            <span class="text-[#F9A13D] font-bold">Read News</span>
                                        </div>
                                    </div>
                                </article>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-center">
                        {{ $news->links() }}
                    </div>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fas fa-newspaper text-5xl text-gray-200 mb-4 block"></i>
                    <p class="text-gray-400 italic">No news articles match your search.</p>
                </div>
            @endif
        </section>
    </div>
@endsection