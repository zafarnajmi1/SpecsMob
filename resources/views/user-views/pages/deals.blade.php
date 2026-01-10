@extends('layouts.app')

@section('title', 'Daily deals')

@section('sidebar')
    @include('partials.aside')
@endsection


@section('content')
    {{-- resources/views/userviews/deals/index.blade.php --}}

    {{-- Dynamic data passed from WebController --}}

    {{-- Header / hero --}}
    <div class="overflow-hidden w-full mb-6 md:h-[310px]">
        <div class="relative bg-cover bg-center h-full"
            style="background-image: url('https://fdn.gsmarena.com/imgroot/static/headers/deals-hd-1.jpg');">
            <div class="bg-black/55 h-full">
                <div class="px-4 h-full md:px-6 py-6 flex flex-col items-start justify-end text-center gap-2">
                    <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-white drop-shadow-md">
                        Daily deals
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-[#e7e4e4] my-5 flex flex-col gap-3 p-2 md:p-3">

        {{-- Markets filter --}}
        <form method="GET" action="{{ url()->current() }}" class="flex items-start gap-3 w-full py-3">
            {{-- Dropdown Wrapper --}}
            <div class="relative w-[85%] z-100">

                {{-- Dropdown Toggle --}}
                <button type="button" id="marketDropdownToggle"
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-left flex justify-between items-center cursor-pointer">
                    <span class="text-[12px] font-semibold text-[#555] uppercase tracking-[0.18em]">
                        Markets:
                        @if(request('markets'))
                            Selected ({{ count(request('markets')) }})
                        @else
                            All
                        @endif
                    </span>

                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Dropdown Panel with Checkboxes --}}
                <div id="marketDropdownPanel"
                    class="hidden absolute z-[100] left-0 right-0 mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-[220px] overflow-y-auto px-3 py-2">
                    @foreach ($availableMarkets as $marketName)
                        <label class="flex items-center gap-2 py-1 text-[13px]">
                            <input type="checkbox" name="markets[]" value="{{ $marketName }}" @if(collect(request('markets', []))->contains($marketName)) checked @endif />
                            {{ $marketName }}
                        </label>
                    @endforeach
                </div>

            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="w-[15%] px-2 py-2 text-[12px] font-semibold uppercase tracking-[0.18em] border-2 border-gray-400  hover:bg-[#F9A13D] hover:text-white transition-colors">
                Show
            </button>
        </form>


        {{-- Info text --}}
        <div class="px-4 py-3 text-[13px] text-[#555]">
            We track recent prices for phones and gadgets and highlight standout offers we’ve spotted.
            Discounts are estimated from the average price over the last 30 days (or since listing for new items).
            Final price and availability can change – always confirm details with the seller before buying.
        </div>

        {{-- Deals list --}}
        <div class="space-y-6">
            @foreach ($deals as $deal)
                <article class="bg-white border border-gray-200 shadow-sm">
                    {{-- Main row: image + info --}}
                    <div class="flex flex-col md:flex-row gap-4 p-3 md:p-4">
                        {{-- Image --}}
                        <a href="{{ $deal->link }}" target="_blank" rel="noopener"
                            class="w-full md:w-[170px] flex-shrink-0 flex items-center justify-center bg-white">
                            <img src="{{ $deal->image_url }}" alt="{{ $deal->title }}" class="max-h-[180px] object-contain" />
                        </a>

                        {{-- Text / Deal details --}}
                        <div class="flex-1 flex flex-col gap-3">
                            {{-- Title + specs --}}
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <h3 class="text-[18px] md:text-[19px] font-semibold text-[#333]">
                                    {{ $deal->title }}
                                </h3>
                            </div>

                            {{-- Description --}}
                            <p class="text-[13px] text-[#555] leading-snug">
                                <a href="{{ $deal->link }}" target="_blank" rel="noopener" class="hover:text-[#F9A13D]">
                                    {{ $deal->description }}
                                </a>
                            </p>

                            {{-- Deal row: memory / store / price / discount --}}
                            <div class="flex flex-wrap items-center gap-3 text-[13px]">
                                @if($deal->memory)
                                    <a href="{{ $deal->link }}" target="_blank" rel="noopener"
                                        class="px-2 py-1 rounded border border-gray-300 text-[#555] hover:border-[#F9A13D] hover:text-[#F9A13D] transition-colors">
                                        {{ $deal->memory }}
                                    </a>
                                @endif

                                @if($deal->store_logo)
                                    <a href="{{ $deal->link }}" target="_blank" rel="noopener" class="flex items-center">
                                        <img src="{{ $deal->store_logo }}" alt="{{ $deal->store_name }}"
                                            class="h-5 object-contain" />
                                    </a>
                                @elseif($deal->store_name)
                                    <a href="{{ $deal->link }}" target="_blank" rel="noopener" class="font-bold text-gray-600">
                                        {{ $deal->store_name }}
                                    </a>
                                @endif

                                <a href="{{ $deal->link }}" target="_blank" rel="noopener"
                                    class="font-semibold text-[14px] text-[#2e7d32]">
                                    {{ $deal->price }}
                                </a>

                                @if($deal->discount_percent)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full bg-[#e8f5e9] text-[#2e7d32] text-[11px] font-semibold">
                                        {{ $deal->discount_percent }} off
                                    </span>
                                @endif

                                <span class="bg-gray-100 text-gray-500 text-[10px] px-2 py-1 rounded">
                                    {{ $deal->region }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Price history (Optional/Placeholder if not implemented) --}}
                    {{-- Removed static history block for now --}}
                </article>
            @endforeach
        </div>
    </section>

@endsection

@push('scripts')
    {{-- Vanilla JS to toggle dropdown --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.getElementById("marketDropdownToggle");
            const panel = document.getElementById("marketDropdownPanel");

            toggle.addEventListener("click", (e) => {
                e.stopPropagation();
                panel.classList.toggle("hidden");
            });

            panel.addEventListener("click", (e) => {
                e.stopPropagation(); // prevent closing when clicking inside panel
            });

            document.addEventListener("click", function (e) {
                // If the click is outside toggle and panel, close it
                if (!toggle.contains(e.target) && !panel.contains(e.target)) {
                    panel.classList.add("hidden");
                }
            });
        });
    </script>
@endpush