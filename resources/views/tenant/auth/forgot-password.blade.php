@extends('layouts.guest')

@section('content')
<div class="h-screen w-full flex items-center justify-center bg-gray-50 px-4 overflow-hidden relative">
    <!-- Sophisticated Background Pattern -->
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23000000\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6zM36 4V0h-2v4h-4v2h4v4h2V6h4V4h-4z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="max-w-md w-full @if($errors->any() || session('status')) animate-shake @endif">
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 p-8 relative z-10 transition-all duration-500 hover:shadow-sky-100">
            
            <!-- Clinic Logo/Icon -->
            <div class="text-center mb-6">
                @if(session('tenant_id'))
                    @php
                        $tenant = \App\Models\Tenant::find(session('tenant_id'));
                    @endphp
                    @if($tenant && $tenant->logo)
                        <img src="{{ asset('storage/' . $tenant->logo) }}" alt="{{ $tenant->name }}" class="h-24 mx-auto object-contain transition-all hover:scale-105 duration-500 rounded-xl">
                    @else
                        <img src="{{ asset('images/dcms-logo.png') }}" alt="DCMS Logo" class="h-24 mx-auto object-contain transition-all hover:scale-105 duration-500">
                    @endif
                @else
                    <img src="{{ asset('images/dcms-logo.png') }}" alt="DCMS Logo" class="h-24 mx-auto object-contain transition-all hover:scale-105 duration-500">
                @endif
            </div>

            <!-- Welcome Message -->
            <div class="text-center mb-6">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight leading-none italic uppercase">Forgot Password?</h2>
                <div class="flex items-center justify-center gap-2 mt-3">
                    <span class="h-px w-8 bg-sky-100"></span>
                    <p class="text-[10px] text-sky-500 font-black tracking-[0.2em] uppercase">Password Reset</p>
                    <span class="h-px w-8 bg-sky-100"></span>
                </div>
            </div>

            <!-- Status Message -->
            @if (session('status'))
                <div class="alert alert-success rounded-2xl shadow-sm border-none bg-emerald-50 text-emerald-800 text-xs py-3 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-bold">{{ session('status') }}</span>
                </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-error rounded-2xl shadow-sm border-none bg-rose-50 text-rose-800 text-xs py-3 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-bold">{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div class="form-control">
                    <div class="relative group">
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="input input-bordered w-full pl-12 bg-gray-50 border-gray-100 focus:bg-white focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all duration-300 rounded-2xl"
                            placeholder="Email Address"
                        >
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-sky-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="btn btn-primary w-full bg-sky-500 hover:bg-sky-600 border-sky-500 hover:border-sky-600 shadow-xl shadow-sky-500/20 active:scale-95 transition-all text-white font-black uppercase tracking-widest rounded-2xl h-14"
                >
                    Email Password Reset Link
                </button>
            </form>

            <!-- Back to Login Link -->
            <div class="mt-6 text-center">
                <a href="{{ url('/login') }}" class="text-[10px] text-gray-400 hover:text-sky-500 font-black transition-colors uppercase tracking-[0.2em] group">
                    Back to Login <span class="group-hover:translate-x-1 inline-block transition-transform ml-1">→</span>
                </a>
            </div>

            <!-- Footer -->
            <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-300">
                    SaaS Engine v1.0
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
