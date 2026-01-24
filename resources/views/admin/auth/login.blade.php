@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 to-slate-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        
        <!-- SaaS Logo/Branding -->
        <div class="text-center mb-8">
            <div class="h-12 w-12 mx-auto mb-4 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">DCMS SaaS</h1>
            <p class="text-sm text-gray-500 mt-1">Provider Dashboard</p>
        </div>

        <!-- Login Heading -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Admin Login</h2>
            <p class="text-sm text-gray-600 mt-2">Manage all clinics and subscriptions</p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
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
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition"
                    placeholder="admin@dcmsapp.com"
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
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition"
                    placeholder="Enter your password"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    name="remember" 
                    id="remember"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-600 border-gray-300 rounded"
                >
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    Remember me
                </label>
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
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 ease-in-out"
            >
                Sign In to Dashboard
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="text-center text-sm">
                <p class="text-gray-600">
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                        ← Back to Landing Page
                    </a>
                </p>
            </div>
            <div class="text-center text-xs text-gray-500 mt-4">
                <p>This is the provider dashboard.</p>
                <p>Are you a clinic? <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700">Register your clinic</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
