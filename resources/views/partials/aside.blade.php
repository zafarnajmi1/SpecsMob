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


{{-- Popular Reviews section --}}
<div class="w-full bg-white">
    <h4 class="border-l-[11px] border-[#F9A13D] text-[#F9A13D] uppercase font-bold text-[12px] px-4 py-3 border-b">
        Popular Reviews
    </h4>

    @php(
        $popularReviewsSidebar = Illuminate\Support\Facades\Cache::remember('popular_reviews_sidebar', 3600, function () {
            return App\Models\Review::published()
                ->orderByDesc('views_count')
                ->limit(3)
                ->get();
        })
    )

    <div class="p-3 space-y-3">
        @foreach ($popularReviewsSidebar as $review)
            <div class="group relative overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <a href="{{ route('review-detail', $review->slug) }}" class="block">
                    <img src="{{ $review->cover_image_url }}" alt="{{ $review->title }}" 
                        class="w-full h-[150px] object-cover hover:scale-105 transition-transform duration-300">
                    <div class="absolute bottom-0 left-0 right-0 bg-black/60 p-2">
                        <span class="text-white text-[12px] font-bold line-clamp-1 group-hover:text-[#F9A13D] transition-colors">
                            {{ $review->title }}
                        </span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
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

                <img src="{{ $device->thumbnail_url }}" alt="{{ $device->name }}"
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

                <img src="{{ $device->thumbnail_url }}" alt="{{ $device->name }}"
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
                    $topByDailyInterest = Illuminate\Support\Facades\Cache::remember('top_10_daily_interest', 300, function () {
                        return App\Models\Device::where('is_published', true)
                            ->join('device_stats', 'devices.id', '=', 'device_stats.device_id')
                            ->whereDate('device_stats.daily_hits_date', today())
                            ->where('device_stats.daily_hits', '>', 0)
                            ->orderByDesc('device_stats.daily_hits')
                            ->limit(10)
                            ->select('devices.*', 'device_stats.daily_hits')
                            ->get();
                }))

                @forelse ($topByDailyInterest as $index => $device)
                    <tr class="{{ $loop->even ? 'bg-[#e8f5e9]' : '' }} transition">

                        <td class="py-1 pl-3 font-semibold">{{ $index + 1 }}.</td>

                        <td class="py-1">
                            <a href="{{ route('device-detail', $device->slug) }}" class="hover:text-[#F9A13D]">
                                {{ $device->name }}
                            </a>
                        </td>

                        <td class="py-1 pr-3 text-right font-semibold">
                            {{ number_format($device->daily_hits) }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-[11px] text-gray-400">
                            No daily hits data available yet
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>
    </div>

</div>


<!-- Top 10 by fans -->
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
                    // Fetch Top 10 devices by total fans
                    $topByFans = Illuminate\Support\Facades\Cache::remember('top_10_fans', 300, function () {
                        return App\Models\Device::where('is_published', true)
                            ->join('device_stats', 'devices.id', '=', 'device_stats.device_id')
                            ->orderByDesc('device_stats.total_fans')
                            ->limit(10)
                            ->select('devices.*', 'device_stats.total_fans')
                            ->get();
                }))

                @forelse ($topByFans as $index => $device)
                    <tr class="{{ $loop->even ? 'bg-[#e4eff6]' : '' }} transition">

                        <td class="py-1 pl-3 font-semibold">{{ $index + 1 }}.</td>

                        <td class="py-1">
                            <a href="{{ route('device-detail', $device->slug) }}" class="hover:text-[#F9A13D]">
                                {{ $device->name }}
                            </a>
                        </td>

                        <td class="py-1 pr-3 text-right font-semibold">
                            {{ number_format($device->total_fans) }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-400">
                            No fan data yet.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>
    </div>

</div>


<!-- Popular Comparisons -->
<div class="w-full bg-white my-6">
    {{-- Header --}}
    <h4 class="border-l-[11px] border-[#F9A13D] text-[#F9A13D] uppercase font-bold text-[12px] px-4 py-3">
        Popular Comparisons
    </h4>

    <div class="overflow-y-auto max-h-[380px] px-4 py-2">

        @php(
            $topComparisons = Illuminate\Support\Facades\Cache::remember('top_popular_comparisons', 300, function () {
                return App\Models\ComparisonStat::with(['device1', 'device2'])
                    ->whereHas('device1', fn($q) => $q->where('is_published', true))
                    ->whereHas('device2', fn($q) => $q->where('is_published', true))
                    ->orderByDesc('total_hits')
                    ->limit(10)
                    ->get();
        }))

        <ul class="space-y-1">
            @forelse ($topComparisons as $index => $stat)
                <li class="{{ $loop->even ? 'bg-[#fff2ee]' : '' }} px-2 py-1 transition">
                    <a href="{{ route('device.compare', ['slug' => $stat->device1->slug, 'id' => $stat->device1->id, 'id2' => $stat->device2->id]) }}"
                        class="text-[12px] text-[#555] hover:text-[#F9A13D] leading-tight block">
                        {{ $stat->device1->name }} <span class="text-gray-400 mx-0.5">vs.</span> {{ $stat->device2->name }}
                    </a>
                </li>
            @empty
                <li class="px-2 py-4 text-center text-[12px] text-gray-400">
                    No comparison data yet.
                </li>
            @endforelse
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

    <div class="px-3 pb-4">
        <ul class="space-y-3">
            @foreach ($evNews as $item)
                <li class="bg-white flex gap-2 p-1 rounded-sm shadow-sm hover:shadow-md transition-shadow border border-gray-100">
                    <a href="{{ $item['url'] }}" target="_blank" class="flex gap-2 w-full">

                        <div class="w-[85px] h-[55px] flex-shrink-0 overflow-hidden rounded-sm">
                            <img src="{{ $item['img'] }}" alt="{{ $item['title'] }}"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-200" />
                        </div>

                        <div class="flex-1 flex items-center">
                            <span class="text-[11px] leading-snug font-medium text-[#444] hover:text-[#F9A13D] line-clamp-2">
                                {{ $item['title'] }}
                            </span>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div> -->


<!-- Sidebar Advertisement Slot -->
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