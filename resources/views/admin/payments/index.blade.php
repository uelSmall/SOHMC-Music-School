<x-layouts.admin :title="'Payments'">
    <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Payments &amp; Receipts</h1>
                <p class="text-sm text-gray-500">Record a payment and instantly send a receipt to the student's phone.</p>
            </div>
            <a href="{{ route('admin.payments.create') }}" class="soh-btn-primary shrink-0">Record Payment</a>
        </div>

        <form method="GET" action="{{ route('admin.payments.index') }}" class="max-w-sm">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student or receipt no..." class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm transition focus:border-[#A6128D] focus:ring-2 focus:ring-[#A6128D]/20 focus:outline-none" />
        </form>

        <div class="soh-card overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400">
                        <th class="px-6 py-3">Receipt No.</th>
                        <th class="px-6 py-3">Student</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Method</th>
                        <th class="px-6 py-3 text-right">Amount</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $payment)
                        <tr class="transition hover:bg-gray-50/50">
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-[#A6128D]">{{ $payment->receipt_number }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $payment->student->name }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $payment->payment_method }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900">{{ $payment->amount_formatted }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="rounded-lg px-3 py-1.5 text-sm font-medium text-[#A6128D] hover:bg-[#A6128D]/5">Preview</a>
                                    <a href="{{ route('admin.payments.download', $payment) }}" class="rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600 hover:bg-gray-100">PDF</a>
                                    <form method="POST" action="{{ route('admin.payments.resend', $payment) }}">
                                        @csrf
                                        <button type="submit" class="rounded-lg px-3 py-1.5 text-sm font-medium text-green-700 hover:bg-green-50">Email</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <p class="text-sm font-medium text-gray-500">No payments yet</p>
                                <p class="mt-1 text-xs text-gray-400">Record the first payment to generate a receipt.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($payments->hasPages())
                <div class="border-t border-gray-100 px-6 py-4">{{ $payments->links() }}</div>
            @endif
        </div>
    </div>
</x-layouts.admin>