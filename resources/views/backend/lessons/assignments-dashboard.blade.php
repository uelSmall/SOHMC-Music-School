<div class="mx-auto max-w-7xl space-y-6">
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

    @if ($commentAssignmentId)
        @php
            $commentAssignment = $assignments->firstWhere('id', $commentAssignmentId);
        @endphp

        @if ($commentAssignment)
            <div class="soh-card p-6">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-black">Teacher Note</h2>
                        <p class="text-sm text-gray-600">Add feedback for {{ $commentAssignment->student->name }} on {{ $commentAssignment->lesson->title }}.</p>
                    </div>
                    <button type="button" wire:click="cancelComment" class="soh-btn-outline">Close</button>
                </div>

                <div class="space-y-4">
                    <textarea
                        wire:model.defer="commentBody"
                        rows="5"
                        class="soh-input w-full"
                        placeholder="Write a short progress note, encouragement, or next step..."
                    ></textarea>

                    @error('commentBody')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="flex gap-3">
                        <button type="button" wire:click="saveComment" class="soh-btn-primary">Save Note</button>
                        <button type="button" wire:click="cancelComment" class="soh-btn-outline">Cancel</button>
                    </div>
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
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-700">Teacher Note</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-700">Assigned</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($assignments as $assignment)
                        <tr class="hover:bg-gray-50 transition-colors">
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
                                <div class="space-y-2">
                                    @if ($assignment->latestComment)
                                        <p class="max-w-xs text-sm text-gray-700 line-clamp-2">{{ $assignment->latestComment->body }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $assignment->latestComment->teacher->name ?? 'Teacher' }} · {{ $assignment->latestComment->created_at->format('M d, Y') }}
                                        </p>
                                    @else
                                        <span class="text-gray-400">No note yet</span>
                                    @endif
                                    <button type="button" wire:click="startComment({{ $assignment->id }})" class="soh-link text-xs font-medium">
                                        {{ $assignment->latestComment ? 'Edit Note' : 'Add Note' }}
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $assignment->assigned_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-gray-500">No assignments yet. Click “Assign Lesson” to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
