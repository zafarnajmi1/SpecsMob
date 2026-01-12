@extends('layouts.app')

@section('title', $review->slug)

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
    <section class="hidden lg:block">
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
                                    <i class="fa fa-star text-[#F9A13D]"></i>
                                @elseif($i == $fullStars + 1 && $halfStar)
                                    <i class="fa fa-star-half-alt text-[#F9A13D]"></i>
                                @else
                                    <i class="fa fa-star text-gray-300"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="font-semibold">{{ $review->rating ?? 0 }}</span>
                    </div>

                    <!-- Nav Links -->
                    <div class="flex gap-4 h-full">
                        <a href="{{ route('device-detail', $review->device->slug) }}"
                            class="flex items-center gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition">
                            <i class="fa-solid fa-mobile-screen"></i> {{ $review->device->name }}
                        </a>
                        <a href=""
                            class="flex items-center gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition">
                            <i class="fa-regular fa-user"></i> User Reviews
                        </a>
                        <a href="{{ route('review.comments', ['slug' => $review->slug, 'id' => $review->id]) }}"
                            class="flex items-center gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition">
                            <i class="fa-regular fa-comment"></i> Comments ({{ $review->comments_count }})
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Share -->
        <div class="flex gap-3 px-4 py-3 md:px-6">
            <a href="https://www.facebook.com/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank"
                class="flex items-center gap-1 text-white bg-blue-600 px-3 py-1 rounded hover:bg-blue-700 transition">
                <i class="fa-brands fa-facebook-f"></i> Share
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}" target="_blank"
                class="flex items-center gap-1 text-white bg-blue-400 px-3 py-1 rounded hover:bg-blue-500 transition">
                <i class="fa-brands fa-twitter"></i> Tweet
            </a>
        </div>

        <!-- Review Meta -->
        <div class="px-4 py-3 md:px-6 text-xs md:text-sm text-gray-700">
            <span class="font-semibold">Reviewed by:</span> {{ $review->author->name ?? 'Anonymous' }} |
            <span class="font-semibold">Published on:</span> {{ $review->published_at->format('F d, Y') }}
        </div>

        <!-- Review Body -->
        <div class="py-6 px-4 md:px-6 overflow-x-auto">
            <div class="prose prose-lg max-w-full break-words">
                {!! $review->body !!}
            </div>
        </div>


        <!-- Footer Navigation -->
        <div
            class="flex justify-end items-center border-t border-[hsla(0,0%,59%,.7)] px-4 md:px-6 h-[38px] bg-[#ddd] gap-4">
            <a href="{{ route('device-detail', $review->device->slug) }}"
                class="flex items-center text-[#555] text-[14px] font-bold gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition hover:text-white">
                <i class="fa-solid fa-mobile-screen text-[21px] text-white"></i> {{ $review->device->name }}
            </a>
            <a href=""
                class="flex items-center text-[#555] text-[14px] font-bold gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition hover:text-white">
                <i class="fa-regular fa-user text-[21px] text-white"></i> User Reviews
            </a>
            <a href="{{ route('review.comments', ['slug' => $review->slug, 'id' => $review->id]) }}"
                class="flex items-center text-[#555] text-[14px] font-bold gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition hover:text-white">
                <i class="fa-regular fa-comment text-[21px] text-white"></i> Comments ({{ $review->comments_count }})
            </a>
        </div>


        <!-- Comments Section -->
        <div class="bg-[#f0f0f0]">
            <h2 class="bg-white pl-[10px] pt-[10px] pr-[5px] mt-2 text-[#555] border-b border-[#ddd]">
                <a href="#" class="hover:text-[#F9A13D] font-bold text-lg transition hover:underline uppercase">Reader
                    Comments</a>
            </h2>

            <div class="space-y-[1px]">
                @forelse ($comments as $comment)
                    @include('partials.review-comment', ['comment' => $comment])
                @empty
                    <div class="bg-white p-10 text-center text-gray-500 italic">
                        No comments yet. Be the first to share your thoughts!
                    </div>
                @endforelse
            </div>

        </div>

        <div
            class="flex justify-between items-center border-t border-gray-400 shadow px-4 md:px-6 mt-3 pb-4 pt-5 bg-[#f0f0f0]">
            <div class="flex items-center gap-3">
                <a href="{{ route('review.comments', ['slug' => $review->slug, 'id' => $review->id]) }}"
                    class="flex justify-center items-center px-3 py-1 font-bold text-[12px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase tracking-tighter shadow-sm">
                    <span>READ ALL COMMENTS</span>
                </a>
            </div>
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                Total reader comments: <span class="text-[#F9A13D] ml-1">{{ $review->comments_count }}</span>
            </span>
        </div>
    </section>

    <x-device-mobile-header :device="$device" activeTab="reviews" />

    <section class="lg:hidden">
        <div class="flex gap-3 pt-3">
            <a href="" class="uppercase text-[14px] font-bold bg-[#F9A13D] text-white px-3 py-2 rounded-sm shadow">comments
                ({{ $review->comments_count }})</a>
            <a href="{{ route('comment.post', ['slug' => $review->slug, 'id' => $review->id, 'type' => 'review'])}}" class="uppercase text-[14px] font-bold bg-[#F9A13D] text-white px-3 py-2 rounded-sm shadow">post your
                comment</a>
            <a href="" class="uppercase text-[14px] font-bold bg-[#F9A13D] text-white px-3 py-2 rounded-sm shadow">SPECS</a>
        </div>

        <div class="py-6 px-4 md:px-6 overflow-x-auto">
            <article class="prose prose-lg max-w-full break-words">
                {!! $review->body !!}
            </article>
        </div>

        <div class="bg-white rounded shadow mb-4">
            <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
                Reader comments
            </h4>

            <div id="all-opinions" class="space-y-[1px]">
                @forelse ($comments as $comment)
                    @include('partials.review-comment', ['comment' => $comment])
                @empty
                    <div class="bg-white p-5 text-center text-gray-500 italic text-sm">
                        No comments yet.
                    </div>
                @endforelse
            </div>


            <div class="flex gap-3">
                <a href="" class="uppercase text-[14px] font-bold bg-[#F9A13D] text-white px-3 py-2 rounded-sm shadow">READ
                    ALL ({{ $review->comments_count }})</a>
                <a href="{{ route('comment.post', ['slug' => $review->slug, 'id' => $review->id, 'type' => 'review'])}}" class="uppercase text-[14px] font-bold bg-[#F9A13D] text-white px-3 py-2 rounded-sm shadow">post
                    comment</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
@endpush