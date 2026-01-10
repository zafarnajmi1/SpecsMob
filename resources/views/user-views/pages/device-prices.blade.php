@extends('layouts.app')

@section('title', "$device->name - Prices & Deals")

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
    <x-device-header :device="$device" activeTab="prices" />
    <x-device-mobile-header :device="$device" activeTab="prices" />

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
        <div class="hidden lg:block space-y-8 px-2">
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


        <!-- Mobile screen Pricing Tables -->
        <div class="lg:hidden space-y-4 px-0">
            @php
                // Group offers by country
                $offersByCountry = $device->offers->groupBy('country.name');

                // Get ALL variants for the device
                $allVariants = $device->variants()
                    ->where('device_variants.status', true)
                    ->orderBy('storage_gb')
                    ->orderBy('ram_gb')
                    ->get();
            @endphp

            @if($offersByCountry->isEmpty() || $allVariants->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mx-2 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
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
                    <div class="bg-white border-t border-b border-gray-200" x-data="{ open: true }">
                        <!-- Country Header -->
                        <div @click="open = !open" 
                             class="flex justify-between items-center px-4 py-3 bg-white cursor-pointer select-none hover:bg-gray-50 transition-colors">
                            <h3 class="text-[19px] font-normal text-[#444] leading-none">{{ $countryName }}</h3>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-500 transition-transform duration-200" 
                               :class="{ 'rotate-180': open }"></i>
                        </div>

                        <!-- Content -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             class="border-t border-gray-100">
                            @foreach($allVariants as $variant)
                                @php
                                    $variantOffers = $countryOffers->where('device_variant_id', $variant->id);
                                @endphp

                                @if($variantOffers->isNotEmpty())
                                    <!-- Variant Header -->
                                    <div class="px-4 pt-4 pb-2 text-[#888] font-bold text-[12px] uppercase">
                                        @if($variant->storage_gb && $variant->ram_gb)
                                            {{ $variant->storage_gb }}GB {{ $variant->ram_gb }}GB RAM
                                        @else
                                            {{ $variant->label }}
                                        @endif
                                    </div>

                                    <!-- Offers List -->
                                    <div>
                                        @foreach($variantOffers as $offer)
                                            @php
                                                // Collect all offers from this store for this variant
                                                $storeId = $offer->store->id ?? 0;
                                                $storeOffers = $variantOffers->where('store_id', $storeId);
                                                
                                                // Prepare offers array for modal
                                                $offersData = [];
                                                foreach($storeOffers as $storeOffer) {
                                                    $offersData[] = [
                                                        'image' => $device->image_url ?? '',
                                                        'title' => $device->name . ' ' . ($variant->storage_gb && $variant->ram_gb ? $variant->storage_gb . 'GB ' . $variant->ram_gb . 'GB RAM' : $variant->label),
                                                        'price' => ($storeOffer->currency->symbol ?? '') . ' ' . number_format($storeOffer->price, 2),
                                                        'url' => $storeOffer->url ?: '#',
                                                        'inStock' => $storeOffer->in_stock
                                                    ];
                                                }
                                            @endphp
                                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 cursor-pointer"
                                                 @click="$dispatch('open-pricing-modal', {
                                                     deviceName: '{{ addslashes($device->name) }}',
                                                     variantLabel: '{{ $variant->storage_gb && $variant->ram_gb ? $variant->storage_gb . "GB " . $variant->ram_gb . "GB RAM" : addslashes($variant->label) }}',
                                                     storeName: '{{ addslashes($offer->store->name ?? "Store") }}',
                                                     storeLogo: '{{ $offer->store->logo_url ?? "" }}',
                                                     offers: {{ json_encode($offersData) }}
                                                 })">
                                                <!-- Store -->
                                                <div class="flex-shrink-0">
                                                    @if($offer->store && $offer->store->logo_url)
                                                        <img src="{{ $offer->store->logo_url }}" alt="{{ $offer->store->name }}" 
                                                             class="h-6 w-auto object-contain max-w-[120px]">
                                                    @else
                                                        <span class="font-bold text-[#333] text-[15px]">{{ $offer->store->name ?? 'Store' }}</span>
                                                    @endif
                                                </div>

                                                <!-- Price -->
                                                <span class="text-[#F9A13D] font-bold text-[16px] whitespace-nowrap">
                                                    {{ $offer->currency->symbol ?? '' }} {{ number_format($offer->price, 2) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
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

    <!-- Pricing Modal (Mobile Only) -->
    <div x-data="pricingModal()" 
         @open-pricing-modal.window="openModal($event.detail)"
         x-show="isOpen"
         x-cloak
         class="fixed inset-0 z-50 lg:hidden"
         style="display: none;">
        
        <!-- Semi-transparent Gray Backdrop (GSMarena Style) -->
        <div class="fixed inset-0 bg-gray-600 bg-opacity-40 transition-opacity"
             @click="closeModal()"></div>

        <!-- Modal Content -->
        <div class="fixed inset-0 flex items-start justify-center pt-12 px-3">
            <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col"
                 @click.stop>
                
                <!-- Header (Fixed) -->
                <div class="px-4 pt-4 pb-3 border-b border-gray-200 flex-shrink-0">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1 pr-4">
                            <h3 class="text-[17px] font-bold text-[#F9A13D] mb-0.5" x-text="modalData.deviceName"></h3>
                            <span class="text-[12px] text-gray-500 font-semibold" x-text="modalData.variantLabel"></span>
                        </div>
                        <button @click="closeModal()" 
                                class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <p class="text-[10px] text-gray-500 leading-relaxed mt-2">
                        For full specs, item condition, and final price incl. taxes and fees refer to each store listing.
                    </p>

                    <!-- Store Logo -->
                    <div class="mt-3">
                        <template x-if="modalData.storeLogo">
                            <img :src="modalData.storeLogo" 
                                 :alt="modalData.storeName"
                                 class="h-8 w-auto object-contain">
                        </template>
                        <template x-if="!modalData.storeLogo">
                            <span class="font-bold text-[16px] text-[#333]" x-text="modalData.storeName"></span>
                        </template>
                    </div>
                </div>

                <!-- Product Listings (Scrollable) -->
                <div class="flex-1 overflow-y-auto px-4 py-3">
                    <template x-for="(offer, index) in modalData.offers" :key="index">
                        <div class="flex items-center gap-3 border-b border-gray-100 py-3 last:border-0">
                            <!-- Product Image -->
                            <div class="flex-shrink-0 w-16 h-16">
                                <img :src="offer.image" 
                                     :alt="offer.title"
                                     class="w-full h-full object-contain">
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] text-gray-800 leading-tight mb-1.5 line-clamp-2" x-text="offer.title"></p>
                                <p class="text-[18px] font-bold text-[#F9A13D]" x-text="offer.price"></p>
                                <template x-if="!offer.inStock">
                                    <span class="inline-block text-[10px] text-[#F9A13D] font-semibold mt-0.5">
                                        Out of stock
                                    </span>
                                </template>
                            </div>

                            <!-- GO TO STORE Button -->
                            <div class="flex-shrink-0">
                                <a :href="offer.url" 
                                   target="_blank" 
                                   rel="nofollow noopener"
                                   class="inline-block bg-[#F9A13D] hover:bg-[#e89530] text-white font-bold text-[11px] py-2 px-3 rounded text-center transition-colors uppercase whitespace-nowrap">
                                    GO TO STORE
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
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

        // Alpine.js component for pricing modal
        function pricingModal() {
            return {
                isOpen: false,
                modalData: {
                    deviceName: '',
                    variantLabel: '',
                    storeName: '',
                    storeLogo: '',
                    offers: []
                },
                openModal(data) {
                    this.modalData = data;
                    this.isOpen = true;
                    // Prevent body scroll when modal is open
                    document.body.style.overflow = 'hidden';
                },
                closeModal() {
                    this.isOpen = false;
                    // Restore body scroll
                    document.body.style.overflow = '';
                }
            }
        }
    </script>
@endpush