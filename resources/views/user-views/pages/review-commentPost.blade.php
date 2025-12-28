@extends('layouts.app')

@section('title', "$review->name")

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')

    <!-- Review Header -->
<div class="relative w-full max-w-full h-72 md:h-[314px] bg-cover bg-center box-border" style="background-image: url('{{ asset($review->cover_image_url) }}');">
    <!-- Overlay -->
    <!-- <div class="absolute inset-0 bg-black/25 backdrop-blur-sm"></div> -->

    <!-- Top Bar -->
    <div class="absolute top-0 left-0 w-full flex justify-between items-center md:px-4 py-2 z-10 text-white text-xs md:text-sm opacity-90">
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

        <div class="flex flex-col md:flex-row flex-wrap justify-between items-center gap-2 px-4 bg-black/40 backdrop-blur-sm border-t uppercase text-shadow-[1px 1px 1px rgba(0, 0, 0, .8)] border-gray-400 text-white text-sm h-[35px]">
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
                    <i class="fa-solid fa-mobile-screen"></i> {{ $review->name }}
                </a>
                <a href="#" class="flex items-center gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition">
                    <i class="fa-regular fa-comment"></i> Comments ({{ $review->comments_count }})
                </a>
            </div>
        </div>
    </div>
</div>

    <div
        class="flex justify-between items-center border-t border-gray-400 shadow text-white px-4 md:px-6 mt-3 py-2 bg-[#f0f0f0] backdrop-blur-sm">
        <a href="#"
            class="flex justify-center items-center px-3 py-2 text-[14px] font-bold bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase">
            <span>POST YOUR COMMENT</span>
        </a>

        <div>
            <span class="text-[#555] text-[14px]">Pages: </span>
            <strong class="bg-[#555] border border-[#333] text-[#f0f0f0] px-[11px] py-[5px] text-[13px]">1</strong>
        </div>
    </div>

    <!-- Opinions Section -->
    <div class="bg-[#f0f0f0]">
        @auth
            <div class="p-5 mt-4 normal-text user-submit">

                <h3 class="text-[21px] font-bold mb-4 text-[#555]">
                    Post your comment
                </h3>

                <form method="POST"
                    action="{{ route('comment.store', ['slug' => $review->slug, 'id' => $review->id, 'type' => 'review']) }}">
                    @csrf

                    {{-- USER INFO --}}
                    <div class="mb-4 flex justify-between items-center border-b border-b-[#d4d4d4] pb-[9px] pt-[3px]">
                        <div class="text-green-700 text-lg">
                            Logged in
                        </div>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                            class="flex justify-center items-center font-bold px-3 py-1 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase">
                            <span>Logout</span>
                        </a>
                    </div>

                    {{-- NICKNAME --}}
                    <fieldset class="flex flex-col gap-2 items-end mb-4">
                        <div class="w-full">
                            <label class="block text-lg mb-1">Your nickname</label>
                            <input type="text" value="{{ Auth::user()->username }}" disabled
                                class="border-1 border-[#a9a9a9] px-3 py-2 bg-[#cecece] text-[#999] text-[16px] w-full" disabled="disabled">
                            </div>
                            <a href="{{ route('user.account.manage', Auth::user()->username) }}"
                                class="flex justify-center items-center font-bold px-3 py-1 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase">
                                <span>Manage</span>
                            </a>
                    </fieldset>

                    {{-- COMMENT --}}
                    <fieldset class="mb-4">
                        <label class="block text-lg mb-1">Your comment</label>
                        <textarea name="comment" rows="8" class="w-full border-1 border-[#a9a9a9] px-3 py- bg-white"
                            required>{{ old('comment') }}</textarea>
                    </fieldset>

                    {{-- SUBMIT --}}
                    <div class="flex justify-end">
                        <button class="flex cursor-pointer justify-center items-center font-bold px-3 py-1 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        @endauth
        @guest
            <div class="bg-white border border-[#d1d1d1] p-5 mt-4 text-center">
                <p class="mb-3 text-[#555]">You must be logged in to post an opinion.</p>
                <a href="{{ route('login') }}" class="bg-[#F9A13D] text-white px-4 py-2 inline-block">
                    Login
                </a>
            </div>
        @endguest

    </div>

    {{-- ================= Posting Rules ================= --}}
<div class="max-w-[900px] mx-auto mt-8 bg-white border border-[#d1d1d1] p-6 text-[#555]">

    <h4 class="text-[20px] font-bold mb-4">
        Posting rules
    </h4>

    <ul class="list-disc pl-6 space-y-2 text-[15px] leading-relaxed">
        <li>English only</li>
        <li>Be polite, use common sense, and avoid foul language</li>
        <li>No hate speech (race/ethnicity, nationality, sex, sexual orientation, disability, religion, political affiliation)</li>
        <li>No political or religious discussion – this is a tech-focused platform</li>
        <li>No spam, commercial ads, or referral links</li>
        <li>No bashing – deliberately and repeatedly attacking the same brand</li>
        <li>No trolling – deliberately antagonizing other brand or model users</li>
        <li>Read before you post. Search before you post. Your question may already have an answer</li>
        <li>Do not share personal information. We are not responsible for misuse</li>
    </ul>

    <p class="mt-4 text-[14px] text-[#777]">
        Posts that violate these rules will be removed without notice.
    </p>
</div>

{{-- ================= Moderation Note ================= --}}
<div class="max-w-[900px] mx-auto mt-4 bg-[#f9f9f9] border border-[#d1d1d1] p-4 text-[14px] text-[#666]">
    <strong class="text-[#555]">Note:</strong>
    Comments are subject to automated and moderator review.
    We may hide or remove any comment at our discretion.
    Comment authors are solely responsible for their content.
    Do not post unlawful, abusive, or otherwise inappropriate material,
    or your account may be banned.
</div>

@endsection