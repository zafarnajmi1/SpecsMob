@extends('layouts.app')

@section('title', $user->name . ' - Posts')

@section('content')
    <!-- Header -->
    <div class="relative w-full h-72 md:h-[314px] bg-cover bg-center mb-[10px]"
        style='background-image: url("{{ asset('user/images/account.jpg') }}");'>
        <!-- Top bar -->
        <div
            class="absolute top-0 left-0 border-b-1 border-gray-400 shadow flex justify-between items-center w-full px-4 py-3 md:px-6 z-10">
        </div>

        <!-- Bottom Title & Details -->
        <div class="absolute bottom-0 left-0 w-full z-10">
            <h1 class="text-white text-[40px] font-bold px-5 mb-3">{{ $user->name }} - user account</h1>
            <div
                class="flex flex-wrap justify-end items-center border-t-1 border-gray-400 shadow text-sm text-white px-4 md:px-6 h-[2.5rem] bg-[Rgba(0,0,0,0.2)] backdrop-blur-sm">
                <div class="flex gap-4 h-full">
                    <a href="{{ route('user.posts', $user->username) }}"
                        class="flex items-center gap-1 hover:bg-[#F9A13D] px-3 transition bg-[#F9A13D]">
                        <i class="fa-regular fa-message"></i> POSTS {{ $posts_count  }}
                    </a>
                    <span class="flex items-center gap-1 px-3">
                        <i class="fa-solid fa-hand-sparkles"></i>
                        UPVOTES {{ $upvotes }}
                    </span>
                    <a href="{{ route('user.account.manage', $user->username) }}"
                        class="flex items-center gap-1 hover:bg-[#F9A13D] transition-colors px-3 transition uppercase">
                        <i class="fa-solid fa-user"></i> Account Settings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="pr-2">
        <!-- Device Opinions Section -->
        <div class="mt-3">
            <h3 class="border-l-[10px] border-l-[rgba(88,160,22,.5)] pt-[5px] pl-[20px] text-[21px] font-bold text-[#555]">
                In device specs</h3>
            <ul class="bg-[#efebe9] py-[6px] pr-[10px] border-l-[10px] border-l-[rgba(88,160,22,.5)]">
                @forelse($groupedComments['Device'] ?? [] as $comment)
                    <li class="py-[6px] pl-[30px]">
                        <a href="{{ route('device.opinions', ['slug' => $comment->commentable->slug, 'id' => $comment->commentable->id]) }}"
                            class="text-[#555] text-[15px] hover:text-[#F9A13D] hover:underline">
                            In <b class="font-bold">{{ $comment->commentable->name }}</b>
                            {{ $comment->created_at->format('F j, Y, g:i a') }}
                        </a>
                        <span class="block text-[#888] mt-1">{{ Str::limit($comment->body, 160) }}</span>
                    </li>
                @empty
                    <li class="pt-[3px] pl-[30px] pb-[3px] text-[15px] text-[#777]">None.</li>
                @endforelse
            </ul>
        </div>

        <!-- Reviews Section -->
        <div class="mt-3">
            <h3 class="border-l-[10px] border-l-[#ed9d9d] pt-[5px] pl-[20px] text-[21px] font-bold text-[#555]">In reviews
            </h3>
            <ul class="bg-[#efebe9] py-[6px] pr-[10px] border-l-[10px] border-l-[#ed9d9d]">
                @forelse($groupedComments['Review'] ?? [] as $comment)
                    <li class="py-[6px] pl-[30px]">
                        <a href="{{ route('review.comments', ['slug' => $comment->commentable->slug, 'id' => $comment->commentable->id]) }}"
                            class="text-[#555] text-[15px] hover:text-[#F9A13D] hover:underline">
                            In <b class="font-bold">{{ $comment->commentable->title }}</b>
                            {{ $comment->created_at->format('F j, Y, g:i a') }}
                        </a>
                        <span class="block text-[#888] mt-1">{{ Str::limit($comment->body, 160) }}</span>
                    </li>
                @empty
                    <li class="pt-[3px] pl-[30px] pb-[3px] text-[15px] text-[#777]">None.</li>
                @endforelse
            </ul>
        </div>

        <!-- Articles Section -->
        <div class="mt-3">
            <h3 class="border-l-[10px] border-l-[#17819f] pt-[5px] pl-[20px] text-[21px] font-bold text-[#555]">In articles
            </h3>
            <ul class="bg-[#efebe9] py-[6px] pr-[10px] border-l-[10px] border-l-[#17819f]">
                @forelse($groupedComments['Article'] ?? [] as $comment)
                    <li class="py-[6px] pl-[30px]">
                        <a href="{{ route('article.comments', ['slug' => $comment->commentable->slug, 'id' => $comment->commentable->id]) }}"
                            class="text-[#555] text-[15px] hover:text-[#F9A13D] hover:underline">
                            In <b class="font-bold">{{ $comment->commentable->title }}</b>
                            {{ $comment->created_at->format('F j, Y, g:i a') }}
                        </a>
                        <span class="block text-[#888] mt-1">{{ Str::limit($comment->body, 160) }}</span>
                    </li>
                @empty
                    <li class="pt-[3px] pl-[30px] pb-[3px] text-[15px] text-[#777]">None.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection