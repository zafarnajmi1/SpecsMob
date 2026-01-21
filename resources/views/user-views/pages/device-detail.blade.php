@extends('layouts.app')

@section('title', $device->name)

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
    <x-device-header :device="$device" activeTab="detail" />

    <!-- Device Summary Section -->
    <div class="my-8">
        <div
            class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
            {{-- Header/Title --}}
            <div class="bg-gray-50/50 border-b border-gray-100 px-6 py-4 flex items-center gap-3">
                <div class="w-1.5 h-6 bg-[#F9A13D] rounded-full"></div>
                <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                    Device Overview
                </h2>
            </div>

            {{-- Content --}}
            <div class="p-2 md:px-8">
                <div
                    class="prose prose-lg max-w-full break-words prose-slate prose-headings:text-gray-900 prose-p:text-gray-600 prose-strong:text-gray-900 prose-a:text-[#F9A13D] prose-a:no-underline hover:prose-a:underline">
                    {!! $device->description !!}
                </div>
            </div>

            {{-- Subtle Footer Accent --}}
            <div class="h-1 w-full bg-gradient-to-r from-transparent via-[#F9A13D]/20 to-transparent"></div>
        </div>
    </div>

    <!-- Specifications Section -->
    <div class="bg-[#D1D5DB91] hidden lg:block">
        @php
            // Use specValues (relation name), NOT spec_values
            $groupedSpecs = $device->specValues
                ->sortBy(fn($i) => $i->field->category->order)
                ->groupBy(fn($i) => $i->field->category->name);
        @endphp

        <div>
            @foreach($groupedSpecs as $categoryName => $specItems)

                <table class="shadow bg-white w-full mb-1">
                    <tbody>

                        @foreach($specItems as $index => $spec)
                            <tr>
                                {{-- Category Name (only for the first row of each category) --}}
                                <th class="text-start pl-2 py-1 w-[86px] text-[#F9A13D] text-[16px] uppercase">
                                    {{ $index === 0 ? $categoryName : '' }}
                                </th>

                                {{-- Field Label --}}
                                <td class="text-start pl-2 w-[110px] font-[700] text-[#7d7464] py-[3px] px-[10px]">
                                    {{ $spec->field->label }}
                                </td>

                                {{-- Field Value --}}
                                <td class="text-start pl-2 py-[3px] px-[10px]">
                                    {{ $spec->value_string ?? $spec->value_number ?? json_encode($spec->value_json) }}
                                    @if($spec->unit)
                                        {{ ' ' . $spec->unit }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            @endforeach
        </div>
    </div>

    <!-- Footer Info bar -->
    <div
        class="hidden lg:flex flex-wrap justify-end items-center border-t border-gray-400 shadow text-sm text-white px-4 md:px-6 h-[2.5rem] bg-[#F9A13D] backdrop-blur-sm">
        <div class="flex h-full">
            @if($device->reviews && $device->reviews->first())
                <a href="{{ route('review-detail', $device->reviews->first()->slug) }}"
                    class="flex items-center gap-1 hover:bg-white hover:text-[#F9A13D] transition-colors px-3 border-r border-gray-200 last:border-r-0">
                    <i class="fa-solid fa-star"></i> REVIEWS
                </a>
            @endif
            <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
                class="flex items-center gap-1 hover:bg-white hover:text-[#F9A13D] transition-colors px-3 border-r border-gray-200 last:border-r-0">
                <i class="fa-solid fa-newspaper"></i> OPINIONS
            </a>
            @if($device->offers->isNotEmpty())
                <a href="{{ route('device.prices', ['slug' => $device->slug, 'id' => $device->id])}}"
                    class="flex items-center gap-1 hover:bg-white hover:text-[#F9A13D] transition-colors px-3 border-r border-gray-200 last:border-r-0">
                    <i class="fa-solid fa-dollar-sign"></i>
                    PRICES
                </a>
            @endif
            @if($device->imageGroups->isNotEmpty() && $device->imageGroups->first()->images->isNotEmpty())
                <a href="{{ route('device.pictures', ['slug' => $device->slug, 'id' => $device->id])}}"
                    class="flex items-center gap-1 hover:bg-white hover:text-[#F9A13D] transition-colors px-3">
                    <i class="fa-solid fa-newspaper"></i> PICTURES
                </a>
            @endif
            <a href="{{ route('device.compare', ['slug' => $device->slug, 'id' => $device->id])}}"
                class="flex items-center justify-center gap-1 py-3 px-3 text-xs font-semibold transition-colors border-r border-gray-200 last:border-r-0 hover:bg-white hover:text-[#F9A13D]">
                <i class="fa-solid fa-code-compare"></i>
                <span class="hidden sm:inline">COMPARE</span>
            </a>
        </div>
    </div>

    <!-- Pricing section -->
    <!-- Pricing Tables -->
    <div class="hidden lg:block space-y-8 px-2">
        @php
            // Group offers by country
            $offersByCountry = $device->offers->groupBy('country.name');

            // Get ALL variants for the device (not just per country)
            $allVariants = $device->variants()
                ->where('device_variants.status', true)
                ->orderBy('storage_gb')
                ->orderBy('ram_gb')
                ->get();
        @endphp

        @if($offersByCountry->isEmpty() || $allVariants->isEmpty())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 my-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            No pricing information available for {{ $device->name }} at the moment. Please check back later.
                        </p>
                    </div>
                </div>
            </div>
        @else
            @foreach($offersByCountry as $countryName => $countryOffers)
                @php
                    // Group offers by store for this country
                    $offersByStore = $countryOffers->groupBy('store.name');

                    // Create a map of variant_id => offers for quick lookup
                    $variantOffersMap = [];
                    foreach ($offersByStore as $storeName => $storeOffers) {
                        foreach ($storeOffers as $offer) {
                            $variantOffersMap[$storeName][$offer->device_variant_id] = $offer;
                        }
                    }
                @endphp

                <div class="bg-white overflow-hidden mb-10">
                    <div class="text-[24px] block font-bold text-[#555] border-b-2 border-[#555] p-0">
                        {{ $countryName }}
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr>
                                    <!-- Store column with auto width -->
                                    <th
                                        class="px-4 py-3 text-left text-xl font-medium text-gray-700 bg-white border border-gray-300 min-w-[200px]">
                                        Store
                                    </th>
                                    <!-- Variant columns with fixed width -->
                                    @foreach($allVariants as $index => $variant)
                                        <th
                                            class="px-4 py-3 text-center text-[14px] font-medium text-black border border-gray-300 min-w-[116px] {{ $index % 2 != 0 ? 'bg-white' : 'bg-[#fafafa]' }}">
                                            @if($variant->storage_gb && $variant->ram_gb)
                                                {{ $variant->storage_gb }}GB {{ $variant->ram_gb }}GB RAM
                                            @else
                                                {{ $variant->label }}
                                            @endif
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($offersByStore as $storeName => $storeOffers)
                                    @php
                                        $store = $storeOffers->first()->store;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <!-- Store cell with auto width -->
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 bg-white border border-gray-300">
                                            <div class="flex items-center">
                                                @if($store->logo_url)
                                                    <img src="{{ $store->logo_url }}" alt="{{ $storeName }}"
                                                        class="h-8 w-auto max-w-[180px] object-contain">
                                                @else
                                                    <span class="font-medium text-gray-800">{{ $storeName }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <!-- Price cells with fixed width -->
                                        @foreach($allVariants as $index => $variant)
                                            @php
                                                $colBgClass = $index % 2 != 0 ? 'bg-white' : 'bg-[#fafafa]';
                                                $variantOffer = $variantOffersMap[$storeName][$variant->id] ?? null;
                                            @endphp
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm text-center border border-gray-300 {{ $colBgClass }} min-w-[116px]">
                                                @if($variantOffer)
                                                    <div class="flex flex-col items-center justify-center h-full">
                                                        <a href="{{ $variantOffer->url ?: '#' }}" target="_blank"
                                                            class="text-[#F9A13D] font-bold text-[17px] hover:underline transition-colors leading-tight"
                                                            @if($variantOffer->url) rel="nofollow noopener" @endif>
                                                            {{ $variantOffer->currency->symbol ?? '' }}{{ number_format($variantOffer->price, 2)
                                                            }}
                                                        </a>
                                                        @if(!$variantOffer->in_stock)
                                                            <span class="text-xs text-[#F9A13D] mt-1 font-medium">Out of stock</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 text-lg">—</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif
    </div>


    <!-- {{-- Devices Grid --}} -->
    <!-- Similarly Priced Devices Module -->
    <div class="bg-white mt-8 mb-8">
        <div class="border-b-2 border-gray-300 pb-2 mb-6">
            <h4 class="text-[#F9A13D] font-bold text-xl uppercase flex items-center justify-between">
                <span class="flex items-center gap-2">
                    Similarly priced
                    <div class="group relative inline-block">
                        <button class="text-gray-400 hover:text-[#F9A13D] focus:outline-none">
                            <i class="fa-regular fa-circle-question"></i>
                        </button>
                        <!-- Tooltip -->
                        <div
                            class="absolute left-0 bottom-full mb-2 w-64 p-3 bg-white border border-gray-200 shadow-xl rounded text-xs text-gray-600 hidden group-hover:block z-20 pointer-events-none">
                            Popular recent phones in the same price range as {{ $device->name }}
                            <div
                                class="absolute -bottom-1 left-3 w-2 h-2 bg-white border-b border-r border-gray-200 transform rotate-45">
                            </div>
                        </div>
                    </div>
                </span>
            </h4>
            <div class="text-xs text-gray-500 mt-1">Popular recent phones in the same price range as {{ $device->name }}
            </div>
        </div>

        @if($similar_priced_devices->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-y-8 gap-x-4">
                @foreach($similar_priced_devices as $item)
                    <div
                        class="flex flex-col items-center group relative p-2 transition-all duration-200 hover:shadow-lg rounded-lg">
                        <a href="{{ route('device-detail', $item->slug) }}" class="block text-center w-full h-full flex flex-col">
                            {{-- Image Container --}}
                            <div class="h-[160px] flex items-center justify-center mb-3 overflow-hidden relative">
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->name }}"
                                    class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-105"
                                    onerror="this.src='{{ asset('images/default-device.png') }}'">
                            </div>

                            {{-- Name --}}
                            <div class="mb-2 px-1 flex-grow">
                                <span
                                    class="block text-[15px] font-bold text-gray-800 group-hover:text-[#F9A13D] leading-5 transition-colors">
                                    {{ $item->name }}
                                </span>
                            </div>

                            {{-- Price --}}
                            @php
                                $offer = $item->activeOffers->first();
                              @endphp
                            @if($offer)
                                <div class="mt-auto">
                                    <span
                                        class="inline-block text-[14px] font-medium text-gray-600 bg-gray-50 px-2 py-1 rounded border border-gray-200 group-hover:border-[#F9A13D]/30 transition-colors">
                                        {{ $offer->currency->symbol ?? '$' }}{{ number_format($offer->price, 0) }}
                                    </span>
                                </div>
                            @else
                                <div class="mt-auto h-[29px]"></div>
                            @endif
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div
                class="flex flex-col items-center justify-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <i class="fas fa-search-dollar text-4xl mb-3 text-gray-300"></i>
                <p class="text-gray-500 font-medium">No similarly priced devices found at the moment.</p>
            </div>
        @endif
    </div>



    <!-- Opinion Section -->
    <div class="bg-[#f0f0f0] hidden lg:block">
        <h2 class="bg-white pl-[10px] pt-[10px] pr-[5px] mt-2 text-[#555] border-b border-[#ddd]">
            <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id]) }}"
                class="hover:text-[#F9A13D] font-bold text-lg transition uppercase tracking-tighter">{{ $device->name }} -
                User Opinions and Reviews</a>
        </h2>

        <div class="space-y-[1px]">
            @forelse ($opinions as $opinion)
                @include('partials.device-comment', ['comment' => $opinion, 'device' => $device])
            @empty
                <div class="bg-white p-10 text-center text-gray-500 italic">
                    No opinions yet. Be the first to share your thoughts!
                </div>
            @endforelse
        </div>
    </div>

    <div
        class="hidden lg:flex justify-between items-center border-t border-gray-400 shadow px-4 md:px-6 mt-3 pb-2 pt-5 bg-[#f0f0f0] backdrop-blur-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id]) }}"
                class="flex justify-center items-center px-3 py-1 font-bold text-[12px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase tracking-tighter shadow-sm">
                <span>READ ALL OPINIONS</span>
            </a>
            <a href="{{ route('device.opinions.post', ['slug' => $device->slug, 'id' => $device->id])}}"
                class="flex justify-center items-center px-3 py-1 font-bold text-[12px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase tracking-tighter shadow-sm">
                <span>POST YOUR OPINION</span>
            </a>
        </div>
        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">
            Total reader comments: <span class="text-[#F9A13D] ml-1">{{ $opinions->count() }}</span>
        </span>
    </div>


    <x-device-mobile-header :device="$device" activeTab="detail" />

    <section class="lg:hidden">
        <div>
            @php
                // Use specValues (relation name), NOT spec_values
                $groupedSpecs = $device->specValues
                    ->sortBy(fn($i) => $i->field->category->order)
                    ->groupBy(fn($i) => $i->field->category->name);
            @endphp

            <div class="bg-white">
                @foreach($groupedSpecs as $categoryName => $specItems)

                    <table class="w-full mb-1 border-b border-gray-200">
                        <tbody>
                            {{-- Category Header Row --}}
                            @if($categoryName)
                                <tr class="bg-gray-50">
                                    <td colspan="2" class="text-[#F9A13D] text-[12px] font-bold uppercase px-3 py-2">
                                        {{ $categoryName }}
                                    </td>
                                </tr>
                            @endif

                            {{-- Spec Rows --}}
                            @foreach($specItems as $spec)
                                <tr class="border-t border-gray-100">
                                    {{-- Field Label --}}
                                    <td class="text-start font-bold text-[#7d7464] text-[13px] py-2 px-3 w-[35%] align-top">
                                        {{ $spec->field->label }}
                                    </td>

                                    {{-- Field Value --}}
                                    <td class="text-start text-[13px] py-2 px-3 text-gray-700">
                                        {{ $spec->value_string ?? $spec->value_number ?? json_encode($spec->value_json) }}
                                        @if($spec->unit)
                                            {{ ' ' . $spec->unit }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                @endforeach
            </div>
        </div>

        <div class="bg-white shadow my-4 w-full flex flex-col">
            <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id]) }}"
                class="w-full uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
                User Opinions and Reviews <i class="fa-solid fa-angle-right"></i>
            </a>

            <div class="bg-[#F0F0F0] my-5 w-full">
                @forelse ($opinions as $opinion)
                    @include('partials.device-comment', ['comment' => $opinion, 'device' => $device])
                @empty
                    <div class="bg-white p-10 text-center text-gray-500 italic">
                        No opinions yet. Be the first to share your thoughts!
                    </div>
                @endforelse
            </div>

            <div class="flex gap-3">
                <a href="" class="uppercase text-[14px] font-bold bg-[#F9A13D] text-white px-3 py-2 rounded-sm shadow">READ
                    ALL ({{ $device->comments_count }})</a>
                <a href="" class="uppercase text-[14px] font-bold bg-[#F9A13D] text-white px-3 py-2 rounded-sm shadow">post
                    comment</a>
            </div>
        </div>

        @if(isset($recommendedArticles) && $recommendedArticles->count() > 0)
            <div class="bg-white rounded shadow my-6">
                <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
                    In the News <i class="fa-solid fa-angle-right"></i>
                </h4>

                <!-- Horizontal slider-->
                <div class="swiper featuredNewsSwiper px-4">
                    <div class="swiper-wrapper px-3 pb-5">
                        @foreach ($recommendedArticles as $article)
                            <a href="{{ route('article-detail', ['slug' => $article->slug, 'type' => $article->type]) }}"
                                class="swiper-slide group bg-transparent !p-0">
                                <div class="max-w-[176px] h-[120px] overflow-hidden relative shadow-sm">
                                    <img src="{{ $article->thumbnail_url }}" class="w-full h-full object-cover block">
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

        @if(isset($latestDevices) && $latestDevices->count() > 0)
            <div class="bg-white rounded shadow mb-4">
                <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
                    Related Devices <i class="fa-solid fa-angle-right"></i>
                </h4>

                <!-- Horizontal slider-->
                <div class="swiper featuredNewsSwiper px-4">
                    <div class="swiper-wrapper px-3 pb-5">
                        @foreach ($latestDevices as $relatedDevice)
                            <a href="{{ route('device-detail', $relatedDevice->slug) }}"
                                class="swiper-slide group bg-transparent !p-0">
                                <img src="{{ $relatedDevice->thumbnail_url }}" class="w-[100px] h-full object-cover block">

                                <h5
                                    class="font-bold text-[14px] leading-tight mt-3 text-gray-800 group-hover:text-[#F9A13D] line-clamp-2">
                                    {{ $relatedDevice->name }}
                                </h5>
                            </a>
                        @endforeach
                    </div>
                    <div class="swiper-scrollbar"></div>
                </div>
            </div>
        @endif

        @if(isset($inStoreDevices) && $inStoreDevices->isNotEmpty())
            <div class="bg-white rounded shadow mb-4">
                <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
                    More from {{ $device->brand->name }}
                </h4>

                <!-- Horizontal slider-->
                <div class="swiper featuredNewsSwiper px-4">
                    <div class="swiper-wrapper px-3 pb-5">
                        @foreach ($inStoreDevices as $brandDevice)
                            <a href="{{ route('device-detail', $brandDevice->slug) }}"
                                class="swiper-slide group bg-transparent !p-0">
                                <img src="{{ $brandDevice->thumbnail_url }}" class="w-[100px] h-full object-cover block">

                                <h5
                                    class="font-bold text-[14px] leading-tight mt-3 text-gray-800 group-hover:text-[#F9A13D] line-clamp-2">
                                    {{ $brandDevice->name }}
                                </h5>
                            </a>
                        @endforeach
                    </div>
                    <div class="swiper-scrollbar"></div>
                </div>
            </div>
        @endif
    </section>
@endsection

@push('scripts')
@endpush