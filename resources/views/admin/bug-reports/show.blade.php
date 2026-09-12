<x-layouts.admin :title="'Bug Report #' . $report->id">
    <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
        <x-frontend.breadcrumbs :items="[
            ['label' => 'Admin Dashboard', 'route' => route('admin.dashboard')],
            ['label' => 'Bug Reports', 'route' => route('admin.bug-reports.index')],
            ['label' => 'Bug Report #' . $report->id, 'current' => true],
        ]" />

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Bug Report #{{ $report->id }}</h1>
                <p class="mt-1 text-sm text-gray-500">Reported {{ $report->created_at->format('M d, Y g:ia') }}</p>
            </div>
            <span class="inline-flex w-fit items-center rounded-full px-3 py-1 text-sm font-semibold {{ $report->statusColor() }}">
                {{ $report->statusLabel() }}
            </span>
        </div>

        {{-- Details card --}}
        <div class="soh-card overflow-hidden">
            <div class="grid grid-cols-2 gap-4 border-b border-gray-100 bg-gray-50/50 px-6 py-4 sm:grid-cols-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Reporter</p>
                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $report->user?->name ?? 'Deleted User' }}</p>
                    <p class="text-xs text-gray-500">{{ $report->user?->email }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Browser</p>
                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $report->browser ?? 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Page</p>
                    <p class="mt-1 break-all text-sm font-medium text-gray-900">{{ $report->page ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Resolved</p>
                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $report->resolved_at?->format('M d, Y') ?? '—' }}</p>
                </div>
            </div>

            <div class="px-6 py-6">
                <h2 class="text-lg font-semibold text-gray-900">{{ $report->title }}</h2>
                <div class="mt-4 whitespace-pre-line rounded-xl bg-[#F2F2F2] p-5 text-sm leading-relaxed text-gray-700">
                    {{ $report->description }}
                </div>
            </div>
        </div>

        {{-- Update form --}}
        <div class="soh-card p-6">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Update Status</h2>
            <form method="POST" action="{{ route('admin.bug-reports.update', $report) }}">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="status" class="text-sm font-semibold text-gray-800">Status</label>
                        <select id="status" name="status" class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm transition focus:border-[#A6128D] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#A6128D]/20">
                            @foreach(App\Models\BugReport::STATUSES as $status)
                                <option value="{{ $status }}" {{ $report->status === $status ? 'selected' : '' }}>
                                    {{ Str::headline(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="admin_notes" class="text-sm font-semibold text-gray-800">Internal Notes (optional)</label>
                        <textarea id="admin_notes" name="admin_notes" rows="4" maxlength="5000" placeholder="Add notes about the fix, reproduction steps, etc."
                                  class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm transition focus:border-[#A6128D] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#A6128D]/20">{{ old('admin_notes', $report->admin_notes) }}</textarea>
                        @error('admin_notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-5 flex justify-end">
                    <button type="submit" class="soh-btn-primary">Save Changes</button>
                </div>
            </form>
        </div>

        {{-- Existing replies/notes --}}
        @if($report->handler)
            <div class="rounded-xl border border-[color:var(--soh-gray)]/25 bg-[#F2F2F2] p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Last handled by</p>
                <p class="mt-1 text-sm font-medium text-gray-800">{{ $report->handler->name }} on {{ $report->updated_at->format('M d, Y g:ia') }}</p>
            </div>
        @endif
    </div>
</x-layouts.admin>