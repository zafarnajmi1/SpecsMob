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
            background-color: #3b82f6;
            border-radius: 2px;
        }

        .slider-handle {
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: white;
            border: 2px solid #3b82f6;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 2;
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
            border-bottom: 2px solid #3b82f6;
            color: #3b82f6;
        }
</style>


{{-- Hero / header --}}
<div class="overflow-hidden w-full mb-6 md:h-[310px]">
    <div
        class="relative bg-cover bg-center h-full"
        style='background-image: url("{{ asset('user/images/phonefinder.jpg') }}");'
    >
        <div class="bg-black/55 h-full">
            <div class="px-4 md:px-6 py-6 h-full flex flex-col justify-end gap-2">
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white drop-shadow-md">
                    Phone Finder
                </h1>
            </div>
        </div>
    </div>
</div>



    <div class="container mx-auto px-4 py-8">
        <!-- Tabs -->
        <div class="flex border-b-6 border-[#b5d779] mb-6 justify-end">
            <button class="px-4 py-2 font-medium bg-[rgba(118,177,208,.5)] text-white shadow hover:bg-[#d50000]">Tablets</button>
            <button class="px-4 py-2 font-medium bg-[rgba(118,177,208,.5)] text-white shadow hover:bg-[#d50000] tab-active active:bg-[#b5d779] active:text-white">Phones</button>
        </div>

        <!-- Filter Form -->
        <form class="bg-white rounded-lg shadow-md p-6">
            <!-- General Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4 border-b pb-2">General</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Brand -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select brands</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Acer</div>
                                <div>Alcatel</div>
                                <div>Apple</div>
                                <div>Asus</div>
                                <div>BlackBerry</div>
                                <div>Google</div>
                                <div>HTC</div>
                                <div>Huawei</div>
                                <div>LG</div>
                                <div>Motorola</div>
                                <div>Nokia</div>
                                <div>OnePlus</div>
                                <div>Samsung</div>
                                <div>Sony</div>
                                <div>Xiaomi</div>
                            </div>
                        </div>
                    </div>

                    <!-- Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">2000</span>
                                <span class="text-sm">2025</span>
                            </div>
                        </div>
                    </div>

                    <!-- Availability -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                        <div class="custom-select">
                            <div class="select-selected">
                                <span>Select availability</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="select-items">
                                <div>Available</div>
                                <div>Coming soon</div>
                                <div>Discontinued</div>
                                <div>Rumored</div>
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">€50/$68</span>
                                <span class="text-sm">€1200/$1200</span>
                            </div>
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
                        <input type="checkbox" id="dualsim" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="dualsim" class="ml-2 block text-sm text-gray-700">Dual SIM</label>
                    </div>

                    <!-- eSIM -->
                    <div class="flex items-center">
                        <input type="checkbox" id="esim" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
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
                            <div class="slider-container">
                                <div class="slider-track">
                                    <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                    <div class="slider-handle" style="left: 0%;"></div>
                                    <div class="slider-handle" style="left: 100%;"></div>
                                </div>
                                <div class="flex justify-between w-full mt-2">
                                    <span class="text-sm">90mm</span>
                                    <span class="text-sm">180mm</span>
                                </div>
                            </div>
                        </div>

                        <!-- Width -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Width</label>
                            <div class="slider-container">
                                <div class="slider-track">
                                    <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                    <div class="slider-handle" style="left: 0%;"></div>
                                    <div class="slider-handle" style="left: 100%;"></div>
                                </div>
                                <div class="flex justify-between w-full mt-2">
                                    <span class="text-sm">40mm</span>
                                    <span class="text-sm">80mm</span>
                                </div>
                            </div>
                        </div>

                        <!-- Thickness -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Thickness</label>
                            <div class="slider-container">
                                <div class="slider-track">
                                    <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                    <div class="slider-handle" style="left: 0%;"></div>
                                    <div class="slider-handle" style="left: 100%;"></div>
                                </div>
                                <div class="flex justify-between w-full mt-2">
                                    <span class="text-sm">5mm</span>
                                    <span class="text-sm">20mm</span>
                                </div>
                            </div>
                        </div>

                        <!-- Weight -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Weight</label>
                            <div class="slider-container">
                                <div class="slider-track">
                                    <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                    <div class="slider-handle" style="left: 0%;"></div>
                                    <div class="slider-handle" style="left: 100%;"></div>
                                </div>
                                <div class="flex justify-between w-full mt-2">
                                    <span class="text-sm">60g</span>
                                    <span class="text-sm">230g</span>
                                </div>
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">1</span>
                                <span class="text-sm">8</span>
                            </div>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">RAM</label>
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">2GB</span>
                                <span class="text-sm">24GB</span>
                            </div>
                        </div>
                    </div>

                    <!-- Storage -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Storage</label>
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">64MB</span>
                                <span class="text-sm">1TB</span>
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
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">QVGA</span>
                                <span class="text-sm">4K</span>
                            </div>
                        </div>
                    </div>

                    <!-- Size -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Size</label>
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">3"</span>
                                <span class="text-sm">7.2"</span>
                            </div>
                        </div>
                    </div>

                    <!-- Density -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Density</label>
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">100ppi</span>
                                <span class="text-sm">550ppi</span>
                            </div>
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
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">90Hz</span>
                                <span class="text-sm">165Hz</span>
                            </div>
                        </div>
                    </div>

                    <!-- HDR -->
                    <div class="flex items-center">
                        <input type="checkbox" id="hdr" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="hdr" class="ml-2 block text-sm text-gray-700">HDR</label>
                    </div>

                    <!-- 1B+ Colors -->
                    <div class="flex items-center">
                        <input type="checkbox" id="colors" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
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
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">No</span>
                                <span class="text-sm">200MP</span>
                            </div>
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
                        <input type="checkbox" id="cameraois" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="cameraois" class="ml-2 block text-sm text-gray-700">OIS</label>
                    </div>

                    <!-- F-number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">F-number</label>
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">f/1.5</span>
                                <span class="text-sm">f/2.8</span>
                            </div>
                        </div>
                    </div>

                    <!-- Telephoto -->
                    <div class="flex items-center">
                        <input type="checkbox" id="telephoto" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="telephoto" class="ml-2 block text-sm text-gray-700">Telephoto</label>
                    </div>

                    <!-- Ultrawide -->
                    <div class="flex items-center">
                        <input type="checkbox" id="ultrawide" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="ultrawide" class="ml-2 block text-sm text-gray-700">Ultrawide</label>
                    </div>

                    <!-- Video -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Video</label>
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">QVGA</span>
                                <span class="text-sm">8K</span>
                            </div>
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
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">No</span>
                                <span class="text-sm">48MP</span>
                            </div>
                        </div>
                    </div>

                    <!-- Dual Camera -->
                    <div class="flex items-center">
                        <input type="checkbox" id="dualcamera" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="dualcamera" class="ml-2 block text-sm text-gray-700">Dual camera</label>
                    </div>

                    <!-- OIS -->
                    <div class="flex items-center">
                        <input type="checkbox" id="selfieois" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="selfieois" class="ml-2 block text-sm text-gray-700">OIS</label>
                    </div>

                    <!-- Front Flash -->
                    <div class="flex items-center">
                        <input type="checkbox" id="frontflash" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="frontflash" class="ml-2 block text-sm text-gray-700">Front flash</label>
                    </div>

                    <!-- Pop-up Camera -->
                    <div class="flex items-center">
                        <input type="checkbox" id="popup" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="popup" class="ml-2 block text-sm text-gray-700">Pop-up camera</label>
                    </div>

                    <!-- Under Display Camera -->
                    <div class="flex items-center">
                        <input type="checkbox" id="undercamera" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
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
                        <input type="checkbox" id="jack" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="jack" class="ml-2 block text-sm text-gray-700">3.5mm jack</label>
                    </div>

                    <!-- Dual Speakers -->
                    <div class="flex items-center">
                        <input type="checkbox" id="stereo" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
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
                        <input type="checkbox" id="accelerometer" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="accelerometer" class="ml-2 block text-sm text-gray-700">Accelerometer</label>
                    </div>

                    <!-- Gyro -->
                    <div class="flex items-center">
                        <input type="checkbox" id="gyro" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="gyro" class="ml-2 block text-sm text-gray-700">Gyro</label>
                    </div>

                    <!-- Compass -->
                    <div class="flex items-center">
                        <input type="checkbox" id="compass" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="compass" class="ml-2 block text-sm text-gray-700">Compass</label>
                    </div>

                    <!-- Proximity -->
                    <div class="flex items-center">
                        <input type="checkbox" id="proximity" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="proximity" class="ml-2 block text-sm text-gray-700">Proximity</label>
                    </div>

                    <!-- Barometer -->
                    <div class="flex items-center">
                        <input type="checkbox" id="barometer" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="barometer" class="ml-2 block text-sm text-gray-700">Barometer</label>
                    </div>

                    <!-- Heart Rate -->
                    <div class="flex items-center">
                        <input type="checkbox" id="heartrate" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
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
                        <input type="checkbox" id="gps" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="gps" class="ml-2 block text-sm text-gray-700">GPS</label>
                    </div>

                    <!-- NFC -->
                    <div class="flex items-center">
                        <input type="checkbox" id="nfc" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="nfc" class="ml-2 block text-sm text-gray-700">NFC</label>
                    </div>

                    <!-- Infrared -->
                    <div class="flex items-center">
                        <input type="checkbox" id="infrared" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="infrared" class="ml-2 block text-sm text-gray-700">Infrared</label>
                    </div>

                    <!-- FM Radio -->
                    <div class="flex items-center">
                        <input type="checkbox" id="fmradio" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
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
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">3000mAh</span>
                                <span class="text-sm">8000mAh</span>
                            </div>
                        </div>
                    </div>

                    <!-- Si/C -->
                    <div class="flex items-center">
                        <input type="checkbox" id="sic" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="sic" class="ml-2 block text-sm text-gray-700">Si/C</label>
                    </div>

                    <!-- Removable -->
                    <div class="flex items-center">
                        <input type="checkbox" id="removable" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="removable" class="ml-2 block text-sm text-gray-700">Removable</label>
                    </div>

                    <!-- Wired Charging -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Wired Charging</label>
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">10W</span>
                                <span class="text-sm">120W</span>
                            </div>
                        </div>
                    </div>

                    <!-- Wireless Charging -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Wireless Charging</label>
                        <div class="slider-container">
                            <div class="slider-track">
                                <div class="slider-range" style="left: 0%; width: 100%;"></div>
                                <div class="slider-handle" style="left: 0%;"></div>
                                <div class="slider-handle" style="left: 100%;"></div>
                            </div>
                            <div class="flex justify-between w-full mt-2">
                                <span class="text-sm">5W</span>
                                <span class="text-sm">65W</span>
                            </div>
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                        <input type="checkbox" id="reviewed" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="reviewed" class="ml-2 block text-sm text-gray-700">Reviewed only</label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition font-medium">
                    <i class="fas fa-filter mr-2"></i>Show Results
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Custom dropdown functionality
        document.addEventListener('DOMContentLoaded', function () {
            const customSelects = document.querySelectorAll('.custom-select');

            customSelects.forEach(select => {
                const selected = select.querySelector('.select-selected');
                const items = select.querySelector('.select-items');

                selected.addEventListener('click', function (e) {
                    e.stopPropagation();
                    closeAllSelect(this);
                    items.style.display = items.style.display === 'block' ? 'none' : 'block';
                });

                const options = items.querySelectorAll('div');
                options.forEach(option => {
                    option.addEventListener('click', function () {
                        selected.querySelector('span').textContent = this.textContent;
                        items.style.display = 'none';
                    });
                });
            });

            function closeAllSelect(elmnt) {
                const selects = document.querySelectorAll('.select-items');
                const selected = document.querySelectorAll('.select-selected');

                selects.forEach(item => {
                    if (elmnt !== item.previousElementSibling) {
                        item.style.display = 'none';
                    }
                });

                selected.forEach(item => {
                    if (elmnt !== item) {
                        item.classList.remove('select-arrow-active');
                    }
                });
            }

            document.addEventListener('click', closeAllSelect);
        });

        // Tab functionality
        const tabs = document.querySelectorAll('.flex.border-b button');
        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                tabs.forEach(t => t.classList.remove('tab-active'));
                this.classList.add('tab-active');
            });
        });
    </script>
@endpush