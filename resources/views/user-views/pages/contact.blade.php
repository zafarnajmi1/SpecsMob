@extends('layouts.app')

@section('title', 'Contact')

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')

    {{-- Hero / header --}}
    <div class="overflow-hidden w-full mb-6 md:h-[310px]">
        <div class="relative bg-cover bg-center h-full"
            style="background-image: url('https://fdn.gsmarena.com/imgroot/static/headers/contact-hlr.jpg');">
            <div class="bg-black/25 h-full">
                <div class="px-4 md:px-6 py-6 h-full flex flex-col justify-end gap-2">
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white drop-shadow-md">
                        Contact us
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <section class=" my-5 flex flex-col gap-4 p-3 md:p-4">

        {{-- Intro / feedback card --}}
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
                <a href="{{ url('/tip-us') }}" class="font-semibold text-[#F9A13D] hover:underline">
                    Tip us form
                </a>
                so the information reaches the right team directly.
            </p>
        </div>

        {{-- Direct contact block --}}
        <div class="bg-white px-4 py-4 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed">
            <h2 class="text-lg font-semibold text-[#333] mb-2">
                General contact
            </h2>

            <p class="mb-2">
                For general questions about the site, corrections, or technical issues, you can email us at:
            </p>

            @if(setting('contact_email'))
                <p class="text-[14px] font-semibold">
                    <a href="mailto:{{ setting('contact_email') }}" class="text-[#F9A13D] hover:underline break-all">
                        {{ setting('contact_email') }}
                    </a>
                </p>
            @else
                <p class="text-[14px] font-semibold text-gray-400">
                    <em>Contact email not configured</em>
                </p>
            @endif

            <p class="mt-3 text-[12px] text-[#777]">
                Please include as much detail as possible (URL, device name, screenshot if relevant) so we can
                investigate and respond more efficiently.
            </p>
        </div>

        {{-- Advertising block --}}
        <div class="bg-white px-4 py-4 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed">
            <h2 class="text-lg font-semibold text-[#333] mb-2">
                Advertising & partnerships
            </h2>

            <p>
                If you run an online store, service, or brand and are interested in advertising or partnership
                opportunities, we’d be happy to discuss formats and options.
            </p>

            <p class="mt-2">
                You can find more details on our dedicated advertising page:
            </p>

            <p class="mt-1">
                <a href="{{ url('/advertising') }}" class="font-semibold text-[#F9A13D] hover:underline">
                    Learn more about advertising
                </a>
            </p>
        </div>

        {{-- Optional: Simple contact form (you can wire this to a controller later) --}}
        <div class="bg-white px-4 py-5 shadow-sm border border-gray-200 text-[13px] text-[#444] leading-relaxed">
            <h2 class="text-lg font-semibold text-[#333] mb-3">
                Send us a message
            </h2>

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