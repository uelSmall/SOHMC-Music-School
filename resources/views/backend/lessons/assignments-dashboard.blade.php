<!-- Teacher Assignment Progress Dashboard -->
<div class="space-y-6">
    <!-- Header with Assignment Button -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Lesson Assignments</h2>
        <livewire:backend.assignments.assign-lesson-modal />
    </div>

    <!-- Assignments Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Lesson</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Due Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Assigned</th>
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
                                <span class="inline-block px-3 py-1 bg-orange-50 text-orange-700 rounded-full text-xs font-medium">
                                    {{ $assignment->due_date->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $assignment->assigned_at->format('M d, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <p class="text-gray-500">No assignments yet. Click "Assign Lesson" to get started.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Progress Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

        @foreach (['assigned' => 'Assigned', 'started' => 'Started', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $statusKey => $statusLabel)
            <div class="bg-white rounded-lg shadow p-6 border-l-4
                {{ $statusKey === 'assigned' ? 'border-gray-400' : '' }}
                {{ $statusKey === 'started' ? 'border-blue-400' : '' }}
                {{ $statusKey === 'in_progress' ? 'border-yellow-400' : '' }}
                {{ $statusKey === 'completed' ? 'border-green-400' : '' }}
            ">
                <p class="text-sm text-gray-600 font-medium">{{ $statusLabel }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $summary[$statusKey] }}</p>
            </div>
        @endforeach
    </div>
</div>
