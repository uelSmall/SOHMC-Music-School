<div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false" wire:poll.30s>
    <button
        type="button"
        @click="open = ! open"
        class="relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white transition hover:bg-white/20"
        aria-label="Open notifications"
        :aria-expanded="open.toString()"
    >
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 17.25a2.25 2.25 0 0 1-4.5 0m9-3.75V11a6.75 6.75 0 1 0-13.5 0v2.5L4.5 16.5h15l-1.75-3Z" />
        </svg>

        @if ($unreadCount > 0)
            <span class="absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-white px-1.5 text-[11px] font-bold text-[color:var(--soh-purple)] shadow">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div
        x-cloak
        x-show="open"
        x-transition.origin.top.right
        @click.outside="open = false"
        class="absolute right-0 top-[calc(100%+0.75rem)] z-50 w-[22rem] overflow-hidden rounded-2xl border border-[color:var(--soh-gray)]/35 bg-white shadow-[0_22px_50px_rgba(13,13,13,0.16)]"
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
                        <span class="mt-1 h-2.5 w-2.5 rounded-full {{ $isRead ? 'bg-gray-300' : 'bg-[color:var(--soh-purple)]' }}"></span>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <p class="text-sm font-semibold text-[color:var(--soh-black)]">{{ $data['title'] ?? 'Notification' }}</p>
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $isRead ? 'bg-gray-100 text-gray-500' : 'bg-purple-100 text-[color:var(--soh-purple)]' }}">
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
            <a href="{{ route('notifications.index') }}" class="soh-btn-primary w-full px-4 py-2 text-sm" @click="open = false">
                My Notifications
            </a>
        </div>
    </div>
</div>