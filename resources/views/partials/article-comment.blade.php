@php $isReply = isset($isReply) ? $isReply : false; @endphp

@if(!$isReply)
    <div class="flex flex-col gap-4 bg-white px-4 py-3 mb-3">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center text-white text-xl font-bold rounded shadow-sm"
                style="background-color: {{ '#' . substr(md5($comment->user->name ?? 'Anon'), 0, 6) }}">
                {{ strtoupper(substr($comment->user->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 flex flex-col">
                <div>
                    <div class="flex flex-wrap items-center justify-between gap-x-4 mb-2">
                        <div class="font-[700] text-[#555] text-[13px]">
                            <b>{{ $comment->user->name ?? 'Anonymous' }}</b>
                        </div>
                        <div class="flex gap-4">
                            <p class="text-[#777] text-[12px] font-[400]"><i class="fa-solid fa-clock mr-1"></i>
                                <time>{{ $comment->created_at->format('d M Y') }}</time>
                            </p>
                            <p class="flex items-center gap-1 text-[#777] text-[12px] font-[400]"><i
                                    class="fa-solid fa-location-dot mr-1"></i>
                                {{ $comment->user->country ?? 'Global' }}
                            </p>
                        </div>
                    </div>

                    <div class="text-[15px] leading-relaxed text-[#444] prose prose-sm max-w-none">
                        {{ $comment->body }}
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button onclick="toggleReplyForm({{ $comment->id }})"
                        class="flex justify-center items-center px-3 py-1 text-[12px] font-bold bg-[#f9f9f9] text-[#555] border border-[#d1d1d1] hover:bg-[#d50000] hover:text-white transition uppercase tracking-tighter">
                        <span>REPLY</span>
                    </button>
                </div>

                {{-- Reply Form --}}
                @auth
                    <div id="reply-form-{{ $comment->id }}"
                        class="hidden mt-4 bg-[#f8f9fa] p-4 border border-[#e9ecef] rounded transition-all">
                        <form
                            action="{{ route('comment.store', ['slug' => $article->slug, 'id' => $article->id, 'type' => 'article']) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <strong class="text-xs text-gray-500 uppercase mb-2 block">Replying to
                                {{ $comment->user->name ?? 'Anonymous' }}</strong>
                            <textarea name="comment" rows="3"
                                class="w-full p-3 text-sm border border-[#ddd] rounded-md focus:ring-1 focus:ring-[#d50000] focus:border-[#d50000] outline-none transition-all placeholder:italic"
                                placeholder="Write your reply..."></textarea>
                            <div class="flex justify-end gap-2 mt-3">
                                <button type="button" onclick="toggleReplyForm({{ $comment->id }})"
                                    class="px-3 py-1 text-xs font-bold text-gray-500 hover:text-gray-700 transition">CANCEL</button>
                                <button type="submit"
                                    class="px-4 py-1 text-xs font-bold bg-[#d50000] text-white rounded hover:bg-red-700 transition shadow-sm">POST
                                    REPLY</button>
                            </div>
                        </form>
                    </div>
                @endauth
            </div>
        </div>

        {{-- Non-Recursive Replies --}}
        @if($comment->replies && $comment->replies->count() > 0)
            <div class="mt-4 space-y-4 bg-[#f0f0f0]">
                @foreach($comment->replies as $reply)
                    @include('partials.article-comment', ['comment' => $comment, 'reply' => $reply, 'isReply' => true])
                @endforeach
            </div>
        @endif
    </div>
@else
    <div class="flex flex-col md:flex-row gap-4 bg-white px-4 py-3 ml-8 md:ml-16 border-l-4 border-gray-100 my-2">
        <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center text-white text-lg font-bold"
            style="background-color: {{ '#' . substr(md5($reply->user->name ?? 'Anonymous'), 0, 6) }}">
            {{ strtoupper(substr($reply->user->name ?? 'A', 0, 1)) }}
        </div>

        <div class="flex-1 flex flex-col">
            <div>
                <div class="flex flex-wrap items-center justify-between gap-x-4 mb-2">
                    <div class="font-[700] text-[#757575] text-[12px]">
                        {{ $reply->user->name ?? 'Anonymous' }}
                    </div>
                    <div class="flex gap-4">
                        <p class="text-[#777] text-[11px] font-[400]">
                            <i class="fa-solid fa-clock"></i>
                            <time>{{ $reply->created_at->diffForHumans() }}</time>
                        </p>
                    </div>
                </div>

                <div class="text-[14px] text-[#222]">
                    <span class="text-[#777] text-[11px] font-[400] mb-[3px] block">
                        <i class="fa-solid fa-share"></i>
                        {{ $comment->user->name ?? 'Anonymous' }},
                        <time>{{ $comment->created_at->format('d M Y') }}</time>
                    </span>

                    <span
                        class="text-[#888] bg-[#f7f7f7] p-[10px] block mb-[10px] text-[11px] font-[700] border-l-2 border-[#ddd]">
                        {{ \Illuminate\Support\Str::limit($comment->body, 150) }}
                    </span>

                    {{ $reply->body }}
                </div>
            </div>
        </div>
    </div>
@endif