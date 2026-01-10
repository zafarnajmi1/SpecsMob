@extends('layouts.app') @section('title', 'Videos') @section('sidebar')
@include('partials.aside') @endsection @section('videos_content') @endsection

@section('videos_mobile_content')
<section
    id="videos"
    class="max-w-[1060px] mx-auto bg-[#e7e4e4] my-5 flex flex-col gap-3 p-2 md:p-3"
>
    {{-- TikTok Creator Embed --}}
    <div class="w-full flex justify-center">
        <blockquote
            class="tiktok-embed"
            cite="https://www.tiktok.com/@gsmarenateam"
            data-unique-id="gsmarenateam"
            data-embed-type="creator"
            style="max-width: 780px; min-width: 288px"
            id="v19567230250704348"
        >
            <iframe
                name="__tt_embed__v19567230250704348"
                sandbox="allow-popups allow-popups-to-escape-sandbox allow-scripts allow-top-navigation allow-same-origin"
                src="https://www.tiktok.com/embed/@gsmarenateam?lang=en-GB&amp;referrer=https%3A%2F%2Fm.gsmarena.com%2Fvideos.php3"
                style="
                    width: 100%;
                    height: 473px;
                    display: block;
                    visibility: unset;
                    max-height: 473px;
                "
            ></iframe>
        </blockquote>
        <script async src="https://www.tiktok.com/embed.js"></script>
    </div>

    {{-- YouTube Videos List --}}
    <div class="space-y-4">
        @foreach ($videos as $video)
            <iframe
                class="inset-0 w-[100vw] h-[56.3vw] block"
                src="https://www.youtube.com/embed/{{ $video->youtube_id }}"
                title="{{ $video->title }}"
                frameborder="0"
                allowfullscreen
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            ></iframe>
        @endforeach
    </div>

    <div class="flex flex-col items-center gap-1 mt-6 text-center">
        <a
            href="https://www.youtube.com/user/gsmarena07/videos"
            target="_blank"
            class="inline-flex flex-col items-center gap-1 px-5 py-3 border border-[#F9A13D] text-[#F9A13D] rounded-md hover:bg-[#F9A13D] hover:text-white transition-colors"
        >
            <strong class="text-[12px] uppercase tracking-[0.22em]">
                More videos »
            </strong>
            <span class="text-[13px]"> Visit our YouTube channel </span>
        </a>
    </div>

    {{-- Optional: "Compare photos" trigger --}}
    <div class="mt-6 flex justify-center">
        <button
            type="button"
            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-[12px] text-[#555] rounded-full border border-gray-300 hover:bg-gray-200 transition-colors"
        >
            <span>Click here to compare two photos</span>
            <i class="fa-solid fa-xmark text-[11px]"></i>
        </button>
    </div>
</section>
@endsection

@section('content') @php $popularTags = [ 'Featured', 'Android', 'Samsung',
'Nokia', 'Sony', 'Rumors', 'Apple', 'LG', 'Xiaomi', 'Motorola', ]; @endphp

{{-- Header / hero --}}
<div class="overflow-hidden w-full mb-6 md:h-[310px] hidden lg:block">
    <div
        class="relative bg-cover bg-center h-full"
        style="background-image: url('{{ asset('user/images/video.jpg') }}');"
    >
        <div class="bg-black/55 h-full">
            <div
                class="px-4 h-full md:px-6 py-6 flex flex-col items-start justify-end text-center gap-2"
            >
                <h1
                    class="text-3xl md:text-4xl font-bold tracking-tight text-white drop-shadow-md"
                >
                    Videos
                </h1>
            </div>
        </div>
    </div>
</div>

<section
    id="videos"
    class="max-w-[1060px] mx-auto bg-[#e7e4e4] my-5 hidden lg:flex flex-col gap-3 p-2 md:p-3"
>
    {{-- TikTok Creator Embed --}}
    <div class="w-full flex justify-center">
        <blockquote
            class="tiktok-embed"
            cite="https://www.tiktok.com/@gsmarenateam"
            data-unique-id="gsmarenateam"
            data-embed-type="creator"
            style="max-width: 780px; min-width: 288px"
            id="v19567230250704348"
        >
            <iframe
                name="__tt_embed__v19567230250704348"
                sandbox="allow-popups allow-popups-to-escape-sandbox allow-scripts allow-top-navigation allow-same-origin"
                src="https://www.tiktok.com/embed/@gsmarenateam?lang=en-GB&amp;referrer=https%3A%2F%2Fm.gsmarena.com%2Fvideos.php3"
                style="
                    width: 100%;
                    height: 473px;
                    display: block;
                    visibility: unset;
                    max-height: 473px;
                "
            ></iframe>
        </blockquote>
        <script async src="https://www.tiktok.com/embed.js"></script>
    </div>

    {{-- YouTube Videos List --}}
    <div class="space-y-4">
        @foreach ($videos as $video)
            <iframe
                class="inset-0 w-full block" height="409" 
                src="https://www.youtube.com/embed/{{ $video->youtube_id }}"
                title="{{ $video->title }}"
                frameborder="0"
                allowfullscreen
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            ></iframe>
        @endforeach
    </div>

    <div class="flex flex-col items-center gap-1 mt-6 text-center">
        <a
            href="https://www.youtube.com/user/gsmarena07/videos"
            target="_blank"
            class="inline-flex flex-col items-center gap-1 px-5 py-3 border border-[#F9A13D] text-[#F9A13D] rounded-md hover:bg-[#F9A13D] hover:text-white transition-colors"
        >
            <strong class="text-[12px] uppercase tracking-[0.22em]">
                More videos »
            </strong>
            <span class="text-[13px]"> Visit our YouTube channel </span>
        </a>
    </div>

    {{-- Optional: "Compare photos" trigger --}}
    <div class="mt-6 flex justify-center">
        <button
            type="button"
            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-[12px] text-[#555] rounded-full border border-gray-300 hover:bg-gray-200 transition-colors"
        >
            <span>Click here to compare two photos</span>
            <i class="fa-solid fa-xmark text-[11px]"></i>
        </button>
    </div>
</section>

@endsection
