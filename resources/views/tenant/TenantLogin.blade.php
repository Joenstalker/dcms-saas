@extends('layouts.guest')

@section('content')
<div class="h-screen w-full flex items-center justify-center bg-gray-50 px-4 overflow-hidden relative">
    <!-- Sophisticated Background Pattern -->
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23000000\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6zM36 4V0h-2v4h-4v2h4v4h2V6h4V4h-4z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="max-w-md w-full @if($errors->any() || session('tenant_access_error')) animate-shake @endif">
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 p-8 relative z-10 transition-all duration-500 hover:shadow-sky-100">
            
            <!-- Clinic Logo/Icon -->
            <div class="text-center mb-6">
                @if($tenant->logo)
                    <img src="{{ asset('storage/' . $tenant->logo) }}" alt="{{ $tenant->name }}" class="h-24 mx-auto object-contain transition-all hover:scale-105 duration-500 rounded-xl">
                @else
                    <div class="h-20 w-20 mx-auto bg-gradient-to-tr from-sky-500 to-indigo-600 rounded-[2rem] flex items-center justify-center shadow-lg shadow-sky-100 transform -rotate-6 hover:rotate-0 transition-all duration-500">
                        <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Welcome Message -->
            <div class="text-center mb-6">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight leading-none italic uppercase">Login Portal</h2>
                <div class="flex items-center justify-center gap-2 mt-3">
                    <span class="h-px w-8 bg-sky-100"></span>
                    <p class="text-[10px] text-sky-500 font-black tracking-[0.2em] uppercase">{{ $tenant->name }}</p>
                    <span class="h-px w-8 bg-sky-100"></span>
                </div>
            </div>

            <!-- Security & Alert Messages -->
            <div class="space-y-3 mb-6">
                @if(session('tenant_access_error'))
                    <div class="alert alert-warning rounded-2xl shadow-sm border-none bg-amber-50 text-amber-800 text-xs py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="font-bold">{{ session('tenant_access_error') }}</span>
                    </div>
                @endif

                @if($errors->has('auth_failed'))
                    <div class="alert alert-error rounded-2xl shadow-sm border-none bg-rose-50 text-rose-800 text-xs py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-bold">{{ $errors->first('auth_failed') }}</span>
                    </div>
                @endif
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('tenant.login.submit', ['tenant' => $tenant->slug]) }}" 
                  class="space-y-4" 
                  onsubmit="this.querySelector('button[type=submit]').classList.add('loading'); this.querySelector('button[type=submit]').disabled = true;">
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
                            class="input input-bordered w-full pl-12 bg-gray-50 border-gray-100 focus:bg-white focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all duration-300 rounded-2xl @error('email') border-rose-300 @enderror"
                            placeholder="Email Address"
                        >
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-sky-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-control">
                    <div class="relative group">
                        <input 
                            type="password" 
                            name="password" 
                            required
                            class="input input-bordered w-full pl-12 bg-gray-50 border-gray-100 focus:bg-white focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all duration-300 rounded-2xl @error('password') border-rose-300 @enderror"
                            placeholder="Password"
                        >
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-sky-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Privacy Policy Checkbox -->
                <div class="form-control mt-4 bg-sky-50/50 p-4 rounded-2xl border border-sky-100/50">
                    <label class="label cursor-pointer justify-start gap-3 items-center">
                        <input type="checkbox" required class="checkbox checkbox-primary checkbox-sm rounded-lg" />
                        <span class="label-text text-[10px] font-black uppercase tracking-[0.1em] text-sky-900/60 leading-tight">
                            I agree to the <a href="#" class="text-sky-600 no-underline hover:underline font-black">DCMS Privacy Policy</a>
                        </span>
                    </label>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit"
                    id="submit-btn"
                    class="btn btn-primary w-full shadow-xl shadow-sky-500/20 active:scale-95 transition-all text-white font-black uppercase tracking-widest rounded-2xl h-14"
                >
                    <span class="loading-spinner loading-sm hidden" id="spinner"></span>
                    Secure Access
                </button>
            </form>

            <!-- Recover Password Link -->
            <div class="mt-6 text-center">
                <a href="#" class="text-[10px] text-gray-400 hover:text-sky-500 font-black transition-colors uppercase tracking-[0.2em] group">
                    Forgot Password? <span class="group-hover:translate-x-1 inline-block transition-transform ml-1">→</span>
                </a>
            </div>

            <!-- Clinic Info -->
            <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-300">
                    SaaS Engine v1.0
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
        20%, 40%, 60%, 80% { transform: translateX(10px); }
    }
    .animate-shake {
        animation: shake 0.6s cubic-bezier(.36,.07,.19,.97) both;
        transform: translate3d(0, 0, 0);
    }
    
    .loading .loading-spinner {
        display: inline-block;
    }
</style>
@endsection
