@extends('layouts.app')

@section('title', "$device->name Reviews")

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
    <x-device-mobile-header :device="$device" activeTab="reviews" />



    <div
        class="flex justify-between items-center border-t border-gray-400 shadow text-white px-4 md:px-6 mt-3 py-2 bg-[#f0f0f0] backdrop-blur-sm">
        <a href="{{ route('device.opinions.post', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex justify-center items-center px-3 py-1 font-bold text-[12px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase tracking-tighter shadow-sm">
            <span>POST YOUR OPINION</span>
        </a>
        @if($comments->lastPage() > 1)
            <div class="flex items-center gap-1">
                <span class="text-[#555] text-[14px]">Pages: </span>
                @for ($i = 1; $i <= $comments->lastPage(); $i++)
                    @if ($i == $comments->currentPage())
                        <strong class="bg-[#555] border border-[#333] text-[#f0f0f0] px-[11px] py-[5px] text-[13px]">{{ $i }}</strong>
                    @else
                        <a href="{{ $comments->url($i) }}"
                            class="bg-[#f0f0f0] border border-[#ddd] text-[#555] px-[11px] py-[5px] text-[13px] hover:bg-[#F9A13D] hover:text-white transition ml-1">{{ $i }}</a>
                    @endif
                @endfor
            </div>
        @endif
    </div>

    <!-- Opinions Section -->
    <div class="bg-[#f0f0f0]">
        <div class="space-y-[1px]">
            @forelse ($comments as $comment)
                @include('partials.device-comment', ['comment' => $comment, 'device' => $device])
            @empty
                <div class="bg-white p-10 text-center text-gray-500 italic">
                    No opinions yet. Be the first to share your thoughts!
                </div>
            @endforelse
        </div>
    </div>

    <div
        class="flex justify-between items-center border-t border-gray-400 shadow px-4 md:px-6 mt-3 py-2 bg-[#f0f0f0] backdrop-blur-sm">
        <a href="{{ route('device.opinions.post', ['slug' => $device->slug, 'id' => $device->id])}}"
            class="flex justify-center items-center px-3 py-1 font-bold text-[12px] bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#F9A13D] hover:text-white transition uppercase tracking-tighter shadow-sm">
            <span>POST YOUR OPINION</span>
        </a>

        @if($comments->lastPage() > 1)
            <div class="flex items-center gap-1 ml-auto">
                <span class="text-[#555] text-[14px]">Pages: </span>
                @for ($i = 1; $i <= $comments->lastPage(); $i++)
                    @if ($i == $comments->currentPage())
                        <strong class="bg-[#555] border border-[#333] text-[#f0f0f0] px-[11px] py-[5px] text-[13px]">{{ $i }}</strong>
                    @else
                        <a href="{{ $comments->url($i) }}"
                            class="bg-[#f0f0f0] border border-[#ddd] text-[#555] px-[11px] py-[5px] text-[13px] hover:bg-[#F9A13D] hover:text-white transition ml-1">{{ $i }}</a>
                    @endif
                @endfor
            </div>
        @endif
    </div>

    @push('scripts')
    @endpush

@endsection