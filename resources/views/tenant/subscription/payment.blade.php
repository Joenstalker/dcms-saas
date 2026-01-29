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

        <!-- Stripe Payment Form -->
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body">
                <h2 class="card-title mb-4">Payment Information</h2>
                
                <form id="payment-form">
                    <div id="payment-element">
                        <!-- Stripe Elements will create form elements here -->
                    </div>
                    
                    <div id="error-message" class="alert alert-error mt-4 hidden"></div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-6">
                        <a href="{{ route('tenant.subscription.cancel', $tenant) }}" class="btn btn-outline flex-1">
                            Cancel
                        </a>
                        <button type="submit" id="submit" class="btn btn-primary btn-lg flex-1">
                            <span id="button-text">Pay ₱{{ number_format($plan->price, 0) }}</span>
                            <span id="spinner" class="loading loading-spinner hidden"></span>
                        </button>
                    </div>
                </form>

                <!-- Payment Security Info -->
                <div class="alert alert-info mt-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm">Payments are securely processed by Stripe. We do not store your card details.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ $stripeKey }}');
    const options = {
        clientSecret: '{{ $clientSecret }}',
        appearance: {
            theme: 'stripe',
            variables: {
                colorPrimary: '#0ea5e9',
            },
        },
    };

    // Set up Stripe.js and Elements to use in checkout form
    const elements = stripe.elements(options);
    const paymentElement = elements.create('payment');
    paymentElement.mount('#payment-element');

    const form = document.getElementById('payment-form');
    const submitBtn = document.getElementById('submit');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('button-text');
    const errorMessage = document.getElementById('error-message');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        setLoading(true);

        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                // Return URL where the customer should be redirected after the PaymentIntent is confirmed.
                return_url: "{{ route('tenant.subscription.confirm-payment', ['tenant' => $tenant, 'plan' => $plan->id]) }}",
            },
        });

        if (error) {
            // This point will only be reached if there is an immediate error when
            // confirming the payment. Show error to your customer (for example, payment
            // details incomplete)
            showMessage(error.message);
            setLoading(false);
        } else {
            // Your customer will be redirected to your `return_url`. For some payment
            // methods like iDEAL, your customer will be redirected to an intermediate
            // site first to authorize the payment, then redirected to the `return_url`.
        }
    });

    function showMessage(messageText) {
        errorMessage.classList.remove('hidden');
        errorMessage.textContent = messageText;
        setTimeout(function () {
            errorMessage.classList.add('hidden');
            errorMessage.textContent = "";
        }, 10000);
    }

    function setLoading(isLoading) {
        if (isLoading) {
            submitBtn.disabled = true;
            spinner.classList.remove('hidden');
            buttonText.classList.add('hidden');
        } else {
            submitBtn.disabled = false;
            spinner.classList.add('hidden');
            buttonText.classList.remove('hidden');
        }
    }
</script>
@endsection
