@extends('layouts.app')

@section('title', 'Brands')

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')

    {{-- Hero Header --}}
    <div class="hidden lg:block relative overflow-hidden w-full mb-10 rounded-b-[40px] shadow-2xl">
        <div class="relative h-[280px] md:h-[350px] bg-cover bg-center transition-transform duration-1000 hover:scale-105"
            style='background-image: url("{{ asset('user/images/all-brands.jpg') }}");'>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>

            <div class="relative h-full container mx-auto px-4 lg:px-6 flex flex-col justify-end pb-12">
                <div class="max-w-2xl">
                    <nav class="flex mb-4 text-blue-400 text-[10px] font-black uppercase tracking-widest gap-2">
                        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                        <span>/</span>
                        <span class="text-white">Brands</span>
                    </nav>
                    <h1
                        class="text-4xl md:text-6xl font-black text-white tracking-tighter leading-none mb-3 drop-shadow-2xl">
                        Explore <span class="text-blue-500">Brands</span>
                    </h1>
                    <p class="text-slate-300 text-sm md:text-base font-medium max-w-md line-clamp-2">
                        Discover the complete lineup of mobile technology from the world's leading manufacturers.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <section class="hidden lg:block my-10 container mx-auto px-4 lg:px-6">
        <div class="mb-8 border-b border-gray-100 pb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-mobile-screen-button text-blue-600"></i> All Brands
                <span
                    class="ml-auto text-sm font-medium text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-100 italic">
                    {{ $brands->count() }} active brands in our database
                </span>
            </h2>
        </div>

        {{-- BRAND CARDS --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4 gap-6">
            @foreach ($brands as $brand)
                <a href="{{ route('brand.devices', $brand->slug) }}"
                    class="group flex flex-col items-center p-4 bg-white border border-gray-200 rounded-2xl hover:border-blue-500 hover:shadow-xl transition-all duration-300 w-40 h-40 justify-between">
                    <!-- Logo with Placeholder Fallback -->
                    <div
                        class="w-full h-16 mb-3 flex items-center justify-center rounded-xl bg-gray-50 overflow-hidden group-hover:bg-blue-50 transition-colors">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}"
                                class="w-12 h-12 object-contain">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-2xl">
                                {{ substr($brand->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <div class="text-center w-full">
                        <h3
                            class="text-[13px] font-bold text-gray-800 group-hover:text-blue-600 uppercase tracking-tight line-clamp-1">
                            {{ $brand->name }}
                        </h3>
                        <p class="text-[10px] font-semibold text-gray-400 mt-0.5">
                            {{ number_format($brand->devices_count) }} devices
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

     <div class="bg-white shadow mb-4 lg:hidden">
        <h4 class="uppercase text-[#F9A13D] px-5 py-1 font-bold text-lg mb-3 border-b">
            All Mobile Phone Brands 
        </h4>

        <div class="bg-[#F0F0F0] my-5 w-full">
            @foreach ($brands as $brand)
                <a href="{{ route('brand.devices', $brand->slug) }}" class="flex items-center w-full px-5 py-3 bg-white font-bold text-[15px] border border-[#eee]">
                    <span class="flex-1">{{ $brand->name }}</span>
                    <i class="fa-solid fa-angle-right text-[#F9A13D]"></i>
                </a>
            @endforeach
        </div>
    </div>
@endsection