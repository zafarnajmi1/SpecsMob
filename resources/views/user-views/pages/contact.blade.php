@extends('layouts.app')

@section('title', 'Contact')

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')

    {{-- Hero / header --}}
    <div class="overflow-hidden w-full mb-6 md:h-[310px]">
        @php
            $headerImg = setting('contact_page_image')
                ? asset('storage/' . setting('contact_page_image'))
                : 'https://fdn.gsmarena.com/imgroot/static/headers/contact-hlr.jpg';
        @endphp
        <div class="relative bg-cover bg-center h-full" style="background-image: url('{{ $headerImg }}');">
            <div class="bg-black/25 h-full">
                <div class="px-4 md:px-6 py-6 h-full flex flex-col justify-end gap-2">
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white drop-shadow-md">
                        {{ setting('contact_page_title', 'Contact us') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <section class=" my-5 flex flex-col gap-4 p-3 md:p-4">

        {{-- Dynamic Content from Admin --}}
        @if(setting('contact_page_content'))
            <div
                class="bg-white px-4 py-4 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed dynamic-content">
                {!! setting('contact_page_content') !!}
            </div>
        @else
            {{-- Fallback Static Content --}}
            <div class="bg-white px-4 py-4 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed space-y-3">
                <h2 class="text-lg font-semibold text-[#333] mb-1">
                    We value your feedback
                </h2>

                <p>
                    We’re happy to hear from you when something on the site needs attention or you have ideas
                    that can help us improve.
                </p>

                <p class="font-semibold text-[13px] text-[#333]">
                    Reach out to us if, for example:
                </p>

                <ul class="list-disc pl-5 space-y-1">
                    <li>You noticed an error or outdated detail in a device specification.</li>
                    <li>You have information about a phone or device that’s missing from our database.</li>
                    <li>You’ve come across a broken link or a page that doesn’t load as expected.</li>
                    <li>You’d like to suggest a new feature or UX improvement for the site.</li>
                </ul>

                <p class="mt-3 font-semibold text-[13px] text-[#333]">
                    Before contacting us, please keep in mind:
                </p>

                <ul class="list-disc pl-5 space-y-1">
                    <li>We don’t sell phones or accessories and can’t provide purchase invoices.</li>
                    <li>We can’t quote local prices for specific countries or shops.</li>
                    <li>We don’t handle SIM unlocking or carrier-unlock requests.</li>
                    <li>We can’t offer personalised “which phone should I buy?” consultations.</li>
                </ul>

                <p class="mt-3">
                    For tips about news, leaks, or interesting stories, please use our
                    <a href="{{ route('tip-us') }}" class="font-semibold text-[#F9A13D] hover:underline">
                        Tip us form
                    </a>
                    so the information reaches the right team directly.
                </p>
            </div>
        @endif

        {{-- Optional: Simple contact form (you can wire this to a controller later) --}}
        <div class="bg-white px-4 py-5 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed">
            <h2 class="text-lg font-semibold text-[#333] mb-3">
                {{ setting('contact_form_title', 'Send us a message') }}
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

            <form method="POST" action="{{ route('contact.submit') }}" class="space-y-3">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[12px] font-semibold text-[#555] mb-1">
                            Your name
                        </label>
                        <input type="text" name="name"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#F9A13D]"
                            required>
                    </div>

                    <div>
                        <label class="block text-[12px] font-semibold text-[#555] mb-1">
                            Email address
                        </label>
                        <input type="email" name="email"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#F9A13D]"
                            required>
                    </div>
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#555] mb-1">
                        Subject
                    </label>
                    <input type="text" name="subject"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#F9A13D]">
                </div>

                <div>
                    <label class="block text-[12px] font-semibold text-[#555] mb-1">
                        Message
                    </label>
                    <textarea name="message" rows="4"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-[13px] focus:outline-none focus:ring-1 focus:ring-[#F9A13D] resize-y"
                        required></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 text-[12px] font-semibold uppercase tracking-[0.18em]
                                                   bg-[#F9A13D] text-white hover:bg-[#D5852E] transition-colors">
                        Send message
                    </button>
                </div>
            </form>
        </div>
    </section>

@endsection