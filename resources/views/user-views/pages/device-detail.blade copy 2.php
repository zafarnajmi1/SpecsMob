@extends('layouts.app')

@section('title', $device->name)

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')

    <div class="mb-5 w-full bg-cover bg-center shadow-lg bg-[#f5f9fc]">
        <div class="flex p-4 gap-4 items-start">
            <!-- Left Section: Image and Buttons -->
            <div class="w-[30%]">
                <div class="bg-white shadow-md rounded-xl flex justify-center items-center p-4 mb-3">
                    <img src="{{ $device->thumbnail_url }}" alt="{{ $device->name }}" class="rounded-lg w-full">
                </div>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('device.compare', ['slug' => $device->slug, 'id' => $device->id])}}"
                        class="compare-btn relative cursor-pointer flex items-center justify-center gap-2 border border-2 border-blue-500 text-blue-500 rounded-full hover:bg-blue-500 hover:text-white transition-all duration-300 px-3 py-2 text-xs font-medium">
                        <i class="fa-solid fa-code-compare"></i>
                        <span class="compare-text">Compare</span>
                    </a>
                    <a href="{{ route('device.pictures', ['slug' => $device->slug, 'id' => $device->id])}}"
                        class="flex items-center justify-center gap-2 border border-2 border-green-500 text-green-500 rounded-full hover:bg-green-500 hover:text-white transition-all duration-300 px-3 py-2 text-xs font-medium">
                        <i class="fa-solid fa-photo-film"></i> Pictures
                    </a>
                    <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
                        class="flex items-center justify-center gap-2 border border-2 border-gray-500 text-gray-500 rounded-full hover:bg-gray-500 hover:text-white transition-all duration-300 px-3 py-2 text-xs font-medium">
                        <i class="fa-solid fa-newspaper"></i> Reviews
                    </a>
                    <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
                        class="flex items-center justify-center gap-2 border border-2 border-[#F9A13D] text-[#F9A13D] rounded-full hover:bg-[#F9A13D] hover:text-white transition-all duration-300 px-3 py-2 text-xs font-medium">
                        <i class="fa-solid fa-newspaper"></i> Opinions
                    </a>
                    <a href="#"
                        class="flex items-center justify-center gap-2 border border-2 border-[#2bdede] text-[#2bdede] rounded-full hover:bg-[#2bdede] hover:text-white transition-all duration-300 px-3 py-2 text-xs font-medium">
                        <i class="fa-solid fa-newspaper"></i> News
                    </a>
                    @if($device->activeOffers()->count() > 0)
                        <a href="{{ route('device.prices', ['slug' => $device->slug, 'id' => $device->id])}}"
                            class="flex items-center justify-center gap-2 border border-2 border-[#2bdede] text-[#2bdede] rounded-full hover:bg-[#2bdede] hover:text-white transition-all duration-300 px-3 py-2 text-xs font-medium">
                            <i class="fa-solid fa-dollar-sign"></i> PRICES
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right Section: Device Info and Specs -->
            <div class="w-[70%]">
                <h1 class="text-[#1976d2] font-bold text-2xl mb-1">{{ $device->name }}</h1>
                <p class="text-gray-700 text-sm mb-1">Brand: <b class="text-[#1976d2]">{{ $device->brand->name }}</b></p>
                <p class="text-gray-700 text-sm mb-4">Released: {{ $device->released_at }}</p>

                <div class="grid grid-cols-3 gap-3">
                    <div
                        class="bg-white py-3 px-3 rounded-lg border border-gray-200 shadow-sm flex flex-col gap-1 justify-center items-center hover:shadow-md transition-shadow">
                        <i class="fa-solid fa-mobile text-blue-500 block text-lg"></i>
                        <span class="text-black font-bold block text-sm text-center">{{ $device->weight_grams }}</span>
                        <span class="text-gray-500 text-xs block">Weight</span>
                    </div>
                    <div
                        class="bg-white py-3 px-3 rounded-lg border border-gray-200 shadow-sm flex flex-col gap-1 justify-center items-center hover:shadow-md transition-shadow">
                        <i class="fa-brands fa-android text-green-600 block text-lg"></i>
                        <span class="text-black font-bold block text-sm text-center">{{ $device->os_short }}</span>
                        <span class="text-gray-500 text-xs block">OS</span>
                    </div>
                    <div
                        class="bg-white py-3 px-3 rounded-lg border border-gray-200 shadow-sm flex flex-col gap-1 justify-center items-center hover:shadow-md transition-shadow">
                        <i class="fa-solid fa-microchip text-[#F9A13D] block text-lg"></i>
                        <span class="text-black font-bold block text-sm text-center">{{ $device->chipset_short }}</span>
                        <span class="text-gray-500 text-xs block">Chipset</span>
                    </div>
                    <div
                        class="bg-white py-3 px-3 rounded-lg border border-gray-200 shadow-sm flex flex-col gap-1 justify-center items-center hover:shadow-md transition-shadow">
                        <i class="fa-solid fa-memory text-[#2bdede] block text-lg"></i>
                        <span class="text-black font-bold block text-sm text-center">{{ $device->storage_short }}</span>
                        <span class="text-gray-500 text-xs block">Memory</span>
                    </div>
                    <div
                        class="bg-white py-3 px-3 rounded-lg border border-gray-200 shadow-sm flex flex-col gap-1 justify-center items-center hover:shadow-md transition-shadow">
                        <i class="fa-solid fa-battery-full text-yellow-500 block text-lg"></i>
                        <span class="text-black font-bold block text-sm text-center">{{ $device->battery_short }}</span>
                        <span class="text-gray-500 text-xs block">Battery</span>
                    </div>
                    <div
                        class="bg-white py-3 px-3 rounded-lg border border-gray-200 shadow-sm flex flex-col gap-1 justify-center items-center hover:shadow-md transition-shadow">
                        <i class="fa-solid fa-camera text-gray-500 block text-lg"></i>
                        <span class="text-black font-bold block text-sm text-center">{{ $device->main_camera_short }}</span>
                        <span class="text-gray-500 text-xs block">Main Camera</span>
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
            <a href="#"
                class="hover:text-[#F9A13D] font-bold text-lg transition uppercase tracking-tighter">{{ $device->name }} -
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