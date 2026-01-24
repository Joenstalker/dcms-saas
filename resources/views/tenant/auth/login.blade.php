@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
        
        <!-- Clinic Logo/Icon -->
        <div class="text-center mb-8">
            @if($tenant->logo)
                <img src="{{ asset('storage/' . $tenant->logo) }}" alt="{{ $tenant->name }}" class="h-16 mx-auto mb-4">
            @else
                <div class="h-16 w-16 mx-auto mb-4 bg-gradient-to-br from-sky-500 to-emerald-500 rounded-lg flex items-center justify-center">
                    <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                    </svg>
                </div>
            @endif
        </div>

        <!-- Welcome Message -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h2>
            <p class="text-lg text-gray-600 font-semibold">{{ $tenant->name }}</p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('tenant.login.submit') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent outline-none transition"
                    placeholder="your@email.com"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent outline-none transition"
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Generic Error -->
            @if ($errors->has('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <!-- Login Button -->
            <button 
                type="submit"
                class="w-full bg-sky-500 hover:bg-sky-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 ease-in-out"
            >
                Login
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-600">
            <p>Don't have an account? 
                <a href="{{ route('home') }}" class="text-sky-500 hover:text-sky-600 font-semibold">
                    Register here
                </a>
            </p>
        </div>

        <!-- Clinic Info -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center text-xs text-gray-500">
            <p>{{ $tenant->name }}</p>
            @if($tenant->city)
                <p>{{ $tenant->city }}@if($tenant->state), {{ $tenant->state }}@endif</p>
            @endif
        </div>

    </div>
</div>
@endsection
