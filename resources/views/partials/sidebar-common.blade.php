@php
    $brands = App\Models\Brand::active()->orderBy('name')->get();
    $limitedBrands = $brands->take(36); // 4 columns × 9 rows (like GSM)
    $brandColumns = $limitedBrands->chunk(9);
@endphp

<div class="h-[350px] bg-[#f1f2f2] border border-[#cccccc] mb-5 flex flex-col overflow-hidden">

    {{-- PHONE FINDER --}}
    <div class="bg-[#b0b0b0] px-4 py-3 border-b border-[#999999] flex items-center justify-center gap-2
                font-bold uppercase text-[12px] text-white hover:bg-[#d50000] cursor-pointer">
        <i class="fas fa-search"></i>
        <span>Phone Finder</span>
    </div>

    {{-- 4 COLUMNS ONLY --}}
    <div class="flex justify-center bg-[#e8e8e8] px-3 py-3 flex-1">
        @foreach ($brandColumns as $column)
            <div class="flex flex-col gap-0">
                @foreach ($column as $brand)
                    <a href="{{ route('brand.devices', ['slug' => $brand->slug]) }}"
                       class="hover:bg-[#d50000] text-[#575757] letter-spacing-[.35px] text-[11px] font-[700] hover:text-white text-center px-2 py-1 block">
                        {{ strtoupper($brand->name) }}
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>

    {{-- ALL BRANDS + RUMOR MILL --}}
    <div class="grid grid-cols-2 gap-[1px] bg-[#b0b0b0]">
        <a href="{{ route('brands.all') }}"
           class="text-white text-[11px] font-bold uppercase flex items-center justify-center gap-2 py-3 px-2 hover:bg-[#d50000]">
            <i class="fas fa-list"></i>
            <span>All Brands</span>
        </a>

        <a href="#"
           class="text-white text-[11px] font-bold uppercase flex items-center justify-center gap-2 py-3 px-2 hover:bg-[#d50000]">
            <i class="fas fa-volume-up"></i>
            <span>Rumor Mill</span>
        </a>
    </div>

</div>
