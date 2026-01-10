@extends('layouts.app')

@section('title', Auth::user()->name . ' - Account Settings')

@section('content')

    {{-- ================= HEADER (already correct) ================= --}}
    <div class="relative w-full h-72 md:h-[314px] bg-cover bg-center mb-[10px]"
        style='background-image: url("{{ asset('user/images/account.jpg') }}");'>

        <div class="absolute bottom-0 left-0 w-full z-10">
            <h1 class="text-white text-[40px] font-bold px-5 mb-3">
                Account Settings
            </h1>

            <div class="flex justify-end items-center text-sm text-white px-6 h-10
                        bg-[rgba(0,0,0,0.25)] backdrop-blur-sm">
                <div class="flex gap-4 h-full">
                    <a href="{{ route('user.posts', Auth::user()->username) }}"
                        class="flex items-center gap-1 hover:bg-[#F9A13D] px-3 transition">
                        <i class="fa-regular fa-message"></i>
                        POSTS {{ $posts_count }}
                    </a>

                    <span class="flex items-center gap-1 px-3">
                        <i class="fa-solid fa-hand-sparkles"></i>
                        UPVOTES {{ $upvotes }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="max-w-[900px] mx-auto px-4 py-8 space-y-10">

        {{-- ===== Change Avatar ===== --}}
        <section class="bg-white border rounded shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Change your avatar</h3>

            <form method="POST" action="{{ route('user.avatar.update', Auth::user()->username) }}"
                enctype="multipart/form-data">
                @csrf

                {{-- UPLOAD IMAGE --}}
                <label class="block mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="font-medium text-gray-700">Upload new profile image</span>
                    </div>
                    <input type="file" name="avatar_file"
                        class="w-full border px-3 py-2 rounded text-sm focus:ring-1 focus:ring-[#F9A13D] outline-none">
                    <p class="text-[11px] text-gray-500 mt-1">Recommended: Square image, max 2MB (JPG, PNG, GIF)</p>
                </label>

                {{-- AVATAR PREVIEW --}}
                <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4 pt-4 border-t border-gray-100">
                    <span class="text-sm font-semibold text-gray-700">Current appearance:</span>

                    <div
                        class="flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full border-2 border-white shadow-sm overflow-hidden flex-shrink-0">
                        @if(Auth::user()->image)
                            <img src="{{ asset('storage/' . Auth::user()->image) }}" class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center bg-[#F9A13D] text-white text-3xl font-bold uppercase">
                                {{ substr(Auth::user()->name ?? Auth::user()->username, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="text-xs text-gray-500 italic max-w-xs">
                        If no custom image is uploaded, we display your initials as your profile icon.
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button
                        class="bg-[#F9A13D] cursor-pointer text-white px-8 py-2.5 rounded font-bold hover:bg-[#e89531] transition shadow-md">
                        SAVE CHANGES
                    </button>
                </div>
            </form>
        </section>


        {{-- ===== Change Nickname ===== --}}
        <section class="bg-white border rounded shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Change your nickname</h3>

            <form method="POST" action="{{ route('user.nickname.update', Auth::user()->username) }}">
                @csrf

                <input type="text" name="nickname" value="{{ Auth::user()->username }}" required minlength="2"
                    maxlength="20" autocomplete="off" class="w-full border px-3 py-2 rounded mb-4">

                <div class="flex justify-end">
                    <button class="bg-[#F9A13D] cursor-pointer text-white px-5 py-2 rounded hover:bg-[#F9A13D]">
                        Change
                    </button>
                </div>
            </form>
        </section>

        {{-- ===== Download Data ===== --}}
        <section class="bg-white border rounded shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-2">Download your data</h3>
            <p class="text-sm text-gray-600 mb-4">
                You have access to all the data we stored for you in machine readable format.
            </p>

            <form method="POST" action="{{ route('user.data.download', Auth::user()->username) }}">
                @csrf
                <div class="flex justify-end">
                    <button class="bg-gray-700 cursor-pointer text-white px-5 py-2 rounded hover:bg-gray-800">
                        Download
                    </button>
                </div>
            </form>
        </section>

        {{-- ===== Freeze Account ===== --}}
        <section class="bg-white border rounded shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-2">Freeze your account</h3>
            <p class="text-sm text-gray-600 mb-4">
                This will hide all your personal data. You will not be able to post until you unfreeze.
            </p>

            <form method="POST" action="">
                @csrf
                <div class="flex justify-end">
                    <button class="bg-yellow-600 cursor-pointer text-white px-5 py-2 rounded hover:bg-yellow-700">
                        Freeze
                    </button>
                </div>
            </form>
        </section>

        {{-- ===== Delete Account ===== --}}
        <section class="bg-white border rounded shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-2 text-[#F9A13D]">Delete your account</h3>
            <p class="text-sm text-gray-600 mb-4">
                This will permanently delete your account and all personal data.
            </p>

            <form method="POST" action="{{ route('user.delete', Auth::user()->username) }}"
                onsubmit="return confirm('This action is permanent. Are you sure?')">
                @csrf
                @method('DELETE')

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-[#F9A13D] cursor-pointer text-white px-5 py-2 rounded font-bold hover:bg-red-600 transition">
                        DELETE PERMANENTLY
                    </button>
                </div>
            </form>
        </section>

    </div>
@endsection