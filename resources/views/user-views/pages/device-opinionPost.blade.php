@extends('layouts.app')

@section('title', "$device->name")

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

                    <a href="{{ route('device.pictures', ['slug' => $device->slug, 'id' => $device->id])}}"
                        class="flex items-center gap-1 hover:bg-[#d50000] transition-colors px-3">
                        <i class="fa-solid fa-newspaper"></i> PICTURES
                    </a>

                    <button class="compare-btn flex items-center gap-1 hover:bg-[#d50000] transition px-3">
                        <i class="fa-solid fa-code-compare"></i> COMPARE
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div
        class="flex justify-between items-center border-t border-gray-400 shadow text-white px-4 md:px-6 mt-3 py-2 bg-[#f0f0f0] backdrop-blur-sm">
        <a href="#"
            class="flex justify-center items-center px-3 py-2 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase">
            <span>POST YOUR OPINION</span>
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
                    Post your opinion about {{ $device->name }}
                </h3>

                <form method="POST"
                    action="{{ route('device.opinions.store', ['slug' => $device->slug, 'id' => $device->id]) }}">
                    @csrf

                    {{-- USER INFO --}}
                    <div class="mb-4 flex justify-between items-center border-b border-b-[#d4d4d4] pb-[9px] pt-[3px]">
                        <div class="text-green-700 text-lg">
                            Logged in
                        </div>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                            class="flex justify-center items-center font-bold px-3 py-1 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase">
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
                                class="flex justify-center items-center font-bold px-3 py-1 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase">
                                <span>Manage</span>
                            </a>
                    </fieldset>

                    {{-- COMMENT --}}
                    <fieldset class="mb-4">
                        <label class="block text-lg mb-1">Your comment</label>
                        <textarea name="opinion" rows="8" class="w-full border-1 border-[#a9a9a9] px-3 py- bg-white"
                            required>{{ old('opinion') }}</textarea>
                    </fieldset>

                    {{-- SUBMIT --}}
                    <div class="flex justify-end">
                        <button class="flex cursor-pointer justify-center items-center font-bold px-3 py-1 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        @endauth
        @guest
            <div class="bg-white border border-[#d1d1d1] p-5 mt-4 text-center">
                <p class="mb-3 text-[#555]">You must be logged in to post an opinion.</p>
                <a href="{{ route('login') }}" class="bg-[#d50000] text-white px-4 py-2 inline-block">
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