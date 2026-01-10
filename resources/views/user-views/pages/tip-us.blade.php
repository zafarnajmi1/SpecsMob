@extends('layouts.app')

@section('title', 'Tip us')

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')

    {{-- Hero / header --}}
    <div class="overflow-hidden w-full mb-6 md:h-[310px]">
        @php
            $headerImg = setting('tip_us_page_image')
                ? asset('storage/' . setting('tip_us_page_image'))
                : 'https://fdn.gsmarena.com/imgroot/static/headers/tip-us.jpg';
        @endphp
        <div class="relative bg-cover bg-center h-full" style="background-image: url('{{ $headerImg }}');">
            <div class="bg-black/25 h-full">
                <div class="px-4 md:px-6 py-6 h-full flex flex-col justify-end gap-2">
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white drop-shadow-md">
                        {{ setting('tip_us_page_title', 'Tip us') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <section class=" my-5 flex flex-col gap-4 p-3 md:p-4">

        {{-- Dynamic Content from Admin --}}
        @if(setting('tip_us_page_content'))
            <div
                class="bg-white px-4 py-4 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed dynamic-content">
                {!! setting('tip_us_page_content') !!}
            </div>
        @else
            {{-- Fallback Static Content --}}
            <div class="bg-white px-4 py-4 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed space-y-3">
                <h2 class="text-lg font-semibold text-[#333] mb-1">
                    So you've got a hot story to share?
                </h2>

                <p>We're always on the lookout for good stories, no matter if it's some leaked product info, a new invention, a
                    nice piece of mobile software, a software update for your favorite phone or stuff, regarding the market
                    availability and pricing of any mobile phone. We're also happy to accept any multimedia material you may
                    have - a good place for sharing images is imgur.com and for videos - there's always YouTube, you know.</p>
            </div>
        @endif

        {{-- Optional: Simple contact form (you can wire this to a controller later) --}}
        <div class="bg-white px-4 py-5 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed">
            <h2 class="text-lg font-semibold text-[#333] mb-3">
                {{ setting('tip_us_form_title', 'Tip us form') }}
            </h2>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-[13px]"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-[13px]"
                    role="alert">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('tip-us.submit') }}" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-[12px] font-semibold text-[#555] mb-1">
                        Subject
                    </label>
                    <input type="text" name="subject"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#F9A13D]"
                        value="{{ old('subject') }}">
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#555] mb-1">
                        Share
                    </label>
                    <textarea name="share" rows="4"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#F9A13D] resize-y"
                        required>{{ old('share') }}</textarea>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#555] mb-1">
                        Simple CAPTCHA. Write "forty-two" as a number.
                    </label>
                    <input type="text" name="captcha"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#F9A13D]"
                        required placeholder="Answer here...">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 text-[12px] font-semibold uppercase tracking-[0.18em]
                                                   bg-[#F9A13D] text-white hover:bg-[#D5852E] transition-colors">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </section>

@endsection