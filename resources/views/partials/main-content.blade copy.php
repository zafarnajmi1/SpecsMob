<style>
    .gsm-pattern {
        background-image: repeating-linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0, rgba(255, 255, 255, 0.1) 1px, transparent 0, transparent 8px);
    }

    .gsm-article-title {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.7));
    }
</style>

<!-- Featured Reviews Section -->
<div class="flex flex-col md:flex-row md:h-[350px] gap-1">
    <!-- Main Featured Review -->
    @if($featuredReview)
        <article class="relative overflow-hidden cursor-pointer w-full md:w-[55%] h-[250px] md:h-full group">
            <a href="{{ route('review-detail', $featuredReview->slug) }}" class="block h-full">
                {{-- Metadata --}}
                <div class="absolute top-2 left-2 z-10 flex items-center gap-2">
                    <span
                        class="bg-black/50 text-white px-2 py-0.5 rounded-sm text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm">
                        <i class="far fa-clock mr-1 text-[#F9A13D]"></i>{{ $featuredReview->published_at->diffForHumans() }}
                    </span>
                </div>
                <div class="absolute top-2 right-2 z-10">
                    <span
                        class="bg-black/50 text-white px-2 py-0.5 rounded-sm text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm">
                        <i class="fa-regular fa-message mr-1 text-[#F9A13D]"></i>{{ $featuredReview->comments_count }}
                    </span>
                </div>

                {{-- Image --}}
                <img src="{{ $featuredReview->cover_image_url ? asset($featuredReview->cover_image_url) : asset('images/default-review.jpg') }}"
                    alt="{{ $featuredReview->title }}"
                 class="w-full h-full object-contain"/>
            <h2 class="review-title absolute bottom-3 left-2 text-[22px] md:text-[28px] font-bold text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.9)] bg-transparent px-2">
                        {{ $featuredReview->title }}
                    </h2>
            </a>
        </article>
    @endif

    <!-- Side Reviews -->
    <div class="side-reviews flex flex-col w-full md:w-[45%] md:h-full gap-1">
        @foreach($sideReviews as $index => $review)
            <div class="side-review relative overflow-hidden flex-1 h-[150px] md:h-auto cursor-pointer group">
                <a href="{{ route('review-detail', $review->slug) }}" class="block h-full">
                    {{-- Metadata --}}
                    <div class="absolute top-2 left-2 z-10">
                        <span
                            class="bg-black/50 text-white px-2 py-0.5 rounded-sm text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm">
                            <i class="far fa-clock mr-1 text-[#F9A13D]"></i>{{ $review->published_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="absolute top-2 right-2 z-10">
                        <span
                            class="bg-black/50 text-white px-2 py-0.5 rounded-sm text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm">
                            <i class="fa-regular fa-message mr-1 text-[#F9A13D]"></i>{{ $review->comments_count }}
                        </span>
                    </div>

                    <img src="{{ $review->cover_image_url ? asset($review->cover_image_url) : asset('images/default-review.jpg') }}"
                        alt="{{ $review->title }}"
                     class="w-full h-full object-contain"/>
                <h3 class="review-title absolute bottom-3 left-2 text-[16px] md:text-[18px] font-bold text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.9)] bg-transparent px-2">
                            {{ $review->title }}
                        </h3>
                </a>
            </div>
        @endforeach
    </div>
</div>

<!-- Featured Articles Section -->
<div class="flex flex-col md:flex-row md:h-[350px] gap-1 mt-6">
    <!-- Main Featured Article -->
    @if($featuredArticle)
        <article class="relative overflow-hidden cursor-pointer w-full md:w-[55%] h-[250px] md:h-full group">
            <a href="{{ route('article-detail', ['slug' => $featuredArticle->slug, 'type' => $featuredArticle->type]) }}"
                class="block h-full">
                {{-- Metadata --}}
                <div class="absolute top-2 left-2 z-10">
                    <span
                        class="bg-black/50 text-white px-2 py-0.5 rounded-sm text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm">
                        <i
                            class="far fa-clock mr-1 text-[#F9A13D]"></i>{{ $featuredArticle->published_at->diffForHumans() }}
                    </span>
                </div>
                <div class="absolute top-2 right-2 z-10">
                    <span
                        class="bg-black/50 text-white px-2 py-0.5 rounded-sm text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm">
                        <i class="fa-regular fa-message mr-1 text-[#F9A13D]"></i>{{ $featuredArticle->comments_count }}
                    </span>
                </div>
            <img src="{{ $featuredArticle->thumbnail_url }}" 
                 alt="{{ $featuredArticle->title }}" 
                 class="w-full h-full object-cover"/>
            <h2 class="review-title absolute bottom-3 left-2 text-[22px] md:text-[28px] font-bold text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.9)] bg-transparent px-2">
                        {{ $featuredArticle->title }}
                    </h2>
            </a>
        </article>
    @endif

    <!-- Side Articles -->
    <div class="side-reviews flex flex-col w-full md:w-[45%] md:h-full gap-1">
        @foreach($sideArticles as $index => $article)
            <div class="side-review relative overflow-hidden flex-1 h-[150px] md:h-auto cursor-pointer group">
                <a href="{{ route('article-detail', ['slug' => $article->slug, 'type' => $article->type]) }}"
                    class="block">
                    {{-- Metadata --}}
                    <div class="absolute top-2 left-2 z-10">
                        <span
                            class="bg-black/50 text-white px-2 py-0.5 rounded-sm text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm">
                            <i class="far fa-clock mr-1 text-[#F9A13D]"></i>{{ $article->published_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="absolute top-2 right-2 z-10">
                        <span
                            class="bg-black/50 text-white px-2 py-0.5 rounded-sm text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm">
                            <i class="fa-regular fa-message mr-1 text-[#F9A13D]"></i>{{ $article->comments_count }}
                        </span>
                    </div>
                <img src="{{ $article->thumbnail_url }}" 
                     alt="{{ $article->title }}" 
                     class="w-full h-full object-contain"/>
                <h3 class="review-title absolute bottom-3 left-2 text-[16px] md:text-[18px] font-bold text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.9)] bg-transparent px-2">
                            {{ $article->title }}
                        </h3>
                </a>
            </div>
        @endforeach
    </div>
</div>

<!-- Latest Articles Grid Section -->
<!-- @if($latestArticles->count() > 3)
<div class="mt-10">
    <h2 class="text-2xl font-bold mb-6 border-l-4 border-blue-600 pl-3">Latest News</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($latestArticles->slice(3) as $article)
        <div class="article-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <a href="{{ route('article-detail', ['slug' => $article->slug, 'type' => $article->type]) }}">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ $article->thumbnail_url }}" 
                         alt="{{ $article->title }}" 
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"/>
                    <div class="absolute top-2 right-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                        {{ ucfirst($article->type) }}
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-2 line-clamp-2">{{ $article->title }}</h3>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span><i class="far fa-clock mr-1"></i>{{ $article->published_at->diffForHumans() }}</span>
                        <span><i class="fa-regular fa-message mr-1"></i>{{ $article->comments_count }}</span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif -->