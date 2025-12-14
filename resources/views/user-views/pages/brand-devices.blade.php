@extends('layouts.app')

@section('title', $brand->name . ' Devices')

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')

    <!-- Brand Header -->
    <div class="relative w-full h-72 md:h-[314px] bg-cover bg-center"
        style='background-image: url("{{ $brand->cover_img ? asset('storage/' . $brand->cover_img) : asset('images/default-brand-bg.jpg') }}");'>
        <!-- Top bar -->
        <div
            class="absolute top-0 left-0 border-b-1 border-gray-400 shadow flex justify-between items-center w-full px-4 py-3 md:px-6 z-10">
        </div>

        <!-- Bottom Title & Details -->
        <div class="absolute bottom-0 left-0 w-full z-10">

            <!-- TITLE -->
            <h1 class="text-white text-3xl md:text-4xl font-bold px-4 md:px-6 drop-shadow-xl mb-4">
                {{ $brand->name }} phones
            </h1>

            <!-- Bottom info bar -->
            <div
                class="flex flex-wrap justify-between items-center border-t-1 border-gray-400 shadow text-sm text-white px-4 md:px-6 h-[2.5rem] bg-[Rgba(0,0,0,0.2)] backdrop-blur-sm">
                <div class="flex gap-4 h-full">
                    <button id="compare-tab"
                        class="compare-btn relative cursor-pointer flex items-center gap-1 transition hover:bg-[#d50000] px-3">
                        <i class="fa-solid fa-code-compare"></i>
                        <span class="compare-text">COMPARE</span>
                        <span id="compare-count"
                            class="ml-1 bg-white text-[#d50000] font-bold text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">
                            0
                        </span>
                    </button>

                    <a href="#" class="flex items-center gap-1 hover:bg-[#d50000] transition-colors px-3 transition">
                        <i class="fa-solid fa-newspaper"></i> {{ strtoupper($brand->name) }} NEWS
                    </a>
                </div>
                <div class="flex gap-4 h-full">
                    <a href="?sort=release"
                        class="flex items-center gap-1 transition hover:bg-[#d50000] transition-colors px-3 {{ !request()->has('sort') || request()->get('sort') == 'release' ? 'bg-[#d50000]' : '' }}">
                        <i class="fa-solid fa-calendar-days"></i> TIME OF RELEASE
                    </a>

                    <a href="?sort=popularity"
                        class="flex items-center gap-1 hover:bg-[#d50000] transition-colors px-3 transition {{ request()->get('sort') == 'popularity' ? 'bg-[#d50000]' : '' }}">
                        <i class="fa-solid fa-chart-line"></i> POPULARITY
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-gray-100 border-b border-gray-300 py-3 px-4">
        <div class="container mx-auto flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4">
                <span class="text-gray-700 font-medium text-sm">Filters:</span>

                <select
                    class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">All Types</option>
                    <option value="phone">Phones</option>
                    <option value="tablet">Tablets</option>
                    <option value="watch">Watches</option>
                </select>

                <select
                    class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">All Status</option>
                    <option value="released">Released</option>
                    <option value="announced">Announced</option>
                    <option value="rumored">Rumored</option>
                </select>

                <select
                    class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">All Years</option>
                    @for($year = date('Y'); $year >= 2015; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <div class="text-gray-600 text-sm">
                <span class="font-semibold">{{ $devices->count() }}</span> devices
            </div>
        </div>
    </div>

    <!-- Devices Grid -->
    <div class="bg-white py-6">
        <div class="container mx-auto px-4">
            @if($devices->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($devices as $device)
                       <a href="{{ route('device-detail', $device->slug) }}" 
   class="relative bg-white overflow-hidden group cursor-pointer transition-all duration-200 text-center h-[180px] block">
    
    <!-- Compare Checkbox -->
    <div class="absolute top-2 right-2 z-10 hidden">
        <label class="flex items-center cursor-pointer compare-checkbox-label">
            <input type="checkbox" class="device-checkbox sr-only peer"
                data-device-id="{{ $device->id }}"
                data-device-name="{{ $device->name }}"
                data-device-slug="{{ $device->slug }}"
                data-device-img="{{ $device->thumbnail_url }}">
            <span
                class="w-6 h-6 bg-white flex items-center justify-center peer-checked:bg-[#d50000] peer-checked:border-[#d50000] transition-all duration-200">
                <i class="fas fa-check text-xs text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
            </span>
        </label>
    </div>

    <!-- Device Image -->
    <div class="block h-[120px] flex items-center justify-center p-3">
        <img src="{{ $device->thumbnail_url }}" alt="{{ $device->name }}"
            title="{{ $device->name }}. {{ $device->description ?? '' }}"
            class="max-h-full max-w-full object-contain"
            onerror="this.src='{{ asset('images/default-device.png') }}'">
    </div>

    <!-- Device Name -->
    <div class="h-[45px] flex items-center justify-center px-2 transition-all duration-200 group-hover:bg-[#d50000]">
        <strong class="text-[#777] font-bold text-[14px] group-hover:text-white">
            {{ $device->name }}
        </strong>
    </div>

</a>

                    @endforeach
                </div>

                <!-- No Results Message -->
            @else
                <div class="text-center py-12">
                    <i class="fas fa-mobile-alt text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl text-gray-600 mb-2">No devices found</h3>
                    <p class="text-gray-500">This brand doesn't have any devices listed yet.</p>
                </div>
            @endif
        </div>
    </div>


    <!-- Pagination - Simple Numbers -->
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
                    <span class="px-3 py-2 border border-[#d50000] bg-[#d50000] text-white rounded text-sm font-bold">
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
                        if (selectedDevices.length >= 2) {
                            // Go to compare page
                            goToComparePage();
                        } else {
                            // Show message if less than 2 devices selected
                            alert('Please select at least 2 devices to compare.');
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
                compareTab.classList.add('bg-[#d50000]');
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
                compareTab.classList.remove('bg-[#d50000]');
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

                // Update compare tab text based on mode and count
                if (compareModeActive) {
                    compareText.textContent = 'GO TO COMPARE';
                    compareTab.classList.add('bg-[#d50000]');

                    // If devices selected, update text
                    if (count > 0) {
                        compareText.textContent = `GO TO COMPARE (${count})`;
                    }
                } else {
                    compareText.textContent = 'COMPARE';
                    compareTab.classList.remove('bg-[#d50000]');

                    // Still show count if we have selected devices
                    if (count > 0) {
                        compareText.textContent = `COMPARE (${count})`;
                    }
                }

                // Update checkboxes
                document.querySelectorAll('.device-checkbox').forEach(checkbox => {
                    const deviceId = parseInt(checkbox.dataset.deviceId);
                    checkbox.checked = selectedDevices.some(d => d.id === deviceId);
                });
            }

            function goToComparePage() {
                if (selectedDevices.length >= 2) {
                    const slugs = selectedDevices.map(device => device.slug);
                    window.location.href = `/compare?devices=${slugs.join(',')}`;
                } else {
                    alert('Please select at least 2 devices to compare.');
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
                message.className = 'compare-instruction fixed top-20 left-1/2 transform -translate-x-1/2 bg-[#d50000] text-white px-4 py-2 rounded shadow-lg z-40';
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
            document.querySelectorAll('.relative.bg-white').forEach(card => {
                card.addEventListener('click', function (e) {
                    // Only work in compare mode
                    if (!compareModeActive) return;

                    // Don't trigger if clicking on link
                    if (e.target.closest('a')) {
                        return;
                    }

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