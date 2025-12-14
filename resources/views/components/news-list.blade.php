@props([
    // Section heading
    'title',

    // Optional header link (e.g. arenaev.com)
    'title_url' => null,

    // Array of items: [ ['title' => ..., 'img' => ..., 'url' => ...], ... ]
    'items' => [],

    // Link target for items
    'item_target' => '_blank',
])

<div class="w-full bg-white mt-6">

    {{-- Header --}}
    <h4 class="border-l-[11px] border-[#9e9e9e] text-[#555] uppercase font-bold text-[12px] px-4 py-3">
        @if ($title_url)
            <a href="{{ $title_url }}" target="_blank" class="hover:text-[#d50000]">
                {{ $title }}
            </a>
        @else
            {{ $title }}
        @endif
    </h4>

    <div class="px-3 py-3">
        <ul class="space-y-3">

            @forelse ($items as $item)
                <li class="bg-white flex gap-2 p-2 rounded-sm shadow-sm hover:shadow-md transition-shadow">
                    <a href="{{ $item['url'] ?? '#' }}" target="{{ $item_target }}" class="flex gap-2 w-full">

                        {{-- Thumbnail --}}
                        <div class="w-[80px] h-[55px] flex-shrink-0 overflow-hidden rounded-sm">
                            <img
                                src="{{ $item['img'] ?? '' }}"
                                alt="{{ $item['title'] ?? '' }}"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-200"
                            />
                        </div>

                        {{-- Title --}}
                        <div class="flex-1 flex items-center">
                            <span class="text-[11px] leading-snug text-[#555] hover:text-[#d50000]">
                                {{ $item['title'] ?? '' }}
                            </span>
                        </div>
                    </a>
                </li>
            @empty
                <li class="text-center text-[11px] text-gray-500 py-2">
                    No articles available.
                </li>
            @endforelse

        </ul>
    </div>
</div>
