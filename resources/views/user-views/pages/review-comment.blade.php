@extends('layouts.app')

@section('title', "$review->title Reviews")

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
    <!-- Review Header -->
    <div class="relative w-full max-w-full h-72 md:h-[314px] bg-cover bg-center box-border"
        style="background-image: url('{{ asset($review->cover_image_url) }}');">
        <!-- Overlay -->
        <!-- <div class="absolute inset-0 bg-black/25 backdrop-blur-sm"></div> -->

        <!-- Top Bar -->
        <div
            class="absolute top-0 left-0 w-full flex justify-between items-center md:px-4 py-2 z-10 text-white text-xs md:text-sm opacity-90">
            <div>
                <i class="far fa-clock mr-1"></i> {{ $review->published_at->diffForHumans() }}
            </div>
            <div>
                <i class="fa-regular fa-message mr-1"></i> {{ $review->comments_count }}
            </div>
        </div>

        <!-- Bottom Title & Info -->
        <div class="absolute bottom-0 left-0 w-full z-10">
            <h1 class="text-white px-4 text-3xl md:text-4xl font-bold drop-shadow-xl mb-2">{{ $review->title }}</h1>

            <div
                class="flex flex-col md:flex-row flex-wrap justify-between items-center gap-2 px-4 bg-black/40 backdrop-blur-sm border-t uppercase text-shadow-[1px 1px 1px rgba(0, 0, 0, .8)] border-gray-400 text-white text-sm h-[35px]">
                <!-- Rating -->
                <div class="flex items-center gap-2 h-full">
                    <div class="flex">
                        @php
                            $fullStars = floor($review->rating);
                            $halfStar = $review->rating - $fullStars >= 0.5;
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            @if($i <= $fullStars)
                                <i class="fa fa-star text-yellow-400"></i>
                            @elseif($i == $fullStars + 1 && $halfStar)
                                <i class="fa fa-star-half-alt text-yellow-400"></i>
                            @else
                                <i class="fa fa-star text-gray-400"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="font-semibold">{{ $review->rating ?? 0 }}</span>
                </div>

                <!-- Nav Links -->
                <div class="flex gap-4 h-full">
                    <a href="#" class="flex items-center gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition">
                        <i class="fa-solid fa-mobile-screen"></i> {{ $review->title }}
                    </a>
                    <a href="#" class="flex items-center gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition">
                        <i class="fa-regular fa-user"></i> User Reviews
                    </a>
                    <a href="" class="flex items-center gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition">
                        <i class="fa-regular fa-comment"></i> Comments ({{ $review->comments_count }})
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div
        class="flex justify-between items-center border-t border-gray-400 shadow text-white px-4 md:px-6 mt-3 py-2 bg-[#f0f0f0] backdrop-blur-sm">
        <a href="{{ route('comment.post', ['slug' => $review->slug, 'id' => $review->id, 'type' => 'review'])}}"
            class="flex justify-center items-center px-3 py-1 font-bold text-[12px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase tracking-tighter shadow-sm">
            <span>POST YOUR OPINION</span>
        </a>
        @if($comments->lastPage() > 1)
            <div class="flex items-center gap-1">
                <span class="text-[#555] text-[14px]">Pages: </span>
                @for ($i = 1; $i <= $comments->lastPage(); $i++)
                    @if ($i == $comments->currentPage())
                        <strong class="bg-[#555] border border-[#333] text-[#f0f0f0] px-[11px] py-[5px] text-[13px]">{{ $i }}</strong>
                    @else
                        <a href="{{ $comments->url($i) }}"
                            class="bg-[#f0f0f0] border border-[#ddd] text-[#555] px-[11px] py-[5px] text-[13px] hover:bg-[#F9A13D] hover:text-white transition ml-1">{{ $i }}</a>
                    @endif
                @endfor
            </div>
        @endif
    </div>

    <!-- Opinions Section -->
    <div class="bg-[#f0f0f0]">
        <div class="space-y-[1px]">
            @forelse ($comments as $comment)
                @include('partials.review-comment', ['comment' => $comment, 'review' => $review])
            @empty
                <div class="bg-white p-10 text-center text-gray-500 italic">
                    No comments yet. Be the first to share your thoughts!
                </div>
            @endforelse
        </div>
    </div>

    <div
        class="flex justify-between items-center border-t border-gray-400 shadow px-4 md:px-6 mt-3 py-2 bg-[#f0f0f0] backdrop-blur-sm">
        <a href="{{ route('comment.post', ['slug' => $review->slug, 'id' => $review->id, 'type' => 'review'])}}"
            class="flex justify-center items-center px-3 py-1 font-bold text-[12px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase tracking-tighter shadow-sm">
            <span>POST YOUR OPINION</span>
        </a>

        @if($comments->lastPage() > 1)
            <div class="flex items-center gap-1 ml-auto">
                <span class="text-[#555] text-[14px]">Pages: </span>
                @for ($i = 1; $i <= $comments->lastPage(); $i++)
                    @if ($i == $comments->currentPage())
                        <strong class="bg-[#555] border border-[#333] text-[#f0f0f0] px-[11px] py-[5px] text-[13px]">{{ $i }}</strong>
                    @else
                        <a href="{{ $comments->url($i) }}"
                            class="bg-[#f0f0f0] border border-[#ddd] text-[#555] px-[11px] py-[5px] text-[13px] hover:bg-[#F9A13D] hover:text-white transition ml-1">{{ $i }}</a>
                    @endif
                @endfor
            </div>
        @endif
    </div>

    @push('scripts')
    @endpush

@endsection