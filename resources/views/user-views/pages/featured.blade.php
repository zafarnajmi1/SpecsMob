@extends('layouts.app')

@section('title', request('tag') ? 'Articles tagged ' . request('tag') : 'Featured Articles')

@section('sidebar')
    @include('partials.aside')
@endsection

@section('content')
    <section class="w-full mb-6 md:h-[310px]">
        <div class="max-w-[1060px] mx-auto overflow-hidden h-full bg-black text-white">
            <div class="relative bg-cover bg-center h-full"
                style="background-image: url('{{ asset('user/images/video.jpg') }}');">
                {{-- Dark overlay for contrast --}}
                <div class="bg-black/55 h-full">
                    <div class="px-4 md:px-6 pt-4 pb-6 space-y-4 h-full flex flex-col justify-between">

                        {{-- Top: Popular tags row --}}
                        <div class="relative border-b border-white/15 pb-3">
                            <span
                                class="inline-flex items-center text-[11px] font-semibold uppercase tracking-[0.18em] bg-black/50 px-3 py-1 rounded-full mr-3">
                                Popular tags
                            </span>

                            <ul class="mt-2 md:mt-0 inline-flex flex-wrap gap-2 align-middle">
                                @foreach ($tags as $tag)
                                    <li>
                                        <a href="{{ route('featured', ['tag' => $tag->slug]) }}"
                                            class="inline-block text-[11px] px-3 py-1 rounded-full bg-black/40 border border-white/15 hover:bg-[#F9A13D] hover:border-[#F9A13D] transition-colors">
                                            {{ $tag->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Bottom: Search band --}}
                        <div class="border-t border-white/15 pt-3">
                            <h1 class="text-3xl mb-3 md:text-4xl font-bold tracking-tight drop-shadow-md">
                                @if(request('tag'))
                                    Articles tagged "{{ request('tag') }}"
                                @elseif(request('q'))
                                    Search results for "{{ request('q') }}"
                                @else
                                    Featured Articles
                                @endif
                            </h1>
                            <form method="GET" action="{{ route('featured') }}"
                                class="flex flex-col sm:flex-row gap-2 sm:items-center">
                                <label class="flex-1 text-[12px] flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                                    <span class="font-semibold whitespace-nowrap">
                                        Search for
                                    </span>
                                    <input type="text" name="q" maxlength="50" value="{{ request('q') }}"
                                        placeholder="Device, brand or keyword"
                                        class="flex-1 px-3 py-1.5 text-[13px] rounded bg-white/95 text-[#333] placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#F9A13D]" />
                                </label>

                                <button type="submit"
                                    class="self-start sm:self-auto px-4 py-1.5 text-[12px] font-semibold uppercase rounded bg-[#F9A13D] hover:bg-[#F9A13D] text-white tracking-[0.15em]">
                                    Search
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="articles bg-[#e7e4e4] my-5 flex flex-col gap-3 p-2 md:p-3">
        @forelse ($featured_articles as $article)
            <x-article :article="$article" />
        @empty
            <div class="bg-white p-10 text-center text-gray-500 italic">
                No featured articles found
                @if(request('tag') || request('q'))
                    matching your criteria.
                @else
                    yet.
                @endif
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6 mb-10 px-2 lg:px-0">
        {{ $featured_articles->links() }}
    </div>
@endsection