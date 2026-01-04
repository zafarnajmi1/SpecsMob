{{-- Sidebar Advertisement Slot --}}
<!-- <div class="w-full bg-white mt-6">

    {{-- Outer wrapper to keep consistent with other sidebar boxes --}}
    <div class="border border-[#d5d5d5] bg-[#f5f5f5] relative flex items-center justify-center py-3">

        {{-- Small "ADVERTISEMENT" label --}}
        <span class="absolute top-1 left-2 text-[9px] tracking-[0.18em] text-[#9e9e9e] uppercase">
            Advertisement
        </span>

        {{-- Ad container (300x250 on desktop, responsive on small screens) --}}
        <div class="w-full flex justify-center">
            <div
                class="bg-[#e0e0e0] w-[300px] h-[250px] flex items-center justify-center text-[11px] text-[#777] uppercase tracking-wide">

                {{-- 🔁 Replace this content with your ad code / iframe / script --}}
                300 × 250 Ad Slot
            </div>
        </div>
    </div>

</div> -->


{{-- components: Latest Devices Sidebar --}}
<div class="w-full bg-white mt-5">

    {{-- Header --}}
    <h4 class="border-l-[11px] border-[#F9A13D] text-[#F9A13D] uppercase font-bold text-[12px] px-4 py-3">
        Latest Devices
    </h4>

    {{-- Device List --}}
    <div class="overflow-y-auto max-h-[390px] p-3 grid grid-cols-3 gap-4">

        @php(
            $latestDevices = Illuminate\Support\Facades\Cache::remember('latest_devices_sidebar', 3600, function () {
    return App\Models\Device::where('is_published', true)
        ->whereIn('release_status', ['announced', 'released'])
        ->orderByRaw("
            CASE 
                WHEN release_status = 'released' 
                     AND released_at IS NOT NULL 
                THEN released_at
                ELSE announcement_date
            END DESC
        ")
        ->limit(9)
        ->get();
})
        )
@foreach ($latestDevices as $device)
    <a href="{{ route('device-detail', $device->slug) }}"
       class="text-center text-[11px] text-[#555] hover:text-[#F9A13D]">

        <img src="{{ $device->thumbnail_url }}"
             alt="{{ $device->name }}"
             class="w-full h-auto rounded shadow-sm hover:scale-105 transition-transform duration-200 mb-1">

        <span class="block font-medium leading-tight">
            {{ $device->name }}
        </span>
    </a>
@endforeach


    </div>
</div>

<!-- In Stores Now -->
<div class="w-full bg-white mt-5">

    {{-- Header --}}
    <h4 class="border-l-[11px] border-[#F9A13D] text-[#F9A13D] uppercase font-bold text-[12px] px-4 py-3">
        IN STORES NOW
    </h4>

    {{-- Device List --}}
    <div class="overflow-y-auto max-h-[390px] bg-[#e7e4e4] p-3 grid grid-cols-3 gap-4">

        @php(
            $inStoresNow = Illuminate\Support\Facades\Cache::remember('in_stores_now', 3600, function () {
    return App\Models\Device::where('is_published', true)
        ->where('release_status', 'released')
        ->whereNotNull('released_at')
        ->whereDate('released_at', '<=', now())
        ->orderByDesc('released_at')
        ->limit(9)
        ->get();
})
           )

       @foreach ($inStoresNow as $device)
    <a href="{{ route('device-detail', $device->slug) }}"
       class="text-center text-[11px] text-[#555] hover:text-[#F9A13D]">

        <img src="{{ $device->thumbnail_url }}"
             alt="{{ $device->name }}"
             class="w-full h-auto rounded shadow-sm hover:scale-105 transition-transform duration-200 mb-1">

        <span class="block font-medium leading-tight">
            {{ $device->name }}
        </span>
    </a>
@endforeach


    </div>
</div>

<!-- Top 10 by Daily Interest -->
<div class="w-full bg-white mt-6">
    {{-- Header --}}
    <h4 class="border-l-[11px] border-[#F9A13D] text-[#F9A13D] uppercase font-bold text-[12px] px-4 py-3">
        Top 10 by Daily Interest
    </h4>

    {{-- Table Wrapper --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-[12px] text-[#555]">

            <thead class="bg-[#a4c08d] text-[white]">
                <tr>
                    <th class="w-[30px] py-2 pl-3">#</th>
                    <th class="py-2">Device</th>
                    <th class="py-2 pr-3 text-right">Daily Hits</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                @php(
                    $rankings = [
                        ['name' => 'Xiaomi Poco F8 Ultra', 'hits' => '35,721', 'url' => '#'],
                        ['name' => 'OnePlus 15', 'hits' => '24,173', 'url' => '#'],
                        ['name' => 'Samsung Galaxy A56', 'hits' => '23,603', 'url' => '#'],
                        ['name' => 'Samsung Galaxy S25 Ultra', 'hits' => '23,102', 'url' => '#'],
                        ['name' => 'Apple iPhone 17 Pro Max', 'hits' => '22,020', 'url' => '#'],
                        ['name' => 'Xiaomi Poco F8 Pro', 'hits' => '21,829', 'url' => '#'],
                        ['name' => 'Xiaomi 17 Pro Max', 'hits' => '17,405', 'url' => '#'],
                        ['name' => 'Samsung Galaxy A17', 'hits' => '16,797', 'url' => '#'],
                        ['name' => 'Samsung Galaxy S25', 'hits' => '14,365', 'url' => '#'],
                        ['name' => 'Xiaomi Poco X7 Pro', 'hits' => '14,085', 'url' => '#'],
                    ])

                @foreach ($rankings as $index => $item)
                    <tr class="{{ $loop->even ? 'bg-[#e8f5e9]' : '' }} transition">

                        <td class="py-1 pl-3 font-semibold">{{ $index + 1 }}.</td>

                        <td class="py-1">
                            <a href="{{ $item['url'] }}" class="hover:text-[#F9A13D]">
                                {{ $item['name'] }}
                            </a>
                        </td>

                        <td class="py-1 pr-3 text-right font-semibold">
                            {{ $item['hits'] }}
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>
    </div>

</div>


<div class="w-full bg-white mt-6">
    {{-- Header --}}
    <h4 class="border-l-[11px] border-[#F9A13D] text-[#F9A13D] uppercase font-bold text-[12px] px-4 py-3">
        Top 10 by fans
    </h4>

    {{-- Table Wrapper --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-[12px] text-[#555]">

            <thead class="bg-[#82a2bd] text-[white]">
                <tr>
                    <th class="w-[30px] py-2 pl-3">#</th>
                    <th class="py-2">Device</th>
                    <th class="py-2 pr-3 text-right">Favorites</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                @php(
                    $rankings = [
                        ['name' => 'Xiaomi Poco F8 Ultra', 'hits' => '35,721', 'url' => '#'],
                        ['name' => 'OnePlus 15', 'hits' => '24,173', 'url' => '#'],
                        ['name' => 'Samsung Galaxy A56', 'hits' => '23,603', 'url' => '#'],
                        ['name' => 'Samsung Galaxy S25 Ultra', 'hits' => '23,102', 'url' => '#'],
                        ['name' => 'Apple iPhone 17 Pro Max', 'hits' => '22,020', 'url' => '#'],
                        ['name' => 'Xiaomi Poco F8 Pro', 'hits' => '21,829', 'url' => '#'],
                        ['name' => 'Xiaomi 17 Pro Max', 'hits' => '17,405', 'url' => '#'],
                        ['name' => 'Samsung Galaxy A17', 'hits' => '16,797', 'url' => '#'],
                        ['name' => 'Samsung Galaxy S25', 'hits' => '14,365', 'url' => '#'],
                        ['name' => 'Xiaomi Poco X7 Pro', 'hits' => '14,085', 'url' => '#'],
                    ])

                @foreach ($rankings as $index => $item)
                    <tr class="{{ $loop->even ? 'bg-[#e4eff6]' : '' }} transition">

                        <td class="py-1 pl-3 font-semibold">{{ $index + 1 }}.</td>

                        <td class="py-1">
                            <a href="{{ $item['url'] }}" class="hover:text-[#F9A13D]">
                                {{ $item['name'] }}
                            </a>
                        </td>

                        <td class="py-1 pr-3 text-right font-semibold">
                            {{ $item['hits'] }}
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>
    </div>

</div>


<div class="w-full bg-white mt-6">
    {{-- Header --}}
    <h4 class="border-l-[11px] border-[#F9A13D] text-[#F9A13D] uppercase font-bold text-[12px] px-4 py-3">
        Popular Comparisons
    </h4>

    <div class="overflow-y-auto max-h-[380px] px-4 py-2">

        @php(
            $comparisons = [
                ['text' => 'Samsung Galaxy S25 Ultra vs. iPhone 17 Pro Max', 'url' => '#'],
                ['text' => 'Samsung Galaxy S25 vs. Galaxy S25 FE 5G', 'url' => '#'],
                ['text' => 'Samsung Galaxy A60 vs. Galaxy M40', 'url' => '#'],
                ['text' => 'Samsung Galaxy S24 Ultra vs. Galaxy S25 Ultra', 'url' => '#'],
                ['text' => 'Apple iPhone 16 Pro Max vs. iPhone 17 Pro Max', 'url' => '#'],
                ['text' => 'Samsung Galaxy A36 vs. Galaxy A56', 'url' => '#'],
                ['text' => 'Apple iPhone 16 vs. Apple iPhone 17', 'url' => '#'],
                ['text' => 'Oppo Find X9 Pro vs. OnePlus 15 5G', 'url' => '#'],
                ['text' => 'OnePlus 13 vs. OnePlus 15 5G', 'url' => '#'],
                ['text' => 'Xiaomi Poco F8 Ultra 5G vs. Poco F8 Pro 5G', 'url' => '#'],
                ['text' => 'Xiaomi 15T 5G vs. Xiaomi 15T Pro 5G', 'url' => '#'],
            ])

        <ul class="space-y-1">
            @foreach ($comparisons as $index => $item)
                <li class="{{ $loop->even ? 'bg-[#fff2ee]' : '' }} px-2 py-1 transition">
                    <a href="{{ $item['url'] }}" class="text-[12px] text-[#555] hover:text-[#F9A13D] leading-tight">
                        {{ $item['text'] }}
                    </a>
                </li>
            @endforeach
        </ul>

    </div>

</div>


<!-- Electric Vehicles News  -->
<!-- <div class="w-full bg-white mt-6">
    <h4 class="border-l-[11px] border-[#F9A13D] text-[#F9A13D] uppercase font-bold text-[12px] px-4 py-3">
        <a href="https://www.arenaev.com/" target="_blank" class="hover:text-[#F9A13D]">
            Electric vehicles
        </a>
    </h4>

    @php(
        $evNews = [
            [
                'title' => 'Electric car sales overtake gasoline and diesel in Europe, Tesla misses momentum',
                'img' => 'https://st.arenaev.com/news/25/11/electric-car-sales-overtake-gasoline-and-diesel-in-europe/-344x215/arenaev_000.jpg',
                'url' => 'https://www.arenaev.com/electric_car_sales_overtake_gasoline_and_diesel_in_europe_tesla_misses_momentum-news-5367.php',
            ],
            [
                'title' => 'Tesla launches 30-day free trial of FSD (Supervised) V14 in North America',
                'img' => 'https://st.arenaev.com/news/25/11/tesla-fsd-v14-free-trial/-344x215/arenaev_000.jpg',
                'url' => 'https://www.arenaev.com/tesla_launches_30day_free_trial_of_fsd_supervised_v14_in_north_america-news-5365.php',
            ],
            [
                'title' => 'UK electric car drivers to pay tax per mile starting 2028',
                'img' => 'https://st.arenaev.com/news/25/11/uk-electric-car-drivers-to-pay-tax-per-mile/-344x215/arenaev_000.jpg',
                'url' => 'https://www.arenaev.com/uk_electric_car_drivers_to_pay_tax_per_mile_starting_2028-news-5364.php',
            ],
            [
                'title' => 'Why solar panels on cars make no sense (at this point)',
                'img' => 'https://st.arenaev.com/news/23/01/solar-panels-stupid/-344x215/arenaev_001.jpg',
                'url' => 'https://www.arenaev.com/why_solar_panels_on_cars_are_beyond_stupid_at_this_point-news-1295.php',
            ],
        ])

    <div class="px-3 py-3">
        <ul class="space-y-3">
            @foreach ($evNews as $item)
                <li class="bg-white flex gap-2 p-2 rounded-sm shadow-sm hover:shadow-md transition-shadow">
                    <a href="{{ $item['url'] }}" target="_blank" class="flex gap-2 w-full">

                        <div class="w-[80px] h-[55px] flex-shrink-0 overflow-hidden rounded-sm">
                            <img src="{{ $item['img'] }}" alt="{{ $item['title'] }}"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-200" />
                        </div>

                        <div class="flex-1 flex items-center">
                            <span class="text-[11px] leading-snug text-[#555] hover:text-[#F9A13D]">
                                {{ $item['title'] }}
                            </span>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div> -->

{{-- Sidebar Advertisement Slot --}}
<!-- <div class="w-full bg-white mt-6">

    {{-- Outer wrapper to keep consistent with other sidebar boxes --}}
    <div class="border border-[#d5d5d5] bg-[#f5f5f5] relative flex items-center justify-center py-3">

        {{-- Small "ADVERTISEMENT" label --}}
        <span class="absolute top-1 left-2 text-[9px] tracking-[0.18em] text-[#9e9e9e] uppercase">
            Advertisement
        </span>

        {{-- Ad container (300x250 on desktop, responsive on small screens) --}}
        <div class="w-full flex justify-center">
            <div
                class="bg-[#e0e0e0] w-[300px] h-[250px] flex items-center justify-center text-[11px] text-[#777] uppercase tracking-wide">

                {{-- 🔁 Replace this content with your ad code / iframe / script --}}
                300 × 250 Ad Slot
            </div>
        </div>
    </div>

</div> -->
