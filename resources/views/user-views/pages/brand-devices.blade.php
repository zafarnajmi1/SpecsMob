@extends('layouts.app')

@section('title', $brand->name . ' Devices')

@section('sidebar')
    @include('partials.aside')
@endsection

@section('brand_devices_content')
<section class="lg:hidden">
    {{-- Brand Header Image --}}
    <div class="relative w-full h-[316px] bg-cover bg-center"
        style='background-image: url("{{ $brand->cover_img ? asset('storage/' . $brand->cover_img) : asset('images/default-brand-bg.jpg') }}");'>
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="absolute bottom-0 left-0 w-full p-4">
            <h1 class="text-white text-2xl font-bold drop-shadow-lg">{{ $brand->name }} phones</h1>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="bg-white border-b border-gray-200 p-3 flex gap-2 flex-wrap">
        <button id="mobile-compare-btn" class="px-4 py-2 bg-[#b00020] text-white text-sm font-bold rounded hover:bg-[#F9A13D] transition">
            COMPARE <span id="mobile-compare-count" class="ml-1 hidden">0</span>
        </button>
        <a href="{{ route('news', ['tag' => $brand->name]) }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-bold rounded hover:bg-gray-300 transition">
            {{ strtoupper($brand->name) }} NEWS
        </a>
        <a href="{{ route('phone-finder') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-bold rounded hover:bg-gray-300 transition">
            ADVANCED FILTER
        </a>
    </div>

    {{-- Sorting Tabs --}}
    <div class="bg-white border-b border-gray-300">
        <div class="flex">
            <a href="?sort=release" 
               class="flex-1 text-center py-3 text-sm font-bold {{ request()->get('sort') == 'release' || !request()->has('sort') ? 'text-[#b00020] border-b-2 border-[#b00020]' : 'text-gray-600' }}">
                TIME OF RELEASE
            </a>
            <a href="?sort=popularity" 
               class="flex-1 text-center py-3 text-sm font-bold {{ request()->get('sort') == 'popularity' ? 'text-[#b00020] border-b-2 border-[#b00020]' : 'text-gray-600' }}">
                POPULARITY
            </a>
        </div>
    </div>

    {{-- Filter Chips (Horizontal Scroll) --}}
    <div class="bg-gray-50 border-b border-gray-200 p-3 overflow-x-auto">
        <div class="flex gap-2 whitespace-nowrap">
            <a href="?filter=headphone" class="px-3 py-1.5 bg-white border border-gray-300 rounded-full text-xs hover:bg-gray-100">
                3.5mm jack
            </a>
            <a href="?filter=esim" class="px-3 py-1.5 bg-white border border-gray-300 rounded-full text-xs hover:bg-gray-100">
                eSIM
            </a>
            <a href="?filter=wireless" class="px-3 py-1.5 bg-white border border-gray-300 rounded-full text-xs hover:bg-gray-100">
                Wireless charging
            </a>
            <a href="?filter=120hz" class="px-3 py-1.5 bg-white border border-gray-300 rounded-full text-xs hover:bg-gray-100">
                120Hz+ display
            </a>
            <a href="?type=tablet" class="px-3 py-1.5 bg-white border border-gray-300 rounded-full text-xs hover:bg-gray-100">
                Tablets
            </a>
            <a href="?type=watch" class="px-3 py-1.5 bg-white border border-gray-300 rounded-full text-xs hover:bg-gray-100">
                Watches
            </a>
        </div>
    </div>

    {{-- Devices Grid --}}
    <div class="bg-white p-3">
        @if($devices->count() > 0)
            <div class="flex flex-col gap-1">
                @foreach($devices as $device)
                    <a href="{{ route('device-detail', $device->slug) }}" 
                       class="bg-white border flex border-gray-200 rounded overflow-hidden hover:shadow-md transition">
                        {{-- Device Image --}}
                        <div class="h-32 flex items-center justify-center p-3 bg-gray-50">
                            <img src="{{ $device->thumbnail_url }}" 
                                 alt="{{ $device->name }}"
                                 class="max-h-full max-w-full object-contain"
                                 onerror="this.src='{{ asset('images/default-device.png') }}'">
                        </div>
                        
                        {{-- Device Name --}}
                        <div class="flex items-center p-2 text-center bg-white hover:bg-[#F9A13D] transition group">
                            <strong class="text-sm text-gray-800 group-hover:text-white">
                                {{ $device->name }}
                            </strong>
                        </div>
                    </a>
                @endforeach
            </div>

             {{-- Mobile Custom Pagination --}}
        @if ($devices->hasPages())
            <div class="flex items-center justify-center gap-2 py-6 px-2 bg-[#f6f6f6] border-t border-gray-200 mt-4 rounded-b">
                {{-- Scroll to Top --}}
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                    class="p-2 text-gray-400 hover:text-[#F9A13D] transition-colors">
                    <i class="fa-solid fa-angles-up"></i>
                </button>

                {{-- First Page --}}
                <a href="{{ $devices->url(1) }}"
                    class="p-2 text-gray-400 hover:text-[#F9A13D] transition-colors {{ $devices->onFirstPage() ? 'opacity-30 pointer-events-none' : '' }}">
                    <i class="fa-solid fa-backward-step"></i>
                </a>

                {{-- Previous Page --}}
                <a href="{{ $devices->previousPageUrl() }}"
                    class="flex items-center justify-center w-9 h-9 rounded-full border border-gray-300 text-gray-500 hover:border-[#F9A13D] hover:text-[#F9A13D] transition-all {{ $devices->onFirstPage() ? 'opacity-30 pointer-events-none' : '' }}">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>

                {{-- Current of Total --}}
                <div class="flex flex-col items-center px-2 min-w-[60px]">
                    <span class="font-bold text-base leading-tight">{{ $devices->currentPage() }}</span>
                    <span class="text-[9px] text-gray-400 uppercase tracking-tighter">of {{ $devices->lastPage() }}</span>
                </div>

                {{-- Next Page --}}
                <a href="{{ $devices->nextPageUrl() }}"
                    class="flex items-center justify-center w-9 h-9 rounded-full border border-gray-300 text-gray-500 hover:border-[#F9A13D] hover:text-[#F9A13D] transition-all {{ !$devices->hasMorePages() ? 'opacity-30 pointer-events-none' : '' }}">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>

                {{-- Last Page --}}
                <a href="{{ $devices->url($devices->lastPage()) }}"
                    class="p-2 text-gray-400 hover:text-[#F9A13D] transition-colors {{ !$devices->hasMorePages() ? 'opacity-30 pointer-events-none' : '' }}">
                    <i class="fa-solid fa-forward-step"></i>
                </a>

                {{-- Scroll to Bottom --}}
                <button onclick="window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'})"
                    class="p-2 text-gray-400 hover:text-[#F9A13D] transition-colors">
                    <i class="fa-solid fa-angles-down"></i>
                </button>
            </div>
        @endif
        @else
            <div class="text-center py-12">
                <i class="fas fa-mobile-alt text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-600">No devices found</p>
            </div>
        @endif
    </div>
</section>
@endsection

@section('content')
<section class="hidden lg:block">
    {{-- Brand Header --}}
    <div class="relative w-full h-72 md:h-[314px] bg-cover bg-center"
        style='background-image: url("{{ $brand->cover_img ? asset('storage/' . $brand->cover_img) : asset('images/default-brand-bg.jpg') }}");'>
        {{-- Top bar --}}
        <div
            class="absolute top-0 left-0 border-b-1 border-gray-400 shadow flex justify-between items-center w-full px-4 py-3 md:px-6 z-10">
        </div>

        {{-- Bottom Title & Details --}}
        <div class="absolute bottom-0 left-0 w-full z-10">

            {{-- TITLE --}}
            <h1 class="text-white text-3xl md:text-4xl font-bold px-4 md:px-6 drop-shadow-xl mb-4">
                {{ $brand->name }} phones
            </h1>

            {{-- Bottom info bar --}}
            <div
                class="flex flex-wrap justify-between items-center border-t-1 border-gray-400 shadow text-sm text-white px-4 md:px-6 h-[2.5rem] bg-[Rgba(0,0,0,0.2)] backdrop-blur-sm">
                <div class="flex gap-4 h-full">
                    <button id="compare-tab"
                        class="compare-btn relative cursor-pointer flex items-center gap-1 transition hover:bg-[#F9A13D] px-3">
                        <i class="fa-solid fa-code-compare"></i>
                        <span class="compare-text">COMPARE</span>
                        <span id="compare-count"
                            class="ml-1 bg-white text-[#F9A13D] font-bold text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">
                            0
                        </span>
                    </button>

                    <a href="{{ route('news', ['tag' => $brand->name]) }}"
                        class="flex items-center gap-1 hover:bg-[#F9A13D] transition-colors px-3 transition">
                        <i class="fa-solid fa-newspaper"></i> {{ strtoupper($brand->name) }} NEWS
                    </a>
                </div>
                <div class="flex gap-4 h-full">
                    <a href="?sort=popularity"
                        class="flex items-center gap-1 transition hover:bg-[#F9A13D] transition-colors px-3 {{ !request()->has('sort') || request()->get('sort') == 'popularity' ? 'bg-[#F9A13D]' : '' }}">
                        <i class="fa-solid fa-chart-line"></i> POPULARITY
                    </a>

                    <a href="?sort=release"
                        class="flex items-center gap-1 hover:bg-[#F9A13D] transition-colors px-3 transition {{ request()->get('sort') == 'release' ? 'bg-[#F9A13D]' : '' }}">
                        <i class="fa-solid fa-calendar-days"></i> TIME OF RELEASE
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters Bar --}}
    <div class="bg-gray-100 border-b border-gray-300 py-3 px-4 sticky top-[60px] z-20 shadow-sm">
        <div class="container mx-auto">
            <form action="{{ url()->current() }}" method="GET" id="filter-form"
                class="flex flex-wrap items-center justify-between gap-4">
                {{-- Keep sort parameter --}}
                @if(request()->has('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif

                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-gray-700 font-bold text-sm hidden md:block">Filters:</span>

                    {{-- Search Input --}}
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search model..."
                            class="pl-8 pr-3 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#F9A13D] w-full md:w-56 transition-all">
                    </div>

                    {{-- Type Select --}}
                    <select name="type" onchange="this.form.submit()"
                        class="border border-gray-300 rounded px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-[#F9A13D] cursor-pointer bg-white">
                        <option value="">All Types</option>
                        @foreach($deviceTypes as $type)
                            <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>{{ $type->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Status Select --}}
                    <select name="status" onchange="this.form.submit()"
                        class="border border-gray-300 rounded px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-[#F9A13D] cursor-pointer bg-white">
                        <option value="">All Status</option>
                        <option value="released" {{ request('status') == 'released' ? 'selected' : '' }}>Released</option>
                        <option value="announced" {{ request('status') == 'announced' ? 'selected' : '' }}>Coming Soon
                        </option>
                        <option value="rumored" {{ request('status') == 'rumored' ? 'selected' : '' }}>Rumored</option>
                        <option value="discontinued" {{ request('status') == 'discontinued' ? 'selected' : '' }}>Discontinued
                        </option>
                    </select>

                    {{-- Year Select --}}
                    <select name="year" onchange="this.form.submit()"
                        class="border border-gray-300 rounded px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-[#F9A13D] cursor-pointer bg-white">
                        <option value="">All Years</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>

                    {{-- Reset Link --}}
                    @if(request()->anyFilled(['q', 'type', 'status', 'year']))
                        <a href="{{ url()->current() }}{{ request()->has('sort') ? '?sort=' . request('sort') : '' }}"
                            class="text-xs text-[#F9A13D] font-bold hover:underline flex items-center gap-1 ml-2 px-2 py-1 rounded hover:bg-[#F9A13D] transition-all">
                            <i class="fas fa-undo"></i> RESET
                        </a>
                    @endif
                </div>

                <div
                    class="text-gray-500 text-xs font-semibold whitespace-nowrap bg-white px-3 py-1.5 rounded-full border border-gray-200 shadow-sm">
                    SHOWING <span class="text-[#F9A13D]">{{ $devices->total() }}</span> DEVICES
                </div>
            </form>
        </div>
    </div>

    {{-- Devices Grid --}}
    <div class="bg-white py-6">
        <div class="container mx-auto px-4">
            @if($devices->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($devices as $device)
                        <a href="{{ route('device-detail', $device->slug) }}"
                            class="relative bg-white overflow-hidden group cursor-pointer transition-all duration-200 text-center h-[180px] block">

                            {{-- Compare Checkbox --}}
                            <div class="absolute top-2 right-2 z-10 hidden">
                                <label class="flex items-center cursor-pointer compare-checkbox-label">
                                    <input type="checkbox" class="device-checkbox sr-only peer" data-device-id="{{ $device->id }}"
                                        data-device-name="{{ $device->name }}" data-device-slug="{{ $device->slug }}"
                                        data-device-img="{{ $device->thumbnail_url }}">
                                    <span class="w-6 h-6 bg-white border-[3px] border-[#F9A13D] transition-all duration-200"></span>
                                    <i
                                        class="fa-solid fa-check text-sm text-[#F9A13D] opacity-0 peer-checked:opacity-100 transition-opacity absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></i>
                                </label>
                            </div>

                            {{-- Device Image --}}
                            <div class="block h-[120px] flex items-center justify-center p-3">
                                <img src="{{ $device->thumbnail_url }}" alt="{{ $device->name }}"
                                    title="{{ $device->name }}. {{ $device->description ?? '' }}"
                                    class="max-h-full max-w-full object-contain"
                                    onerror="this.src='{{ asset('images/default-device.png') }}'">
                            </div>

                            {{-- Device Name --}}
                            <div
                                class="h-[45px] flex items-center justify-center px-2 transition-all duration-200 group-hover:bg-[#F9A13D]">
                                <strong class="text-[#777] font-bold text-[14px] group-hover:text-white">
                                    {{ $device->name }}
                                </strong>
                            </div>

                        </a>

                    @endforeach
                </div>

                {{-- No Results Message --}}
            @else
                <div class="text-center py-12">
                    <i class="fas fa-mobile-alt text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl text-gray-600 mb-2">No devices found</h3>
                    <p class="text-gray-500">This brand doesn't have any devices listed yet.</p>
                </div>
            @endif
        </div>
    </div>


    {{-- Pagination - Simple Numbers --}}
    @if($devices->hasPages())
        <div class="mt-8 flex justify-center items-center space-x-1">
            {{-- Previous Button --}}
            @if($devices->onFirstPage())
                <span class="px-3 py-2 border border-gray-300 rounded text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chevron-left text-xs"></i>
                </span>
            @else
                <a href="{{ $devices->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 rounded hover:bg-gray-100">
                    <i class="fas fa-chevron-left text-xs"></i>
                </a>
            @endif

            {{-- Page Numbers --}}
            @php
                $current = $devices->currentPage();
                $last = $devices->lastPage();
                $start = max(1, $current - 1);
                $end = min($last, $current + 1);
            @endphp

            @for($page = $start; $page <= $end; $page++)
                @if($page == $current)
                    <span class="px-3 py-2 border border-[#F9A13D] bg-[#F9A13D] text-white rounded text-sm font-bold">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $devices->url($page) }}" class="px-3 py-2 border border-gray-300 rounded text-sm hover:bg-gray-100">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            {{-- Next Button --}}
            @if($devices->hasMorePages())
                <a href="{{ $devices->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 rounded hover:bg-gray-100">
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            @else
                <span class="px-3 py-2 border border-gray-300 rounded text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chevron-right text-xs"></i>
                </span>
            @endif
        </div>
    @endif
</section>


    @push('scripts')
        <script>
            // Compare functionality
            let selectedDevices = [];
            const maxSelection = 3;
            let compareModeActive = false;

            // Load from localStorage on page load
            document.addEventListener('DOMContentLoaded', function () {
                const savedDevices = localStorage.getItem('compareDevices');
                if (savedDevices) {
                    selectedDevices = JSON.parse(savedDevices);
                    updateUI();
                }

                // Setup event listeners
                setupEventListeners();
            });

            function setupEventListeners() {
                // Compare tab click - Toggle compare mode
                document.getElementById('compare-tab').addEventListener('click', function (e) {
                    e.preventDefault();

                    if (!compareModeActive) {
                        // First click: Activate compare mode (show checkboxes)
                        activateCompareMode();
                    } else {
                        // Second click: Check if we can go to compare page
                        if (selectedDevices.length >= 1) {
                            // Go to compare page
                            goToComparePage();
                        } else {
                            // Show message if less than 1 devices selected
                            alert('Please select at least 1 device to compare.');
                        }
                    }
                });

                // Device checkbox changes
                document.querySelectorAll('.device-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        if (!compareModeActive) return;

                        const deviceId = parseInt(this.dataset.deviceId);
                        const deviceName = this.dataset.deviceName;
                        const deviceSlug = this.dataset.deviceSlug;
                        const deviceImg = this.dataset.deviceImg;

                        if (this.checked) {
                            // Add device if limit not reached
                            if (selectedDevices.length >= maxSelection) {
                                alert(`You can select up to ${maxSelection} devices for comparison.`);
                                this.checked = false;
                                return;
                            }

                            if (!selectedDevices.some(d => d.id === deviceId)) {
                                selectedDevices.push({
                                    id: deviceId,
                                    name: deviceName,
                                    slug: deviceSlug,
                                    img: deviceImg
                                });
                            }
                        } else {
                            // Remove device
                            selectedDevices = selectedDevices.filter(d => d.id !== deviceId);
                        }

                        // Save to localStorage and update UI
                        saveToLocalStorage();
                        updateUI();
                    });
                });
            }

            function activateCompareMode() {
                compareModeActive = true;

                // Show all checkboxes
                document.querySelectorAll('.absolute.top-2.right-2.z-10').forEach(checkboxContainer => {
                    checkboxContainer.classList.remove('hidden');
                });

                // Update compare tab appearance
                const compareTab = document.getElementById('compare-tab');
                const compareText = document.querySelector('.compare-text');
                compareTab.classList.add('bg-[#F9A13D]');
                compareText.textContent = 'GO TO COMPARE';

                // Show instruction message
                showInstructionMessage();
            }

            function deactivateCompareMode() {
                compareModeActive = false;

                // Hide all checkboxes
                document.querySelectorAll('.absolute.top-2.right-2.z-10').forEach(checkboxContainer => {
                    checkboxContainer.classList.add('hidden');
                });

                // Update compare tab appearance
                const compareTab = document.getElementById('compare-tab');
                const compareText = document.querySelector('.compare-text');
                compareTab.classList.remove('bg-[#F9A13D]');
                compareText.textContent = 'COMPARE';

                // Hide instruction message
                hideInstructionMessage();
            }

            function updateUI() {
                const count = selectedDevices.length;
                const compareCount = document.getElementById('compare-count');
                const compareTab = document.getElementById('compare-tab');
                const compareText = document.querySelector('.compare-text');

                // Update count badge
                compareCount.textContent = count;

                // Show/hide count badge
                if (count > 0) {
                    compareCount.classList.remove('hidden');
                } else {
                    compareCount.classList.add('hidden');
                }

                // Update compare tab text based on mode
                if (compareModeActive) {
                    compareText.textContent = 'GO TO COMPARE';
                    compareTab.classList.add('bg-[#F9A13D]');
                } else {
                    compareText.textContent = 'COMPARE';
                    compareTab.classList.remove('bg-[#F9A13D]');
                }

                // Update checkboxes
                document.querySelectorAll('.device-checkbox').forEach(checkbox => {
                    const deviceId = parseInt(checkbox.dataset.deviceId);
                    checkbox.checked = selectedDevices.some(d => d.id === deviceId);
                });
            }

            function goToComparePage() {
                if (selectedDevices.length >= 1) {
                    const slugs = selectedDevices.map(device => device.slug);
                    window.location.href = `{{ route('device-comparison') }}?devices=${slugs.join(',')}`;
                } else {
                    alert('Please select at least 1 device to compare.');
                }
            }

            function saveToLocalStorage() {
                localStorage.setItem('compareDevices', JSON.stringify(selectedDevices));
            }

            // Show instruction message when compare mode is activated
            function showInstructionMessage() {
                // Remove existing message if any
                const existingMessage = document.querySelector('.compare-instruction');
                if (existingMessage) existingMessage.remove();

                // Create instruction message
                const message = document.createElement('div');
                message.className = 'compare-instruction fixed top-20 left-1/2 transform -translate-x-1/2 bg-[#F9A13D] text-white px-4 py-2 rounded shadow-lg z-40';
                message.innerHTML = `
                                                                                                            <div class="flex items-center gap-2">
                                                                                                                <i class="fas fa-info-circle"></i>
                                                                                                                <span>Select up to 3 devices. Click "GO TO COMPARE" again when ready.</span>
                                                                                                                <button onclick="hideInstructionMessage()" class="ml-4 text-white hover:text-gray-200">
                                                                                                                    <i class="fas fa-times"></i>
                                                                                                                </button>
                                                                                                            </div>
                                                                                                        `;

                document.body.appendChild(message);

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    if (message.parentNode) {
                        message.style.opacity = '0';
                        message.style.transition = 'opacity 0.5s';
                        setTimeout(() => {
                            if (message.parentNode) message.remove();
                        }, 500);
                    }
                }, 5000);
            }

            function hideInstructionMessage() {
                const message = document.querySelector('.compare-instruction');
                if (message) message.remove();
            }

            // Handle device selection from anywhere on the card (only in compare mode)
            document.querySelectorAll('a.relative.bg-white').forEach(card => {
                card.addEventListener('click', function (e) {
                    // Only work in compare mode
                    if (!compareModeActive) return;

                    // Prevent navigation
                    e.preventDefault();

                    const checkbox = this.querySelector('.device-checkbox');
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            });

            // Also allow clicking on the checkbox label
            document.querySelectorAll('.compare-checkbox-label').forEach(label => {
                label.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            });

            // Escape key to exit compare mode
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && compareModeActive) {
                    deactivateCompareMode();
                }
            });

            // Click outside to exit compare mode (optional)
            document.addEventListener('click', function (e) {
                if (compareModeActive &&
                    !e.target.closest('#compare-tab') &&
                    !e.target.closest('.device-checkbox') &&
                    !e.target.closest('.compare-checkbox-label') &&
                    !e.target.closest('.compare-instruction')) {
                }
            });
        </script>
    @endpush

@endsection