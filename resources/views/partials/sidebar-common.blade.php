@php
    $brands = App\Models\Brand::active()->orderBy('name')->get();
    $limitedBrands = $brands->take(36); // 4 columns × 9 rows (like GSM)
    $brandColumns = $limitedBrands->chunk(9);
@endphp

<div class="h-[350px] bg-[#f1f2f2] border border-[#cccccc] mb-5 flex flex-col overflow-hidden">

    {{-- PHONE FINDER --}}
    <div class="bg-[#F9A13D] px-4 py-3 flex items-center justify-center gap-2
                font-bold uppercase text-[13px] text-white">
        <i class="fas fa-search"></i>
        <span>Phone Finder</span>
    </div>

    {{-- 4 COLUMNS ONLY --}}
    <div class="flex justify-center bg-[#e8e8e8] px-3 py-3 flex-1">
        @foreach ($brandColumns as $column)
            <div class="flex flex-col gap-0">
                @foreach ($column as $brand)
                    <a href="{{ route('brand.devices', ['slug' => $brand->slug]) }}"
                        class="hover:bg-[#F9A13D] text-black letter-spacing-[.35px] text-[11px] font-normal hover:text-white text-center px-2 py-1 block">
                        {{ strtoupper($brand->name) }}
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>

    {{-- ALL BRANDS + RUMOR MILL --}}
    <div class="grid grid-cols-2 gap-[1px] bg-[#F9A13D]">
        <a href="{{ route('brands.all') }}"
            class="text-white text-[11px] font-bold border-r border-white uppercase flex items-center justify-center gap-2 py-3 px-2 hover:bg-white hover:text-[#F9A13D] hover:border hover:border-[#F9A13D] duration-100">
            <i class="fas fa-list"></i>
            <span>All Brands</span>
        </a>

        <a href="#"
            class="text-white text-[11px] font-bold uppercase flex items-center justify-center gap-2 py-3 px-2 hover:bg-white hover:text-[#F9A13D] hover:border hover:border-[#F9A13D] duration-100">
            <i class="fas fa-volume-up"></i>
            <span>Rumor Mill</span>
        </a>
    </div>

</div>