@extends('layouts.app')

@section('title', $device->name)

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
    <x-device-header :device="$device" activeTab="detail" />

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
        <div class="flex gap-4 h-full">
            <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
                class="flex items-center gap-1 hover:bg-white hover:text-[#F9A13D] transition-colors px-3">
                <i class="fa-solid fa-newspaper"></i> OPINIONS
            </a>
            <button id="compare-tab"
                class="compare-btn relative cursor-pointer flex items-center gap-1 transition hover:bg-white hover:text-[#F9A13D] px-3">
                <i class="fa-solid fa-code-compare"></i>
                <span class="compare-text">COMPARE</span>
            </button>
            <a href="{{ route('device.pictures', ['slug' => $device->slug, 'id' => $device->id])}}"
                class="flex items-center gap-1 hover:bg-white hover:text-[#F9A13D] transition-colors px-3">
                <i class="fa-solid fa-newspaper"></i> PICTURES
            </a>
        </div>
    </div>

    <!-- Opinion Section -->
    <div class="bg-[#f0f0f0] hidden lg:block">
        <h2 class="bg-white pl-[10px] pt-[10px] pr-[5px] mt-2 text-[#555] border-b border-[#ddd]">
            <a href="#"
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
                        <a href="{{ route('device-detail', $relatedDevice->slug) }}" class="swiper-slide group bg-transparent !p-0">
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
                        <a href="{{ route('device-detail', $brandDevice->slug) }}" class="swiper-slide group bg-transparent !p-0">
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