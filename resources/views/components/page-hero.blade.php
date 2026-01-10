@props([
    // Main heading text
    'title' => 'Reviews',

    // Array of tags (strings)
    'tags' => [],

    // Background image URL (full URL or asset() result)
    'background' => null,

    // Search form action URL (string). If null => current URL
    'search_action' => null,

    // Search query parameter name
    'query_param' => 'q',

    // Tag query parameter name
    'tag_param' => 'tag',
])

@php
    $bgUrl = $background ?? asset('user/images/reviews.jpg');
    $actionUrl = $search_action ?? url()->current();
@endphp

<section class="w-full mb-6 md:h-[310px]">
    <div class="max-w-[1060px] mx-auto overflow-hidden h-full bg-black text-white">
        <div
            class="relative bg-cover bg-center h-full"
            style="background-image: url('{{ $bgUrl }}');"
        >
            {{-- Dark overlay for contrast --}}
            <div class="bg-black/55 h-full">
                <div class="px-4 md:px-6 pt-4 pb-6 space-y-4 h-full flex flex-col justify-between">

                    {{-- Top: Popular tags row --}}
                    <div class="relative border-b border-white/15 pb-3">
                        <span
                            class="inline-flex items-center text-[11px] font-semibold uppercase tracking-[0.18em] bg-black/50 px-3 py-1 rounded-full mr-3">
                            Popular tags
                        </span>

                        <ul class="mt-2 md:mt-0 inline-flex flex-wrap gap-2 align-middle">
                            @forelse ($tags as $tag)
                                <li>
                                    <a
                                        href="{{ url()->current() . '?' . $tag_param . '=' . urlencode($tag) }}"
                                        class="inline-block text-[11px] px-3 py-1 rounded-full bg-black/40 border border-white/15 hover:bg-[#d50000] hover:border-[#d50000] transition-colors"
                                    >
                                        {{ $tag }}
                                    </a>
                                </li>
                            @empty
                                <li class="text-[11px] text-gray-300">
                                    No tags available.
                                </li>
                            @endforelse
                        </ul>
                    </div>

                    {{-- Bottom: Search band --}}
                    <div class="border-t border-white/15 pt-3">
                        <h1 class="text-3xl mb-3 md:text-4xl font-bold tracking-tight drop-shadow-md">
                            {{ $title }}
                        </h1>

                        <form
                            method="GET"
                            action="{{ $actionUrl }}"
                            class="flex flex-col sm:flex-row gap-2 sm:items-center"
                        >
                            <label class="flex-1 text-[12px] flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                                <span class="font-semibold whitespace-nowrap">
                                    Search for
                                </span>
                                <input
                                    type="text"
                                    name="{{ $query_param }}"
                                    maxlength="50"
                                    value="{{ request($query_param) }}"
                                    placeholder="Device, brand or keyword"
                                    class="flex-1 px-3 py-1.5 text-[13px] rounded bg-white/95 text-[#333] placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#d50000]"
                                />
                            </label>

                            <button
                                type="submit"
                                class="self-start sm:self-auto px-4 py-1.5 text-[12px] font-semibold uppercase rounded bg-[#d50000] hover:bg-[#F9A13D] text-white tracking-[0.15em]"
                            >
                                Search
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
