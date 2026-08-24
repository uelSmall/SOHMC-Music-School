<div class="mx-auto max-w-7xl space-y-6">
    <x-frontend.breadcrumbs :items="[
        ['label' => request()->routeIs('teacher.*') ? 'Teacher Dashboard' : 'Admin Dashboard', 'route' => request()->routeIs('teacher.*') ? route('teacher.dashboard') : route('admin.dashboard')],
        ['label' => 'Lesson Assignments', 'current' => true],
    ]" />

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="soh-page-title">Lesson Assignments</h2>
            <p class="soh-page-subtitle">Track and update assignment progress for your students.</p>
        </div>
        <livewire:backend.assignments.assign-lesson-modal />
    </div>

    @php
        $summary = [
            'assigned' => 0,
            'started' => 0,
            'in_progress' => 0,
            'completed' => 0,
        ];

        foreach ($assignments as $assignment) {
            $summary[$assignment->status->value]++;
        }
    @endphp

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div class="soh-stat-card">
            <p class="soh-kpi-label">Assigned</p>
            <p class="soh-kpi-value mt-2">{{ $summary['assigned'] }}</p>
        </div>
        <div class="soh-stat-card">
            <p class="soh-kpi-label">Started</p>
            <p class="soh-kpi-value mt-2">{{ $summary['started'] }}</p>
        </div>
        <div class="soh-stat-card">
            <p class="soh-kpi-label">In Progress</p>
            <p class="soh-kpi-value mt-2">{{ $summary['in_progress'] }}</p>
        </div>
        <div class="soh-stat-card">
            <p class="soh-kpi-label">Completed</p>
            <p class="soh-kpi-value mt-2">{{ $summary['completed'] }}</p>
        </div>
    </div>

    {{-- Comment Thread Panel --}}
    @if ($commentAssignmentId)
        @php
            $commentAssignment = $assignments->firstWhere('id', $commentAssignmentId);
        @endphp

        @if ($commentAssignment)
            <div class="soh-card overflow-hidden p-0">
                <div class="flex items-center justify-between border-b px-6 py-4" style="border-color:#D991CD; background:linear-gradient(135deg, #A6128D 0%, #8C0375 100%);">
                    <div>
                        <h3 class="text-lg font-semibold text-white">Conversation</h3>
                        <p class="text-sm text-white/75">{{ $commentAssignment->lesson->title }} — {{ $commentAssignment->student->name }}</p>
                    </div>
                    <button type="button" wire:click="cancelComment" class="rounded-lg bg-white/15 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm transition hover:bg-white/25">
                        Close
                    </button>
                </div>

                {{-- Messages --}}
                <div class="flex flex-col gap-0 overflow-y-auto px-6 py-4" style="max-height:400px; background:#FAF7FC;">
                    @forelse ($comments as $comment)
                        @php
                            $isMe = (int) $comment->user_id === (int) auth()->id();
                        @endphp
                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} py-1.5">
                            <div class="max-w-[75%] {{ $isMe ? 'order-2' : 'order-1' }}">
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
                            <p class="text-sm text-gray-400">No messages yet. Start the conversation below.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Input --}}
                <div class="border-t px-6 py-4" style="border-color:#D991CD; background:#FFFFFF;">
                    <form wire:submit="saveComment" class="flex gap-3">
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
        @endif
    @endif

    <div class="soh-card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background:#F2F2F2; border-bottom:1px solid #D991CD;">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-700">Lesson</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-700">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-700">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-700">Messages</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-700">Assigned</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($assignments as $assignment)
                        <tr class="hover:bg-gray-50 transition-colors {{ $commentAssignmentId === $assignment->id ? 'bg-[#A6128D]/5' : '' }}">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <strong>{{ $assignment->lesson->title }}</strong>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $assignment->student->name }}
                            </td>
                            <td class="px-6 py-4">
                                <livewire:backend.assignments.update-assignment-status
                                    :key="'assignment-' . $assignment->id"
                                    :assignment="$assignment"
                                />
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if ($assignment->due_date)
                                    <span class="inline-block rounded-full px-3 py-1 text-xs font-medium" style="background:#F2F2F2; color:#A6128D; border:1px solid #D991CD;">
                                        {{ $assignment->due_date->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <button type="button" wire:click="startComment({{ $assignment->id }})" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition-all hover:opacity-80"
                                    style="{{ $commentAssignmentId === $assignment->id ? 'background:#A6128D; color:white;' : 'background:#F2F2F2; color:#A6128D; border:1px solid #D991CD;' }}"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    @if ($assignment->latestComment)
                                        Reply
                                    @else
                                        Message
                                    @endif
                                </button>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $assignment->assigned_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-gray-500">No assignments yet. Click "Assign Lesson" to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
