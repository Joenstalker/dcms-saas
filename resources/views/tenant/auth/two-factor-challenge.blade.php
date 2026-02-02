@extends('layouts.guest')

@section('content')
<div class="h-screen w-full flex items-center justify-center bg-gray-50 px-4 overflow-hidden relative">
    <!-- Sophisticated Background Pattern -->
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23000000\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6zM36 4V0h-2v4h-4v2h4v4h2V6h4V4h-4z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="max-w-md w-full">
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 p-8 relative z-10 transition-all duration-500 hover:shadow-sky-100">
            
            <!-- Header -->
            <div class="text-center mb-6">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight leading-none italic uppercase">Two-Factor</h2>
                <div class="flex items-center justify-center gap-2 mt-3">
                    <span class="h-px w-8 bg-sky-100"></span>
                    <p class="text-[10px] text-sky-500 font-black tracking-[0.2em] uppercase">Authentication</p>
                    <span class="h-px w-8 bg-sky-100"></span>
                </div>
                <p class="text-xs text-gray-500 mt-4">Please confirm access by entering your authentication code.</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-error rounded-2xl shadow-sm border-none bg-rose-50 text-rose-800 text-xs py-3 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-bold">{{ $errors->first() }}</span>
                </div>
            @endif

            <div x-data="{ recovery: false }">
                <!-- Authentication Code -->
                <div x-show="! recovery">
                    <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4">
                        @csrf

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-gray-700">Authentication Code</span>
                            </label>
                            <div class="relative group">
                                <input 
                                    type="text" 
                                    name="code" 
                                    autofocus
                                    autocomplete="one-time-code"
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                    class="input input-bordered w-full pl-12 bg-gray-50 border-gray-100 focus:bg-white focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all duration-300 rounded-2xl text-center text-2xl tracking-widest"
                                    placeholder="000000"
                                >
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-sky-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <button 
                            type="submit"
                            class="btn btn-primary w-full bg-sky-500 hover:bg-sky-600 border-sky-500 hover:border-sky-600 shadow-xl shadow-sky-500/20 active:scale-95 transition-all text-white font-black uppercase tracking-widest rounded-2xl h-14"
                        >
                            Login
                        </button>

                        <div class="text-center mt-4">
                            <button type="button" @click="recovery = true" class="text-[10px] text-gray-400 hover:text-sky-500 font-black transition-colors uppercase tracking-[0.2em]">
                                Use a recovery code
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Recovery Code -->
                <div x-show="recovery" style="display: none;">
                    <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4">
                        @csrf

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-gray-700">Recovery Code</span>
                            </label>
                            <div class="relative group">
                                <input 
                                    type="text" 
                                    name="recovery_code" 
                                    autocomplete="one-time-code"
                                    class="input input-bordered w-full pl-12 bg-gray-50 border-gray-100 focus:bg-white focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all duration-300 rounded-2xl"
                                    placeholder="Enter recovery code"
                                >
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-sky-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <button 
                            type="submit"
                            class="btn btn-primary w-full bg-sky-500 hover:bg-sky-600 border-sky-500 hover:border-sky-600 shadow-xl shadow-sky-500/20 active:scale-95 transition-all text-white font-black uppercase tracking-widest rounded-2xl h-14"
                        >
                            Login
                        </button>

                        <div class="text-center mt-4">
                            <button type="button" @click="recovery = false" class="text-[10px] text-gray-400 hover:text-sky-500 font-black transition-colors uppercase tracking-[0.2em]">
                                Use an authentication code
                            </button>
                        </div>
                    </form>
                </div>
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

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
