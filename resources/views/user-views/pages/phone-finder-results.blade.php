@extends('layouts.app')
@section('title', 'Phone Finder')



@section('content')
    <style>
        /* Custom styles for sliders and dropdowns */
        .slider-container {
            position: relative;
            height: 40px;
            display: flex;
            align-items: center;
        }

        .slider-track {
            height: 4px;
            background-color: #e5e7eb;
            border-radius: 2px;
            position: relative;
            width: 100%;
        }

        .slider-range {
            position: absolute;
            height: 4px;
            background-color: #F9A13D;
            border-radius: 2px;
        }

        .slider-handle {
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: white;
            border: 2px solid #F9A13D;
            border-radius: 50%;
            top: 50%;
            transform: translateX(-50%) translateY(-50%);
            cursor: pointer;
            z-index: 2;
            transition: z-index 0.1s ease;
        }

        .slider-handle:active {
            z-index: 5;
        }

        .custom-select {
            position: relative;
            width: 100%;
        }

        .custom-select select {
            display: none;
        }

        .select-selected {
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .select-items {
            position: absolute;
            background-color: white;
            border: 1px solid #d1d5db;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            z-index: 99;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            display: none;
        }

        .select-items div {
            padding: 0.5rem 0.75rem;
            cursor: pointer;
        }

        .select-items div:hover {
            background-color: #f3f4f6;
        }

        .select-hide {
            display: none;
        }

        .tab-active {
            border-bottom: 2px solid #F9A13D;
            color: #F9A13D;
        }
    </style>

@endsection

@push('scripts')
    <style>
        .select-items {
            z-index: 50 !important;
            display: none;
        }

        .select-items.block {
            display: block !important;
        }

        .slider-container+div {
            margin-top: 1.5rem !important;
        }
    </style>
    <script>
            document.addEventListener('DOMContentLoaded',         function () {
                // Force text overlap fix immediately
                const textContainers = document.querySelectorAll('.slider-container + div');
                textContainers.forEach(div => {
                    div.classList.remove('mt-2');
                    div.style.marginTop = '2.5rem';
                });

                // --- BRAND SELECT FIX ---
                const brandSelect = document.getElementById('brand-select');
                if (brandSelect) {
                    const selected = brandSelect.querySelector('.select-selected');
                    const items = brandSelect.querySelector('.select-items');

                    // Clone selected to strip old event listeners if any
                    const newSelected = selected.cloneNode(true);
                    selected.parentNode.replaceChild(newSelected, selected);

                    newSelected.addEventListener('click', (e) => {
                        e.stopPropagation();
                        e.preventDefault();

                        // Toggle visibility
                        // Check if strictly hidden by class or style
                        const isHidden = items.classList.contains('hidden') || items.style.display === 'none';

                        if (isHidden) {
                            items.classList.remove('hidden');
                            items.style.display = 'block';
                        } else {
                            items.classList.add('hidden');
                            items.style.display = 'none';
                        }
                    });

                    // Prevent closing when clicking inside items (checkboxes)
                    items.addEventListener('click', (e) => e.stopPropagation());

                    // Start hidden
                    items.classList.add('hidden');
                    items.style.display = 'none';
                }

                // --- GENERIC CUSTOM SELECTS ---
                const customSelects = document.querySelectorAll('.custom-select');
                customSelects.forEach(select => {
                    if (select.id === 'brand-select') return;

                    // Check if it's the specific Status options select which has its own logic
                    // if (select.querySelector('.status-options')) return; 
                    // Actually, let's override generic logic for all to ensure they work.

                    const selected = select.querySelector('.select-selected');
                    const items = select.querySelector('.select-items');
                    const displaySpan = selected ? selected.querySelector('span') : null;
                    const inputId = selected ? selected.dataset.target : null;
                    const hiddenInput = inputId ? document.getElementById(inputId) : null;

                    if (!selected || !items) return;

                    // Clone to remove old listeners
                    const newSelected = selected.cloneNode(true);
                    selected.parentNode.replaceChild(newSelected, selected);

                    newSelected.addEventListener('click', (e) => {
                        e.stopPropagation();
                        // Close all others
                        customSelects.forEach(s => {
                            if (s !== select) {
                                const i = s.querySelector('.select-items');
                                if (i) {
                                    i.classList.add('hidden');
                                    i.style.display = 'none';
                                }
                            }
                        });

                        // Toggle current
                        const isHidden = items.classList.contains('hidden') || items.style.display === 'none';
                        if (isHidden) {
                            items.classList.remove('hidden');
                            items.style.display = 'block';
                        } else {
                            items.classList.add('hidden');
                            items.style.display = 'none';
                        }
                    });

                    // Re-bind click options
                    items.querySelectorAll('div').forEach(div => {
                        // Clone div? No, just add new listener. 
                        // Duplicate listeners are harmless here as they just set value and close.
                        div.addEventListener('click', (e) => {
                             const val = div.dataset.value || div.textContent.trim();
                             const text = div.textContent.trim();

                             if (hiddenInput) hiddenInput.value = val;
                             // Special case for brand display? No, brand is skipped.
                             if (displaySpan) displaySpan.textContent = text;

                             items.classList.add('hidden');
                             items.style.display = 'none';
                        });
                    });
                });

                // Global Close
                document.addEventListener('click', () => {
                    customSelects.forEach(s => {
                        // Don't auto-close brand-select items if we are clicking inside it? 
                        // brand-select items click propagation is stopped above.
                        // But if we click outside, we should close it?
                        // Yes, close all.
                        const i = s.querySelector('.select-items');
                        if (i) {
                            i.classList.add('hidden');
                            i.style.display = 'none';
                        }
                    });
                });


                // --- GENERIC SLIDERS ---
                const sliders = document.querySelectorAll('.slider-container');
                sliders.forEach(container => {
                    initGenericSlider(container);
                });

                function initGenericSlider(container) {
                    const track = container.querySelector('.slider-track');
                    let handles = Array.from(container.querySelectorAll('.slider-handle'));
                    const range = container.querySelector('.slider-range');
                    const textContainer = container.nextElementSibling;

                    if (!track || !handles.length || !textContainer) return;

                    // Identify Inputs or Create Them
                    let minInputId = null;
                    let maxInputId = null;

                    if (container.id === 'ram-slider') minInputId = 'ram-min';
                    else if (container.id === 'storage-slider') minInputId = 'storage-min';
                    // Year falls through to dynamic creation
                    else {
                        // Create dynamic inputs
                        const label = container.parentElement.querySelector('label');
                        const nameBase = label ? label.textContent.trim().toLowerCase().replace(/[^a-z0-9]/g, '_') : 'slider_' + Math.random().toString(36).substr(2, 5);

                        // Create Min Input
                        const minInp = document.createElement('input');
                        minInp.type = 'hidden';
                        minInp.name = nameBase + '_min';
                        minInp.id = nameBase + '_min';
                        container.appendChild(minInp);
                        minInputId = minInp.id;

                        // Create Max Input if 2 handles
                        if (handles.length === 2) {
                            const maxInp = document.createElement('input');
                            maxInp.type = 'hidden';
                            maxInp.name = nameBase + '_max';
                            maxInp.id = nameBase + '_max';
                            container.appendChild(maxInp);
                            maxInputId = maxInp.id;
                        }
                    }

                    const minValSpan = textContainer.children[0];
                    const maxValSpan = textContainer.children[1];
                    const minLimit = parseInt(minValSpan.dataset.min || minValSpan.textContent.replace(/[^0-9]/g, '')) || 0;
                    const maxLimit = parseInt(maxValSpan.dataset.max || maxValSpan.textContent.replace(/[^0-9]/g, '')) || 100;

                    // Initialize positions from inputs if they have values (persistence)
                    if (minInputId) {
                         const inp = document.getElementById(minInputId);
                         if (inp && inp.value !== '') {
                             const val = parseFloat(inp.value);
                             if (!isNaN(val) && (maxLimit - minLimit) !== 0) {
                                 const p = ((val - minLimit) / (maxLimit - minLimit)) * 100;
                                 handles[0].style.left = Math.max(0, Math.min(100, p)) + '%';
                             }
                         }
                    }
                    if (handles.length === 2 && maxInputId) {
                         const inp = document.getElementById(maxInputId);
                         if (inp && inp.value !== '') {
                             const val = parseFloat(inp.value);
                             if (!isNaN(val) && (maxLimit - minLimit) !== 0) {
                                 const p = ((val - minLimit) / (maxLimit - minLimit)) * 100;
                                 handles[1].style.left = Math.max(0, Math.min(100, p)) + '%';
                             }
                         }
                    }

                    let isDragging = null;

                    const update = () => {
                        const minP = parseFloat(handles[0].style.left) || 0;
                        if (handles.length === 2) {
                            const maxP = parseFloat(handles[1].style.left) || 100;
                            range.style.left = minP + '%';
                            range.style.width = (maxP - minP) + '%';

                            const currMin = Math.round(minLimit + (minP/100)*(maxLimit-minLimit));
                            const currMax = Math.round(minLimit + (maxP/100)*(maxLimit-minLimit));

                            updateText(minValSpan, currMin);
                            updateText(maxValSpan, currMax);

                            if(minInputId && document.getElementById(minInputId)) document.getElementById(minInputId).value = currMin;
                            if(maxInputId && document.getElementById(maxInputId)) document.getElementById(maxInputId).value = currMax;

                        } else {
                            range.style.left = '0%';
                            range.style.width = minP + '%';
                            const currMin = Math.round(minLimit + (minP/100)*(maxLimit-minLimit));
                            updateText(minValSpan, currMin);
                            if(minInputId && document.getElementById(minInputId)) document.getElementById(minInputId).value = currMin;
                        }
                    };

                    function updateText(el, val) {
                         const txt = el.textContent;
                         const unit = txt.replace(/[0-9]/g, '').trim(); 
                         el.textContent = val + (unit || '');
                    }

                    handles.forEach((h, i) => {
                         // Clone handle to remove old listeners (Reset)
                         const newH = h.cloneNode(true);
                         h.parentNode.replaceChild(newH, h);
                         handles[i] = newH; // Update reference

                         newH.addEventListener('mousedown', (e) => {
                             isDragging = i;
                             e.preventDefault();
                         });
                    });

                    document.addEventListener('mousemove', (e) => {
                        if (isDragging === null) return;
                        e.preventDefault();
                        const rect = track.getBoundingClientRect();
                        let p = ((e.clientX - rect.left) / rect.width) * 100;
                        p = Math.max(0, Math.min(100, p));

                        if (handles.length === 2) {
                           if (isDragging === 0 && p >= parseFloat(handles[1].style.left)) return;
                           if (isDragging === 1 && p <= parseFloat(handles[0].style.left)) return;
                        }

                        handles[isDragging].style.left = p + '%';
                        update();
                    });

                    document.addEventListener('mouseup', () => isDragging = null);
                }
            });
        </script>
@endpush
