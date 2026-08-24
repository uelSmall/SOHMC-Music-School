<div class="soh-card overflow-hidden p-0" x-data="{ open: false }">
    <button type="button" @click="open = !open" class="flex w-full items-center justify-between px-6 py-4 transition hover:bg-gray-50">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-full" style="background:linear-gradient(135deg, #A6128D, #8C0375);">
                <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <div class="text-left">
                <p class="text-sm font-semibold text-gray-900">Messages with Teacher</p>
                <p class="text-xs text-gray-500">{{ $comments->count() }} {{ Str::plural('message', $comments->count()) }}</p>
            </div>
        </div>
        <svg class="h-5 w-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak>
        {{-- Messages --}}
        <div class="overflow-y-auto border-t px-6 py-4" style="max-height:350px; border-color:#D991CD; background:#FAF7FC;">
            @forelse ($comments as $comment)
                @php
                    $isMe = (int) $comment->user_id === (int) auth()->id();
                @endphp
                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} py-1.5">
                    <div class="max-w-[80%]">
                        <div class="flex items-center gap-1.5 {{ $isMe ? 'justify-end' : '' }} mb-1">
                            <span class="text-[11px] font-semibold {{ $isMe ? 'text-[#A6128D]' : 'text-gray-600' }}">
                                {{ $comment->user->name }}
                            </span>
                            <span class="text-[10px] text-gray-400">
                                {{ $comment->created_at->format('M d, g:ia') }}
                            </span>
                        </div>
                        <div class="rounded-2xl px-4 py-2.5 text-sm leading-relaxed
                            {{ $isMe
                                ? 'rounded-br-md text-white'
                                : 'rounded-bl-md border text-gray-800'
                            }}"
                            style="{{ $isMe
                                ? 'background:linear-gradient(135deg, #A6128D, #8C0375);'
                                : 'border-color:#D991CD; background:#FFFFFF;'
                            }}"
                        >
                            {{ $comment->body }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center">
                    <p class="text-sm text-gray-400">No messages yet. Say hello to your teacher!</p>
                </div>
            @endforelse
        </div>

        {{-- Input --}}
        <div class="border-t px-6 py-4" style="border-color:#D991CD; background:#FFFFFF;">
            <form wire:submit="sendComment" class="flex gap-3">
                <input
                    type="text"
                    wire:model="commentBody"
                    placeholder="Type a message..."
                    class="flex-1 rounded-xl border px-4 py-2.5 text-sm outline-none transition-all focus:ring-2"
                    style="border-color:#D991CD; --tw-ring-color:rgba(166,18,141,0.2);"
                    autocomplete="off"
                />
                <button type="submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white transition-all hover:opacity-90" style="background:#A6128D;">
                    Send
                </button>
            </form>
            @error('commentBody')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
