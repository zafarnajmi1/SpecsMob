@extends('layouts.app')

@section('title', "$device->name Reviews")

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
 <!-- Device Header -->
    <div class="mb-6 bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden">
        <div class="p-4 md:p-6">
            <!-- Main Content Area -->
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left: Device Image -->
                <div class="lg:w-1/4 w-full flex-shrink-0">
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 shadow-sm">
                        <img src="{{ $device->thumbnail_url }}" alt="{{ $device->name }}"
                            class="w-full h-auto object-contain">
                    </div>
                </div>

                <!-- Right: Device Info -->
                <div class="flex-1">
                    <!-- Top Bar: Brand, Date, and Favorite -->
                    <div class="flex items-start justify-between mb-3">
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
                            <form action="{{ route('device.fan', $device->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="group">
                                    @php
                                        $isFan = auth()->check() && $device->favorites()->where('user_id', auth()->id())->exists();
                                    @endphp
                                    <i
                                        class="fa-{{ $isFan ? 'solid' : 'regular' }} fa-heart text-3xl {{ $isFan ? 'text-[#F9A13D]' : 'text-gray-300 group-hover:text-[#F9A13D]' }} transition-colors"></i>
                                </button>
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
            <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
                class="flex-1 flex items-center justify-center gap-2 py-3 px-2 text-xs font-semibold text-gray-700 hover:bg-[#F9A13D] hover:text-white transition-colors border-r border-gray-200 last:border-r-0">
                <i class="fa-solid fa-star text-yellow-500"></i>
                <span class="hidden sm:inline">REVIEWS</span>
            </a>
            <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
                class="flex-1 flex items-center justify-center gap-2 py-3 px-2 text-xs font-semibold text-gray-700 hover:bg-[#F9A13D] hover:text-white transition-colors border-r border-gray-200 last:border-r-0">
                <i class="fa-solid fa-dollar-sign"></i>
                <span class="hidden sm:inline">PRICES</span>
            </a>
            <a href="{{ route('device.prices', ['slug' => $device->slug, 'id' => $device->id])}}"
                class="flex-1 flex items-center justify-center gap-2 py-3 px-2 text-xs font-semibold text-gray-700 hover:bg-[#F9A13D] hover:text-white transition-colors border-r border-gray-200 last:border-r-0">
                <i class="fa-solid fa-photo-film text-gray-400"></i>
                <span class="hidden sm:inline">PICTURES</span>
            </a>
            <a href="{{ route('device.compare', ['slug' => $device->slug, 'id' => $device->id])}}"
                class="flex-1 flex items-center justify-center gap-2 py-3 px-2 text-xs font-semibold text-gray-700 hover:bg-[#F9A13D] hover:text-white transition-colors border-r border-gray-200 last:border-r-0">
                <i class="fa-solid fa-code-compare text-gray-400"></i>
                <span class="hidden sm:inline">COMPARE</span>
            </a>
            <a href="{{ route('device-detail', $device->slug) }}"
                class="flex-1 flex items-center justify-center gap-2 py-3 uppercase px-2 text-xs font-semibold text-gray-700 hover:bg-[#F9A13D] hover:text-white transition-colors">
                <i class="fa-solid fa-mobile text-gray-400"></i>
                <span class="hidden sm:inline">Specifications</span>
            </a>
        </div>
    </div>


    



    <div
        class="flex justify-between items-center border-t border-gray-400 shadow text-white px-4 md:px-6 mt-3 py-2 bg-[#f0f0f0] backdrop-blur-sm">
        <a href="{{ route('device.opinions.post', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex justify-center items-center px-3 py-1 font-bold text-[12px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase tracking-tighter shadow-sm">
            <span>POST YOUR OPINION</span>
        </a>
        @if($comments->lastPage() > 1)
            <div class="flex items-center gap-1">
                <span class="text-[#555] text-[14px]">Pages: </span>
                @for ($i = 1; $i <= $comments->lastPage(); $i++)
                    @if ($i == $comments->currentPage())
                        <strong class="bg-[#555] border border-[#333] text-[#f0f0f0] px-[11px] py-[5px] text-[13px]">{{ $i }}</strong>
                    @else
                        <a href="{{ $comments->url($i) }}"
                            class="bg-[#f0f0f0] border border-[#ddd] text-[#555] px-[11px] py-[5px] text-[13px] hover:bg-[#F9A13D] hover:text-white transition ml-1">{{ $i }}</a>
                    @endif
                @endfor
            </div>
        @endif
    </div>

    <!-- Opinions Section -->
    <div class="bg-[#f0f0f0]">
        <div class="space-y-[1px]">
            @forelse ($comments as $comment)
                @include('partials.device-comment', ['comment' => $comment, 'device' => $device])
            @empty
                <div class="bg-white p-10 text-center text-gray-500 italic">
                    No opinions yet. Be the first to share your thoughts!
                </div>
            @endforelse
        </div>
    </div>

    <div
        class="flex justify-between items-center border-t border-gray-400 shadow px-4 md:px-6 mt-3 py-2 bg-[#f0f0f0] backdrop-blur-sm">
        <a href="{{ route('device.opinions.post', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex justify-center items-center px-3 py-1 font-bold text-[12px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase tracking-tighter shadow-sm">
            <span>POST YOUR OPINION</span>
        </a>

        @if($comments->lastPage() > 1)
            <div class="flex items-center gap-1 ml-auto">
                <span class="text-[#555] text-[14px]">Pages: </span>
                @for ($i = 1; $i <= $comments->lastPage(); $i++)
                    @if ($i == $comments->currentPage())
                        <strong class="bg-[#555] border border-[#333] text-[#f0f0f0] px-[11px] py-[5px] text-[13px]">{{ $i }}</strong>
                    @else
                        <a href="{{ $comments->url($i) }}"
                            class="bg-[#f0f0f0] border border-[#ddd] text-[#555] px-[11px] py-[5px] text-[13px] hover:bg-[#F9A13D] hover:text-white transition ml-1">{{ $i }}</a>
                    @endif
                @endfor
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function toggleReplyForm(commentId) {
                const form = document.getElementById(`reply-form-${commentId}`);
                if (form) {
                    form.classList.toggle('hidden');
                    if (!form.classList.contains('hidden')) {
                        form.querySelector('textarea').focus();
                    }
                }
            }
        </script>
    @endpush

@endsection