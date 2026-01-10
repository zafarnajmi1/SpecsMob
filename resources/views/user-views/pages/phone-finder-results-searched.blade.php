@extends('layouts.app')

@section('title', 'Phone Finder Results')

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
    {{-- Hero / Header --}}
    <div class="overflow-hidden w-full md:h-[310px]">
        <div class="relative bg-cover bg-center h-full"
            style='background-image: url("{{ asset("user/images/phonefinder.jpg") }}");'>
            <div class="bg-black/25 h-full">
                <div class="px-4 md:px-6 py-6 h-full flex flex-col justify-end gap-2">
                    <h1 class="text-[32px] md:text-[40px] font-bold tracking-tight text-white drop-shadow-md">
                        Phone Finder Results
                    </h1>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Sections --}}
    <div class="bg-[#f2f2f2] border border-gray-300 py-5">

        <div class="px-4 flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Devices Found ({{ $devices->total() }})</h2>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">Order by:</span>
                <select onchange="updateOrder(this.value)"
                    class="border border-gray-300 rounded px-3 py-1 text-sm outline-none">
                    <option value="latest" @if(request("order") == "latest") selected @endif>Latest</option>
                    <option value="popular" @if(request("order") == "popular") selected @endif>Popularity</option>
                </select>
            </div>
        </div>

        {{-- Section: Matched Devices --}}
        <section class="mb-8">
            <h4 class="border-l-[11px] border-[#accf83] text-[#777] font-[500] text-[23px] px-4 py-3 mb-4">
                Matched Devices
            </h4>
            @if($devices->count() > 0)
                <div class="px-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4">
                        @foreach($devices as $device)
                            <a href="{{ route("device-detail", $device->slug) }}"
                                class="relative bg-white shadow-sm overflow-hidden group cursor-pointer border border-[#eee] hover:border-[#F9A13D] hover:shadow-md transition-all duration-300 text-center block rounded">

                                <!-- Device Image -->
                                <div class="block h-[170px] flex items-center justify-center px-2 py-4 bg-white">
                                    <img src="{{ $device->thumbnail_url }}" alt="{{ $device->name }}" title="{{ $device->name }}"
                                        class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-500"
                                        onerror="this.src='{{ asset("images/default-device.png") }}'">
                                </div>

                                <!-- Device Name -->
                                <div class="py-3 border-t border-[#f8f8f8] transition-all duration-300 group-hover:bg-[#F9A13D]">
                                    <strong class="text-[#555] font-bold text-[14px] group-hover:text-white px-2 line-clamp-1">
                                        {{ $device->name }}
                                    </strong>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-center">
                        {{ $devices->links() }}
                    </div>
                </div>
            @else
                <div class="p-12 text-center bg-white mx-4 rounded-lg shadow-sm">
                    <i class="fas fa-mobile-alt text-5xl text-gray-200 mb-4 block"></i>
                    <p class="text-gray-400 italic font-bold">No devices matched your search criteria.</p>
                    <a href="{{ route("phone-finder") }}" class="inline-block mt-4 text-[#F9A13D] hover:underline font-bold">
                        Return to Phone Finder
                    </a>
                </div>
            @endif
        </section>
    </div>

    <script>
        function updateOrder(value) {
            let url = new URL(window.location.href);
            url.searchParams.set('order', value);
            window.location.href = url.toString();
        }
    </script>
@endsection