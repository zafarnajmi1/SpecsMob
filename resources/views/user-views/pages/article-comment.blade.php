@extends('layouts.app')

@section('title', "$article->title Comments")

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
<div class="relative w-full max-w-full h-72 md:h-[314px] bg-cover bg-center box-border" style="background-image: url('{{ $article->thumbnail_url }}');">
    <!-- Top Bar -->
    <div class="absolute top-0 left-0 w-full flex justify-between items-center md:px-4 py-2 z-10 text-white text-xs md:text-sm opacity-90">
    </div>

    <!-- Bottom Title & Info -->
    <div class="absolute bottom-0 left-0 w-full z-10">
        <h1 class="text-white px-4 text-3xl md:text-4xl font-bold drop-shadow-xl mb-2">{{ $article->title }}</h1>

        <div class="flex flex-col md:flex-row flex-wrap justify-end items-center gap-2 px-4 bg-black/20 backdrop-blur-sm border-t border-gray-400 text-white text-sm h-[35px]">
            <!-- Nav Links -->
            <div class="flex gap-4 h-full">
                <a href="{{ route('article.comments', ['slug' => $article->slug, 'id' => $article->id]) }}" class="flex items-center gap-1 h-full hover:bg-[#d50000] transition-colors px-2 transition">
                    <i class="fa-regular fa-comment"></i> COMMENTS ({{ $article->comments_count ?? 0 }})
                </a>
                <a href="#comments" class="flex items-center gap-1 h-full hover:bg-[#d50000] transition-colors px-2 transition">
                    <i class="fa-regular fa-message"></i> POST YOUR COMMENTS 
                </a>
            </div>
        </div>
    </div>
</div>

    <div
        class="flex justify-between items-center border-t border-gray-400 shadow text-white px-4 md:px-6 mt-3 py-2 bg-[#f0f0f0] backdrop-blur-sm">
        <a href="{{ route('comment.post', ['slug' => $article->slug, 'id' => $article->id, 'type'=>'article'])}}"
            class="flex justify-center items-center px-2 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase">
            <span>POST YOUR OPINION</span>
        </a>
        <div>
            <span class="text-[#555] text-[14px]">Pages: </span>
            <strong
                class="bg-[#555] border border-[#333] text-[#f0f0f0] px-[11px] py-[5px] text-[13px]">1</strong>
        </div>
    </div>

    <!-- Opinions Section -->
    <div class="bg-[#f0f0f0]">
        <div class="space-y-6">
            @foreach ($comments as $comment)
                <div class="flex flex-col md:flex-row gap-4 bg-white px-4 py-3">
                    <div class="w-12 h-12 flex items-center justify-center text-white text-xl"
                        style="background-color: {{ $comment->avatar_color ?? '#8ca6c6' }}">
                        {{ strtoupper(substr($comment->username, 0, 1)) }}
                    </div>

                    <div class="flex-1 flex flex-col">
                        <div>
                            <div class="flex flex-wrap items-center justify-between gap-x-4 mb-2">
                                <div class="font-[700] text-[#757575] text-[13px]">
                                    {{ $comment->username ?? 'Anonymous' }}
                                </div>
                                <div class="flex gap-4">
                                    <p class="text-[#777] text-[12px] font-[400]">
                                        <i class="fa-solid fa-clock"></i>
                                        <time>{{ $comment->created_at->format('d M Y') }}</time>
                                    </p>
                                    <p class="flex items-center gap-1 text-[#777] text-[12px] font-[400]">
                                        <i class="fa-solid fa-location-dot"></i>
                                        {{ $comment->location ?? 'XYZ' }}
                                    </p>
                                </div>
                            </div>

                            <p class="leading-relaxed mb-2 text-[15px]">
                                {{ $comment->content }}
                            </p>
                        </div>

                        <div class="flex justify-end mt-4">
                            <a href="#"
                                class="flex justify-center items-center px-2 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase">
                                <span>REPLY</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="flex flex-col md:flex-row gap-4 bg-white px-4 py-3">
                <div class="w-12 h-12 flex items-center justify-center text-white text-xl"
                    style="background-color: {{ $comment->avatar_color ?? '#8ca6c6' }}">
                    {{ strtoupper(substr($comment->username, 0, 1)) }}
                </div>

                <div class="flex-1 flex flex-col">
                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-x-4 mb-2">
                            <div class="font-[700] text-[#757575] text-[13px]">
                                {{ $comment->username ?? 'Anonymous' }}
                            </div>
                            <div class="flex gap-4">
                                <p class="text-[#777] text-[12px] font-[400]">
                                    <i class="fa-solid fa-clock"></i>
                                    <time>{{ $comment->created_at->format('d M Y') }}</time>
                                </p>
                                <p class="flex items-center gap-1 text-[#777] text-[12px] font-[400]">
                                    <i class="fa-solid fa-location-dot"></i>
                                    {{ $comment->location ?? 'XYZ' }}
                                </p>
                            </div>
                        </div>

                        <p>
                            <span class="text-[#777] text-[12px] font-[400] mb-[3px]">
                                <i class="fa-solid fa-share"></i>
                                {{ $comment->reply->username ?? 'Anonymous' }},
                                <time>{{ $comment->created_at->format('d M Y') }}</time>
                            </span>

                            <span
                                class="text-[#888] bg-[#f7f7f7] p-[10px] block mb-[15px] text-[11px] font-[700]">
                                {{ $comment->reply->content ?? 'No content available.' }}
                            </span>

                            {{ $comment->content ?? 'No content available.' }}
                        </p>
                    </div>

                    <div class="flex justify-end mt-4">
                        <a href="#"
                            class="flex justify-center items-center px-2 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase">
                            <span>REPLY</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="flex justify-start items-center border-t border-gray-400 shadow px-4 md:px-6 mt-3 py-2 bg-[#f0f0f0] backdrop-blur-sm">
        <a href="{{ route('comment.post', ['slug' => $article->slug, 'id' => $article->id, 'type' => 'article'])}}"
            class="flex justify-center items-center px-2 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase">
            <span>POST YOUR OPINION</span>
        </a>
    </div>

@endsection
