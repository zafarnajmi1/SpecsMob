@extends('layouts.app')

@section('title', Auth::user()->name . ' - Posts')

@section('content')
    <!--  Header -->
    <div class="relative w-full h-72 md:h-[314px] bg-cover bg-center mb-[10px]"
        style='background-image: url("{{ asset('user/images/account.jpg') }}");'>
        <!-- Top bar -->
        <div
            class="absolute top-0 left-0 border-b-1 border-gray-400 shadow flex justify-between items-center w-full px-4 py-3 md:px-6 z-10">
            </div>

        <!-- Bottom Title & Details -->
        <div class="absolute bottom-0 left-0 w-full z-10">
             <h1 class="text-white text-[40px] font-bold px-5 mb-3">
    {{ $user->name }} - user account
</h1>

            <!-- Bottom info bar -->
            <div
                class="flex flex-wrap justify-end items-center border-t-1 border-gray-400 shadow text-sm text-white px-4 md:px-6 h-[2.5rem] bg-[Rgba(0,0,0,0.2)] backdrop-blur-sm">
                <div class="flex gap-4 h-full">
                    <a href="" class="flex items-center gap-1 hover:bg-[#d50000] transition-colors px-3 transition">
                        <i class="fa-regular fa-message"></i> POSTS {{ $posts_count  }}
                    </a>
                    <button id="compare-tab"
                        class="compare-btn relative cursor-pointer flex items-center gap-1 transition hover:bg-[#d50000] px-3">
                        <i class="fa-solid fa-hand-sparkles"></i>
                        <span class="compare-text">UPVOTES</span> {{ $upvotes }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="py-5">
        <div class="pt-[10px] pl-[5px]">
            <h3 class="font-[700] text-[21px] px-[30px] mb-[10px] text-[#555]">Favorite devices</h3>
        </div>
        <ul class="bg-[#efebe9] py-[6px] pr-[10px] border-l-[10px] border-l-[#ed9d9d]">
            <li class="pt-[3px] pl-[30px] pb-[3px] text-[15px] font-normal">None.</li>
        </ul>
        <div class="pt-[10px] pl-[5px]">
             <h3 class="font-[700] text-[21px] px-[30px] mb-[10px] text-[#555]">
    Latest posts of {{ $user->username }}
</h3>

        </div>
        <ul class="bg-[#efebe9] py-[6px] pr-[10px] border-l-[10px] border-l-[#ed9d9d]">
            <li class="pt-[3px] pl-[30px] pb-[3px] text-[15px] font-normal">None.</li>
        </ul>


        <div class="mt-3">
    <h3 class="border-l-[10px] border-l-[rgba(88,160,22,.5)] pt-[5px] pl-[20px]
               text-[21px] font-bold text-[#555]">
        In device specs
    </h3>

    <ul class="bg-[#efebe9] py-[6px] pr-[10px]
               border-l-[10px] border-l-[rgba(88,160,22,.5)]">

        @forelse($deviceOpinions as $opinion)
            <li class="py-[6px] pl-[30px]">
                <a href="{{ route('device.opinions.post', [
                        'slug' => $opinion->device->slug,
                        'id'   => $opinion->device->id
                    ]) }}"
                   class="text-[#555] text-[15px] hover:text-[#d50000] hover:underline">

                    In <b class="font-bold">{{ $opinion->device->name }}</b>
                    {{ $opinion->created_at->format('F j, Y, g:i a') }}
                </a>

                <span class="block text-[#888] mt-1">
                    {{ Str::limit($opinion->body, 160) }}
                </span>
            </li>
        @empty
            <li class="pt-[3px] pl-[30px] pb-[3px] text-[15px] text-[#777]">
                None.
            </li>
        @endforelse

    </ul>
</div>



        <div class="mt-3">
            <h3 class="border-l-[10px] border-l-[#ed9d9d] pt-[5px] pl-[20px] text-[21px] font-bold text-[#555]">In news/blog articles</h3>
            <ul class="bg-[#efebe9] py-[6px] pr-[10px] border-l-[10px] border-l-[#ed9d9d]">
                <li class="py-[3px] pl-[30px]">
                    <a href="" class="text-[#555] text-[15px] hover:text-[#d50000] hover:underline">
                        In
                        <b class="font-bold">{{ $device->name ?? 'ABC' }}</b>
                        {{ isset($post) ? $post->created_at->format('F j, Y, g:i a') : 'Jan 1, 2024, 12:00 am' }}
                    </a>
                    <span class="block text-[#888]">{{ $post->content ?? 'None'}}</span>
                </li>
            </ul>
        </div>
        <div class="mt-3">
            <h3 class="border-l-[10px] border-l-[#17819f] pt-[5px] pl-[20px] text-[21px] font-bold text-[#555]">In review articles</h3>
            <ul class="bg-[#efebe9] py-[6px] pr-[10px] border-l-[10px] border-l-[#17819f]">
                <li class="py-[3px] pl-[30px]">
                    <a href="" class="text-[#555] text-[15px] hover:text-[#d50000] hover:underline">
                        In
                        <b class="font-bold">{{ $device->name ?? 'ABC' }}</b>
                        {{ isset($post) ? $post->created_at->format('F j, Y, g:i a') : 'Jan 1, 2024, 12:00 am' }}
                    </a>
                    <span class="block text-[#888]">{{ $post->content ?? 'None'}}</span>
                </li>
            </ul>
        </div>
    </div>
@endsection