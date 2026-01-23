@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/5 via-base-100 to-secondary/5 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-primary mb-2">Complete Your Payment</h1>
            <p class="text-lg text-base-content/70">Review your plan and proceed to payment</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Plan Summary Card -->
        <div class="card bg-base-100 shadow-2xl mb-6">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-4">Plan Summary</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b">
                        <div>
                            <h3 class="font-bold text-lg">{{ $plan->name }} Plan</h3>
                            <p class="text-sm text-base-content/70">{{ $plan->description }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-primary">₱{{ number_format($plan->price, 0) }}</div>
                            <div class="text-sm text-base-content/70">per month</div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold mb-2">Plan Features:</h4>
                        <ul class="space-y-2">
                            @if($plan->max_users)
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm">Up to {{ $plan->max_users }} users</span>
                                </li>
                            @else
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm">Unlimited users</span>
                                </li>
                            @endif

                            @if($plan->max_patients)
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm">Up to {{ number_format($plan->max_patients) }} patients</span>
                                </li>
                            @else
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm">Unlimited patients</span>
                                </li>
                            @endif

                            @if($plan->features)
                                @foreach($plan->features as $feature)
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm">{{ ucfirst(str_replace('_', ' ', $feature)) }}</span>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="divider"></div>

                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total Amount:</span>
                        <span class="text-primary text-2xl">₱{{ number_format($plan->price, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body">
                <h2 class="card-title mb-4">Payment Information</h2>
                
                <form action="{{ route('tenant.subscription.confirm-payment', ['tenant' => $tenant, 'plan' => $plan->id]) }}" method="POST" id="payment-form">
                    @csrf

                    <!-- Payment Method Selection -->
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">Payment Method</span>
                        </label>
                        <select name="payment_method" class="select select-bordered" required>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="gcash">GCash</option>
                            <option value="paymaya">PayMaya</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <!-- Card Details (for credit/debit card) -->
                    <div id="card-details" class="space-y-4 mb-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Card Number</span>
                            </label>
                            <input type="text" name="card_number" class="input input-bordered" placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Expiry Date</span>
                                </label>
                                <input type="text" name="expiry_date" class="input input-bordered" placeholder="MM/YY" maxlength="5">
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">CVV</span>
                                </label>
                                <input type="text" name="cvv" class="input input-bordered" placeholder="123" maxlength="4">
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Cardholder Name</span>
                            </label>
                            <input type="text" name="cardholder_name" class="input input-bordered" placeholder="John Doe">
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="form-control mb-6">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="checkbox" name="terms" class="checkbox checkbox-primary" required>
                            <span class="label-text">
                                I agree to the <a href="#" class="link link-primary">Terms of Service</a> 
                                and <a href="#" class="link link-primary">Privacy Policy</a>
                            </span>
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('tenant.subscription.cancel', $tenant) }}" class="btn btn-outline flex-1">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg flex-1">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Pay ₱{{ number_format($plan->price, 0) }}
                        </button>
                    </div>
                </form>

                <!-- Payment Security Info -->
                <div class="alert alert-info mt-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm">Your payment information is secure and encrypted. In production, this will integrate with Stripe or other secure payment gateways.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethod = document.querySelector('select[name="payment_method"]');
    const cardDetails = document.getElementById('card-details');
    
    if (paymentMethod && cardDetails) {
        paymentMethod.addEventListener('change', function() {
            if (this.value === 'credit_card' || this.value === 'debit_card') {
                cardDetails.style.display = 'block';
                cardDetails.querySelectorAll('input').forEach(input => {
                    input.setAttribute('required', 'required');
                });
            } else {
                cardDetails.style.display = 'none';
                cardDetails.querySelectorAll('input').forEach(input => {
                    input.removeAttribute('required');
                });
            }
        });
        
        // Trigger on load
        paymentMethod.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
