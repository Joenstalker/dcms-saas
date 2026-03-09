@extends('layouts.tenant')

@section('content')
<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 no-print">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Invoice Details</h1>
            <p class="text-sm text-base-content/60">Manage payments and print receipts.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="btn btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Invoice
            </button>
            <a href="{{ route('tenant.billing.index', $tenant->slug) }}" class="btn btn-ghost">Back to List</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Invoice (Printable) -->
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden print:shadow-none print:border-0" id="printable-invoice">
                <!-- Branding Header -->
                <div class="p-8 bg-base-200/30 flex justify-between items-start border-b border-base-200">
                    <div class="flex items-center gap-4">
                        @php
                            $tenantSettings = \App\Models\TenantSetting::where('tenant_id', $tenant->id)->first();
                            $logoUrl = $tenantSettings?->getLogoUrl();
                        @endphp
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $tenant->name }}" class="h-16 w-auto object-contain">
                        @endif
                        <div>
                            <h2 class="text-2xl font-black uppercase tracking-tight">{{ $tenant->name }}</h2>
                            <p class="text-xs opacity-70 max-w-xs">{{ $tenant->address }}</p>
                            <p class="text-xs opacity-70">{{ $tenant->phone }} | {{ $tenant->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-black text-primary/20 uppercase mb-1">Invoice</div>
                        <div class="font-bold text-lg">{{ $invoice->invoice_number }}</div>
                        <div class="text-xs opacity-60">Date: {{ $invoice->created_at->format('M d, Y') }}</div>
                        
                        @php
                            $statusClass = match($invoice->status) {
                                'Paid' => 'badge-success',
                                'Partial' => 'badge-warning',
                                'Unpaid' => 'badge-error',
                                default => 'badge-ghost',
                            };
                        @endphp
                        <div class="badge {{ $statusClass }} mt-2 font-bold">{{ $invoice->status }}</div>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Bill To -->
                    <div class="grid grid-cols-2 gap-8 mb-10">
                        <div>
                            <div class="text-[10px] uppercase font-bold tracking-widest text-base-content/40 mb-2">Patient Details</div>
                            <div class="font-bold text-lg text-primary">{{ $invoice->patient->last_name }}, {{ $invoice->patient->first_name }}</div>
                            <div class="text-sm opacity-70">{{ $invoice->patient->address }}</div>
                            <div class="text-sm opacity-70">{{ $invoice->patient->phone }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[10px] uppercase font-bold tracking-widest text-base-content/40 mb-2">Summary</div>
                            <div class="space-y-1">
                                <div class="flex justify-end gap-4 text-sm">
                                    <span class="opacity-60">Total Amount:</span>
                                    <span class="font-medium">₱{{ number_format($invoice->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-end gap-4 text-sm">
                                    <span class="opacity-60">Discount:</span>
                                    <span class="text-error">-₱{{ number_format($invoice->discount_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-end gap-4 text-lg font-bold border-t border-base-200 pt-1 mt-1">
                                    <span>Grand Total:</span>
                                    <span class="text-primary">₱{{ number_format($invoice->grand_total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <table class="table w-full mb-10">
                        <thead class="bg-base-200/50">
                            <tr>
                                <th class="rounded-l-lg">Procedure / Service</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right rounded-r-lg">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                            <tr class="hover:bg-base-200/20 transition-colors">
                                <td class="font-bold">{{ $item->service_name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-right font-bold">₱{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($invoice->notes)
                    <div class="bg-base-200/30 p-4 rounded-lg text-sm italic mb-10">
                        <span class="font-bold not-italic block mb-1">Notes:</span>
                        {{ $invoice->notes }}
                    </div>
                    @endif

                    <!-- Signature Section (Print only) -->
                    <div class="hidden print:grid grid-cols-2 gap-20 mt-20">
                        <div class="text-center">
                            <div class="border-b border-base-content w-full h-8"></div>
                            <div class="text-[10px] uppercase mt-2">Patient Signature</div>
                        </div>
                        <div class="text-center">
                            <div class="border-b border-base-content w-full h-8"></div>
                            <div class="text-[10px] uppercase mt-2">Authorized Personnel</div>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-primary text-primary-content text-center text-xs">
                    Thank you for choosing {{ $tenant->name }}!
                </div>
            </div>
        </div>

        <!-- Sidebar (Payments & History) -->
        <div class="lg:col-span-1 space-y-6 no-print">
            <!-- Payment Panel -->
            @if($invoice->status !== 'Paid')
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold mb-4">Record Payment</h2>
                    <form action="{{ route('tenant.billing.payment', [$tenant->slug, $invoice->id]) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="form-control">
                            <label class="label"><span class="label-text">Amount Due: <span class="font-bold text-primary">₱{{ number_format($invoice->getAmountDue(), 2) }}</span></span></label>
                            <div class="join w-full">
                                <span class="join-item btn btn-disabled bg-base-200 border-base-300">₱</span>
                                <input type="number" name="amount_paid" step="0.01" value="{{ $invoice->getAmountDue() }}" class="input input-bordered join-item w-full font-bold" required>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">Method</span></label>
                            <select name="payment_method" class="select select-bordered" required>
                                <option value="Cash">Cash</option>
                                <option value="GCash">GCash</option>
                                <option value="Card">Credit/Debit Card</option>
                                <option value="Check">Check</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">Reference No. (Optional)</span></label>
                            <input type="text" name="reference_number" class="input input-bordered" placeholder="e.g. GCash ID, Check No.">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-4">Record Payment</button>
                    </form>
                </div>
            </div>
            @else
            <div class="alert alert-success shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="font-bold uppercase tracking-wide">Fully Paid</span>
            </div>
            @endif

            <!-- Payment History -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold mb-4">Payment History</h2>
                    @if($invoice->payments->count() > 0)
                        <div class="space-y-4">
                            @foreach($invoice->payments->sortByDesc('transaction_date') as $payment)
                            <div class="flex justify-between items-start border-l-4 border-primary pl-4 py-1">
                                <div>
                                    <div class="font-bold">₱{{ number_format($payment->amount_paid, 2) }}</div>
                                    <div class="text-xs opacity-60">{{ $payment->payment_method }} • {{ $payment->transaction_date->format('M d, H:i') }}</div>
                                    @if($payment->reference_number)
                                        <div class="text-[10px] bg-base-200 rounded px-1 mt-1 inline-block">Ref: {{ $payment->reference_number }}</div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 opacity-40 text-sm">No payments recorded yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #printable-invoice, #printable-invoice * { visibility: visible; }
    #printable-invoice {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none !important;
    }
    .no-print { display: none !important; }
}
</style>
@endsection
