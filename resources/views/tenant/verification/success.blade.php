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

                <h1 class="text-3xl font-bold text-success mb-4">Email Verified Successfully!</h1>
                <p class="text-lg text-base-content/70 mb-6">
                    Your clinic <strong>{{ $tenant->name }}</strong> has been activated.
                </p>

                <div class="bg-base-200 rounded-lg p-6 mb-6">
                    <h2 class="font-semibold mb-4 text-lg">Your Account is Ready!</h2>
                    <div class="space-y-3 text-left">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-success" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Email address verified</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-success" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Tenant account activated</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-success" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Ready to access your dashboard</span>
                        </div>
                    </div>
                </div>

                <div class="alert alert-success mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>You can now log in to your clinic dashboard!</span>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#" class="btn btn-primary btn-lg">
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
