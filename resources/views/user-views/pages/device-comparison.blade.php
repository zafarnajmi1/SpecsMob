@extends('layouts.app')

@section('title', 'Device Comparison')


@section('content')
    {{-- Hero Header --}}
    <div class="hidden lg:block overflow-hidden w-full mb-6 md:h-[320px]">
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
@endsection

@section('phone-comparison')

    <div class="bg-[#f6f6f6] border-b border-[#ddd] mb-8">
        <div class="max-w-[1200px] mx-auto px-4 py-10">
            <div id="desktop-comparison-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6">

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
                                <div class="absolute left-0 right-0 mt-1 bg-white shadow-xl z-50 rounded-lg hidden search-results max-h-[300px] overflow-y-auto border border-gray-100"
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
                                <div class="absolute left-0 right-0 mt-1 bg-white shadow-xl z-50 rounded-lg hidden search-results max-h-[300px] overflow-y-auto border border-gray-100"
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
                                <div class="absolute left-0 right-0 mt-1 bg-white shadow-xl z-50 rounded-lg hidden search-results max-h-[300px] overflow-y-auto border border-gray-100"
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

                if (!function_exists('getSpecDisplay')) {
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
                }
            @endphp

            <div id="desktop-comparison-specs" class="bg-[#efebe9] py-1">
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

@section('mobile-screen-phone-comparison')

    <div class="px-4 py-1">
        <h3 class="text-[15px] font-bold">Compare</h3>
        <p class="text-gray-600">SPECIFICATIONS</p>
    </div>
    <div class="bg-[#f6f6f6] border-b border-[#ddd] mb-8">
        <div class="max-w-[1200px] mx-auto px-4 py-10">
            <div id="mobile-comparison-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6">

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
                                <div class="absolute left-0 right-0 mt-1 bg-white shadow-xl z-50 rounded-lg hidden search-results max-h-[300px] overflow-y-auto border border-gray-100"
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
                                <div class="absolute left-0 right-0 mt-1 bg-white shadow-xl z-50 rounded-lg hidden search-results max-h-[300px] overflow-y-auto border border-gray-100"
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
                                <div class="absolute left-0 right-0 mt-1 bg-white shadow-xl z-50 rounded-lg hidden search-results max-h-[300px] overflow-y-auto border border-gray-100"
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

                if (!function_exists('getSpecDisplay')) {
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
                }
            @endphp

            <div id="mobile-comparison-specs" class="bg-[#efebe9] py-1">
                @foreach($categories as $category)
                    @if($category->fields->count() > 0)
                        <div class="mb-4">
                            {{-- Category Header --}}
                            <div class="bg-[#f0f0f0] px-3 py-1 border-t border-b border-gray-200">
                                <h3 class="text-[#F9A13D] font-bold uppercase text-sm leading-7">
                                    {{ $category->name }}
                                </h3>
                            </div>

                            {{-- Specs Table --}}
                            <table class="w-full border-collapse table-fixed bg-white">
                                <tbody>
                                    @foreach($category->fields as $field)
                                        <tr class="border-b border-gray-100 last:border-0">
                                            {{-- Field Label --}}
                                            <td class="w-[110px] p-2 text-[13px] font-bold text-[#444] align-top">
                                                {{ $field->label }}
                                            </td>

                                            {{-- Device 1 Value --}}
                                            @if($device1)
                                                <td class="p-2 text-[13px] text-gray-600 align-top border-l border-gray-100">
                                                    {!! getSpecDisplay($d1Specs, $field->id) !!}
                                                </td>
                                            @endif

                                            {{-- Device 2 Value --}}
                                            @if($device2)
                                                <td class="p-2 text-[13px] text-gray-600 align-top border-l border-gray-100">
                                                    {!! getSpecDisplay($d2Specs, $field->id) !!}
                                                </td>
                                            @endif

                                            {{-- Device 3 Value --}}
                                            @if($device3)
                                                <td class="p-2 text-[13px] text-gray-600 align-top border-l border-gray-100">
                                                    {!! getSpecDisplay($d3Specs, $field->id) !!}
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initComparisonSearch();

            // Close search results on click outside
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.relative') || (!e.target.closest('.search-device') && !e.target.closest('.search-results'))) {
                    document.querySelectorAll('.search-results').forEach(r => r.classList.add('hidden'));
                }
            });
        });

        function initComparisonSearch() {
            const searchInputs = document.querySelectorAll('.search-device');

            searchInputs.forEach((input, idx) => {
                // Avoid double-binding if we re-run init
                if (input.dataset.bound) return;
                input.dataset.bound = "true";

                let debounceTimer;
                const index = input.dataset.index;
                const resultsContainer = input.closest('.relative').querySelector('.search-results');

                input.addEventListener('input', function () {
                    clearTimeout(debounceTimer);
                    const query = this.value.trim();

                    if (query.length < 1) {
                        resultsContainer.classList.add('hidden');
                        return;
                    }

                    debounceTimer = setTimeout(async () => {
                        resultsContainer.innerHTML = '<div class="p-4 text-sm text-gray-500 flex items-center gap-2"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
                        resultsContainer.classList.remove('hidden');

                        try {
                            const response = await fetch(`{{ route('device.search') }}?q=${encodeURIComponent(query)}`);
                            if (!response.ok) throw new Error(`Network response was not ok: ${response.status}`);

                            const devices = await response.json();

                            if (devices.length === 0) {
                                resultsContainer.innerHTML = '<div class="p-4 text-sm text-gray-500 italic text-center">No devices found matching "' + query + '"</div>';
                                return;
                            }

                            resultsContainer.innerHTML = '';
                            devices.forEach(device => {
                                const div = document.createElement('div');
                                div.className = 'p-3 hover:bg-orange-50 cursor-pointer flex items-center gap-3 border-b border-gray-50 last:border-0 transition-colors group';
                                div.innerHTML = `
                                        <div class="w-10 h-12 flex-shrink-0 bg-gray-50 rounded p-1 flex items-center justify-center">
                                            <img src="${device.thumbnail_url}" class="max-h-full max-w-full object-contain" alt="${device.name}">
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm font-semibold group-hover:text-[#F9A13D]">${device.name}</div>
                                            <div class="text-xs text-gray-400 capitalize">${device.slug.split('-')[0]}</div>
                                        </div>
                                    `;

                                div.onclick = async () => {
                                    // Construct the new URL params based on current state + new selection
                                    // We need to know the CURRENT slugs in the slots.
                                    // We can infer them from the DOM or simply use the server-rendered values if possible,
                                    // but purely client-side logic is safer for the AJAX flow.

                                    // Let's get current slugs from the input logic? 
                                    // The inputs are placeholders if empty.
                                    // Better approach: Parse the current URL 'devices' param.
                                    // Start with state from URL
                                    const urlParams = new URLSearchParams(window.location.search);
                                    let devicesParam = urlParams.get('devices');
                                    let currentDevices = [];

                                    if (devicesParam) {
                                        currentDevices = devicesParam.split(',');
                                    } else {
                                        // Fallback to Blade-injected initial state if URL param is missing
                                        currentDevices = [
                                            @if($device1) '{{ $device1->slug }}' @else '' @endif,
                                            @if($device2) '{{ $device2->slug }}' @else '' @endif,
                                            @if($device3) '{{ $device3->slug }}' @else '' @endif
                                        ];
                                    }

                                    // Ensure we have 3 slots
                                    while (currentDevices.length < 3) currentDevices.push('');

                                    // Update the specific slot (0-indexed)
                                    const slotIndex = parseInt(index) - 1;
                                    currentDevices[slotIndex] = device.slug;

                                    // Reconstruct clean string
                                    // We MUST keep empty slots if they are in the middle (e.g. A,,B)
                                    // But we can trim trailing commas if we want, or just keep it simple.
                                    // The backend now handles "A,,B" correctly using array_filter + key matching.

                                    const newDevicesString = currentDevices.join(',');
                                    const baseUrl = "{{ route('device-comparison') }}";
                                    const newUrl = `${baseUrl}?devices=${newDevicesString}`;

                                    // Update UI with loading state
                                    resultsContainer.innerHTML = '<div class="p-4 text-sm text-gray-500"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';

                                    try {
                                        // Fetch new HTML
                                        const pageRes = await fetch(newUrl);
                                        const html = await pageRes.text();

                                        // Parse HTML
                                        const parser = new DOMParser();
                                        const doc = parser.parseFromString(html, 'text/html');

                                        // Update Desktop Containers
                                        const newDesktopGrid = doc.getElementById('desktop-comparison-grid');
                                        const newDesktopSpecs = doc.getElementById('desktop-comparison-specs');

                                        if (newDesktopGrid) document.getElementById('desktop-comparison-grid').innerHTML = newDesktopGrid.innerHTML;
                                        if (newDesktopSpecs) document.getElementById('desktop-comparison-specs').innerHTML = newDesktopSpecs.innerHTML;

                                        // Update Mobile Containers
                                        const newMobileGrid = doc.getElementById('mobile-comparison-grid');
                                        const newMobileSpecs = doc.getElementById('mobile-comparison-specs');

                                        if (newMobileGrid) document.getElementById('mobile-comparison-grid').innerHTML = newMobileGrid.innerHTML;
                                        if (newMobileSpecs) document.getElementById('mobile-comparison-specs').innerHTML = newMobileSpecs.innerHTML;

                                        // Update URL
                                        window.history.pushState({ path: newUrl }, '', newUrl);

                                        // Re-init listeners for the new inputs
                                        initComparisonSearch();

                                    } catch (err) {
                                        console.error('Comparison update failed:', err);
                                        resultsContainer.innerHTML = '<div class="p-4 text-sm text-red-500">Failed to load device.</div>';
                                    }
                                };
                                resultsContainer.appendChild(div);
                            });
                        } catch (error) {
                            console.error('Search error:', error);
                            resultsContainer.innerHTML = '<div class="p-4 text-sm text-red-500 italic text-center">Error: ' + error.message + '</div>';
                        }
                    }, 300);
                });

                // Open dropdown on focus
                input.addEventListener('focus', function () {
                    if (this.value.trim().length > 0) {
                        resultsContainer.classList.remove('hidden');
                    }
                });
            });
        }
    </script>
@endpush