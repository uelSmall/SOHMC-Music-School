<x-layouts.admin :title="'Receipt ' . $payment->receipt_number">
    <div class="mx-auto max-w-3xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Receipt</h1>
                <p class="text-sm text-gray-500">{{ $payment->receipt_number }} · {{ $payment->student->name }}</p>
            </div>
            <div class="flex shrink-0 gap-2">
                <a href="{{ route('admin.payments.download', $payment) }}" class="soh-btn-primary">Download PDF</a>
                <form method="POST" action="{{ route('admin.payments.resend', $payment) }}">
                    @csrf
                    <button type="submit" class="soh-btn-outline">Email Again</button>
                </form>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            @php $logoBase64 = \Illuminate\Support\Facades\File::exists(public_path('img/sohmc-piano-icon.png')) ? 'data:image/png;base64,'.base64_encode(\Illuminate\Support\Facades\File::get(public_path('img/sohmc-piano-icon.png'))) : ''; @endphp
            <div class="max-h-[75vh] overflow-y-auto">
                @include('receipts.receipt-content', compact('logoBase64'))
            </div>
        </div>
    </div>
</x-layouts.admin>