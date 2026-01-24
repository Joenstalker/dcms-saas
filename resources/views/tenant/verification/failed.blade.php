@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/5 via-base-100 to-secondary/5 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body text-center p-8 sm:p-12">
                <!-- Error Icon -->
                <div class="flex justify-center mb-6">
                    <div class="rounded-full bg-error/20 p-6">
                        <svg class="w-16 h-16 text-error" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-3xl font-bold text-error mb-4">Verification Failed</h1>
                
                @if(session('error'))
                    <div class="alert alert-error mb-6">
                        <span>{{ session('error') }}</span>
                    </div>
                @else
                    <p class="text-lg text-base-content/70 mb-6">
                        The verification link is invalid or has expired.
                    </p>
                @endif

                <div class="bg-base-200 rounded-lg p-6 mb-6 text-left">
                    <h2 class="font-semibold mb-4">What can you do?</h2>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="badge badge-primary badge-lg mt-1">1</div>
                            <div>
                                <p class="font-medium">Check Your Email</p>
                                <p class="text-sm text-base-content/70">
                                    Make sure you're using the latest verification link from your email.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="badge badge-primary badge-lg mt-1">2</div>
                            <div>
                                <p class="font-medium">Request New Link</p>
                                <p class="text-sm text-base-content/70">
                                    Contact support to request a new verification email.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="badge badge-primary badge-lg mt-1">3</div>
                            <div>
                                <p class="font-medium">Check Expiration</p>
                                <p class="text-sm text-base-content/70">
                                    Verification links expire after 24 hours. You may need to register again.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('tenant.registration.index') }}" class="btn btn-primary">
                        Register Again
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline">
                        Return to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
