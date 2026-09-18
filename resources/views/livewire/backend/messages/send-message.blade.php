<div class="mx-auto max-w-2xl space-y-6 px-4 sm:px-6 lg:px-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Send a Message</h1>
        <p class="text-sm text-gray-500">Leave a message for one student or the whole school. It appears in their notification bell instantly.</p>
    </div>

    <form wire:submit="send" class="soh-card space-y-5 p-6">
        <div>
            <label for="recipient" class="mb-1 block text-sm font-medium text-gray-700">Send to</label>
            <select wire:model="recipient" id="recipient" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none">
                <option value="">Choose a student…</option>
                <option value="all_students">All students (broadcast)</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                @endforeach
            </select>
            @error('recipient') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="title" class="mb-1 block text-sm font-medium text-gray-700">Subject</label>
            <input wire:model="title" type="text" id="title" maxlength="255" placeholder="e.g. Reminder: recital rehearsal Saturday" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
            @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="message" class="mb-1 block text-sm font-medium text-gray-700">Message</label>
            <textarea wire:model="message" id="message" rows="5" placeholder="Write your message here…" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none"></textarea>
            @error('message') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" wire:model="alsoEmail" class="h-4 w-4 rounded border-gray-300 text-[#A6128D] focus:ring-[#A6128D]/20" />
            Also email a copy to the student(s)
        </label>

        <div class="flex items-center gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('admin.dashboard') }}" class="soh-btn-outline">Cancel</a>
            <button type="submit" class="soh-btn-primary">Send Message</button>
        </div>
    </form>
</div>