@extends('layouts.app')

@section('title', $item->title ?? $item->name)

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')

    <!-- Header -->
    <div class="relative w-full max-w-full h-72 md:h-[314px] bg-cover bg-center" style="background-image: url('{{ asset( $type == 'review' ? $item->cover_image_url : $item->thumbnail_url) }}');">
      <div class="absolute top-0 left-0 w-full flex justify-between items-center md:px-4 py-2 z-10 text-white text-xs md:text-sm opacity-90">
            <div>
                <i class="far fa-clock mr-1"></i> {{ $item->published_at->diffForHumans() }}
            </div>
            <div>
                <i class="fa-regular fa-message mr-1"></i> {{ $item->comments_count }}
            </div>
        </div>

        <!-- Title -->
        <div class="absolute bottom-0 left-0 w-full z-10">
            <h1 class="text-white px-4 text-3xl md:text-4xl font-bold drop-shadow-xl mb-2">{{ $item->title ?? $item->name }}</h1>
            <div class="flex flex-col md:flex-row flex-wrap justify-between items-center gap-2 px-4 bg-black/40 backdrop-blur-sm border-t uppercase text-shadow-[1px 1px 1px rgba(0, 0, 0, .8)] border-gray-400 text-white text-sm h-[35px]">
                <div class="flex items-center gap-2 h-full">
                    <!-- Rating Display (if needed) -->
                    <div class="flex">
                        <!-- Your rating logic here -->
                    </div>
                    <span class="font-semibold">{{ $item->rating ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Comment Section -->
    <div class="bg-[#f0f0f0]">
        @auth
            <div class="p-5 mt-4 normal-text user-submit">

                <h3 class="text-[21px] font-bold mb-4 text-[#555]">
                    Post your comment
                </h3>

                <form method="POST" action="{{ route("comment.store", ['slug' => $item->slug, 'id' => $item->id, 'type' => $type]) }}">
                    @csrf

                    <div class="mb-4 flex justify-between items-center border-b border-b-[#d4d4d4] pb-[9px] pt-[3px]">
                        <div class="text-green-700 text-lg">
                            Logged in
                        </div>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                            class="flex justify-center items-center font-bold px-3 py-1 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase">
                            <span>Logout</span>
                        </a>
                    </div>

                    <!-- COMMENT -->
                    <fieldset class="mb-4">
                        <label class="block text-lg mb-1">Your comment</label>
                        <textarea name="comment" rows="8" class="w-full border-1 border-[#a9a9a9] px-3 py- bg-white" required>{{ old('comment') }}</textarea>
                    </fieldset>

                    <!-- SUBMIT -->
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
                <p class="mb-3 text-[#555]">You must be logged in to post a comment.</p>
                <a href="{{ route('login') }}" class="bg-[#d50000] text-white px-4 py-2 inline-block">
                    Login
                </a>
            </div>
        @endguest
    </div>

@endsection
