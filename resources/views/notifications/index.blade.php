@extends('layouts.app')

@section('content')
    @php
        if (auth()->user()->hasRole('teacher')) {
            $breadcrumbItems = [
                ['label' => 'Teacher Dashboard', 'route' => route('teacher.dashboard')],
                ['label' => 'My Notifications', 'current' => true],
            ];
        } elseif (auth()->user()->hasRole('student')) {
            $breadcrumbItems = [
                ['label' => 'Student Dashboard', 'route' => route('student.dashboard')],
                ['label' => 'My Notifications', 'current' => true],
            ];
        } elseif (auth()->user()->hasRole('parent')) {
            $breadcrumbItems = [
                ['label' => 'Parent Dashboard', 'route' => route('parent.dashboard')],
                ['label' => 'My Notifications', 'current' => true],
            ];
        } else {
            $breadcrumbItems = [
                ['label' => 'Dashboard', 'route' => route(auth()->user()->dashboardRouteName())],
                ['label' => 'My Notifications', 'current' => true],
            ];
        }
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="$breadcrumbItems" />

        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="soh-page-title">My Notifications</h1>
                <p class="soh-page-subtitle">Keep track of lesson updates, request status changes, and booking activity.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="soh-btn-primary px-5 py-3 text-sm">
                        Mark All as Read
                    </button>
                </form>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <article class="soh-card p-5">
                <p class="text-sm font-medium text-gray-500">Total</p>
                <p class="mt-2 text-3xl font-semibold text-[color:var(--soh-black)]">{{ $notifications->total() }}</p>
            </article>
            <article class="soh-card p-5">
                <p class="text-sm font-medium text-gray-500">Unread</p>
                <p class="mt-2 text-3xl font-semibold text-[color:var(--soh-black)]">{{ $unreadCount }}</p>
            </article>
            <article class="soh-card p-5">
                <p class="text-sm font-medium text-gray-500">Read</p>
                <p class="mt-2 text-3xl font-semibold text-[color:var(--soh-black)]">{{ max($notifications->total() - $unreadCount, 0) }}</p>
            </article>
        </div>

        <div class="mt-8 space-y-4">
            @forelse ($notifications as $notification)
                @php
                    $data = $notification->data ?? [];
                    $isRead = filled($notification->read_at);
                @endphp

                <article class="soh-card border-l-4 p-5 {{ $isRead ? 'border-l-gray-200' : 'border-l-[color:var(--soh-purple)]' }}">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-lg font-semibold text-[color:var(--soh-black)]">{{ $data['title'] ?? 'Notification' }}</h2>
                                <span class="rounded-full px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide {{ $isRead ? 'bg-gray-100 text-gray-500' : 'bg-purple-100 text-[color:var(--soh-purple)]' }}">
                                    {{ $isRead ? 'Read' : 'Unread' }}
                                </span>
                            </div>

                            <p class="mt-2 text-sm leading-6 text-gray-700">{{ $data['message'] ?? '' }}</p>

                            <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-gray-500">
                                <span>Created {{ $notification->created_at?->format('M d, Y g:i A') }}</span>
                                <span>{{ $notification->created_at?->diffForHumans() }}</span>
                                @if (! empty($data['lesson_request_id']))
                                    <span>Request #{{ $data['lesson_request_id'] }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('notifications.open', $notification->id) }}" class="soh-btn-primary px-4 py-2 text-sm">
                                Open
                            </a>

                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="soh-btn-outline px-4 py-2 text-sm">
                                    Mark as Read
                                </button>
                            </form>

                            <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" onsubmit="return confirm('Delete this notification?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-md border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="soh-card p-8 text-center text-sm text-gray-600">
                    You have no notifications yet.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection