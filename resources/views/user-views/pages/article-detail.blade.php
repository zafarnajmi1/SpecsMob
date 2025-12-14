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
                <a href="#comments" class="flex items-center gap-1 h-full hover:bg-[#d50000] transition-colors px-2 transition">
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
<div id="comments" class="bg-gray-100 border border-gray-300 my-5 px-4 md:px-6 py-6">
    <h2 class="text-lg font-semibold mb-6 text-gray-700 border-b border-gray-300 pb-3">
        @if($article->device)
        <a href="{{ route('devices.show', $article->device->slug) }}" class="hover:text-red-600 transition">{{ $article->device->name }}</a> - 
        @endif
        Reader Comments
    </h2>

    @if($comments->count() > 0)
    <div class="space-y-6">
        @foreach ($comments as $comment)
            <div class="flex flex-col md:flex-row gap-4 bg-white border border-gray-300 p-4 rounded">
                <div class="w-12 h-12 flex items-center justify-center rounded text-white font-bold text-xl" 
                     style="background-color: {{ $comment->avatar_color ?? '#8ca6c6' }}">
                    {{ strtoupper(substr($comment->username ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 flex flex-col">
                    <ul class="flex flex-wrap items-center text-gray-500 text-sm gap-x-4 mb-2">
                        <li class="font-semibold">{{ $comment->username ?? 'Anonymous' }}</li>
                        <li class="flex items-center gap-1">
                            <i class="fa-solid fa-location-dot"></i> {{ $comment->location ?? 'Unknown' }}
                        </li>
                        <li>
                            <time datetime="{{ isset($comment->created_at) ? $comment->created_at->toISOString() : '' }}">
                                {{ isset($comment->created_at) ? $comment->created_at->format('d M Y') : 'Recently' }}
                            </time>
                        </li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed mb-2">{{ $comment->content }}</p>
                    <div class="flex justify-end">
                        <button class="text-sm text-red-600 hover:underline reply-button" 
                                data-comment-id="{{ $comment->id ?? $loop->index }}">Reply</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-8">
        <i class="fa-regular fa-comment text-4xl text-gray-300 mb-4"></i>
        <p class="text-gray-600">No comments yet. Be the first to share your thoughts!</p>
    </div>
    @endif

    <!-- Footer Buttons -->
    <div class="border-t border-gray-300 bg-white px-4 py-4 flex flex-col md:flex-row justify-between items-center gap-3 mt-6">
        <div class="flex gap-3 flex-wrap">
            <a href="#comments" class="px-5 py-2 text-sm bg-gray-100 border border-gray-300 hover:bg-red-600 hover:text-white transition">
                Read all comments ({{ $comments->count() }})
            </a>
            <a href="#comment-form" class="px-5 py-2 text-sm bg-gray-100 border border-gray-300 hover:bg-red-600 hover:text-white transition">
                Post your comment
            </a>
        </div>
        <div class="text-gray-700 text-sm">
            Total comments: <strong>{{ $comments->count() }}</strong>
        </div>
    </div>
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