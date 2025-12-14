{{-- resources/views/components/article.blade.php --}}
@props(['article'])

<a href="{{ route('article-detail', ['slug' => $article->slug, 'type' => $article->type]) }}">
    <article class="flex flex-col md:flex-row bg-white h-auto md:h-[180px] overflow-hidden">
        {{-- Image --}}
        <div class="w-full md:w-[220px] flex-shrink-0">
            <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover" />
        </div>
    
        {{-- Text content --}}
        <div class="flex flex-col justify-between flex-1 p-4 gap-3">
            <div class="flex flex-col gap-3">
                <h3 class="text-[18px] md:text-[20px] leading-[1.3] font-semibold text-[#555]">
                        {{ $article->title }}
                </h3>
    
                <p class="text-[13px] md:text-[14px] leading-[1.5] text-[#555] line-clamp-2">
                    {{ $article->excerpt }}
                </p>
            </div>
    
            <div class="w-full flex justify-end gap-4 px-1 text-[11px] font-semibold leading-[1.6] text-[#9c9c9c]">
                <span class="flex items-center gap-1">
                    <i class="far fa-clock"></i>{{ $article->published_at->diffForHumans() }}
                </span>
                <span class="flex items-center gap-1">
                    <i class="fa-regular fa-message"></i>{{ $article->comments->count() }}
                </span>
            </div>
        </div>
    </article>
</a>