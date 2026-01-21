@props(['device', 'activeTab' => 'detail'])

<!-- Device Header -->
<div class="hidden lg:block mb-6 bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden">
    <div class="px-4 py-3 md:px-4 md:py-3">
        <!-- Main Content Area -->
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Left: Device Image -->
            <div class="lg:w-1/3 w-full flex-shrink-0 pb-5">
                <div
                    class="bg-gray-50 rounded-xl border border-gray-200 shadow-sm h-full flex items-center justify-center">
                    <img src="{{ $device->thumbnail_url }}" alt="{{ $device->name }}"
                        class="w-full h-[80%]">
                </div>
            </div>

            <!-- Right: Device Info -->
            <div class="flex-1">
                <!-- Top Bar: Brand, Date, and Favorite -->
                <div class="flex items-start justify-between">
                    <div class="flex flex-wrap items-center gap-3">
                        <span
                            class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-3 py-1.5 rounded-full text-xs font-semibold uppercase">
                            <i class="fa-solid fa-tag"></i>
                            {{ $device->brand->name }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-gray-600 text-sm">
                            <i class="fa-regular fa-calendar"></i>
                            {{ $device->released_at ? $device->released_at->format('F Y') : 'Unknown' }}
                        </span>
                    </div>

                    @if($device->allow_fans)
                        <form action="{{ route('device.fan', $device->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <div class="flex flex-col items-center">
                                <button type="submit" class="group cursor-pointer">
                                    @php
                                        $isFan = auth()->check() && $device->favorites()->where('user_id', auth()->id())->exists();
                                    @endphp
                                    <i
                                        class="fa-{{ $isFan ? 'solid' : 'regular' }} fa-heart text-3xl {{ $isFan ? 'text-[#F9A13D]' : 'text-gray-300 group-hover:text-[#F9A13D]' }} transition-colors"></i>
                                </button>
                                <span
                                    class="text-xs font-bold text-gray-500 mt-1">{{ $device->favorites()->count() }}</span>
                            </div>
                        </form>
                    @endif
                </div>

                <!-- Device Name -->
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">{{ $device->name }}</h1>

                <!-- Spec Cards Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                    @php
                        $specs = [
                            ['icon' => 'fa-mobile-screen', 'color' => 'blue', 'label' => 'WEIGHT', 'value' => $device->weight_grams ? $device->weight_grams . ' g' : 'N/A'],
                            ['icon' => 'fa-brands fa-android', 'color' => 'green', 'label' => 'OS', 'value' => $device->os_short ?? 'N/A'],
                            ['icon' => 'fa-microchip', 'color' => '#F9A13D', 'label' => 'CHIPSET', 'value' => $device->chipset_short ?? 'N/A'],
                            ['icon' => 'fa-memory', 'color' => 'cyan', 'label' => 'STORAGE', 'value' => $device->storage_short ?? 'N/A'],
                            ['icon' => 'fa-battery-full', 'color' => 'yellow', 'label' => 'BATTERY', 'value' => $device->battery_short ?? 'N/A'],
                            ['icon' => 'fa-camera', 'color' => 'gray', 'label' => 'CAMERA', 'value' => $device->main_camera_short ?? 'N/A'],
                        ];
                    @endphp

                    @foreach($specs as $spec)
                        <div
                            class="bg-white border border-gray-200 rounded-lg p-3 text-center hover:shadow-md transition-shadow">
                            <i
                                class="fa-solid {{ $spec['icon'] }} {{ str_starts_with($spec['color'], '#') ? 'text-[' . $spec['color'] . ']' : 'text-' . $spec['color'] . '-500' }} text-2xl mb-2 block"></i>
                            <div class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider mb-1">
                                {{ $spec['label'] }}
                            </div>
                            <div class="text-sm font-bold text-gray-900">{{ $spec['value'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons Bar -->
    <div class="border-t border-gray-200 flex flex-wrap">
        <a href="{{ route('device-detail', $device->slug) }}"
            class="flex-1 flex items-center justify-center gap-2 py-3 uppercase px-2 text-xs font-semibold {{ $activeTab === 'detail' ? 'bg-[#F9A13D] text-white' : 'text-gray-700 hover:bg-[#F9A13D] hover:text-white' }} transition-colors">
            <i class="fa-solid fa-mobile {{ $activeTab === 'detail' ? 'text-white' : 'text-gray-400' }}"></i>
            <span class="hidden sm:inline">Specifications</span>
        </a>
        @if($device->reviews && $device->reviews->first())
            <a href="{{ route('review-detail', $device->reviews->first()->slug) }}"
                class="flex-1 flex items-center justify-center gap-2 py-3 px-2 text-xs font-semibold {{ $activeTab === 'reviews' ? 'bg-[#F9A13D] text-white' : 'text-gray-700 hover:bg-[#F9A13D] hover:text-white' }} transition-colors border-r border-gray-200 last:border-r-0">
                <i class="fa-solid fa-star {{ $activeTab === 'reviews' ? 'text-white' : 'text-yellow-500' }}"></i>
                <span class="hidden sm:inline">REVIEWS</span>
            </a>
        @endif
        <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex-1 flex items-center justify-center gap-2 py-3 px-2 text-xs font-semibold {{ $activeTab === 'opinions' ? 'bg-[#F9A13D] text-white' : 'text-gray-700 hover:bg-[#F9A13D] hover:text-white' }} transition-colors border-r border-gray-200 last:border-r-0">
            <i class="fa-solid fa-comment-dots {{ $activeTab === 'opinions' ? 'text-white' : 'text-blue-500' }}"></i>
            <span class="hidden sm:inline">OPINIONS</span>
        </a>
        @if($device->offers->isNotEmpty())
        <a href="{{ route('device.prices', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex-1 flex items-center justify-center gap-2 py-3 px-2 text-xs font-semibold {{ $activeTab === 'prices' ? 'bg-[#F9A13D] text-white' : 'text-gray-700 hover:bg-[#F9A13D] hover:text-white' }} transition-colors border-r border-gray-200 last:border-r-0">
            <i class="fa-solid fa-dollar-sign {{ $activeTab === 'prices' ? 'text-white' : 'text-gray-400' }}"></i>
            <span class="hidden sm:inline">PRICES</span>
        </a>
        @endif
        @if($device->imageGroups->isNotEmpty() && $device->imageGroups->first()->images->isNotEmpty())
        <a href="{{ route('device.pictures', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex-1 flex items-center justify-center gap-2 py-3 px-2 text-xs font-semibold {{ $activeTab === 'pictures' ? 'bg-[#F9A13D] text-white' : 'text-gray-700 hover:bg-[#F9A13D] hover:text-white' }} transition-colors border-r border-gray-200 last:border-r-0">
            <i class="fa-solid fa-images {{ $activeTab === 'pictures' ? 'text-white' : 'text-gray-400' }}"></i>
            <span class="hidden sm:inline">PICTURES</span>
        </a>
        @endif
        <a href="{{ route('device.compare', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex-1 flex items-center justify-center gap-2 py-3 px-2 text-xs font-semibold {{ $activeTab === 'compare' ? 'bg-[#F9A13D] text-white' : 'text-gray-700 hover:bg-[#F9A13D] hover:text-white' }} transition-colors border-r border-gray-200 last:border-r-0">
            <i class="fa-solid fa-code-compare {{ $activeTab === 'compare' ? 'text-white' : 'text-gray-400' }}"></i>
            <span class="hidden sm:inline">COMPARE</span>
        </a>
    </div>
</div>