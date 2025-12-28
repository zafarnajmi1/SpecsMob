@extends('layouts.app')

@section('title', $device->name)

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
    <x-device-header :device="$device" activeTab="detail" />

    <!-- Specifications Section -->
    <div class="bg-[#D1D5DB91]">
        @php
            // Use specValues (relation name), NOT spec_values
            $groupedSpecs = $device->specValues
                ->sortBy(fn($i) => $i->field->category->order)
                ->groupBy(fn($i) => $i->field->category->name);
        @endphp

        <div>
            @foreach($groupedSpecs as $categoryName => $specItems)

                <table class="shadow bg-white w-full mb-1">
                    <tbody>

                        @foreach($specItems as $index => $spec)
                            <tr>
                                {{-- Category Name (only for the first row of each category) --}}
                                <th class="text-start pl-2 py-1 w-[86px] text-[#F9A13D] text-[16px] uppercase">
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

    <!-- Footer Info bar -->
    <div
        class="flex flex-wrap justify-end items-center border-t border-gray-400 shadow text-sm text-white px-4 md:px-6 h-[2.5rem] bg-[#F9A13D] backdrop-blur-sm">
        <div class="flex gap-4 h-full">
            <a href="{{ route('device.opinions', ['slug' => $device->slug, 'id' => $device->id])}}"
                class="flex items-center gap-1 hover:bg-white hover:text-[#F9A13D] transition-colors px-3">
                <i class="fa-solid fa-newspaper"></i> OPINIONS
            </a>
            <button id="compare-tab"
                class="compare-btn relative cursor-pointer flex items-center gap-1 transition hover:bg-white hover:text-[#F9A13D] px-3">
                <i class="fa-solid fa-code-compare"></i>
                <span class="compare-text">COMPARE</span>
            </button>
            <a href="{{ route('device.pictures', ['slug' => $device->slug, 'id' => $device->id])}}"
                class="flex items-center gap-1 hover:bg-white hover:text-[#F9A13D] transition-colors px-3">
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
@endsection

@push('scripts')
@endpush