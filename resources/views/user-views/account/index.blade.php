@extends('layouts.app')

@section('title', Auth::user()->name . ' - Account')

@section('content')
    <!--  Header -->
    <div class="relative w-full h-72 md:h-[314px] bg-cover bg-center mb-[10px]"
        style='background-image: url("{{ asset('user/images/account.jpg') }}");'>
        <!-- Top bar -->
        <div
            class="absolute top-0 left-0 border-b border-gray-400 shadow flex justify-between items-center w-full px-4 py-3 md:px-6 z-10">
        </div>

        <!-- Bottom Title & Details -->
        <div class="absolute bottom-0 left-0 w-full z-10">
            <h1 class="text-white text-[40px] font-bold px-5 mb-3">{{ Auth::user()->name ?? 'Anonymous'}} - user account</h1>
            <!-- Bottom info bar -->
            <div
                class="flex flex-wrap justify-end items-center border-t border-gray-400 shadow text-sm text-white px-4 md:px-6 h-[2.5rem] bg-[Rgba(0,0,0,0.2)] backdrop-blur-sm">
                <div class="flex gap-4 h-full">
                    <a href="{{ route('user.posts', Auth::user()->username) }}" class="flex items-center gap-1 hover:bg-[#F9A13D] transition-colors px-3">
                        <i class="fa-regular fa-message"></i> POSTS {{ $posts_count  }}
                    </a>
                    <button id="compare-tab"
                        class="compare-btn relative cursor-pointer flex items-center gap-1 transition hover:bg-[#F9A13D] px-3">
                        <i class="fa-solid fa-hand-sparkles"></i>
                        <span class="compare-text">UPVOTES</span> {{ $upvotes }}
                    </button>
                    <a href="{{ route('user.account.manage', Auth::user()->username) }}"
                        class="flex items-center gap-1 hover:bg-[#F9A13D] transition-colors px-3 transition uppercase">
                        <i class="fa-solid fa-user"></i> Account Settings
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="py-5">
        <div class="pt-[10px] pl-[5px]">
            <h3 class="font-[500] text-[21px] px-[30px] mb-[10px] text-[#555]">Favorite devices</h3>
        </div>
        <div class="pt-[10px] pl-[5px]">
            <h3 class="font-[500] text-[21px] px-[30px] mb-[10px] text-[#555]">Latest replies to your comments</h3>
        </div>
        <ul class="bg-[#efebe9] py-[6px] pr-[10px] border-l-[10px] border-l-[#ed9d9d]">
            <li class="pt-[3px] pl-[30px] pb-[3px] text-[15px] font-normal">None.</li>
        </ul>
    </div>
@endsection