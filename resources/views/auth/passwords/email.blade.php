@extends('layouts.app')

@section('content')

    {{-- 1️⃣ HEADER / HERO --}}
    <div class="overflow-hidden w-full md:h-[310px]">
        <div class="relative bg-cover bg-center h-full"
            style="background-image: url('https://fdn.gsmarena.com/imgroot/static/headers/glossary-hlr.jpg');">
            <div class="bg-black/10 h-full">
                <div class="px-4 md:px-6 py-6 h-full flex flex-col justify-end">
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white drop-shadow-md">
                        Forgotten password
                    </h1>
                </div>
            </div>
        </div>
    </div>

    {{-- 2️⃣ Reset password INFO --}}
    <div class="max-w-[900px] mb-4">
        <div class="p-6 normal-text text-sm text-gray-800">
            <h3 class="text-lg font-semibold mb-3">Initiating password reset</h3>
            <ul class="list-disc pl-5 space-y-2">
                <li>You can reset your password by enterting the email you registered with us.</li>
            </ul>
        </div>
    </div>

    {{-- 3️⃣ Reset password FORM --}}
    <div class="max-w-[900px] mx-auto px-4 pb-[5rem]">
        <div class="bg-[#f0f0f0]">
            <div class="p-6">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full border px-3 py-2 rounded @error('email') border-[#F9A13D] @enderror">
                        @error('email')
                            <p class="text-[#F9A13D] text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="bg-[#F9A13D] hover:bg-[#e89530] text-white px-6 py-2 rounded font-semibold">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection