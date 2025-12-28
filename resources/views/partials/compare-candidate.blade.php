<div class="flex flex-col gap-4 border-r border-[#eee] pr-6 last:border-0 last:pr-0">
    <div class="candidate-search relative">
        <form class="flex flex-col gap-2" action="" method="get">
            <strong class="text-xs font-bold text-[#777] uppercase">Compare with</strong>
            <div class="relative">
                <input type="text" id="sSearch{{ $index }}"
                    class="w-full pl-3 pr-10 py-2 border border-[#ccc] rounded text-sm focus:ring-1 focus:ring-[#d50000] focus:border-[#d50000] outline-none transition-all"
                    value="" autocomplete="off" placeholder="Search for a device...">
                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fa-solid fa-search"></i>
                </div>
            </div>
        </form>

        {{-- Autocomplete Results Placeholder --}}
        <div id="results-{{ $index }}"
            class="absolute z-20 w-full mt-1 bg-white border border-[#ccc] rounded shadow-xl hidden overflow-hidden">
            <div class="loading p-4 text-center text-gray-400 text-xs hidden">
                <i class="fa-solid fa-spinner fa-spin"></i> Searching...
            </div>
            <ul class="max-h-[300px] overflow-y-auto" id="list-{{ $index }}">
                {{-- Dynamic results here --}}
            </ul>
        </div>
    </div>

    @if($device)
        <div class="flex flex-col gap-3">
            <div class="relative group self-center">
                <div
                    class="swap-phones absolute -right-8 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                    @php
                        // Logic to swap: if index is 1, swap with 2. If 2, swap with 1.
                        $swapId = $index == 1 ? ($device2->id ?? '') : ($device1->id ?? '');
                        // Simplified for now: just a placeholder icon
                    @endphp
                    <a href="#" title="Swap phones" class="text-gray-400 hover:text-[#d50000]">
                        <i class="fa-solid fa-repeat text-xl"></i>
                    </a>
                </div>

                <div
                    class="w-full max-w-[160px] aspect-[3/4] flex items-center justify-center bg-white p-2 border border-[#eee] rounded shadow-sm">
                    <img src="{{ $device->thumbnail_url }}" alt="{{ $device->name }}"
                        class="max-h-full max-w-full object-contain">
                </div>
            </div>

            <div class="flex flex-col gap-1 text-center md:text-left">
                <h3 class="font-bold text-lg text-[#1976d2] hover:text-[#d50000] transition-colors leading-tight">
                    <a href="{{ route('device-detail', $device->slug) }}">{{ $device->name }}</a>
                </h3>

                <ul
                    class="flex flex-wrap items-center justify-center md:justify-start gap-x-3 gap-y-1 mt-1 text-[11px] font-bold uppercase text-[#777]">
                    <li class="hover:text-[#d50000] transition-colors">
                        <a href="{{ route('device-detail', $device->slug) }}">Specifications</a>
                    </li>
                    <li class="hover:text-[#d50000] transition-colors">
                        <a
                            href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id]) }}">Opinions</a>
                    </li>
                    <li class="hover:text-[#d50000] transition-colors">
                        <a
                            href="{{ route('device.pictures', ['slug' => $device->slug, 'id' => $device->id]) }}">Pictures</a>
                    </li>
                </ul>

                @if($device->activeOffers()->count() > 0)
                    <div class="mt-4 p-3 bg-[#fdfdfd] border border-[#eee] rounded">
                        @php $offer = $device->activeOffers->first(); @endphp
                        <a href="#" class="flex flex-col gap-1 items-center md:items-start group">
                            <span class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">Deal</span>
                            <span
                                class="text-lg font-bold text-[#d50000] group-hover:underline">${{ number_format($offer->price, 2) }}</span>
                            <img src="https://fdn.gsmarena.com/imgroot/static/stores/amazon-com1.png" alt="Deal"
                                class="h-4 object-contain">
                        </a>
                        <a href="{{ route('device.prices', ['slug' => $device->slug, 'id' => $device->id]) }}"
                            class="block mt-2 text-[10px] font-bold text-[#1976d2] uppercase hover:underline">All prices</a>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div
            class="flex flex-col items-center justify-center py-10 bg-white border border-dashed border-[#ccc] rounded-lg opacity-50">
            <i class="fa-solid fa-plus text-3xl text-gray-300 mb-2"></i>
            <span class="text-xs font-bold text-gray-400 uppercase">Add another device</span>
        </div>
    @endif
</div>