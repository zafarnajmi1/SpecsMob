@extends('layouts.app')

@section('content')

{{-- 1️⃣ HEADER / HERO --}}
<div class="overflow-hidden w-full md:h-[310px]">
    <div class="relative bg-cover bg-center h-full"
        style="background-image: url('https://fdn.gsmarena.com/imgroot/static/headers/glossary-hlr.jpg');">
        <div class="bg-black/10 h-full">
            <div class="px-4 md:px-6 py-6 h-full flex flex-col justify-end">
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white drop-shadow-md">
                    Sign Up
                </h1>
            </div>
        </div>
    </div>
</div>

{{-- 2️⃣ REGISTRATION INFO --}}
<div class="max-w-[900px] mb-4">
    <div class="p-6 normal-text text-sm text-gray-800">
        <h3 class="text-lg font-semibold mb-3">Why register</h3>
        <ul class="list-disc pl-5 space-y-2">
            <li>Your nickname will be reserved for you only and you will be able to use an avatar</li>
            <li>Your comments and opinions will be posted immediately</li>
            <li>You will get additional features like reply notification and device bookmarks</li>
            <li>We care about protecting your privacy. We won't share your data</li>
        </ul>
    </div>
</div>

{{-- 3️⃣ REGISTRATION FORM --}}
<div class="max-w-[900px] mx-auto px-4 pb-[5rem]">
    <div class="bg-[#f0f0f0]">
        <div class="px-6 py-3 font-semibold text-lg">
            Create account
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           class="w-full border px-3 py-2 rounded @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           class="w-full border px-3 py-2 rounded @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input type="password"
                           name="password"
                           required
                           class="w-full border px-3 py-2 rounded @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Confirm Password</label>
                    <input type="password"
                           name="password_confirmation"
                           required
                           class="w-full border px-3 py-2 rounded">
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded font-semibold">
                    Submit
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
