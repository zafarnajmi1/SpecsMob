@props(['reviews' => []])

<div class="w-full bg-white mt-6">

    {{-- Header --}}
    <h4 class="border-l-[11px] border-[#9e9e9e] text-[#555] uppercase font-bold text-[12px] px-4 py-3">
        Popular Reviews
    </h4>

    <div class="px-3 pb-3 pt-2">

        @forelse ($reviews as $review)
            <a
                href="{{ $review['url'] ?? '#' }}"
                class="block group bg-black relative overflow-hidden shadow-sm"
            >
                {{-- Thumbnail --}}
                <img
                    src="{{ $review['img'] ?? '' }}"
                    alt="{{ $review['title'] ?? '' }}"
                    class="w-full h-[140px] object-cover opacity-90 group-hover:opacity-100 group-hover:scale-[1.03] transition-transform transition-opacity duration-200"
                />

                {{-- Title strip --}}
                <div class="absolute bottom-0 left-0 right-0 bg-black/75 px-3 py-2">
                    <p class="text-[12px] font-semibold text-white leading-snug">
                        {{ $review['title'] ?? '' }}
                    </p>
                </div>
            </a>
        @empty
            <p class="text-center text-sm text-gray-500 py-3">No popular reviews available.</p>
        @endforelse

    </div>
</div>
