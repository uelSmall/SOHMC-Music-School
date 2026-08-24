<div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false" wire:poll.30s>
    <button
        type="button"
        @click="open = ! open"
        class="relative inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white transition hover:bg-white/20"
        aria-label="Open notifications"
        :aria-expanded="open.toString()"
    >
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 17.25a2.25 2.25 0 0 1-4.5 0m9-3.75V11a6.75 6.75 0 1 0-13.5 0v2.5L4.5 16.5h15l-1.75-3Z" />
        </svg>

        @if ($unreadCount > 0)
            <span class="absolute -right-0.5 -top-0.5 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-white px-1.5 text-[11px] font-bold text-[color:var(--soh-purple)] shadow">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Desktop dropdown --}}
    <div
        x-cloak
        x-show="open"
        x-transition.origin.top.right
        @click.outside="open = false"
        class="absolute right-0 top-[calc(100%+0.75rem)] z-50 hidden w-[22rem] overflow-hidden rounded-2xl border border-[color:var(--soh-gray)]/35 bg-white shadow-[0_22px_50px_rgba(13,13,13,0.16)] md:block"
    >
        <div class="flex items-center justify-between border-b border-[color:var(--soh-gray)]/25 px-4 py-3">
            <div>
                <p class="text-sm font-semibold text-[color:var(--soh-black)]">Notifications</p>
                <p class="text-xs text-gray-500">{{ $unreadCount }} unread</p>
            </div>
            <a href="{{ route('notifications.index') }}" class="soh-link text-xs" @click="open = false">View all</a>
        </div>

        <div class="max-h-96 divide-y divide-[color:var(--soh-gray)]/20 overflow-y-auto">
            @forelse ($recentNotifications as $notification)
                @php
                    $data = $notification->data ?? [];
                    $isRead = filled($notification->read_at);
                @endphp

                <a href="{{ route('notifications.open', $notification->id) }}" @click="open = false" class="block px-4 py-3 transition hover:bg-[color:var(--soh-surface)]">
                    <div class="flex gap-3">
                        <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full {{ $isRead ? 'bg-gray-300' : 'bg-[color:var(--soh-purple)]' }}"></span>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-sm font-semibold text-[color:var(--soh-black)]">{{ $data['title'] ?? 'Notification' }}</p>
                                <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $isRead ? 'bg-gray-100 text-gray-500' : 'bg-purple-100 text-[color:var(--soh-purple)]' }}">
                                    {{ $isRead ? 'Read' : 'New' }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs leading-5 text-gray-600">{{ $data['message'] ?? '' }}</p>
                            <p class="mt-2 text-[11px] text-gray-400">{{ $notification->created_at?->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-8 text-center text-sm text-gray-500">
                    You do not have any notifications yet.
                </div>
            @endforelse
        </div>

        <div class="border-t border-[color:var(--soh-gray)]/20 bg-[color:var(--soh-surface)] px-4 py-3">
            <a href="{{ route('notifications.index') }}" class="soh-btn-primary block w-full px-4 py-2.5 text-center text-sm" @click="open = false">
                My Notifications
            </a>
        </div>
    </div>

    {{-- Mobile: full-width bottom sheet --}}
    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[999] bg-black/40 md:hidden"
        @click="open = false"
    >
        <div
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="absolute inset-x-0 bottom-0 max-h-[85vh] overflow-hidden rounded-t-3xl bg-white shadow-2xl"
            @click.stop
        >
            {{-- Handle bar --}}
            <div class="flex justify-center pt-3">
                <div class="h-1 w-10 rounded-full bg-gray-300"></div>
            </div>

            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                <div>
                    <p class="text-base font-semibold text-[color:var(--soh-black)]">Notifications</p>
                    <p class="text-xs text-gray-500">{{ $unreadCount }} unread</p>
                </div>
                <button @click="open = false" class="flex h-9 w-9 items-center justify-center rounded-full text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Close">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Notification list --}}
            <div class="max-h-[60vh] overflow-y-auto overscroll-contain">
                @forelse ($recentNotifications as $notification)
                    @php
                        $data = $notification->data ?? [];
                        $isRead = filled($notification->read_at);
                    @endphp

                    <a href="{{ route('notifications.open', $notification->id) }}" @click="open = false" class="block border-b border-gray-100 px-5 py-4 transition active:bg-gray-50">
                        <div class="flex gap-3">
                            <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $isRead ? 'bg-gray-300' : 'bg-[color:var(--soh-purple)]' }}"></span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-sm font-semibold text-[color:var(--soh-black)]">{{ $data['title'] ?? 'Notification' }}</p>
                                    <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $isRead ? 'bg-gray-100 text-gray-500' : 'bg-purple-100 text-[color:var(--soh-purple)]' }}">
                                        {{ $isRead ? 'Read' : 'New' }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm leading-relaxed text-gray-600">{{ $data['message'] ?? '' }}</p>
                                <p class="mt-2 text-xs text-gray-400">{{ $notification->created_at?->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-12 text-center text-sm text-gray-500">
                        <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 17.25a2.25 2.25 0 0 1-4.5 0m9-3.75V11a6.75 6.75 0 1 0-13.5 0v2.5L4.5 16.5h15l-1.75-3Z" />
                        </svg>
                        You do not have any notifications yet.
                    </div>
                @endforelse
            </div>

            {{-- Footer --}}
            <div class="border-t border-gray-200 bg-gray-50 px-5 py-4 safe-area-bottom">
                <a href="{{ route('notifications.index') }}" @click="open = false" class="soh-btn-primary block w-full px-4 py-3 text-center text-sm">
                    My Notifications
                </a>
            </div>
        </div>
    </div>
</div>
