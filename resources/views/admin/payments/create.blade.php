<x-layouts.admin :title="'Record Payment'">
    <div class="mx-auto max-w-2xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Record Payment</h1>
            <p class="text-sm text-gray-500">Enter the payment details — a receipt is generated and emailed to the student automatically.</p>
        </div>

        <form method="POST" action="{{ route('admin.payments.store') }}" class="soh-card space-y-5 p-6">
            @csrf

            <div>
                <label for="student_id" class="mb-1 block text-sm font-medium text-gray-700">Student</label>
                <select name="student_id" id="student_id" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none">
                    <option value="">Select a student…</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }} ({{ $student->email }})</option>
                    @endforeach
                </select>
                @error('student_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="amount" class="mb-1 block text-sm font-medium text-gray-700">Amount (TT$)</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0.01" required placeholder="0.00" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                    @error('amount') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="payment_method" class="mb-1 block text-sm font-medium text-gray-700">Method</label>
                    <select name="payment_method" id="payment_method" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none">
                        <option value="Cash">Cash</option>
                        <option value="Card">Card</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Online Transfer">Online Transfer</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('payment_method') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="payment_date" class="mb-1 block text-sm font-medium text-gray-700">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                    @error('payment_date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="reference" class="mb-1 block text-sm font-medium text-gray-700">Reference <span class="text-gray-400">(optional)</span></label>
                    <input type="text" name="reference" id="reference" value="{{ old('reference') }}" placeholder="e.g. transfer ID" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
                    @error('reference') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="notes" class="mb-1 block text-sm font-medium text-gray-700">Notes <span class="text-gray-400">(optional — shown on the receipt)</span></label>
                <textarea name="notes" id="notes" rows="3" placeholder="e.g. March music tuition" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none">{{ old('notes') }}</textarea>
                @error('notes') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 border-t border-gray-100 pt-5">
                <a href="{{ route('admin.payments.index') }}" class="soh-btn-outline">Cancel</a>
                <button type="submit" class="soh-btn-primary">Record &amp; Send Receipt</button>
            </div>
        </form>
    </div>
</x-layouts.admin>