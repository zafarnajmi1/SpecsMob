<!-- Featured Reviews Section -->
<div class="flex flex-col md:flex-row md:h-[310px] gap-1">
    <!-- Main Featured Review -->
    @if($featuredReview)
    <article class="relative overflow-hidden cursor-pointer w-full md:w-[55%]">
        <a href="{{ route('review-detail', $featuredReview->slug) }}">
            <div class="review-meta absolute text-white top-0 left-0 w-full flex items-center justify-between px-3 py-2 text-[11px] text-black bg-transparent">
                <span><i class="far fa-clock mr-1"></i>{{ $featuredReview->published_at->diffForHumans() }}</span>
                <span><i class="fa-regular fa-message mr-1"></i>{{ $featuredReview->comments_count }}</span>
            </div>
            <img src="{{ $featuredReview->cover_image_url ? asset($featuredReview->cover_image_url) : asset('images/default-review.jpg') }}" 
                 alt="{{ $featuredReview->title }}" 
                 class="w-full h-full object-cover"/>
            <h2 class="review-title absolute bottom-3 left-2 text-[22px] md:text-[28px] font-bold text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.9)] bg-transparent px-2">
                {{ $featuredReview->title }}
            </h2>
        </a>
    </article>
    @endif
    
    <!-- Side Reviews -->
    <div class="side-reviews flex flex-col w-full md:w-[45%] h-full gap-1">
        @foreach($sideReviews as $index => $review)
        <div class="side-review relative overflow-hidden h-full cursor-pointer">
            <a href="{{ route('review-detail', $review->slug) }}">
                <div class="review-meta text-white absolute top-0 left-0 w-full flex items-center justify-end px-3 py-2 text-[11px] text-black bg-transparent">
                    <span><i class="fa-regular fa-message mr-1"></i>{{ $review->comments_count }}</span>
                </div>
                <img src="{{ $review->cover_image_url ? asset($review->cover_image_url) : asset('images/default-review.jpg') }}" 
                     alt="{{ $review->title }}" 
                     class="w-full h-full object-cover"/>
                <h3 class="review-title absolute bottom-3 left-2 text-[16px] md:text-[18px] font-bold text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.9)] bg-transparent px-2">
                    {{ $review->title }}
                </h3>
            </a>
        </div>
        @endforeach
    </div>
</div>

<!-- Featured Articles Section -->
<div class="flex flex-col md:flex-row md:h-[310px] mt-5">
    <!-- Main Featured Article -->
    @if($featuredArticle)
    <article class="relative overflow-hidden cursor-pointer w-full md:w-[55%]">
        <a href="{{ route('article-detail', ['slug' => $featuredArticle->slug, 'type' => $featuredArticle->type]) }}">
            <div class="review-meta absolute text-white top-0 left-0 w-full flex items-center justify-between px-3 py-2 text-[11px] text-black bg-transparent">
                <span><i class="far fa-clock mr-1"></i>{{ $featuredArticle->published_at->diffForHumans() }}</span>
                <span><i class="fa-regular fa-message mr-1"></i>{{ $featuredArticle->comments_count }}</span>
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
    <div class="side-reviews flex flex-col w-full md:w-[45%] h-full">
        @foreach($sideArticles as $index => $article)
        <div class="side-review relative overflow-hidden h-full cursor-pointer {{ $index == 0 ? '' : 'mt-2' }}">
            <a href="{{ route('article-detail', ['slug' => $article->slug, 'type' => $article->type]) }}">
                <div class="review-meta text-white absolute top-0 left-0 w-full flex items-center justify-end px-3 py-2 text-[11px] text-black bg-transparent">
                    <span><i class="fa-regular fa-message mr-1"></i>{{ $article->comments_count }}</span>
                </div>
                <img src="{{ $article->thumbnail_url }}" 
                     alt="{{ $article->title }}" 
                     class="w-full h-full object-cover"/>
                <h3 class="review-title absolute bottom-3 left-2 text-[16px] md:text-[18px] font-bold text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.9)] bg-transparent px-2">
                    {{ $article->title }}
                </h3>
            </a>
        </div>
        @endforeach
    </div>
</div>

<!-- Latest Articles Grid Section -->
@if($latestArticles->count() > 3)
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
@endif