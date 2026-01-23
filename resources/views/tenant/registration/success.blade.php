@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/5 via-base-100 to-secondary/5 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body text-center p-8 sm:p-12">
                <!-- Success Icon -->
                <div class="flex justify-center mb-6">
                    <div class="rounded-full bg-success/20 p-6">
                        <svg class="w-16 h-16 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-3xl font-bold text-primary mb-4">Registration Submitted!</h1>
                <p class="text-lg text-base-content/70 mb-6">
                    Thank you for registering <strong>{{ $tenant->name }}</strong>
                </p>

                <div class="bg-base-200 rounded-lg p-6 mb-6 text-left">
                    <h2 class="font-semibold mb-4 text-lg">What's Next?</h2>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="badge badge-primary badge-lg mt-1">1</div>
                            <div>
                                <p class="font-medium">Check Your Email</p>
                                <p class="text-sm text-base-content/70">
                                    We've sent a verification email to <strong>{{ $tenant->email }}</strong>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="badge badge-primary badge-lg mt-1">2</div>
                            <div>
                                <p class="font-medium">Verify Your Email</p>
                                <p class="text-sm text-base-content/70">
                                    Click the verification link in the email to activate your tenant account.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="badge badge-primary badge-lg mt-1">3</div>
                            <div>
                                <p class="font-medium">Access Your Dashboard</p>
                                <p class="text-sm text-base-content/70">
                                    Once verified, you can access your clinic dashboard at:
                                </p>
                                <p class="text-sm font-mono text-primary mt-1">{{ $tenant->slug }}.dcmsapp.com</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Please check your email inbox (and spam folder) for the verification link. The link will expire in 24 hours.</span>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        Go to Login
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
