@extends('layouts.app')

@section('title', "$article->title Comments")

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
    <div class="relative w-full max-w-full h-72 md:h-[314px] bg-cover bg-center box-border"
        style="background-image: url('{{ $article->thumbnail_url }}');">
        <!-- Top Bar -->
        <div
            class="absolute top-0 left-0 w-full flex justify-between items-center md:px-4 py-2 z-10 text-white text-xs md:text-sm opacity-90">
        </div>

        <!-- Bottom Title & Info -->
        <div class="absolute bottom-0 left-0 w-full z-10">
            <h1 class="text-white px-4 text-3xl md:text-4xl font-bold drop-shadow-xl mb-2">{{ $article->title }}</h1>

            <div
                class="flex flex-col md:flex-row flex-wrap justify-end items-center gap-2 px-4 bg-black/20 backdrop-blur-sm border-t border-gray-400 text-white text-sm h-[35px]">
                <!-- Nav Links -->
                <div class="flex gap-4 h-full">
                    <a href="{{ route('article.comments', ['slug' => $article->slug, 'id' => $article->id]) }}"
                        class="flex items-center gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition">
                        <i class="fa-regular fa-comment"></i> COMMENTS ({{ $article->comments_count ?? 0 }})
                    </a>
                    <a href="{{ route('comment.post', ['slug' => $article->slug, 'id' => $article->id, 'type' => 'article'])}}"
                        class="flex items-center gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition">
                        <i class="fa-regular fa-message"></i> POST YOUR COMMENTS
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div
        class="flex justify-between items-center border-t border-gray-400 shadow text-white px-4 md:px-6 mt-3 py-2 bg-[#f0f0f0] backdrop-blur-sm">
        <a href="{{ route('comment.post', ['slug' => $article->slug, 'id' => $article->id, 'type' => 'article'])}}"
            class="flex justify-center items-center px-2 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase">
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

    <!-- Comments Section -->
    <div class="bg-[#f0f0f0]">
        <div class="space-y-[1px]">
            @forelse ($comments as $comment)
                @include('partials.article-comment', ['comment' => $comment])
            @empty
                <div class="bg-white p-10 text-center text-gray-500 italic">
                    No comments yet. Be the first to share your thoughts!
                </div>
            @endforelse
        </div>
    </div>

    <div
        class="flex justify-start items-center border-t border-gray-400 shadow px-4 md:px-6 mt-3 py-2 bg-[#f0f0f0] backdrop-blur-sm">
        <a href="{{ route('comment.post', ['slug' => $article->slug, 'id' => $article->id, 'type' => 'article'])}}"
            class="flex justify-center items-center px-2 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase">
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

@endsection

@push('scripts')
@endpush