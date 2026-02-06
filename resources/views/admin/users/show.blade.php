@extends('layouts.admin')

@section('page-title', 'User Profile - ' . $user->name)

@section('content')
<div class="p-6 space-y-6 max-w-5xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="text-sm breadcrumbs">
        <ul>
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="font-bold">Profile</li>
        </ul>
    </div>

    <!-- Profile Header -->
    <div class="card bg-base-100 shadow-xl border border-base-300 overflow-hidden">
        <div class="h-32 bg-gradient-to-r from-primary/20 via-primary/10 to-transparent"></div>
        <div class="card-body -mt-16 pt-0">
            <div class="flex flex-col md:flex-row items-end gap-6">
                <!-- Avatar -->
                <div class="avatar">
                    <div class="w-32 h-32 rounded-2xl ring ring-base-100 ring-offset-4 overflow-hidden bg-base-200">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    </div>
                </div>
                
                <div class="flex-1 pb-2">
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                        @if($user->is_system_admin)
                            <span class="badge badge-primary font-bold uppercase tracking-wider text-[10px] px-3 py-2">Super Admin</span>
                        @else
                            @php
                                $roleColor = match($user->role) {
                                    \App\Models\User::ROLE_TENANT => 'badge-secondary',
                                    \App\Models\User::ROLE_DENTIST => 'badge-accent',
                                    \App\Models\User::ROLE_ASSISTANT => 'badge-info',
                                    default => 'badge-ghost'
                                };
                            @endphp
                            <span class="badge {{ $roleColor }} font-bold uppercase tracking-wider text-[10px] px-3 py-2">
                                {{ ucwords(str_replace('_', ' ', $user->role ?? 'User')) }}
                            </span>
                        @endif
                    </div>
                    <p class="text-base-content/60 flex items-center gap-2 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        {{ $user->email }}
                    </p>
                </div>

                <div class="flex gap-2 pb-2">
                    @if($user->id !== auth()->id())
                        <button class="btn btn-warning btn-sm gap-2" onclick="alert('Password reset link sent!')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 17.536A3 3 0 015.657 15.657l1.879-1.879A6 6 0 0115 7z"></path></svg>
                            Reset Password
                        </button>
                    @endif
                    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Details -->
        <div class="space-y-6 lg:col-span-2">
            <div class="card bg-base-100 shadow-xl border border-base-300">
                <div class="card-body">
                    <h2 class="card-title text-sm uppercase tracking-widest opacity-50 mb-4">Account Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase font-bold tracking-wider opacity-40">User ID</label>
                            <p class="font-mono text-sm break-all">{{ $user->id }}</p>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase font-bold tracking-wider opacity-40">Verification Status</label>
                            <div>
                                @if($user->email_verified_at)
                                    <div class="badge badge-success badge-sm gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Verified
                                    </div>
                                    <p class="text-[10px] mt-1 opacity-50 uppercase font-medium">on {{ $user->email_verified_at->format('M d, Y H:i') }}</p>
                                @else
                                    <div class="badge badge-warning badge-sm">Unverified</div>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] uppercase font-bold tracking-wider opacity-40">Member Since</label>
                            <p class="text-sm font-medium">{{ $user->created_at->format('F d, Y') }}</p>
                            <p class="text-[10px] opacity-50 uppercase font-medium">{{ $user->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] uppercase font-bold tracking-wider opacity-40">Last Updated</label>
                            <p class="text-sm font-medium">{{ $user->updated_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles & Permissions Section -->
            <div class="card bg-base-100 shadow-xl border border-base-300">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="card-title text-sm uppercase tracking-widest opacity-50">Roles & Permissions</h2>
                        <svg class="w-5 h-5 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if($user->is_system_admin)
                            <span class="badge badge-primary badge-outline px-4 py-3">Super Administrator Access</span>
                        @else
                            @forelse($user->roles as $role)
                                <span class="badge badge-ghost border-base-300 px-4 py-3">{{ ucfirst($role->name) }}</span>
                            @empty
                                <span class="text-sm italic opacity-50 text-center w-full py-4 border-2 border-dashed border-base-200 rounded-xl">No specific roles assigned</span>
                            @endforelse
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Clinic info -->
        <div class="space-y-6">
            @if($user->tenant)
                <div class="card bg-base-100 shadow-xl border border-base-300 overflow-hidden">
                    <div class="bg-secondary/10 p-4 border-b border-base-300">
                        <h2 class="font-bold text-sm uppercase tracking-widest text-secondary flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-7h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Clinic Association
                        </h2>
                    </div>
                    <div class="card-body p-6">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-20 h-20 bg-secondary/20 rounded-2xl flex items-center justify-center mb-4 border border-secondary/30">
                                <span class="text-3xl font-black text-secondary">{{ substr($user->tenant->name, 0, 1) }}</span>
                            </div>
                            <h3 class="font-bold text-xl">{{ $user->tenant->name }}</h3>
                            <p class="text-sm opacity-60 mb-6">{{ $user->tenant->email }}</p>
                            
                            <div class="w-full space-y-3 mb-6">
                                <div class="flex justify-between text-xs border-b border-base-200 pb-2">
                                    <span class="opacity-50">Plan</span>
                                    <span class="font-bold text-primary">{{ $user->tenant->pricingPlan->name ?? 'None' }}</span>
                                </div>
                                <div class="flex justify-between text-xs border-b border-base-200 pb-2">
                                    <span class="opacity-50">Status</span>
                                    <span class="badge badge-success badge-xs">Active</span>
                                </div>
                                <div class="flex justify-between text-xs border-b border-base-200 pb-2">
                                    <span class="opacity-50">Domain</span>
                                    <span class="font-mono">{{ $user->tenant->slug }}.{{ env('LOCAL_BASE_DOMAIN') }}</span>
                                </div>
                            </div>

                            <a href="{{ route('admin.tenants.show', $user->tenant) }}" class="btn btn-secondary btn-block btn-sm">Manage Clinic</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="card bg-neutral text-neutral-content shadow-xl">
                    <div class="card-body items-center text-center">
                        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h2 class="card-title text-sm uppercase tracking-tighter">System Account</h2>
                        <p class="text-xs opacity-60">This user is a global administrator and is not associated with any specific clinic.</p>
                    </div>
                </div>
            @endif

            <!-- Account Settings Card -->
            <div class="card bg-base-100 shadow-xl border border-base-300">
                <div class="card-body p-6">
                    <h2 class="font-bold text-xs uppercase tracking-[0.2em] opacity-30 mb-4">Quick Actions</h2>
                    <div class="flex flex-col gap-2">
                        <button class="btn btn-sm btn-ghost justify-start gap-2" onclick="alert('Sent!')">
                            <span class="loading loading-spinner loading-xs hidden" id="loading-verify"></span>
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Resend Verification
                        </button>
                        @if(!$user->is_system_admin && $user->id !== auth()->id())
                            <button class="btn btn-sm btn-ghost justify-start gap-2 text-error" onclick="alert('Confirming deletion...')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Delete Account
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
