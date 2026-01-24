@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/5 via-base-100 to-secondary/5 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-primary mb-2">DCMS Login</h1>
            <p class="text-lg text-base-content/70">Choose your login type</p>
        </div>

        <!-- Login Options -->
        <div class="space-y-4">
            <!-- Admin Login -->
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Provider Access
                    </h2>
                    <p class="text-sm text-base-content/70 mb-4">
                        Manage all clinics and subscriptions
                    </p>
                    <div class="card-actions justify-end">
                        <a href="{{ route('admin.login') }}" class="btn btn-primary btn-sm">
                            Admin Login
                        </a>
                    </div>
                </div>
            </div>

            <!-- Clinic Login -->
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948-.684l1.498-4.493a1 1 0 011.502-.684l1.498 4.493a1 1 0 00.948.684H19a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" />
                        </svg>
                        Clinic Access
                    </h2>
                    <p class="text-sm text-base-content/70 mb-4">
                        Login to your clinic dashboard
                    </p>
                    <div class="card-actions justify-end">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                            Register / Login
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-sm text-base-content/70">
                Don't have an account?
                <a href="{{ route('tenant.registration.index') }}" class="text-primary font-semibold hover:underline">
                    Register as clinic
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
