@extends('layouts.app')

@section('title', "$device->name")

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
    <x-device-header :device="$device" activeTab="opinions" />
    <x-device-mobile-header :device="$device" activeTab="opinions" />

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
                            class="flex justify-center items-center font-bold px-3 py-1 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase">
                            <span>Logout</span>
                        </a>
                    </div>

                    {{-- NICKNAME --}}
                    <fieldset class="flex flex-col gap-2 items-end mb-4">
                        <div class="w-full">
                            <label class="block text-lg mb-1">Your nickname</label>
                            <input type="text" value="{{ Auth::user()->username }}" disabled
                                class="border-1 border-[#a9a9a9] px-3 py-2 bg-[#cecece] text-[#999] text-[16px] w-full"
                                disabled="disabled">
                        </div>
                        <a href="{{ route('user.account.manage', Auth::user()->username) }}"
                            class="flex justify-center items-center font-bold px-3 py-1 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase">
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
                        <button
                            class="flex cursor-pointer justify-center items-center font-bold px-3 py-1 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase">
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
            <li>No hate speech (race/ethnicity, nationality, sex, sexual orientation, disability, religion, political
                affiliation)</li>
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