@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/5 via-base-100 to-secondary/5 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body text-center p-8 sm:p-12">
                <div class="flex justify-center mb-6">
                    <div class="rounded-full bg-success/20 p-6">
                        <svg class="w-16 h-16 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-3xl font-bold text-success mb-4">Setup Complete!</h1>
                <p class="text-lg text-base-content/70 mb-6">
                    Your clinic <strong>{{ $tenant->name }}</strong> is now ready to use.
                </p>

                <div class="alert alert-success mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>You can now access your clinic dashboard!</span>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
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
