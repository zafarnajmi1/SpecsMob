@extends('layouts.app')
@section('title', 'Phone Finder')



@section('content')
    <style>
        /* Custom styles for sliders and dropdowns */
        .slider-container {
            position: relative;
            height: 40px;
            display: flex;
            align-items: center;
        }

        .slider-track {
            height: 4px;
            background-color: #e5e7eb;
            border-radius: 2px;
            position: relative;
            width: 100%;
        }

        .slider-range {
            position: absolute;
            height: 4px;
            background-color: #F9A13D;
            border-radius: 2px;
        }

        .slider-handle {
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: white;
            border: 2px solid #F9A13D;
            border-radius: 50%;
            top: 50%;
            transform: translateX(-50%) translateY(-50%);
            cursor: pointer;
            z-index: 2;
            transition: z-index 0.1s ease;
        }

        .slider-handle:active {
            z-index: 5;
        }

        .custom-select {
            position: relative;
            width: 100%;
        }

        .custom-select select {
            display: none;
        }

        .select-selected {
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .select-items {
            position: absolute;
            background-color: white;
            border: 1px solid #d1d5db;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            z-index: 99;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            display: none;
        }

        .select-items div {
            padding: 0.5rem 0.75rem;
            cursor: pointer;
        }

        .select-items div:hover {
            background-color: #f3f4f6;
        }

        .select-hide {
            display: none;
        }

        .tab-active {
            border-bottom: 2px solid #F9A13D;
            color: #F9A13D;
        }
    </style>


    {{-- Hero / header --}}
    <div class="hidden lg:block overflow-hidden w-full mb-6 md:h-[310px]">
        <div class="relative bg-cover bg-center h-full"
            style='background-image: url("{{ asset('user/images/phonefinder.jpg') }}");'>
            <div class="bg-black/25 h-full">
                <div class="px-4 md:px-6 py-6 h-full flex flex-col justify-end gap-2">
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white drop-shadow-md">
                        Phone Finder
                    </h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('phone-finder')


    <div class="container mx-auto lg:px-4 lg:py-8 py-2">
        <!-- PC & laptop Tabs -->
        <div class="hidden lg:flex border-b-6 border-[#b5d779] mb-6 justify-end">
            <button
                class="px-4 py-2 font-medium bg-[rgba(118,177,208,.5)] text-white shadow hover:bg-[#F9A13D]">Tablets</button>
            <button
                class="px-4 py-2 font-medium bg-[rgba(118,177,208,.5)] text-white shadow hover:bg-[#F9A13D] tab-active active:bg-[#b5d779] active:text-white">Phones</button>
        </div>

        <!-- Mobile and tablet tabs -->
         <div class="lg:hidden px-2 flex flex-col">
            <h2 class="font-bold text-lg ml-4">Phone Finder</h2>
             <div class="flex gap-2 mb-6 justify-start border-b border-gray-300">
                 <button
                     class="px-4 py-2 font-bold text-[14px] text-[#F9A13D] border-b-4 border-[#F9A13D]">Phones</button>
                 <button
                     class="px-4 py-2 font-bold text-[14px] text-gray-500">Tablets</button>
             </div>
             <a href="{{route('phone-finder-results')}}" class="uppercase text-white bg-[#F9A13D] rounded-sm shadow text-center py-2 font-bold text-[23px]">SHOW {{ $results ?? 0}} RESULTS</a>
         </div>

        <!-- Filter Form -->
        <form action="{{ route('phone-finder') }}" method="GET" id="phone-finder-form"
            class="bg-white rounded-lg shadow-md p-6">
            <input type="hidden" name="brands" id="selected-brands" value="{{ request('brands') }}">
            <input type="hidden" name="year_min" id="year-min" value="{{ request('year_min', 2000) }}">
            <input type="hidden" name="year_max" id="year-max" value="{{ request('year_max', 2025) }}">
            <input type="hidden" name="ram_min" id="ram-min" value="{{ request('ram_min', 0) }}">
            <input type="hidden" name="storage_min" id="storage-min" value="{{ request('storage_min', 0) }}">
            <input type="hidden" name="status" id="release-status" value="{{ request('status', 'all') }}">

            <!-- General Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">General</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Brand -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <div class="custom-select" id="brand-select">
                            <div class="select-selected">
                                <span id="brand-display">@if(request('brands')) Brands Selected @else Select brands
                                @endif</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div data-value="">All Brands</div>
                                @foreach($brands as $brand)
                                    <div data-value="{{ $brand->id }}" class="flex items-center gap-2">
                                        <input type="checkbox" class="brand-checkbox" value="{{ $brand->id }}"
                                            @if(in_array($brand->id, explode(',', request('brands')))) checked @endif
                                            onclick="event.stopPropagation()">
                                        <span>{{ $brand->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                        <div class="slider-container" id="year-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="2000" data-max="2025">2000</span>
                            <span class="text-sm font-medium text-gray-700" data-min="2000" data-max="2025">2025</span>
                        </div>
                    </div>

                    <!-- Availability -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                        <div class="custom-select">
                            <div class="select-selected" data-target="release-status">
                                <span id="availability-display">
                                    @if(request('status') == 'available') Available
                                    @elseif(request('status') == 'coming_soon') Coming soon
                                    @elseif(request('status') == 'discontinued') Discontinued
                                    @elseif(request('status') == 'rumored') Rumored
                                    @else Select availability
                                    @endif
                                </span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items status-options">
                                <div data-value="available">Available</div>
                                <div data-value="coming_soon">Coming soon</div>
                                <div data-value="discontinued">Discontinued</div>
                                <div data-value="rumored">Rumored</div>
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                        <div class="slider-container" id="price-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="50" data-max="1200">€50/$68</span>
                            <span class="text-sm font-medium text-gray-700" data-min="50" data-max="1200">€1200/$1200</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Network Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Network</h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- 2G -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">2G</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select bands</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>GSM 850</div>
                                <div>GSM 900</div>
                                <div>GSM 1800</div>
                                <div>GSM 1900</div>
                            </div>
                        </div>
                    </div>

                    <!-- 3G -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">3G</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select bands</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>HSPA 850</div>
                                <div>HSPA 900</div>
                                <div>HSPA 1700</div>
                                <div>HSPA 1900</div>
                                <div>HSPA 2100</div>
                            </div>
                        </div>
                    </div>

                    <!-- 4G -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">4G</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select bands</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Any 4G</div>
                                <div>LTE band 1(2100)</div>
                                <div>LTE band 2(1900)</div>
                                <div>LTE band 3(1800)</div>
                                <div>LTE band 4(1700/2100)</div>
                            </div>
                        </div>
                    </div>

                    <!-- 5G -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">5G</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select bands</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Any 5G</div>
                                <div>5G band 1(2100)</div>
                                <div>5G band 2(1900)</div>
                                <div>5G band 3(1800)</div>
                                <div>5G band 5(850)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SIM Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">SIM</h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Dual SIM -->
                    <div class="flex items-center">
                        <input type="checkbox" id="dualsim"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="dualsim" class="ml-2 block text-sm text-gray-700">Dual SIM</label>
                    </div>

                    <!-- eSIM -->
                    <div class="flex items-center">
                        <input type="checkbox" id="esim"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="esim" class="ml-2 block text-sm text-gray-700">eSIM</label>
                    </div>

                    <!-- SIM Size -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Size</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select size</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Mini-SIM (regular size)</div>
                                <div>Micro-SIM</div>
                                <div>Nano-SIM</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Body Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Body</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Form Factor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Form factor</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select form factor</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Bar</div>
                                <div>Flip up</div>
                                <div>Flip down</div>
                                <div>Slide</div>
                                <div>Swivel</div>
                                <div>Watch</div>
                                <div>Other</div>
                                <div>Foldable</div>
                            </div>
                        </div>
                    </div>

                    <!-- Keyboard -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keyboard</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select keyboard type</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>With QWERTY</div>
                                <div>Without QWERTY</div>
                            </div>
                        </div>
                    </div>

                    <!-- Dimensions and Weight -->
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Height -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Height</label>
                            <div class="slider-container" id="height-slider">
                                <div class="slider-track">
                                    <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                    <div class="slider-handle" style="left: 0%;"></div>
                                    <div class="slider-handle" style="left: 100%;"></div>
                                </div>
                            </div>
                            <div class="flex justify-between w-full mt-4 px-1">
                                <span class="text-sm font-medium text-gray-700" data-min="90" data-max="180">90mm</span>
                                <span class="text-sm font-medium text-gray-700" data-min="90" data-max="180">180mm</span>
                            </div>
                        </div>

                        <!-- Width -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Width</label>
                            <div class="slider-container" id="width-slider">
                                <div class="slider-track">
                                    <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                    <div class="slider-handle" style="left: 0%;"></div>
                                    <div class="slider-handle" style="left: 100%;"></div>
                                </div>
                            </div>
                            <div class="flex justify-between w-full mt-4 px-1">
                                <span class="text-sm font-medium text-gray-700" data-min="40" data-max="80">40mm</span>
                                <span class="text-sm font-medium text-gray-700" data-min="40" data-max="80">80mm</span>
                            </div>
                        </div>

                        <!-- Thickness -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Thickness</label>
                            <div class="slider-container" id="thickness-slider">
                                <div class="slider-track">
                                    <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                    <div class="slider-handle" style="left: 0%;"></div>
                                    <div class="slider-handle" style="left: 100%;"></div>
                                </div>
                            </div>
                            <div class="flex justify-between w-full mt-4 px-1">
                                <span class="text-sm font-medium text-gray-700" data-min="5" data-max="20">5mm</span>
                                <span class="text-sm font-medium text-gray-700" data-min="5" data-max="20">20mm</span>
                            </div>
                        </div>

                        <!-- Weight -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Weight</label>
                            <div class="slider-container" id="weight-slider">
                                <div class="slider-track">
                                    <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                    <div class="slider-handle" style="left: 0%;"></div>
                                    <div class="slider-handle" style="left: 100%;"></div>
                                </div>
                            </div>
                            <div class="flex justify-between w-full mt-4 px-1">
                                <span class="text-sm font-medium text-gray-700" data-min="60" data-max="230">60g</span>
                                <span class="text-sm font-medium text-gray-700" data-min="60" data-max="230">230g</span>
                            </div>
                        </div>
                    </div>

                    <!-- IP Certificate -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">IP certificate</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select IP rating</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>IP5x</div>
                                <div>IP6x</div>
                                <div>IPx5</div>
                                <div>IPx6</div>
                                <div>IPx7</div>
                                <div>IPx8</div>
                                <div>IPx9</div>
                                <div>MIL-STD-810D</div>
                                <div>MIL-STD-810F</div>
                                <div>MIL-STD-810G</div>
                                <div>MIL-STD-810H</div>
                            </div>
                        </div>
                    </div>

                    <!-- Color -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                        <input type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F9A13D]">
                    </div>

                    <!-- Back Material -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Back material</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select material</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Plastic</div>
                                <div>Aluminum</div>
                                <div>Glass</div>
                                <div>Ceramic</div>
                            </div>
                        </div>
                    </div>

                    <!-- Frame Material -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Frame material</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select material</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Plastic</div>
                                <div>Aluminum</div>
                                <div>Stainless steel</div>
                                <div>Ceramic</div>
                                <div>Titanium</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Platform Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Platform</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- OS -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">OS</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select OS</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Feature phones</div>
                                <div>Android</div>
                                <div>iOS</div>
                                <div>KaiOS</div>
                                <div>Windows Phone</div>
                                <div>Symbian</div>
                                <div>RIM</div>
                                <div>Bada</div>
                                <div>Firefox</div>
                            </div>
                        </div>
                    </div>

                    <!-- Min OS Version -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Min OS Version</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select an OS first</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Select an OS first</div>
                            </div>
                        </div>
                    </div>

                    <!-- Chipset -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chipset</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select chipset</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Any Snapdragon</div>
                                <div>Any Exynos</div>
                                <div>Any Dimensity</div>
                                <div>Any Helio</div>
                                <div>Any Kirin</div>
                                <div>Snapdragon 8 Elite Gen 5</div>
                                <div>Snapdragon 8 Elite</div>
                                <div>Snapdragon 8 Gen 3</div>
                                <div>Snapdragon 8s Gen 4</div>
                            </div>
                        </div>
                    </div>

                    <!-- CPU Cores -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">CPU Cores</label>
                        <div class="slider-container" id="cpu-cores-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="1" data-max="8">1</span>
                            <span class="text-sm font-medium text-gray-700" data-min="1" data-max="8">8</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Memory Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Memory</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- RAM -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">RAM(Min GB)</label>
                        <div class="slider-container" id="ram-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 0%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">0</span>
                                <span class="text-sm">24</span>
                            </div>
                        </div>
                    </div>

                    <!-- Storage -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Storage (Min GB)</label>
                        <div class="slider-container" id="storage-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 0%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">0</span>
                                <span class="text-sm">1024</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Slot -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Card slot</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select card slot type</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Yes (any type)</div>
                                <div>Yes (dedicated)</div>
                                <div>No</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Display Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Display</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Resolution -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Resolution</label>
                        <div class="slider-container" id="display-resolution-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="0" data-max="2160">QVGA</span>
                            <span class="text-sm font-medium text-gray-700" data-min="0" data-max="2160">4K</span>
                        </div>
                    </div>

                    <!-- Size -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Size</label>
                        <div class="slider-container" id="display-size-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="3" data-max="7.2">3"</span>
                            <span class="text-sm font-medium text-gray-700" data-min="3" data-max="7.2">7.2"</span>
                        </div>
                    </div>

                    <!-- Density -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Density</label>
                        <div class="slider-container" id="density-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="100" data-max="550">100ppi</span>
                            <span class="text-sm font-medium text-gray-700" data-min="100" data-max="550">550ppi</span>
                        </div>
                    </div>

                    <!-- Technology -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Technology</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select technology</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>IPS</div>
                                <div>Any OLED</div>
                                <div>LTPO OLED</div>
                            </div>
                        </div>
                    </div>

                    <!-- Notch -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notch</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select notch type</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>No</div>
                                <div>Yes</div>
                                <div>Punch hole</div>
                            </div>
                        </div>
                    </div>

                    <!-- Refresh Rate -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Refresh rate</label>
                        <div class="slider-container" id="refresh-rate-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 0%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="90" data-max="165">90Hz</span>
                            <span class="text-sm font-medium text-gray-700" data-min="90" data-max="165">165Hz</span>
                        </div>
                    </div>

                    <!-- HDR -->
                    <div class="flex items-center">
                        <input type="checkbox" id="hdr"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="hdr" class="ml-2 block text-sm text-gray-700">HDR</label>
                    </div>

                    <!-- 1B+ Colors -->
                    <div class="flex items-center">
                        <input type="checkbox" id="colors"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="colors" class="ml-2 block text-sm text-gray-700">1B+ colors</label>
                    </div>
                </div>
            </div>

            <!-- Main Camera Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Main camera</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Resolution -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Resolution</label>
                        <div class="slider-container" id="main-camera-resolution-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 0%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="0" data-max="200">No</span>
                            <span class="text-sm font-medium text-gray-700" data-min="0" data-max="200">200MP</span>
                        </div>
                    </div>

                    <!-- Cameras -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cameras</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select number of cameras</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>One</div>
                                <div>Two</div>
                                <div>Three</div>
                                <div>Four or more</div>
                            </div>
                        </div>
                    </div>

                    <!-- OIS -->
                    <div class="flex items-center">
                        <input type="checkbox" id="cameraois"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="cameraois" class="ml-2 block text-sm text-gray-700">OIS</label>
                    </div>

                    <!-- F-number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">F-number</label>
                        <div class="slider-container" id="fnumber-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="1.5" data-max="2.8">f/1.5</span>
                            <span class="text-sm font-medium text-gray-700" data-min="1.5" data-max="2.8">f/2.8</span>
                        </div>
                    </div>

                    <!-- Telephoto -->
                    <div class="flex items-center">
                        <input type="checkbox" id="telephoto"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="telephoto" class="ml-2 block text-sm text-gray-700">Telephoto</label>
                    </div>

                    <!-- Ultrawide -->
                    <div class="flex items-center">
                        <input type="checkbox" id="ultrawide"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="ultrawide" class="ml-2 block text-sm text-gray-700">Ultrawide</label>
                    </div>

                    <!-- Video -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Video</label>
                        <div class="slider-container" id="video-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 0%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="0" data-max="2160">QVGA</span>
                            <span class="text-sm font-medium text-gray-700" data-min="0" data-max="2160">8K</span>
                        </div>
                    </div>

                    <!-- Flash -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Flash</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select flash type</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>LED</div>
                                <div>Dual-LED</div>
                                <div>Xenon</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selfie Camera Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Selfie camera</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Resolution -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Resolution</label>
                        <div class="slider-container" id="selfie-camera-resolution-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 0%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="0" data-max="48">No</span>
                            <span class="text-sm font-medium text-gray-700" data-min="0" data-max="48">48MP</span>
                        </div>
                    </div>

                    <!-- Dual Camera -->
                    <div class="flex items-center">
                        <input type="checkbox" id="dualcamera"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="dualcamera" class="ml-2 block text-sm text-gray-700">Dual camera</label>
                    </div>

                    <!-- OIS -->
                    <div class="flex items-center">
                        <input type="checkbox" id="selfieois"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="selfieois" class="ml-2 block text-sm text-gray-700">OIS</label>
                    </div>

                    <!-- Front Flash -->
                    <div class="flex items-center">
                        <input type="checkbox" id="frontflash"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="frontflash" class="ml-2 block text-sm text-gray-700">Front flash</label>
                    </div>

                    <!-- Pop-up Camera -->
                    <div class="flex items-center">
                        <input type="checkbox" id="popup"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="popup" class="ml-2 block text-sm text-gray-700">Pop-up camera</label>
                    </div>

                    <!-- Under Display Camera -->
                    <div class="flex items-center">
                        <input type="checkbox" id="undercamera"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="undercamera" class="ml-2 block text-sm text-gray-700">Under display camera</label>
                    </div>
                </div>
            </div>

            <!-- Audio Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Audio</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- 3.5mm Jack -->
                    <div class="flex items-center">
                        <input type="checkbox" id="jack"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="jack" class="ml-2 block text-sm text-gray-700">3.5mm jack</label>
                    </div>

                    <!-- Dual Speakers -->
                    <div class="flex items-center">
                        <input type="checkbox" id="stereo"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="stereo" class="ml-2 block text-sm text-gray-700">Dual speakers</label>
                    </div>
                </div>
            </div>

            <!-- Sensors Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Sensors</h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Accelerometer -->
                    <div class="flex items-center">
                        <input type="checkbox" id="accelerometer"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="accelerometer" class="ml-2 block text-sm text-gray-700">Accelerometer</label>
                    </div>

                    <!-- Gyro -->
                    <div class="flex items-center">
                        <input type="checkbox" id="gyro"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="gyro" class="ml-2 block text-sm text-gray-700">Gyro</label>
                    </div>

                    <!-- Compass -->
                    <div class="flex items-center">
                        <input type="checkbox" id="compass"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="compass" class="ml-2 block text-sm text-gray-700">Compass</label>
                    </div>

                    <!-- Proximity -->
                    <div class="flex items-center">
                        <input type="checkbox" id="proximity"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="proximity" class="ml-2 block text-sm text-gray-700">Proximity</label>
                    </div>

                    <!-- Barometer -->
                    <div class="flex items-center">
                        <input type="checkbox" id="barometer"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="barometer" class="ml-2 block text-sm text-gray-700">Barometer</label>
                    </div>

                    <!-- Heart Rate -->
                    <div class="flex items-center">
                        <input type="checkbox" id="heartrate"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="heartrate" class="ml-2 block text-sm text-gray-700">Heart rate</label>
                    </div>

                    <!-- Fingerprint -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fingerprint</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select fingerprint type</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Yes (any type)</div>
                                <div>Front-mounted</div>
                                <div>Rear-mounted</div>
                                <div>Side-mounted</div>
                                <div>Top-mounted</div>
                                <div>Under display</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Connectivity Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Connectivity</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- WLAN -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">WLAN (Wi-Fi)</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select Wi-Fi standards</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Wi-Fi 4 (802.11n)</div>
                                <div>Wi-Fi 5 (802.11ac)</div>
                                <div>Wi-Fi 6 (802.11ax)</div>
                                <div>Wi-Fi 7 (802.11be)</div>
                            </div>
                        </div>
                    </div>

                    <!-- Bluetooth -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bluetooth</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select Bluetooth versions</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Any Bluetooth</div>
                                <div>Bluetooth 4.0</div>
                                <div>Bluetooth 4.1</div>
                                <div>Bluetooth 4.2</div>
                                <div>Bluetooth 5.0</div>
                                <div>Bluetooth 5.1</div>
                                <div>Bluetooth 5.2</div>
                                <div>Bluetooth 5.3</div>
                                <div>Bluetooth 5.4</div>
                                <div>Bluetooth 6.0</div>
                            </div>
                        </div>
                    </div>

                    <!-- GPS -->
                    <div class="flex items-center">
                        <input type="checkbox" id="gps"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="gps" class="ml-2 block text-sm text-gray-700">GPS</label>
                    </div>

                    <!-- NFC -->
                    <div class="flex items-center">
                        <input type="checkbox" id="nfc"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="nfc" class="ml-2 block text-sm text-gray-700">NFC</label>
                    </div>

                    <!-- Infrared -->
                    <div class="flex items-center">
                        <input type="checkbox" id="infrared"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="infrared" class="ml-2 block text-sm text-gray-700">Infrared</label>
                    </div>

                    <!-- FM Radio -->
                    <div class="flex items-center">
                        <input type="checkbox" id="fmradio"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="fmradio" class="ml-2 block text-sm text-gray-700">FM Radio</label>
                    </div>

                    <!-- USB -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">USB</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select USB type</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Any USB-C</div>
                                <div>USB-C 3.0 and higher</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Battery Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Battery</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Capacity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
                        <div class="slider-container" id="battery-capacity-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="3000" data-max="8000">3000mAh</span>
                            <span class="text-sm font-medium text-gray-700" data-min="3000" data-max="8000">8000mAh</span>
                        </div>
                    </div>

                    <!-- Si/C -->
                    <div class="flex items-center">
                        <input type="checkbox" id="sic"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="sic" class="ml-2 block text-sm text-gray-700">Si/C</label>
                    </div>

                    <!-- Removable -->
                    <div class="flex items-center">
                        <input type="checkbox" id="removable"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="removable" class="ml-2 block text-sm text-gray-700">Removable</label>
                    </div>

                    <!-- Wired Charging -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Wired Charging</label>
                        <div class="slider-container" id="wired-charging-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="10" data-max="120">10W</span>
                            <span class="text-sm font-medium text-gray-700" data-min="10" data-max="120">120W</span>
                        </div>
                    </div>

                    <!-- Wireless Charging -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Wireless Charging</label>
                        <div class="slider-container" id="wireless-charging-slider">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                        </div>
                        <div class="flex justify-between w-full mt-4 px-1">
                            <span class="text-sm font-medium text-gray-700" data-min="5" data-max="65">5W</span>
                            <span class="text-sm font-medium text-gray-700" data-min="5" data-max="65">65W</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Misc Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Misc</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Free Text -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Free text</label>
                        <input type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F9A13D]">
                    </div>

                    <!-- Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Popularity</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Popularity</div>
                                <div>Price</div>
                                <div>Weight</div>
                                <div>Camera resolution</div>
                                <div>Battery capacity</div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviewed Only -->
                    <div class="flex items-center">
                        <input type="checkbox" id="reviewed"
                            class="h-4 w-4 text-[#F9A13D] border-gray-300 rounded focus:ring-[#F9A13D]">
                        <label for="reviewed" class="ml-2 block text-sm text-gray-700">Reviewed only</label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="bg-[#F9A13D] text-white px-6 py-3 rounded-lg hover:bg-[#e89530] transition font-medium">
                    <i class="fas fa-filter mr-2"></i>Show Results
                </button>
            </div>
        </form>

        <!-- Results Section -->
        <div class="mt-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Results ({{ $devices->total() }})</h2>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">Order by:</span>
                    <select
                        onchange="window.location.href = updateQueryStringParameter(window.location.href, 'order', this.value)"
                        class="border border-gray-300 rounded px-3 py-1 text-sm outline-none">
                        <option value="latest" @if(request('order') == 'latest') selected @endif>Latest</option>
                        <option value="popular" @if(request('order') == 'popular') selected @endif>Popularity</option>
                    </select>
                </div>
            </div>

            @if($devices->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($devices as $device)
                        <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden flex flex-col">
                            <a href="{{ route('device-detail', $device->slug) }}" class="p-4 flex-grow flex flex-col items-center">
                                <div class="w-full h-48 bg-white flex items-center justify-center mb-4">
                                    <img src="{{ $device->thumbnail_url }}" alt="{{ $device->name }}"
                                        class="max-h-full max-w-full object-contain">
                                </div>
                                <h3 class="font-bold text-[#F9A13D] text-center mb-2">{{ $device->name }}</h3>
                                <div class="text-xs text-center text-gray-500 space-y-1">
                                    <p>{{ $device->os_short }}</p>
                                    <p>{{ $device->storage_short }} / {{ $device->ram_short }} RAM</p>
                                    <p>{{ $device->battery_short }}</p>
                                </div>
                            </a>
                            <div
                                class="bg-gray-50 px-4 py-2 border-t flex justify-between items-center text-[10px] font-bold uppercase text-gray-400">
                                <span>{{ $device->brand->name }}</span>
                                <span class="text-[#F9A13D]">{{ $device->released_at->format('M Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10 flex justify-center">
                    {{ $devices->links() }}
                </div>
            @else
                <div class="bg-white p-20 rounded-lg shadow-sm text-center">
                    <i class="fa-solid fa-mobile-screen-button text-5xl text-gray-200 mb-4 block"></i>
                    <h3 class="text-xl font-bold text-gray-400">No phones match your filters</h3>
                    <p class="text-gray-400 mt-2">Try adjusting your criteria or clearing some filters.</p>
                    <a href="{{ route('phone-finder') }}"
                        class="inline-block mt-6 text-[#F9A13D] font-bold hover:underline uppercase text-sm">Clear All
                        Filters</a>
                </div>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
    <style>
        .select-items {
            z-index: 50 !important;
            display: none;
        }

        .select-items.block {
            display: block !important;
        }

        .slider-container+div {
            margin-top: 1.5rem !important;
        }
    </style>
    <script>
            document.addEventListener('DOMContentLoaded',         function () {
                // Force text overlap fix immediately
                const textContainers = document.querySelectorAll('.slider-container + div');
                textContainers.forEach(div => {
                    div.classList.remove('mt-2');
                    div.style.marginTop = '2.5rem';
                });

                // --- BRAND SELECT FIX ---
                const brandSelect = document.getElementById('brand-select');
                if (brandSelect) {
                    const selected = brandSelect.querySelector('.select-selected');
                    const items = brandSelect.querySelector('.select-items');

                    // Clone selected to strip old event listeners if any
                    const newSelected = selected.cloneNode(true);
                    selected.parentNode.replaceChild(newSelected, selected);

                    newSelected.addEventListener('click', (e) => {
                        e.stopPropagation();
                        e.preventDefault();

                        // Toggle visibility
                        // Check if strictly hidden by class or style
                        const isHidden = items.classList.contains('hidden') || items.style.display === 'none';

                        if (isHidden) {
                            items.classList.remove('hidden');
                            items.style.display = 'block';
                        } else {
                            items.classList.add('hidden');
                            items.style.display = 'none';
                        }
                    });

                    // Prevent closing when clicking inside items (checkboxes)
                    items.addEventListener('click', (e) => e.stopPropagation());

                    // Start hidden
                    items.classList.add('hidden');
                    items.style.display = 'none';
                }

                // --- GENERIC CUSTOM SELECTS ---
                const customSelects = document.querySelectorAll('.custom-select');
                customSelects.forEach(select => {
                    if (select.id === 'brand-select') return;

                    // Check if it's the specific Status options select which has its own logic
                    // if (select.querySelector('.status-options')) return; 
                    // Actually, let's override generic logic for all to ensure they work.

                    const selected = select.querySelector('.select-selected');
                    const items = select.querySelector('.select-items');
                    const displaySpan = selected ? selected.querySelector('span') : null;
                    const inputId = selected ? selected.dataset.target : null;
                    const hiddenInput = inputId ? document.getElementById(inputId) : null;

                    if (!selected || !items) return;

                    // Clone to remove old listeners
                    const newSelected = selected.cloneNode(true);
                    selected.parentNode.replaceChild(newSelected, selected);

                    newSelected.addEventListener('click', (e) => {
                        e.stopPropagation();
                        // Close all others
                        customSelects.forEach(s => {
                            if (s !== select) {
                                const i = s.querySelector('.select-items');
                                if (i) {
                                    i.classList.add('hidden');
                                    i.style.display = 'none';
                                }
                            }
                        });

                        // Toggle current
                        const isHidden = items.classList.contains('hidden') || items.style.display === 'none';
                        if (isHidden) {
                            items.classList.remove('hidden');
                            items.style.display = 'block';
                        } else {
                            items.classList.add('hidden');
                            items.style.display = 'none';
                        }
                    });

                    // Re-bind click options
                    items.querySelectorAll('div').forEach(div => {
                        // Clone div? No, just add new listener. 
                        // Duplicate listeners are harmless here as they just set value and close.
                        div.addEventListener('click', (e) => {
                             const val = div.dataset.value || div.textContent.trim();
                             const text = div.textContent.trim();

                             if (hiddenInput) hiddenInput.value = val;
                             // Special case for brand display? No, brand is skipped.
                             if (displaySpan) displaySpan.textContent = text;

                             items.classList.add('hidden');
                             items.style.display = 'none';
                        });
                    });
                });

                // Global Close
                document.addEventListener('click', () => {
                    customSelects.forEach(s => {
                        // Don't auto-close brand-select items if we are clicking inside it? 
                        // brand-select items click propagation is stopped above.
                        // But if we click outside, we should close it?
                        // Yes, close all.
                        const i = s.querySelector('.select-items');
                        if (i) {
                            i.classList.add('hidden');
                            i.style.display = 'none';
                        }
                    });
                });


                // --- GENERIC SLIDERS ---
                const sliders = document.querySelectorAll('.slider-container');
                sliders.forEach(container => {
                    initGenericSlider(container);
                });

                function initGenericSlider(container) {
                    const track = container.querySelector('.slider-track');
                    let handles = Array.from(container.querySelectorAll('.slider-handle'));
                    const range = container.querySelector('.slider-range');
                    const textContainer = container.nextElementSibling;

                    if (!track || !handles.length || !textContainer) return;

                    // Identify Inputs or Create Them
                    let minInputId = null;
                    let maxInputId = null;

                    if (container.id === 'ram-slider') minInputId = 'ram-min';
                    else if (container.id === 'storage-slider') minInputId = 'storage-min';
                    // Year falls through to dynamic creation
                    else {
                        // Create dynamic inputs
                        const label = container.parentElement.querySelector('label');
                        const nameBase = label ? label.textContent.trim().toLowerCase().replace(/[^a-z0-9]/g, '_') : 'slider_' + Math.random().toString(36).substr(2, 5);

                        // Create Min Input
                        const minInp = document.createElement('input');
                        minInp.type = 'hidden';
                        minInp.name = nameBase + '_min';
                        minInp.id = nameBase + '_min';
                        container.appendChild(minInp);
                        minInputId = minInp.id;

                        // Create Max Input if 2 handles
                        if (handles.length === 2) {
                            const maxInp = document.createElement('input');
                            maxInp.type = 'hidden';
                            maxInp.name = nameBase + '_max';
                            maxInp.id = nameBase + '_max';
                            container.appendChild(maxInp);
                            maxInputId = maxInp.id;
                        }
                    }

                    const minValSpan = textContainer.children[0];
                    const maxValSpan = textContainer.children[1];
                    const minLimit = parseInt(minValSpan.dataset.min || minValSpan.textContent.replace(/[^0-9]/g, '')) || 0;
                    const maxLimit = parseInt(maxValSpan.dataset.max || maxValSpan.textContent.replace(/[^0-9]/g, '')) || 100;

                    // Initialize positions from inputs if they have values (persistence)
                    if (minInputId) {
                         const inp = document.getElementById(minInputId);
                         if (inp && inp.value !== '') {
                             const val = parseFloat(inp.value);
                             if (!isNaN(val) && (maxLimit - minLimit) !== 0) {
                                 const p = ((val - minLimit) / (maxLimit - minLimit)) * 100;
                                 handles[0].style.left = Math.max(0, Math.min(100, p)) + '%';
                             }
                         }
                    }
                    if (handles.length === 2 && maxInputId) {
                         const inp = document.getElementById(maxInputId);
                         if (inp && inp.value !== '') {
                             const val = parseFloat(inp.value);
                             if (!isNaN(val) && (maxLimit - minLimit) !== 0) {
                                 const p = ((val - minLimit) / (maxLimit - minLimit)) * 100;
                                 handles[1].style.left = Math.max(0, Math.min(100, p)) + '%';
                             }
                         }
                    }

                    let isDragging = null;

                    const update = () => {
                        const minP = parseFloat(handles[0].style.left) || 0;
                        if (handles.length === 2) {
                            const maxP = parseFloat(handles[1].style.left) || 100;
                            range.style.left = minP + '%';
                            range.style.width = (maxP - minP) + '%';

                            const currMin = Math.round(minLimit + (minP/100)*(maxLimit-minLimit));
                            const currMax = Math.round(minLimit + (maxP/100)*(maxLimit-minLimit));

                            updateText(minValSpan, currMin);
                            updateText(maxValSpan, currMax);

                            if(minInputId && document.getElementById(minInputId)) document.getElementById(minInputId).value = currMin;
                            if(maxInputId && document.getElementById(maxInputId)) document.getElementById(maxInputId).value = currMax;

                        } else {
                            range.style.left = '0%';
                            range.style.width = minP + '%';
                            const currMin = Math.round(minLimit + (minP/100)*(maxLimit-minLimit));
                            updateText(minValSpan, currMin);
                            if(minInputId && document.getElementById(minInputId)) document.getElementById(minInputId).value = currMin;
                        }
                    };

                    function updateText(el, val) {
                         const txt = el.textContent;
                         const unit = txt.replace(/[0-9]/g, '').trim(); 
                         el.textContent = val + (unit || '');
                    }

                    handles.forEach((h, i) => {
                         // Clone handle to remove old listeners (Reset)
                         const newH = h.cloneNode(true);
                         h.parentNode.replaceChild(newH, h);
                         handles[i] = newH; // Update reference

                         newH.addEventListener('mousedown', (e) => {
                             isDragging = i;
                             e.preventDefault();
                         });
                    });

                    document.addEventListener('mousemove', (e) => {
                        if (isDragging === null) return;
                        e.preventDefault();
                        const rect = track.getBoundingClientRect();
                        let p = ((e.clientX - rect.left) / rect.width) * 100;
                        p = Math.max(0, Math.min(100, p));

                        if (handles.length === 2) {
                           if (isDragging === 0 && p >= parseFloat(handles[1].style.left)) return;
                           if (isDragging === 1 && p <= parseFloat(handles[0].style.left)) return;
                        }

                        handles[isDragging].style.left = p + '%';
                        update();
                    });

                    document.addEventListener('mouseup', () => isDragging = null);
                }
            });
        </script>
@endpush
