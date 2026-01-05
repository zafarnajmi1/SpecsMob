@extends('layouts.app')

@section('title', Auth::user()->name . ' - Account Settings')

@section('content')

{{-- ================= HEADER (already correct) ================= --}}
<div class="relative w-full h-72 md:h-[314px] bg-cover bg-center mb-[10px]"
     style='background-image: url("{{ asset('user/images/account.jpg') }}");'>

    <div class="absolute bottom-0 left-0 w-full z-10">
        <h1 class="text-white text-[40px] font-bold px-5 mb-3">
            Account Settings
        </h1>

        <div class="flex justify-end items-center text-sm text-white px-6 h-10
                    bg-[rgba(0,0,0,0.25)] backdrop-blur-sm">
            <div class="flex gap-4 h-full">
                <a href="{{ route('user.posts', Auth::user()->username) }}"
                   class="flex items-center gap-1 hover:bg-[#F9A13D] px-3 transition">
                    <i class="fa-regular fa-message"></i>
                    POSTS {{ $posts_count }}
                </a>

                <span class="flex items-center gap-1 px-3">
                    <i class="fa-solid fa-hand-sparkles"></i>
                    UPVOTES {{ $upvotes }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ================= MAIN CONTENT ================= --}}
<div class="max-w-[900px] mx-auto px-4 py-8 space-y-10">

    {{-- ===== Change Avatar ===== --}}
 <section class="bg-white border rounded shadow-sm p-6">
    <h3 class="text-lg font-semibold mb-4">Change your avatar</h3>

    <form method="POST" action="{{ route('user.avatar.update', Auth::user()->username) }}">
        @csrf

        <p class="font-medium mb-3">Choose an option</p>

        {{-- NO AVATAR --}}
        <label class="flex items-center gap-2 mb-3">
            <input type="radio"
                   name="avatar_type"
                   value="none"
                   {{ Auth::user()->avatar_type === 'none' ? 'checked' : '' }}>
            <span>No avatar</span>
        </label>

        {{-- GRAVATAR --}}
        <label class="block mb-4">
            <div class="flex items-center gap-2">
                <input type="radio"
                       name="avatar_type"
                       value="gravatar"
                       {{ Auth::user()->avatar_type === 'gravatar' ? 'checked' : '' }}>
                <span>Gravatar (enter email)</span>
            </div>

            <input type="email"
       name="gravatar_email"
       value="{{ Auth::user()->gravatar_email }}"
       class="mt-2 w-full border px-3 py-2 rounded"
       placeholder="your@email.com"
       {{ Auth::user()->avatar_type !== 'gravatar' ? 'disabled' : '' }}>


            <a href="https://en.gravatar.com/" target="_blank"
               class="text-sm text-blue-600 underline mt-1 inline-block">
                Register on Gravatar
            </a>
        </label>

        {{-- AVATAR PREVIEW --}}
        <div class="flex items-center gap-4 mb-4">
            <span class="text-sm text-gray-600">Preview:</span>

            @if(Auth::user()->avatar_type === 'gravatar' && Auth::user()->gravatar_email)
                <img
  src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->gravatar_email))) }}?s=80&d=identicon&v={{ time() }}"
  class="w-16 h-16 rounded-full border">

            @else
                <i class="fa-solid fa-user-circle text-[64px] text-gray-400"></i>
            @endif
        </div>

        <div class="flex justify-end">
            <button class="bg-[#F9A13D] cursor-pointer text-white px-5 py-2 rounded hover:bg-[#F9A13D]">
                Submit
            </button>
        </div>
    </form>
</section>


    {{-- ===== Change Nickname ===== --}}
    <section class="bg-white border rounded shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Change your nickname</h3>

        <form method="POST" action="{{ route('user.nickname.update', Auth::user()->username) }}">
            @csrf

            <input type="text"
                   name="nickname"
                   value="{{ Auth::user()->username }}"
                   required
                   minlength="2"
                   maxlength="20"
                   autocomplete="off"
                   class="w-full border px-3 py-2 rounded mb-4">

            <div class="flex justify-end">
                <button class="bg-[#F9A13D] cursor-pointer text-white px-5 py-2 rounded hover:bg-[#F9A13D]">
                    Change
                </button>
            </div>
        </form>
    </section>

    {{-- ===== Download Data ===== --}}
    <section class="bg-white border rounded shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-2">Download your data</h3>
        <p class="text-sm text-gray-600 mb-4">
            You have access to all the data we stored for you in machine readable format.
        </p>

        <form method="POST" action="{{ route('user.data.download', Auth::user()->username) }}">
            @csrf
            <div class="flex justify-end">
                <button class="bg-gray-700 cursor-pointer text-white px-5 py-2 rounded hover:bg-gray-800">
                    Download
                </button>
            </div>
        </form>
    </section>

    {{-- ===== Freeze Account ===== --}}
    <section class="bg-white border rounded shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-2">Freeze your account</h3>
        <p class="text-sm text-gray-600 mb-4">
            This will hide all your personal data. You will not be able to post until you unfreeze.
        </p>

        <form method="POST" action="">
            @csrf
            <div class="flex justify-end">
                <button class="bg-yellow-600 cursor-pointer text-white px-5 py-2 rounded hover:bg-yellow-700">
                    Freeze
                </button>
            </div>
        </form>
    </section>

    {{-- ===== Delete Account ===== --}}
    <section class="bg-white border rounded shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-2 text-[#F9A13D]">Delete your account</h3>
        <p class="text-sm text-gray-600 mb-4">
            This will permanently delete your account and all personal data.
        </p>

        <form method="POST" action=""
              onsubmit="return confirm('This action is permanent. Are you sure?')">
            @csrf
            @method('DELETE')

            <div class="flex justify-end">
                <button class="bg-[#F9A13D] cursor-pointer text-white px-5 py-2 rounded hover:bg-[#F9A13D]">
                    Delete
                </button>
            </div>
        </form>
    </section>

</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const gravatarRadio = document.querySelector('input[name="avatar_type"][value="gravatar"]');
    const noneRadio = document.querySelector('input[name="avatar_type"][value="none"]');
    const emailInput = document.querySelector('input[name="gravatar_email"]');

    function toggleEmail() {
        if (gravatarRadio.checked) {
            emailInput.disabled = false;
            emailInput.focus();
        } else {
            emailInput.disabled = true;
            emailInput.value = '';
        }
    }

    gravatarRadio.addEventListener('change', toggleEmail);
    noneRadio.addEventListener('change', toggleEmail);

    // Run on page load
    toggleEmail();
});
</script>

@endpush