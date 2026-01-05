@extends('layouts.app')

@section('title', 'Device Comparison')

@section('content')

    {{-- Hero Header --}}
    <div class="overflow-hidden w-full mb-6 md:h-[220px]">
        <div class="relative bg-cover bg-center h-full"
            style='background-image: url("{{ asset('user/images/phone-comparison.jpg') }}");'>
            <div class="bg-black/45 h-full">
                <div class="px-4 md:px-6 py-8 h-full flex flex-col justify-end gap-2">
                    <h1 class="text-3xl md:text-5xl uppercase font-bold tracking-tight text-white drop-shadow-md">
                        Device Comparison
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-[#f6f6f6] border-b border-[#ddd] mb-8">
        <div class="max-w-[1200px] mx-auto px-4 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Box 1 --}}
                <div
                    class="bg-white p-4 shadow-sm border border-gray-200 flex flex-col items-center text-center gap-4 min-h-[300px] relative">
                    @if($device1)
                        <div class="h-[180px] flex items-center justify-center w-full">
                            <img src="{{ $device1->thumbnail_url }}" alt="{{ $device1->name }}"
                                class="max-h-full object-contain">
                        </div>
                        <h2 class="text-xl font-bold text-[#F9A13D]">{{ $device1->name }}</h2>
                        <div class="flex gap-2 text-xs text-gray-500">
                            <span><i class="far fa-comment"></i> {{ $device1->comments_count }}</span>
                            <span><i class="far fa-clock"></i>
                                {{ $device1->released_at ? $device1->released_at->format('Y') : 'N/A' }}</span>
                        </div>
                    @else
                        <div class="h-full flex flex-col items-center justify-center w-full gap-4 py-10">
                            <i class="fas fa-plus-circle text-4xl text-gray-200"></i>
                            <p class="text-gray-400 font-medium italic">Add device to compare</p>
                            <div class="w-full relative px-4">
                                <input type="text" placeholder="Search device..."
                                    class="w-full px-3 py-2 border rounded-full text-sm focus:ring-2 focus:ring-[#F9A13D] outline-none search-device"
                                    data-index="1">
                                <div class="absolute left-0 right-0 mt-1 bg-white shadow-xl z-50 rounded-lg hidden search-results"
                                    data-index="1"></div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Box 2 --}}
                <div
                    class="bg-white p-4 shadow-sm border border-gray-200 flex flex-col items-center text-center gap-4 min-h-[300px] relative">
                    @if($device2)
                        <div class="h-[180px] flex items-center justify-center w-full">
                            <img src="{{ $device2->thumbnail_url }}" alt="{{ $device2->name }}"
                                class="max-h-full object-contain">
                        </div>
                        <h2 class="text-xl font-bold text-[#F9A13D]">{{ $device2->name }}</h2>
                        <div class="flex gap-2 text-xs text-gray-500">
                            <span><i class="far fa-comment"></i> {{ $device2->comments_count }}</span>
                            <span><i class="far fa-clock"></i>
                                {{ $device2->released_at ? $device2->released_at->format('Y') : 'N/A' }}</span>
                        </div>
                    @else
                        <div class="h-full flex flex-col items-center justify-center w-full gap-4 py-10">
                            <i class="fas fa-plus-circle text-4xl text-gray-200"></i>
                            <p class="text-gray-400 font-medium italic">Add device to compare</p>
                            <div class="w-full relative px-4">
                                <input type="text" placeholder="Search device..."
                                    class="w-full px-3 py-2 border rounded-full text-sm focus:ring-2 focus:ring-[#F9A13D] outline-none search-device"
                                    data-index="2">
                                <div class="absolute left-0 right-0 mt-1 bg-white shadow-xl z-50 rounded-lg hidden search-results"
                                    data-index="2"></div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Box 3 --}}
                <div
                    class="bg-white p-4 shadow-sm border border-gray-200 flex flex-col items-center text-center gap-4 min-h-[300px] relative">
                    @if($device3)
                        <div class="h-[180px] flex items-center justify-center w-full">
                            <img src="{{ $device3->thumbnail_url }}" alt="{{ $device3->name }}"
                                class="max-h-full object-contain">
                        </div>
                        <h2 class="text-xl font-bold text-[#F9A13D]">{{ $device3->name }}</h2>
                        <div class="flex gap-2 text-xs text-gray-500">
                            <span><i class="far fa-comment"></i> {{ $device3->comments_count }}</span>
                            <span><i class="far fa-clock"></i>
                                {{ $device3->released_at ? $device3->released_at->format('Y') : 'N/A' }}</span>
                        </div>
                    @else
                        <div class="h-full flex flex-col items-center justify-center w-full gap-4 py-10">
                            <i class="fas fa-plus-circle text-4xl text-gray-200"></i>
                            <p class="text-gray-400 font-medium italic">Add device to compare</p>
                            <div class="w-full relative px-4">
                                <input type="text" placeholder="Search device..."
                                    class="w-full px-3 py-2 border rounded-full text-sm focus:ring-2 focus:ring-[#F9A13D] outline-none search-device"
                                    data-index="3">
                                <div class="absolute left-0 right-0 mt-1 bg-white shadow-xl z-50 rounded-lg hidden search-results"
                                    data-index="3"></div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- Comparison Specs Section --}}
    <div class="bg-white py-6">
        <div class="container mx-auto px-4">
            @php
                $categories = \App\Models\SpecCategory::with([
                    'fields' => function ($q) {
                        $q->orderBy('order');
                    }
                ])->orderBy('order')->get();

                // Pre-map specs for quick lookup
                $d1Specs = $device1 ? $device1->specValues->groupBy('spec_field_id') : collect();
                $d2Specs = $device2 ? $device2->specValues->groupBy('spec_field_id') : collect();
                $d3Specs = $device3 ? $device3->specValues->groupBy('spec_field_id') : collect();

                function getSpecDisplay($specs, $fieldId)
                {
                    $spec = $specs->get($fieldId)?->first();
                    if (!$spec)
                        return '-';

                    $val = $spec->value_string ?? $spec->value_number ?? (is_array($spec->value_json) ? json_encode($spec->value_json) : $spec->value_json);
                    if ($spec->unit) {
                        $val .= ' ' . $spec->unit;
                    }
                    return $val;
                }
            @endphp

            <div class="bg-[#efebe9] py-1">
                @foreach($categories as $category)
                    @if($category->fields->count() > 0)
                        <table class="shadow bg-white w-full mb-2 mt-2 border-collapse">
                            <tbody>
                                @foreach($category->fields as $index => $field)
                                    <tr class="border-b border-[#f3f3f3]">
                                        {{-- Category Name --}}
                                        <th
                                            class="text-start pl-2 w-[120px] text-[#F9A13D] text-[15px] uppercase font-bold align-top py-2">
                                            {{ $index === 0 ? $category->name : '' }}
                                        </th>

                                        {{-- Field Label --}}
                                        <td
                                            class="text-start pl-2 w-[130px] font-[700] text-[#7d7464] py-2 px-[10px] text-[13px] border-l border-[#eee] bg-[#fafafa] align-top">
                                            {{ $field->label }}
                                        </td>

                                        {{-- Device 1 Value --}}
                                        <td class="text-start pl-4 py-2 px-[10px] text-[14px] w-1/4 border-l border-[#eee] align-top">
                                            @if($device1)
                                                {!! getSpecDisplay($d1Specs, $field->id) !!}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        {{-- Device 2 Value --}}
                                        <td class="text-start pl-4 py-2 px-[10px] text-[14px] w-1/4 border-l border-[#eee] align-top">
                                            @if($device2)
                                                {!! getSpecDisplay($d2Specs, $field->id) !!}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        {{-- Device 3 Value --}}
                                        <td class="text-start pl-4 py-2 px-[10px] text-[14px] w-1/4 border-l border-[#eee] align-top">
                                            @if($device3)
                                                {!! getSpecDisplay($d3Specs, $field->id) !!}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInputs = document.querySelectorAll('.search-device');

            searchInputs.forEach(input => {
                let debounceTimer;
                const index = input.dataset.index;
                const resultsContainer = document.querySelector(`.search-results[data-index="${index}"]`);

                input.addEventListener('input', function () {
                    clearTimeout(debounceTimer);
                    const query = this.value.trim();

                    if (query.length < 2) {
                        resultsContainer.classList.add('hidden');
                        return;
                    }

                    debounceTimer = setTimeout(async () => {
                        try {
                            const response = await fetch(`{{ route('device.search') }}?q=${encodeURIComponent(query)}`);
                            const devices = await response.json();

                            resultsContainer.innerHTML = '';
                            resultsContainer.classList.remove('hidden');

                            if (devices.length === 0) {
                                resultsContainer.innerHTML = '<div class="p-4 text-sm text-gray-500 italic">No devices found.</div>';
                                return;
                            }

                            devices.forEach(device => {
                                const div = document.createElement('div');
                                div.className = 'p-3 hover:bg-gray-100 cursor-pointer flex items-center gap-3 border-b border-gray-50 last:border-0';
                                div.innerHTML = `
                                <img src="${device.thumbnail_url}" class="w-8 h-10 object-contain">
                                <span class="text-sm font-semibold">${device.name}</span>
                            `;
                                div.onclick = () => {
                                    const url = new URL(window.location.href);
                                    const currentDevices = url.searchParams.get('devices') ? url.searchParams.get('devices').split(',') : [];

                                    // We use slugs for currentDevices
                                    // If index - 1 exists in the array, replace it. Otherwise push.
                                    // But usually, easier is to just rebuild from current state
                                    // For simplicity, let's just use IDs/slugs based on what's already there

                                    // Actually, let's just append or replace
                                    let newDevices = [];
                                @if($device1) newDevices.push('{{ $device1->slug }}'); @elsenewDevices.push(null); @endif
                                    @if($device2) newDevices.push('{{ $device2->slug }}'); @elsenewDevices.push(null); @endif
                                    @if($device3) newDevices.push('{{ $device3->slug }}'); @elsenewDevices.push(null); @endif

                                    newDevices[index - 1] = device.slug;

                                    // Filter out nulls and join
                                    const devicesString = newDevices.filter(d => d !== null).join(',');
                                    window.location.href = `{{ route('device-comparison') }}?devices=${devicesString}`;
                                };
                                resultsContainer.appendChild(div);
                            });
                        } catch (error) {
                            console.error('Search error:', error);
                        }
                    }, 300);
                });
            });

            document.addEventListener('click', function (e) {
                if (!e.target.closest('.search-device') && !e.target.closest('.search-results')) {
                    document.querySelectorAll('.search-results').forEach(r => r.classList.add('hidden'));
                }
            });
        });
    </script>
@endpush