@extends('layouts.app')

@section('title', 'Coverage')

    @section('sidebar')
        @include('partials.aside')
    @endsection

@section('content')
    {{-- resources/views/userviews/deals/index.blade.php --}}


    {{-- Header / hero --}}
    <div class="overflow-hidden w-full mb-6 md:h-[310px]">
        <div class="relative bg-cover bg-center h-full"
            style='background-image: url("{{ asset('user/images/coverage.jpg') }}");'>
            <div class="bg-black/55 h-full">
                <div class="px-4 h-full md:px-6 py-6 flex flex-col items-start justify-end text-center gap-2">
                    <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-white drop-shadow-md">
                        Network coverage in Pakistan
                    </h1>
                </div>
            </div>
        </div>
    </div>


    @php
        // Current country (you can pass from controller instead)
        $currentCountry = $currentCountry ?? 'Pakistan';

        // Example country list – replace with full list from DB or config
        $countries = [
            ['name' => 'Afghanistan', 'slug' => 'afghanistan'],
            ['name' => 'Albania', 'slug' => 'albania'],
            ['name' => 'Algeria', 'slug' => 'algeria'],
            ['name' => 'Germany', 'slug' => 'germany'],
            ['name' => 'India', 'slug' => 'india'],
            ['name' => 'Pakistan', 'slug' => 'pakistan'],
            ['name' => 'United Kingdom', 'slug' => 'united-kingdom'],
            ['name' => 'United States', 'slug' => 'united-states'],
            // TODO: load full list from DB / config
        ];

        // Example bands for Pakistan – normally from DB
        $bands = [
            '2G' => 'GSM 900, GSM 1800',
            '3G' => 'UMTS 850, UMTS 900, UMTS 2100',
            '4G' => 'LTE 850, LTE 1800',
            '5G' => '', // none listed
        ];
    @endphp

    <section class="my-5 flex flex-col gap-4 p-3 md:p-4">
        {{-- Intro text --}}
        <div class="bg-white px-4 py-4 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed space-y-3">
            <p>
                Every mobile device supports a specific set of radio frequency bands.
                Those bands decide whether a phone can actually register and work on a
                given network in a particular country.
            </p>
            <p>
                Besides listing bands in the device specs, this page gives you a
                country-level view of which technologies are commonly used there.
                It’s useful both when picking a phone for use at home and when you want
                to check if your existing handset will work when you travel.
            </p>
        </div>

        {{-- Country selector row --}}
        <div class="bg-[#dddddd] px-4 py-3 border border-[#c7c7c7]">
            <div class="flex flex-col md:flex-row md:items-center gap-2 text-[13px] text-[#333]">
                <span class="font-semibold">Select country:</span>

                <div class="md:ml-3 flex-1">
                    <select name="country"
                        class="w-full rounded border border-gray-300 bg-white px-2 py-1.5 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#F9A13D]"
                        onchange="
                            if (this.value) {
                                // replace with your own route if needed, e.g.:
                                // window.location.href = '{{ url('coverage') }}/' + this.value;
                                window.location.href = this.dataset.baseUrl.replace('__slug__', this.value);
                            }
                        " data-base-url="{{ url('coverage/__slug__') }}">
                        @foreach ($countries as $country)
                            <option value="{{ $country['slug'] }}"
                                @selected(strtolower($currentCountry) === strtolower($country['name']))>
                                {{ $country['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Country heading --}}
        <div class="mt-2 px-1">
            <h2 class="text-lg md:text-xl font-semibold text-[#333]">
                {{ $currentCountry }}
            </h2>
        </div>

        {{-- Capability rows --}}
        <div class="bg-white border border-gray-200 shadow-sm divide-y divide-gray-200 text-[13px]">
            {{-- 2G --}}
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 bg-[#f5f5f5] px-4 py-3 border-r border-gray-200">
                    <h3 class="text-[15px] font-semibold text-[#333]">2G capabilities</h3>
                </div>
                <div class="flex-1 px-4 py-3">
                    <p class="text-[14px] font-semibold text-[#444]">
                        {{ $bands['2G'] ?: 'Not listed' }}
                    </p>
                </div>
            </div>

            {{-- 3G --}}
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 bg-[#f5f5f5] px-4 py-3 border-r border-gray-200">
                    <h3 class="text-[15px] font-semibold text-[#333]">3G capabilities</h3>
                </div>
                <div class="flex-1 px-4 py-3">
                    <p class="text-[14px] font-semibold text-[#444]">
                        {{ $bands['3G'] ?: 'Not listed' }}
                    </p>
                </div>
            </div>

            {{-- 4G --}}
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 bg-[#f5f5f5] px-4 py-3 border-r border-gray-200">
                    <h3 class="text-[15px] font-semibold text-[#333]">4G capabilities</h3>
                </div>
                <div class="flex-1 px-4 py-3">
                    <p class="text-[14px] font-semibold text-[#444]">
                        {{ $bands['4G'] ?: 'Not listed' }}
                    </p>
                </div>
            </div>

            {{-- 5G --}}
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 bg-[#f5f5f5] px-4 py-3 border-r border-gray-200">
                    <h3 class="text-[15px] font-semibold text-[#333]">5G capabilities</h3>
                </div>
                <div class="flex-1 px-4 py-3">
                    <p class="text-[14px] font-semibold text-[#444]">
                        {{ $bands['5G'] ?: 'Currently not specified' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Technology explanations --}}
        <div
            class="bg-white px-4 py-4 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed space-y-3 mt-2">
            <p>
                <strong>2G</strong> (second generation) brought digital voice and basic
                data. It enabled SMS and simple data services, but speeds are very low
                by today’s standards.
            </p>

            <p>
                <strong>3G</strong> improved on 2G with much faster data rates and support
                for features like video calling. It was the first generation that made
                always-connected smartphones practical.
            </p>

            <p>
                <strong>4G</strong> (LTE and beyond) delivers high-speed mobile broadband.
                It’s fast enough for HD video streaming, online gaming, and can even
                serve as a home internet replacement in some regions.
            </p>

            <p>
                <strong>5G</strong> is the next step, designed for even higher throughput,
                very low latency, and the ability to handle huge numbers of connected
                devices. This enables use cases like AR/VR, massive IoT deployments,
                and ultra-responsive cloud services.
            </p>
        </div>

        {{-- Notes --}}
        <div class=" text-[12px] text-[#8c6d1f] px-4 py-3 mt-2 space-y-2">
            <p>
                For absolute confirmation, always verify supported bands with your
                mobile operator in {{ $currentCountry }} or the operator in the country
                you plan to visit.
            </p>
            <p>
                If you spot any mismatch between the listed bands and reality in your
                region, make sure your own site has a simple “Report an issue” contact
                so users can help you keep this directory accurate.
            </p>
        </div>
    </section>
@endsection