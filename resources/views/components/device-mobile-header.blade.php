@props(['device', 'activeTab' => 'detail'])

<div class="lg:hidden bg-white border-b border-gray-200 shadow-sm">
    <!-- Device Title -->
    <div class="px-4 py-3 border-b border-gray-100">
        <h1 class="text-xl font-bold text-gray-900">{{ $device->name }}</h1>
    </div>

    <div class="p-4">
        <!-- Main Content Area -->
        <div class="flex gap-4">
            <!-- Left: Device Image -->
            <div class="w-1/3 flex-shrink-0">
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-2 h-full flex items-center justify-center">
                    <img src="{{ $device->thumbnail_url }}" alt="{{ $device->name }}"
                        class="w-full h-auto object-contain">
                </div>
            </div>

            <!-- Right: Device Info -->
            <div class="flex-1">
                <!-- Top Bar: Brand, Date, and Favorite -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex flex-col gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-2 py-1 rounded-full text-[10px] font-semibold uppercase">
                            <i class="fa-solid fa-tag"></i>
                            {{ $device->brand->name }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-gray-600 text-[11px]">
                            <i class="fa-regular fa-calendar"></i>
                            {{ $device->released_at ? $device->released_at->format('M Y') : 'TBA' }}
                        </span>
                    </div>

                    @if($device->allow_fans)
                        <form action="{{ route('device.fan', $device->id) }}" method="POST">
                            @csrf
                            <div class="flex flex-col items-center">
                                <button type="submit" class="group">
                                    @php
                                        $isFan = auth()->check() && $device->favorites()->where('user_id', auth()->id())->exists();
                                    @endphp
                                    <i
                                        class="fa-{{ $isFan ? 'solid' : 'regular' }} fa-heart text-2xl {{ $isFan ? 'text-[#F9A13D]' : 'text-gray-300 group-hover:text-[#F9A13D]' }} transition-colors"></i>
                                </button>
                                <span
                                    class="text-[10px] font-bold text-gray-500 mt-0.5">{{ $device->favorites()->count() }}</span>
                            </div>
                        </form>
                    @endif
                </div>

                <!-- Spec Cards Grid -->
                <div class="grid grid-cols-2 gap-2">
                    @php
                        $specs = [
                            ['icon' => 'fa-mobile-screen', 'label' => 'WEIGHT', 'value' => $device->weight_grams ? $device->weight_grams . ' g' : 'N/A'],
                            ['icon' => 'fa-brands fa-android', 'label' => 'OS', 'value' => $device->os_short ?? 'N/A'],
                            ['icon' => 'fa-microchip', 'label' => 'CHIPSET', 'value' => $device->chipset_short ?? 'N/A'],
                            ['icon' => 'fa-memory', 'label' => 'STORAGE', 'value' => $device->storage_short ?? 'N/A'],
                            ['icon' => 'fa-battery-full', 'label' => 'BATTERY', 'value' => $device->battery_short ?? 'N/A'],
                            ['icon' => 'fa-camera', 'label' => 'CAMERA', 'value' => $device->main_camera_short ?? 'N/A'],
                        ];
                    @endphp

                    @foreach($specs as $spec)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-2 text-center">
                            <i class="fa-solid {{ $spec['icon'] }} text-[#F9A13D] text-lg mb-1 block"></i>
                            <div class="text-[8px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">
                                {{ $spec['label'] }}
                            </div>
                            <div class="text-[11px] font-bold text-gray-900 line-clamp-1">{{ $spec['value'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons Bar -->
    <div class="border-t border-gray-200 flex flex-wrap">
        <a href="{{ route('device-detail', $device->slug) }}"
            class="flex-1 flex items-center justify-center gap-1 py-3 px-1 text-[10px] font-semibold {{ $activeTab === 'detail' ? 'bg-[#F9A13D] text-white' : 'text-gray-700 hover:bg-gray-50' }} transition-colors border-r border-gray-200">
            <i class="fa-solid fa-mobile text-sm"></i>
            <span class="uppercase">Specs</span>
        </a>

        @if($device->reviews && $device->reviews->first() && $device->reviews->first()->isPublished())
            <a href="{{ route('review-detail', $device->reviews->first()->slug) }}"
                class="flex-1 flex items-center justify-center gap-1 py-3 px-1 text-[10px] font-semibold {{ $activeTab === 'reviews' ? 'bg-[#F9A13D] text-white' : 'text-gray-700 hover:bg-gray-50' }} transition-colors border-r border-gray-200">
                <i class="fa-solid fa-star text-sm {{ $activeTab === 'reviews' ? 'text-white' : 'text-yellow-500' }}"></i>
                <span class="uppercase">Review</span>
            </a>
        @endif

        <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex-1 flex items-center justify-center gap-1 py-3 px-1 text-[10px] font-semibold {{ $activeTab === 'opinions' ? 'bg-[#F9A13D] text-white' : 'text-gray-700 hover:bg-gray-50' }} transition-colors border-r border-gray-200">
            <i
                class="fa-solid fa-comment-dots text-sm {{ $activeTab === 'opinions' ? 'text-white' : 'text-blue-500' }}"></i>
            <span class="uppercase">Opinions</span>
        </a>

        <a href="{{ route('device.prices', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex-1 flex items-center justify-center gap-1 py-3 px-1 text-[10px] font-semibold {{ $activeTab === 'prices' ? 'bg-[#F9A13D] text-white' : 'text-gray-700 hover:bg-gray-50' }} transition-colors border-r border-gray-200">
            <i
                class="fa-solid fa-dollar-sign text-sm {{ $activeTab === 'prices' ? 'text-white' : 'text-green-500' }}"></i>
            <span class="uppercase">Prices</span>
        </a>

        <a href="{{ route('device.pictures', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex-1 flex items-center justify-center gap-1 py-3 px-1 text-[10px] font-semibold {{ $activeTab === 'pictures' ? 'bg-[#F9A13D] text-white' : 'text-gray-700 hover:bg-gray-50' }} transition-colors border-r border-gray-200">
            <i
                class="fa-solid fa-images text-sm {{ $activeTab === 'pictures' ? 'text-white' : 'text-purple-500' }}"></i>
            <span class="uppercase">Pictures</span>
        </a>

        <a href="{{ route('device.compare', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex-1 flex items-center justify-center gap-1 py-3 px-1 text-[10px] font-semibold {{ $activeTab === 'compare' ? 'bg-[#F9A13D] text-white' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
            <i
                class="fa-solid fa-code-compare text-sm {{ $activeTab === 'compare' ? 'text-white' : 'text-gray-500' }}"></i>
            <span class="uppercase">Compare</span>
        </a>
    </div>
</div>