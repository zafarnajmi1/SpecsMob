@extends('layouts.app')

@section('title', $device->name)

@section('sidebar')
@include('partials.aside')
@endsection

@section('content')
<!-- Refined Balanced Premium Device Header -->
<div class="mb-10 bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-6 md:p-10">
        <div class="flex flex-col lg:flex-row gap-8 items-center">
            <!-- Left Column: Premium Device Showcase -->
            <div class="lg:w-[30%] xl:w-[25%] w-full flex-shrink-0">
                <div
                    class="relative group bg-gradient-to-b from-gray-50/50 to-gray-100/50 rounded-2xl border border-gray-100 p-6 flex items-center justify-center min-h-[320px] lg:min-h-[380px] overflow-hidden">
                    <!-- Subtle Background Accent -->
                    <div
                        class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white/40 via-transparent to-transparent opacity-60">
                    </div>

                    <img src="{{ $device->thumbnail_url }}" alt="{{ $device->name }}"
                        class="relative z-10 w-full h-auto max-h-[300px] object-contain drop-shadow-[0_20px_30px_rgba(0,0,0,0.12)] transform group-hover:scale-110 transition-all duration-700 ease-out">
                </div>
            </div>

            <!-- Right Column: Product Intelligence -->
            <div class="lg:w-[70%] xl:w-[75%] w-full">
                <!-- Identity & Engagement -->
                <div class="flex items-start justify-between mb-4">
                    <div class="space-y-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <span
                                class="bg-blue-600 text-white px-4 py-1.5 rounded-xl text-[11px] font-black uppercase tracking-widest shadow-lg shadow-blue-100">
                                <i class="fa-solid fa-tag"></i> {{ $device->brand->name }}
                            </span>
                            <span
                                class="bg-gray-100 text-gray-500 px-3 py-1.5 rounded-xl text-[11px] font-bold uppercase tracking-wide flex items-center gap-2">
                                <i class="fa-regular fa-calendar-alt"></i>
                                {{ $device->released_at ? $device->released_at->format('M Y') : 'Upcoming' }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                                {{ $device->name }}</h1>
                            <p class="text-slate-400 text-sm font-medium mt-1">
                                {{ $device->description ? Str::limit($device->description, 100) : 'Elegance meets
                                performance in every detail.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Fan Control -->
                    @if($device->allow_fans)
                    <form action="{{ route('device.fan', $device->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="relative group p-2">
                            @php
                            $isFan = auth()->check() && $device->favorites()->where('user_id', auth()->id())->exists();
                            $fanCount = $device->favorites()->count();
                            @endphp
                            <i
                                class="fa-heart {{ $isFan ? 'fa-solid text-[#F9A13D]' : 'fa-regular text-slate-300 group-hover:text-[#F9A13D]' }} text-3xl transition-all duration-300"></i>
                            @if($fanCount > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-[#F9A13D] text-white text-[10px] font-black min-w-[20px] h-[20px] px-1 flex items-center justify-center rounded-full border-2 border-white shadow-sm transition-transform duration-300 group-hover:scale-110">
                                {{ $fanCount }}
                            </span>
                            @endif
                        </button>
                    </form>
                    @endif
                </div>

                <div class="h-px bg-gray-100 w-full mb-8"></div>

                <!-- Tech Matrix -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                    @php
                    $techSpecs = [
                    ['icon' => 'fa-weight-hanging', 'color' => 'blue', 'label' => 'WEIGHT', 'value' =>
                    $device->weight_grams ? $device->weight_grams . ' g' : 'N/A'],
                    ['icon' => 'fa-brands fa-android', 'color' => 'green', 'label' => 'OS', 'value' => $device->os_short
                    ?? 'N/A'],
                    ['icon' => 'fa-microchip', 'color' => '#F9A13D', 'label' => 'CHIPSET', 'value' =>
                    $device->chipset_short ?? 'N/A'],
                    ['icon' => 'fa-memory', 'color' => 'cyan', 'label' => 'STORAGE', 'value' => $device->storage_short
                    ?? 'N/A'],
                    ['icon' => 'fa-battery-full', 'color' => 'yellow', 'label' => 'BATTERY', 'value' =>
                    $device->battery_short ?? 'N/A'],
                    ['icon' => 'fa-camera', 'color' => 'gray', 'label' => 'CAMERA', 'value' =>
                    $device->main_camera_short ?? 'N/A'],
                    ];
                    @endphp

                    @foreach($techSpecs as $spec)
                    <div
                        class="bg-gray-50/40 border border-gray-100 rounded-2xl p-4 hover:border-blue-200 hover:bg-white transition-all group shadow-sm">
                        <i
                            class="fa-solid {{ $spec['icon'] }} {{ str_starts_with($spec['color'], '#') ? 'text-[' . $spec['color'] . ']' : 'text-' . $spec['color'] . '-500' }} text-xl mb-3 block group-hover:scale-110 transition-transform"></i>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                            {{ $spec['label'] }}</div>
                        <div class="text-[14px] font-bold text-slate-800 leading-tight">{{ $spec['value'] }}</div>
                    </div>
                    @endforeach
                </div>

                <!-- Multi-row Action Center -->
                <div class="flex flex-col gap-3">
                    <!-- Primary Actions -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
                            class="flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-3 rounded-xl text-[12px] font-bold uppercase transition hover:bg-gray-50 shadow-sm leading-none">
                            <i class="fa-solid fa-star text-yellow-500"></i> Reviews
                        </a>
                        <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
                            class="flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-3 rounded-xl text-[12px] font-bold uppercase transition hover:bg-gray-50 shadow-sm leading-none">
                            <i class="fa-solid fa-comment-dots text-blue-500"></i> Opinions
                        </a>
                        <button
                            class="flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-3 rounded-xl text-[12px] font-bold uppercase transition hover:bg-gray-50 shadow-sm leading-none">
                            <i class="fa-solid fa-newspaper text-green-500"></i> News
                        </button>
                        <a href="{{ route('device.prices', ['slug' => $device->slug, 'id' => $device->id])}}"
                            class="flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-3 rounded-xl text-[12px] font-bold uppercase transition hover:bg-gray-50 shadow-sm leading-none">
                            <i class="fa-solid fa-dollar-sign text-gray-400"></i> Prices
                        </a>
                    </div>
                    <!-- Secondary Actions -->
                    <div class="grid grid-cols-2 gap-3 md:w-1/2">
                        <a href="{{ route('device.compare', ['slug' => $device->slug, 'id' => $device->id])}}"
                            class="flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-3 rounded-xl text-[12px] font-bold uppercase transition hover:bg-gray-50 shadow-sm leading-none">
                            <i class="fa-solid fa-code-compare text-gray-400"></i> Compare
                        </a>
                        <a href="{{ route('device.pictures', ['slug' => $device->slug, 'id' => $device->id])}}"
                            class="flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-3 rounded-xl text-[12px] font-bold uppercase transition hover:bg-gray-50 shadow-sm leading-none">
                            <i class="fa-solid fa-images text-gray-400"></i> Pictures
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Specifications Section -->
<div class="bg-white py-6">
    <div class="container mx-auto px-4">
        @php
        // Use specValues (relation name), NOT spec_values
        $groupedSpecs = $device->specValues
        ->sortBy(fn($i) => $i->field->category->order)
        ->groupBy(fn($i) => $i->field->category->name);
        @endphp

        <div class="bg-[#efebe9] py-1">
            @foreach($groupedSpecs as $categoryName => $specItems)

            <table class="shadow bg-white w-full mb-2 mt-2">
                <tbody>

                    @foreach($specItems as $index => $spec)
                    <tr>
                        {{-- Category Name (only for the first row of each category) --}}
                        <th class="text-start pl-2 w-[86px] text-[#F9A13D] text-[16px] uppercase">
                            {{ $index === 0 ? $categoryName : '' }}
                        </th>

                        {{-- Field Label --}}
                        <td class="text-start pl-2 w-[110px] font-[700] text-[#7d7464] py-[3px] px-[10px]">
                            {{ $spec->field->label }}
                        </td>

                        {{-- Field Value --}}
                        <td class="text-start pl-2 py-[3px] px-[10px]">
                            {{ $spec->value_string ?? $spec->value_number ?? json_encode($spec->value_json) }}
                            @if($spec->unit)
                            {{ ' ' . $spec->unit }}
                            @endif
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            @endforeach
        </div>


    </div>
</div>

<!-- Footer Info bar -->
<div
    class="flex flex-wrap justify-end items-center border-t border-gray-400 shadow text-sm text-white px-4 md:px-6 h-[2.5rem] bg-[rgba(0,0,0,0.2)] backdrop-blur-sm">
    <div class="flex gap-4 h-full">
        <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex items-center gap-1 hover:bg-[#F9A13D] transition-colors px-3">
            <i class="fa-solid fa-newspaper"></i> OPINIONS
        </a>
        <button id="compare-tab"
            class="compare-btn relative cursor-pointer flex items-center gap-1 transition hover:bg-[#F9A13D] px-3">
            <i class="fa-solid fa-code-compare"></i>
            <span class="compare-text">COMPARE</span>
        </button>
        <a href="{{ route('device.pictures', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex items-center gap-1 hover:bg-[#F9A13D] transition-colors px-3">
            <i class="fa-solid fa-newspaper"></i> PICTURES
        </a>
    </div>
</div>

<!-- Opinion Section -->
<div class="bg-[#f0f0f0]">
    <h2 class="bg-white pl-[10px] pt-[10px] pr-[5px] mt-2 text-[#555] border-b border-[#ddd]">
        <a href="#" class="hover:text-[#F9A13D] font-bold text-lg transition uppercase tracking-tighter">{{
            $device->name }} -
            User Opinions and Reviews</a>
    </h2>

    <div class="space-y-[1px]">
        @forelse ($opinions as $opinion)
        @include('partials.device-comment', ['comment' => $opinion, 'device' => $device])
        @empty
        <div class="bg-white p-10 text-center text-gray-500 italic">
            No opinions yet. Be the first to share your thoughts!
        </div>
        @endforelse
    </div>
</div>

<div
    class="flex justify-between items-center border-t border-gray-400 shadow px-4 md:px-6 mt-3 pb-2 pt-5 bg-[#f0f0f0] backdrop-blur-sm">
    <div class="flex items-center gap-3">
        <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id]) }}"
            class="flex justify-center items-center px-3 py-1 font-bold text-[12px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase tracking-tighter shadow-sm">
            <span>READ ALL OPINIONS</span>
        </a>
        <a href="{{ route('device.opinions.post', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex justify-center items-center px-3 py-1 font-bold text-[12px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase tracking-tighter shadow-sm">
            <span>POST YOUR OPINION</span>
        </a>
    </div>
    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">
        Total reader comments: <span class="text-[#F9A13D] ml-1">{{ $opinions->count() }}</span>
    </span>
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