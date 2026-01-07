@props(['article'])

<a href="{{ route('article-detail', ['slug' => $article->slug, 'type' => $article->type]) }}" class="group block mb-2 w-full">
    <article
        class="flex flex-col md:flex-row bg-white overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-[#eee] w-full">
        {{-- Image --}}
        <div class="w-full md:w-[240px] h-[200px] md:h-auto flex-shrink-0 overflow-hidden bg-gray-100">
            <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                onerror="this.src='{{ asset('user/images/default-preview.png') }}'" />
        </div>

        {{-- Text content --}}
        <div class="flex flex-col justify-between flex-1 p-5 gap-3">
            <div class="flex flex-col gap-2">
                <h3
                    class="text-[20px] md:text-[23px] leading-[1.3] font-[400] text-[#333] group-hover:text-[#F9A13D] transition-colors duration-300 line-clamp-2">
                    {{ $article->title }}
                </h3>

                <p class="text-[14px] leading-[1.6] text-[#666] line-clamp-2">
                    {{ $article->excerpt ?? Str::limit(strip_tags($article->body), 150) }}
                </p>
            </div>

            <div
                class="w-full flex justify-between items-center text-[12px] font-semibold text-[#9c9c9c] border-t border-[#f7f7f7] pt-3">
                <div class="flex gap-4">
                    <span class="flex items-center gap-1.5">
                        <i class="far fa-clock"></i>
                        <span>{{ $article->published_at ? $article->published_at->diffForHumans() : 'Recently' }}</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="far fa-comment"></i>
                        <span>{{ $article->comments_count ?? $article->comments->count() }}</span>
                    </span>
                </div>
                <span
                    class="text-[#F9A13D] opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                    Read more <i class="fas fa-chevron-right text-[10px]"></i>
                </span>
            </div>
        </div>
    </article>
</a>