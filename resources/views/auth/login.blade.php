@extends('layouts.app')

@section('content')

    {{-- 1️⃣ HEADER / HERO --}}
    <div class="overflow-hidden w-full md:h-[310px]">
        <div class="relative bg-cover bg-center h-full"
            style="background-image: url('https://fdn.gsmarena.com/imgroot/static/headers/glossary-hlr.jpg');">
            <div class="bg-black/10 h-full">
                <div class="px-4 md:px-6 py-6 h-full flex flex-col justify-end">
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white drop-shadow-md">
                        Log in
                    </h1>
                </div>
            </div>
        </div>
    </div>

    {{-- 2️⃣ LOGIN INFO --}}
    <div class="max-w-[900px] mb-4">
        <div class="p-6 normal-text text-sm text-gray-800">
            <h3 class="text-lg font-semibold mb-3">Welcome back</h3>
            <ul class="list-disc pl-5 space-y-2">
                <li>Log in to post comments and opinions</li>
                <li>Manage your device bookmarks and favorites</li>
                <li>Access your personalized settings and history</li>
                <li>Engage with the GSM Arena community</li>
            </ul>
        </div>
    </div>

    {{-- 3️⃣ LOGIN FORM --}}
    <div class="max-w-[900px] mx-auto px-4 pb-[5rem]">
        <div class="bg-[#f0f0f0]">
            <div class="px-6 py-3 font-semibold text-lg">
                Login to your account
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">{{ __('Email Address') }}</label>
                        <input id="email" type="email"
                            class="w-full border px-3 py-2 rounded @error('email') border-red-500 @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">{{ __('Password') }}</label>
                        <input id="password" type="password"
                            class="w-full border px-3 py-2 rounded @error('password') border-red-500 @enderror"
                            name="password" required autocomplete="current-password">

                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500" type="checkbox"
                                name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="ml-2 block text-sm text-gray-900" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-red-600 hover:text-red-800 hover:underline"
                                href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center gap-4">
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded font-semibold transition">
                            {{ __('Login') }}
                        </button>

                        <span class="text-sm text-gray-600">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-red-600 hover:underline font-semibold">Sign up</a>
                        </span>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection