@extends('layouts.app')

@section('title', "$device->name - Prices & Deals")

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
    <x-device-header :device="$device" activeTab="prices" />

    <!-- Pricing Section -->
    <div>
        <!-- Currency Conversion Dropdown -->
        <div class="bg-[#fafafa] border-b border-[#ddd] mb-6 shadow-sm">
            <div class="w-full">
                <div class="flex items-center justify-end pb-2 pt-4 px-4 md:px-6">
                    <span class="font-bold mr-3">Currency conversion:</span>
                    <select name="selCurrencies" onchange="jumpto(this)"
                        class="p-2 border border-gray-300 rounded bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" selected>No conversion</option>
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->iso_code }}">{{ $currency->iso_code }} {{ $currency->symbol }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Pricing Information -->
        <div class="mb-8 px-2">
            <h3 class="text-[21px] font-bold text-[#555] mb-3">Best deals for {{ $device->name }}</h3>
            <p class="text-[#292828] mb-4 leading-relaxed text-[16px]">
                Here are the lowest prices we could find for the {{ $device->name }} at our partner stores.<br>
                Click on any of the prices to see the best deals from the corresponding store.
                We may get a commission from qualifying sales.
            </p>
        </div>

        <!-- Pricing Tables -->
        <div class="space-y-8 px-2">
            @php
                // Group offers by country
                $offersByCountry = $device->offers->groupBy('country.name');

                // Get ALL variants for the device (not just per country)
                $allVariants = $device->variants()
                    ->where('device_variants.status', true)
                    ->orderBy('storage_gb')
                    ->orderBy('ram_gb')
                    ->get();
            @endphp

            @if($offersByCountry->isEmpty() || $allVariants->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                No pricing information available for {{ $device->name }} at the moment. Please check back later.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                @foreach($offersByCountry as $countryName => $countryOffers)
                    @php
                        // Group offers by store for this country
                        $offersByStore = $countryOffers->groupBy('store.name');

                        // Create a map of variant_id => offers for quick lookup
                        $variantOffersMap = [];
                        foreach ($offersByStore as $storeName => $storeOffers) {
                            foreach ($storeOffers as $offer) {
                                $variantOffersMap[$storeName][$offer->device_variant_id] = $offer;
                            }
                        }
                    @endphp

                    <div class="bg-white overflow-hidden mb-10">
                        <div class="text-[24px] block font-bold text-[#555] border-b-2 border-[#555] p-0">
                            {{ $countryName }}
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr>
                                        <!-- Store column with auto width -->
                                        <th
                                            class="px-4 py-3 text-left text-sm font-medium text-gray-700 bg-white border border-gray-300 min-w-[200px]">
                                            Store
                                        </th>
                                        <!-- Variant columns with fixed width -->
                                        @foreach($allVariants as $index => $variant)
                                            <th
                                                class="px-4 py-3 text-center text-[14px] font-medium text-black border border-gray-300 min-w-[116px] {{ $index % 2 != 0 ? 'bg-white' : 'bg-[#fafafa]' }}">
                                                @if($variant->storage_gb && $variant->ram_gb)
                                                    {{ $variant->storage_gb }}GB {{ $variant->ram_gb }}GB RAM
                                                @else
                                                    {{ $variant->label }}
                                                @endif
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offersByStore as $storeName => $storeOffers)
                                        @php
                                            $store = $storeOffers->first()->store;
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <!-- Store cell with auto width -->
                                            <td
                                                class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 bg-white border border-gray-300">
                                                <div class="flex items-center">
                                                    @if($store->logo_url)
                                                        <img src="{{ $store->logo_url }}" alt="{{ $storeName }}"
                                                            class="h-8 w-auto max-w-[180px] object-contain">
                                                    @else
                                                        <span class="font-medium text-gray-800">{{ $storeName }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <!-- Price cells with fixed width -->
                                            @foreach($allVariants as $index => $variant)
                                                @php
                                                    $colBgClass = $index % 2 != 0 ? 'bg-white' : 'bg-[#fafafa]';
                                                    $variantOffer = $variantOffersMap[$storeName][$variant->id] ?? null;
                                                @endphp
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm text-center border border-gray-300 {{ $colBgClass }} min-w-[116px]">
                                                    @if($variantOffer)
                                                        <div class="flex flex-col items-center justify-center h-full">
                                                            <a href="{{ $variantOffer->url ?: '#' }}" target="_blank"
                                                                class="text-[#F9A13D] font-bold text-[17px] hover:underline transition-colors leading-tight"
                                                                @if($variantOffer->url) rel="nofollow noopener" @endif>
                                                                {{ $variantOffer->currency->symbol ?? '' }}{{ number_format($variantOffer->price, 2) }}
                                                            </a>
                                                            @if(!$variantOffer->in_stock)
                                                                <span class="text-xs text-[#F9A13D] mt-1 font-medium">Out of stock</span>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 text-lg">—</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>


        <!-- Disclaimer Notes -->
        <div class="mt-8 space-y-3 text-[#292828] mb-4 leading-relaxed text-[13px] px-2">
            <p class="leading-relaxed">
                <span class="font-semibold">Note:</span> The pricing published on this page is meant to be used for general
                information only.
                While we monitor prices regularly, the ones listed above might be outdated.
                We also cannot guarantee these are the lowest prices possible so shopping around is always a good idea.
            </p>
            <p class="leading-relaxed">
                If you decide to make a purchase, make sure you review the listing carefully so that the hardware
                configuration,
                the item condition, and its price all match what you expect. Check the warranty coverage for your country
                and be aware of any potential extra charges like sales tax and shipping or customs fees.
            </p>
            <p class="leading-relaxed">
                We provide the links for price comparison purposes but as Associates to Amazon and the other stores linked
                above,
                we may get a commission from any qualifying purchases you make because we have referred you to the store.
                The affiliate programs we participate in are completely independent of our editorial product review process
                and our editors do not benefit from picking out specific deals.
            </p>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function jumpto(select) {
            const value = select.value;
            if (value) {
                // Redirect with currency parameter
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('currency', value);
                window.location.href = currentUrl.toString();
            }
        }
    </script>
@endpush