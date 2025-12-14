@extends('layouts.app')

@section('title', "$device->name Pictures")

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')

    <!-- Device Header -->
    <div class="relative w-full h-72 md:h-[314px] bg-cover bg-center"
        style='background-image: url("{{ $device->thumbnail_url }}");'>

        <div
            class="absolute top-0 left-0 border-b border-gray-400 shadow flex justify-between items-center w-full px-4 py-3 md:px-6 z-10">
            <h1>{{ $device->name }}</h1>
        </div>

        <div class="absolute bottom-0 left-0 w-full z-10">
            <div
                class="flex flex-wrap justify-end items-center border-t border-gray-400 shadow text-sm text-white px-4 md:px-6 h-[2.5rem] bg-[rgba(0,0,0,0.2)] backdrop-blur-sm">
                <div class="flex gap-4 h-full">
                    <a href="{{ route('device-detail', $device->slug) }}"
                        class="flex items-center gap-1 hover:bg-[#d50000] transition-colors px-3">
                        <i class="fa-solid fa-mobile"></i> Specifications
                    </a>

                    <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
                        class="flex items-center gap-1 hover:bg-[#d50000] transition px-3">
                        <i class="fa-solid fa-newspaper"></i> OPINIONS
                    </a>

                    <button class="compare-btn flex items-center gap-1 hover:bg-[#d50000] transition px-3">
                        <i class="fa-solid fa-code-compare"></i> COMPARE
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pictures Section -->
    <div class="bg-white py-6 container mx-auto px-4">

        @foreach($device->imageGroups as $group)
            <div class="flex flex-col gap-2 items-start mt-6 w-full">

                <h2
                    class="text-[23px] font-normal text-[#777]
                           border-l-[10px] border-solid border-[#17819f]
                           py-[5px] px-[20px] pl-[30px]
                           font-[Arimo,'Google-Oswald',sans-serif]">
                    {{ $group->title }}
                </h2>

                <div class="flex flex-col gap-3 items-center w-full">
                    @forelse($group->images as $img)
                        <img
                            src="{{ Storage::url($img->image_url) }}"
                            alt="{{ $img->caption ?? $group->title }}"
                            class="w-[80%] max-w-[100%]" />
                    @empty
                        <p class="text-gray-500">No images available.</p>
                    @endforelse
                </div>
            </div>
        @endforeach

    </div>

    <div
        class="flex flex-wrap justify-end items-center border-t border-gray-400 shadow text-sm text-white px-4 md:px-6 h-[2.5rem] bg-[rgba(0,0,0,0.2)] backdrop-blur-sm">
        <div class="flex gap-4 h-full">
            <a href="{{ route('device-detail', $device->slug) }}"
                class="flex items-center gap-1 hover:bg-[#d50000] transition-colors px-3">
                <i class="fa-solid fa-mobile"></i> Specifications
            </a>

            <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
                class="flex items-center gap-1 hover:bg-[#d50000] transition px-3">
                <i class="fa-solid fa-newspaper"></i> OPINIONS
            </a>

            <button class="compare-btn flex items-center gap-1 hover:bg-[#d50000] transition px-3">
                <i class="fa-solid fa-code-compare"></i> COMPARE
            </button>
        </div>
    </div>

@endsection
