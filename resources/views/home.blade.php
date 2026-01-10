@extends('layouts.app')
@section('title', 'Home')

@section('sidebar')
    @include('partials.aside')
@endsection

@section('mobile-home-section')
    @if ($featuredArticles->isNotEmpty())
        <div class="bg-white rounded shadow mb-4">
            <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
                Featured
            </h4>

            <!-- Horizontal slider-->
            <div class="swiper featuredNewsSwiper px-4">
                <div class="swiper-wrapper px-3 pb-5">
                    @foreach ($featuredArticles as $article)
                        <a href="{{ route('article-detail', ['slug' => $article->slug, 'type' => $article->type]) }}" class="swiper-slide group bg-transparent !p-0">
                            <div class="max-w-[176px] h-[120px] overflow-hidden relative shadow-sm">
                                <img src="{{ $article->thumbnail_url }}"
                                    class="w-full h-full object-cover block">
                            </div>

                            <h5
                                class="font-bold text-[14px] leading-tight mt-3 text-gray-800 group-hover:text-[#F9A13D] line-clamp-2">
                                {{ $article->title }}
                            </h5>
                        </a>
                    @endforeach
                </div>
                <div class="swiper-scrollbar"></div>
            </div>

        </div>
    @endif

    @if ($featuredReviews->isNotEmpty())
        <div class="bg-white rounded shadow mb-4">
            <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3">
                Reviews <i class="fa-solid fa-angle-right"></i>
            </h4>
            <a href="#" class="flex flex-col w-full items-start justify-start group pb-3 px-4">
                <div class="w-full h-[370px] shadow-sm ">
                    <img src="{{ $featuredReviews[0]->cover_image_url }}" class="w-full h-full object-cover block">
                </div>
                <h5
                    class="font-bold text-[14px] mt-3 text-gray-800 group-hover:text-[#F9A13D] line-clamp-2 text-left w-full">
                    {{ $featuredReviews[0]->title }}
                </h5>
            </a>
            <!-- Horizontal slider-->
            <div class="swiper featuredNewsSwiper px-4">
                <div class="swiper-wrapper px-3 pb-5">
                    @foreach ($featuredReviews->skip(1) as $review)
                        <a href="#" class="swiper-slide group bg-transparent !p-0">
                            <div class="w-full h-[120px] overflow-hidden rounded relative shadow-sm">
                                <img src="{{ $review->cover_image_url }}" class="w-full h-full object-cover block">
                            </div>

                            <h5
                                class="font-bold text-[14px] leading-tight mt-3 text-gray-800 group-hover:text-[#d50000] line-clamp-2">
                                {{ $review->title }}
                            </h5>
                        </a>
                    @endforeach
                </div>
                <div class="swiper-scrollbar"></div>
            </div>
        </div>
    @endif
    

    @if ($latestArticles->count() > 0)
        <div class="bg-white rounded shadow mb-4">
            <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
                News <i class="fa-solid fa-angle-right"></i>
            </h4>
    @if ($latestArticles->count() > 0)
        <div class="bg-[#F0F0F0] my-5 px-2">
            @foreach ($latestArticles as $article)
                <x-article :article="$article" />
            @endforeach
            <a href="#" class="flex justify-end items-center gap-3 text-[#F9A13D] w-full px-5 py-3 bg-white font-bold text-[15px] border border-[#eee]">
                    <span>More NEWS</span>
                    <i class="fa-solid fa-angle-right"></i>
            </a>
        </div>
    @endif
        </div>
    @endif

    <div class="bg-white rounded shadow mb-4">
        <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
            Latest Devices
        </h4>

        <!-- Horizontal slider-->
        <div class="swiper featuredNewsSwiper px-4">
            <div class="swiper-wrapper px-3 pb-5">
                @foreach ($latestDevices as $device)
                    <a href="{{ route('device-detail', $device->slug) }}" class="swiper-slide group bg-transparent !p-0">
                            <img src="{{ $device->thumbnail_url }}"
                                class="w-[100px] h-full object-cover block">

                        <h5
                            class="font-bold text-[14px] leading-tight mt-3 text-gray-800 group-hover:text-[#F9A13D] line-clamp-2">
                            {{ $device->name }}
                        </h5>
                    </a>
                @endforeach
            </div>
            <div class="swiper-scrollbar"></div>
        </div>

    </div>

    @if ($inStoreDevices->isNotEmpty())
        <div class="bg-white rounded shadow mb-4">
            <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
                In stores Now
            </h4>

            <!-- Horizontal slider-->
            <div class="swiper featuredNewsSwiper px-4">
                <div class="swiper-wrapper px-3 pb-5">
                    @foreach ($inStoreDevices as $device)
                        <a href="{{ route('device-detail', $device->slug) }}" class="swiper-slide group bg-transparent !p-0">
                            <img src="{{ $device->thumbnail_url }}"
                                class="w-[100px] h-full object-cover block">

                            <h5
                                class="font-bold text-[14px] leading-tight mt-3 text-gray-800 group-hover:text-[#F9A13D] line-clamp-2">
                                {{ $device->name }}
                            </h5>
                        </a>
                    @endforeach
                </div>
                <div class="swiper-scrollbar"></div>
            </div>

        </div>
    @endif

        <div class="bg-white shadow mb-4">
        <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
            Brands <i class="fa-solid fa-angle-right"></i>
        </h4>

        <div class="bg-[#F0F0F0] my-5 w-full">
            @foreach ($mobileBrands as $brand)
                <a href="{{ route('brand.devices', $brand->slug) }}" class="flex items-center w-full px-5 py-3 bg-white font-bold text-[15px] border border-[#eee]">
                    <span class="flex-1">{{ $brand->name }}</span>
                    <i class="fa-solid fa-angle-right text-[#F9A13D]"></i>
                </a>
            @endforeach
                <a href="{{ route('brands.all') }}" class="flex items-center w-full px-5 py-3 bg-white font-bold text-[15px] border border-[#eee]">
                    <span class="flex-1">All Brands</span>
                    <i class="fa-solid fa-angle-right text-[#F9A13D]"></i>
                </a>
                <a href="#" class="flex items-center w-full px-5 py-3 bg-white font-bold text-[15px] border border-[#eee]">
                    <span class="flex-1">Rumor Mill</span>
                    <i class="fa-solid fa-angle-right text-[#F9A13D]"></i>
                </a>
        </div>
    </div>

@endsection


@section('content')
    @include('partials.main-content')

    @if ($latestArticles->count() > 0)
        <div class="articles bg-[#F0F0F0] my-5 flex flex-col gap-3 p-2 md:p-3">
            @foreach ($latestArticles as $article)
                <x-article :article="$article" />
            @endforeach
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper(".featuredNewsSwiper", {
                slidesPerView: "auto",
                spaceBetween: 15,
                freeMode: true,
                mousewheel: {
                    forceToAxis: true,
                },
                scrollbar: {
                    el: ".swiper-scrollbar",
                    draggable: true,
                    hide: false,
                },
            });
        });
    </script>
@endpush
