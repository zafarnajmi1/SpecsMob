@extends('layouts.app') @section('title', $article->title)
{{-- Fixed: Use title, not slug --}}

@section('sidebar') @include('partials.aside') @endsection @section('content')
    <section class="hidden lg:block">
        <!-- Article Header -->
        <div class="relative w-full max-w-full h-72 md:h-[314px] bg-cover bg-center box-border"
            style="background-image: url('{{ $article->thumbnail_url }}');">
            <!-- Top Bar -->
            <div
                class="absolute top-0 left-0 w-full flex justify-between items-center md:px-4 py-2 z-10 text-white text-xs md:text-sm opacity-90 shadow-sm bg-black/10">
                <div class="flex items-center gap-2">
                    <i class="far fa-clock"></i> {{ $article->published_at->diffForHumans() }}
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-message"></i> {{ $article->comments_count }}
                </div>
            </div>

            <!-- Bottom Title & Info -->
            <div class="absolute bottom-0 left-0 w-full z-10">
                <h1 class="text-white px-4 text-3xl md:text-4xl font-bold drop-shadow-xl mb-2">
                    {{ $article->title }}
                </h1>

                <div
                    class="flex flex-col md:flex-row flex-wrap justify-end items-center gap-2 px-4 bg-black/20 backdrop-blur-sm border-t border-gray-400 text-white text-sm h-[35px]">
                    <!-- Nav Links -->
                    <div class="flex gap-4 h-full">
                        <a href="{{ route('article.comments', ['slug' => $article->slug, 'id' => $article->id]) }}"
                            class="flex items-center gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition">
                            <i class="fa-regular fa-comment"></i> COMMENTS ({{ $article->comments_count ?? 0
                                    }})
                        </a>
                        <a href="{{ route('comment.post', ['slug' => $article->slug, 'id' => $article->id, 'type' => 'article']) }}"
                            class="flex items-center gap-1 h-full hover:bg-[#F9A13D] transition-colors px-2 transition">
                            <i class="fa-regular fa-message"></i> POST YOUR COMMENTS
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
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($article->title) }}"
                target="_blank"
                class="flex items-center gap-1 text-white bg-blue-400 px-3 py-1 rounded hover:bg-blue-500 transition">
                <i class="fa-brands fa-twitter"></i> Tweet
            </a>
            <button onclick="copyToClipboard('{{ request()->fullUrl() }}')"
                class="flex items-center gap-1 text-white bg-gray-600 px-3 py-1 rounded hover:bg-gray-700 transition">
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
                                alt="{{ $article->author->name }}" class="w-8 h-8 rounded-full" />
                        @endif
                        <span>{{ $article->author->name }}</span>
                    </div>
                </div>
                <span class="font-semibold">Published:</span>
                <time datetime="{{ $article->published_at->toISOString() }}">
                    {{ $article->published_at->format('F d, Y \a\t g:i A') }}
                </time>
                <span class="font-semibold">Views:</span>
                {{ number_format($article->views_count) }}

                @if($article->tags->count() > 0)
                    <div class="flex items-center gap-2">
                        <span class="font-semibold">Tags:</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach($article->tags as $tag)
                                <a href="{{ route('tags.show', $tag->slug) }}"
                                    class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded hover:bg-gray-200">
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
                <h3 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">
                    Related Articles
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($relatedArticles as $related)
                        <div
                            class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                            <a href="{{ route('article-detail', ['slug' => $related->slug, 'type' => $related->type]) }}">
                                <div class="relative h-40">
                                    <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}"
                                        class="w-full h-full object-cover" />
                                    <div
                                        class="absolute top-2 right-2 px-2 py-1 text-xs font-semibold text-white rounded 
                                                                                                {{ $related->type == 'news' ? 'bg-blue-600' : '' }}
                                                                                                {{ $related->type == 'article' ? 'bg-green-600' : '' }}
                                                                                                {{ $related->type == 'featured' ? 'bg-purple-600' : '' }}">
                                        {{ ucfirst($related->type) }}
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h4 class="font-bold text-gray-800 hover:text-[#F9A13D] mb-2 line-clamp-2">
                                        {{ $related->title }}
                                    </h4>
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
                <a href="{{ route('article.comments', ['slug' => $article->slug, 'id' => $article->id]) }}" class="hover:text-[#F9A13D] font-bold text-lg transition hover:underline uppercase">Reader
                    Comments</a>
            </h2>

            <div class="space-y-[1px] bg-[#ddd]">
                @forelse ($comments as $comment)
                @include('partials.article-comment', ['comment' => $comment]) @empty
                    <div class="bg-white p-10 text-center text-gray-500 italic">
                        No comments yet. Be the first to share your thoughts!
                    </div>
                @endforelse
            </div>
        </div>

        <div
            class="flex justify-between items-center border-t border-gray-400 shadow px-4 md:px-6 mt-3 pb-4 pt-5 bg-[#f0f0f0]">
            <div class="flex items-center gap-3">
                <a href="{{ route('article.comments', ['slug' => $article->slug, 'id' => $article->id]) }}"
                    class="flex justify-center items-center px-3 py-1 font-bold text-[12px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase tracking-tighter shadow-sm">
                    <span>READ ALL COMMENTS</span>
                </a>
                <a href="{{ route('comment.post', ['slug' => $article->slug, 'id' => $article->id, 'type' => 'article']) }}"
                    class="flex justify-center items-center px-3 py-1 font-bold text-[12px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase tracking-tighter shadow-sm">
                    <span>POST YOUR COMMENT</span>
                </a>
            </div>
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">
                Total reader comments:
                <span class="text-[#F9A13D] ml-1">{{ $article->comments_count }}</span>
            </span>
        </div>
    </section>

    <section class="lg:hidden">
        <div class="flex gap-3 pt-3">
            <a href="{{ route('article.comments', ['slug' => $article->slug, 'id' => $article->id]) }}"
                class="uppercase text-[14px] font-bold bg-[#F9A13D] text-white px-3 py-2 rounded-sm shadow">comments
                ({{ $article->comments_count }})</a>
            <a href="{{ route('comment.post', ['slug' => $article->slug, 'id' => $article->id, 'type' => 'article'])}}"
                class="uppercase text-[14px] font-bold bg-[#F9A13D] text-white px-3 py-2 rounded-sm shadow">post your
                comment</a>
        </div>

        <div class="py-6 px-4 md:px-6 overflow-x-auto">
            <article class="prose prose-lg max-w-full break-words">
                {!! $article->body !!}
            </article>
        </div>

        @if(isset($recommendedArticles) && $recommendedArticles->count() > 0)
            <div class="bg-white rounded shadow mb-4">
                <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
                    Recommended
                </h4>

                <!-- Horizontal slider-->
                <div class="swiper featuredNewsSwiper px-4">
                    <div class="swiper-wrapper px-3 pb-5">
                        @foreach ($recommendedArticles as $article)
                            <a href="{{ route('article-detail', ['slug' => $article->slug, 'type' => $article->type]) }}"
                                class="swiper-slide group bg-transparent !p-0">
                                <div class="max-w-[176px] h-[120px] overflow-hidden relative shadow-sm">
                                    <img src="{{ $article->thumbnail_url }}" class="w-full h-full object-cover block">
                                </div>

                                <h5
                                    class="font-bold text-[14px] leading-tight mt-3 text-gray-800 group-hover:text-[#F9A13D] line-clamp-2">
                                    {{ $article->title }}
                                </h5>
                            </a>
                        @endforeach
                    </div>
                    <div class="swiper-scrollbar"></div>
                </div>

            </div>
        @endif

        {{-- Reader Comments Header --}}
        <div class="bg-white rounded shadow mb-4">
            <h4
                class="flex justify-between items-center uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
                <span>Reader Comments</span>
                <a href="{{ route('comment.post', ['slug' => $article->slug, 'id' => $article->id, 'type' => 'article'])}}"
                    class="text-[12px] text-[#555] hover:text-[#F9A13D] transition-colors font-normal no-underline lowercase first-letter:uppercase">
                    <i class="fa-regular fa-message mr-1"></i> Post your comment
                </a>
            </h4>
        </div>

        {{-- Popular Articles --}}
        @if(isset($popularArticles) && $popularArticles->count() > 0)
            <div class="bg-white rounded shadow mb-4 pb-2">
                <h4 class="uppercase text-[#F9A13D] px-5 py-3 font-bold text-lg border-b border-gray-100">
                    Popular Articles
                </h4>

                <div class="flex flex-col">
                    @foreach ($popularArticles as $pArticle)
                        @php
                            $percentage = $maxArticleViews > 0 ? ($pArticle->views_count / $maxArticleViews) * 100 : 0;
                        @endphp
                        <a href="{{ route('article-detail', ['slug' => $pArticle->slug, 'type' => $pArticle->type]) }}"
                            class="flex gap-4 p-3 hover:bg-gray-50 transition-colors group">
                            {{-- Image --}}
                            <div class="w-[90px] h-[60px] flex-shrink-0">
                                <img src="{{ $pArticle->thumbnail_url }}" alt="{{ $pArticle->title }}"
                                    class="w-full h-full object-cover rounded-sm">
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                {{-- Popularity Bar --}}
                                <div class="w-full h-[2px] bg-gray-200 mb-1.5 mt-0.5 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#F9A13D]" style="width: {{ $percentage }}%"></div>
                                </div>
                                <h3 class="font-bold text-[14px] leading-tight text-[#333] group-hover:text-[#F9A13D] line-clamp-2">
                                    {{ $pArticle->title }}
                                </h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Popular Devices --}}
        @if(isset($popularDevices) && $popularDevices->count() > 0)
            <div class="bg-white rounded shadow mb-4 pb-2">
                <h4 class="uppercase text-[#F9A13D] px-5 py-3 font-bold text-lg border-b border-gray-100">
                    Popular Devices
                </h4>

                <div class="flex flex-col">
                    @foreach ($popularDevices as $pDevice)
                        @php
                            $hits = $pDevice->stats->daily_hits ?? 0;
                            $percentage = $maxDeviceHits > 0 ? ($hits / $maxDeviceHits) * 100 : 0;
                        @endphp
                        <a href="{{ route('device-detail', ['slug' => $pDevice->slug]) }}"
                            class="flex gap-4 p-3 hover:bg-gray-50 transition-colors group">
                            {{-- Image --}}
                            <div class="w-[50px] flex-shrink-0 flex items-center justify-center">
                                <img src="{{ $pDevice->thumbnail_url }}" alt="{{ $pDevice->name }}"
                                    class="max-w-full max-h-[70px] object-contain">
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                {{-- Popularity Bar --}}
                                <div class="w-full h-[2px] bg-gray-200 mb-1.5 mt-0.5 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#F9A13D]" style="width: {{ $percentage }}%"></div>
                                </div>
                                <h3 class="font-bold text-[14px] leading-tight text-[#333] group-hover:text-[#F9A13D]">
                                    {{ $pDevice->brand->name ?? '' }} {{ $pDevice->name }}
                                </h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection @push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(
                function () {
                    alert("Link copied to clipboard!");
                },
                function (err) {
                    console.error("Could not copy text: ", err);
                }
            );
        }
    </script>
@endpush