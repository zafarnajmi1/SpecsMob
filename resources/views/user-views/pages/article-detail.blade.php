@extends('layouts.app')

@section('title', $article->title) {{-- Fixed: Use title, not slug --}}

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
<!-- Article Header -->
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

<!-- Social Share -->
<div class="flex gap-3 px-4 py-3 md:px-6">
    <a href="https://www.facebook.com/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="flex items-center gap-1 text-white bg-blue-600 px-3 py-1 rounded hover:bg-blue-700 transition">
        <i class="fa-brands fa-facebook-f"></i> Share
    </a>
    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($article->title) }}" target="_blank" class="flex items-center gap-1 text-white bg-blue-400 px-3 py-1 rounded hover:bg-blue-500 transition">
        <i class="fa-brands fa-twitter"></i> Tweet
    </a>
    <button onclick="copyToClipboard('{{ request()->fullUrl() }}')" class="flex items-center gap-1 text-white bg-gray-600 px-3 py-1 rounded hover:bg-gray-700 transition">
        <i class="fa-solid fa-link"></i> Copy Link
    </button>
</div>

<!-- Article Meta -->
<div class="px-4 py-3 md:px-6 text-xs md:text-sm text-gray-700 border-b border-gray-200">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2">
            <span class="font-semibold">Author:</span>
            <div class="flex items-center gap-2">
                @if($article->author->image)
                <img src="{{ $article->author->image_url ?? asset('images/default-avatar.png') }}" 
                     alt="{{ $article->author->name }}" 
                     class="w-8 h-8 rounded-full">
                @endif
                <span>{{ $article->author->name }}</span>
            </div>
        </div>
        <span class="font-semibold">Published:</span> 
        <time datetime="{{ $article->published_at->toISOString() }}">
            {{ $article->published_at->format('F d, Y \a\t g:i A') }}
        </time>
        <span class="font-semibold">Views:</span> {{ number_format($article->views_count) }}
        
        @if($article->tags->count() > 0)
        <div class="flex items-center gap-2">
            <span class="font-semibold">Tags:</span>
            <div class="flex flex-wrap gap-1">
                @foreach($article->tags as $tag)
                <a href="{{ route('tags.show', $tag->slug) }}" class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded hover:bg-gray-200">
                    {{ $tag->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Article Body -->
<div class="py-6 px-4 md:px-6 overflow-x-auto">
    <article class="prose prose-lg max-w-full break-words">
        {!! $article->body !!}
    </article>
</div>

<!-- Related Articles -->
@if(isset($relatedArticles) && $relatedArticles->count() > 0)
<div class="mt-8 px-4 md:px-6">
    <h3 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">Related Articles</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($relatedArticles as $related)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <a href="{{ route('article-detail', ['slug' => $related->slug, 'type' => $related->type]) }}">
                <div class="relative h-40">
                    <img src="{{ $related->thumbnail_url }}" 
                         alt="{{ $related->title }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute top-2 right-2 px-2 py-1 text-xs font-semibold text-white rounded 
                        {{ $related->type == 'news' ? 'bg-blue-600' : '' }}
                        {{ $related->type == 'article' ? 'bg-green-600' : '' }}
                        {{ $related->type == 'featured' ? 'bg-purple-600' : '' }}">
                        {{ ucfirst($related->type) }}
                    </div>
                </div>
                <div class="p-4">
                    <h4 class="font-bold text-gray-800 hover:text-red-600 mb-2 line-clamp-2">{{ $related->title }}</h4>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span><i class="far fa-clock mr-1"></i>{{ $related->published_at->diffForHumans() }}</span>
                        <span><i class="fa-regular fa-message mr-1"></i>{{ $related->comments_count }}</span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Comments Section -->
<div class="bg-[#f0f0f0]">
     <h2 class="bg-white pl-[10px] pt-[10px] pr-[5px] mt-2 text-[#555] border-b border-[#ddd]">
            <a href="#" class="hover:text-[#d50000] font-bold text-lg transition hover:underline">READER COMMENTS</a>
        </h2>
        <div class="space-y-5">
            @foreach ($comments as $comment)
                <div class="flex flex-col md:flex-row gap-4 bg-white  px-4 py-3">
                    <div class="w-12 h-12 flex items-center justify-center text-white text-xl"
                        style="background-color: {{ $comment->avatar_color ?? '#8ca6c6' }}">
                        {{ strtoupper(substr($comment->username, 0, 1)) }}
                    </div>
                    <div class="flex-1 flex flex-col">
                        <div>
                            <div class="flex flex-wrap items-center justify-between gap-x-4 mb-2">
                                <div class="font-[700] text-[#555] text-[13px]"><b>{{ $comment->username ?? 'Anonymous' }}</b></div>
                                <div class="flex gap-4">
                                    <p class="text-[#777] text-[12px] font-[400]"><i class="fa-solid fa-clock"></i>
                                        <time>{{ $comment->created_at->format('d M Y') }}</time></p>
                                    <p class="flex items-center gap-1 text-[#777] text-[12px] font-[400]"><i
                                            class="fa-solid fa-location-dot"></i>
                                        {{ $comment->location ?? 'XYZ' }}</p>
                                </div>
                            </div>
                            <p class="leading-relaxed mb-2 text-[15px]">{{ $comment->content }}</p>
                        </div>
                        <div class="flex justify-end mt-4">
                            <a href="#"
                                class="flex justify-center items-center px-2 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase"><span>REPLY</span></a>
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
                            <div class="font-[700] text-[#555] text-[13px]"><b>{{ $comment->username ?? 'Anonymous' }}</b></div>
                            <div class="flex gap-4">
                                <p class="text-[#777] text-[12px] font-[400]"><i class="fa-solid fa-clock"></i>
                                    <time>{{ $comment->created_at->format('d M Y') }}</time></p>
                                <p class="flex items-center gap-1 text-[#777] text-[12px] font-[400]"><i
                                        class="fa-solid fa-location-dot"></i>
                                    {{ $comment->location ?? 'XYZ' }}</p>
                            </div>
                        </div>
                        <p>
                            <span class="text-[#777] text-[12px] font-[400] mb-[3px]">
                                <i class="fa-solid fa-share"></i>
                                {{ $comment->reply->username ?? 'Anonymous' }},
                                <time>{{ $comment->created_at->format('d M Y') }}</time>
                            </span>
                            <span class="text-[#888] bg-[#f7f7f7] p-[10px] block mb-[15px] text-[11px] font-[700]">
                                {{ $comment->reply->content ?? 'No content available.' }}
                            </span>
                            {{ $comment->content ?? 'No content available.' }}
                        </p>
                    </div>
                    <div class="flex justify-end mt-4">
                        <a href="#"
                            class="flex justify-center items-center px-2 text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase"><span>REPLY</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="flex justify-between items-center border-t border-gray-400 shadow px-4 md:px-6 mt-3 pb-2 pt-5 bg-[#f0f0f0] backdrop-blur-sm">
        <div class="flex items-center gap-3">
            <a href="#"
            class="flex justify-center items-center px-2 font-bold text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase"><span>READ ALL COMMENTSS</span></a>
        <a href="{{ route('comment.post', ['slug' => $article->slug, 'id' => $article->id, 'type' => 'article']) }}"
            class="flex justify-center items-center px-2 font-bold text-[14px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase"><span>POST
                YOUR COMMENTS</span></a>
        </div>
        <span>
            Total reader comments: {{ $comments->count() }}
        </span>
    </div>

<!-- JavaScript for Copy Link -->
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Link copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>

@endsection