<div class="flex items-center justify-between mb-6">
    <h3 class="text-lg font-bold flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Payment & Billing History
    </h3>
    <div class="text-right">
        <div class="text-[10px] uppercase opacity-50 font-bold tracking-wider">Current Balance</div>
        <div class="text-xl font-black text-error" id="profile-balance-display">Php {{ number_format($patient->balance ?? 0, 2) }}</div>
    </div>
</div>

<div class="overflow-x-auto">
    <table class="table table-md w-full">
        <thead>
            <tr class="bg-base-200/50">
                <th class="text-[10px] uppercase font-bold opacity-50 pl-4">Date</th>
                <th class="text-[10px] uppercase font-bold opacity-50">Description</th>
                <th class="text-[10px] uppercase font-bold opacity-50">Amount</th>
                <th class="text-[10px] uppercase font-bold opacity-50">Paid</th>
                <th class="text-[10px] uppercase font-bold opacity-50">Status</th>
                <th class="text-right text-[10px] uppercase font-bold opacity-50 pr-4">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments ?? [] as $payment)
            <tr class="hover:bg-base-200/30 transition-colors">
                <td class="text-sm opacity-70 pl-4">{{ $payment->date->format('M d, Y') }}</td>
                <td class="text-sm font-medium">{{ $payment->description }}</td>
                <td class="text-sm font-bold">Php {{ number_format($payment->amount, 2) }}</td>
                <td class="text-sm text-success font-bold">Php {{ number_format($payment->paid, 2) }}</td>
                <td>
                    @if($payment->balance <= 0)
                        <div class="badge badge-success badge-xs">Paid</div>
                    @else
                        <div class="badge badge-warning badge-xs">Partial</div>
                    @endif
                </td>
                <td class="text-right pr-4">
                    <button class="btn btn-ghost btn-xs text-primary">Details</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-10 opacity-40 italic text-sm">No payment records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(auth()->user()->isAssistant() || auth()->user()->isOwner())
<div class="flex justify-end mt-6">
    <button onclick="update_balance_modal.showModal()" class="btn btn-primary btn-sm rounded-xl px-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Update Balance
    </button>
</div>
@endif
