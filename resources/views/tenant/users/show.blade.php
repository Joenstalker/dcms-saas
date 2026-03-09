<div class="space-y-6 p-2">
    <!-- Header with Profile Photo and Basic Info -->
    <div class="flex items-start gap-6">
        <div class="avatar">
            <div class="w-24 h-24 rounded-2xl ring ring-primary ring-offset-base-100 ring-offset-2">
                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
            </div>
        </div>
        <div class="flex-1">
            <h2 class="text-2xl font-bold text-base-content">{{ $user->name }}</h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="badge badge-primary badge-outline capitalize">{{ $user->role ?? 'Staff' }}</span>
                <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-ghost' }} badge-sm capitalize">{{ $user->status }}</span>
            </div>
            <p class="text-base-content/60 mt-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                {{ $user->email }}
            </p>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-1">
            <p class="text-[10px] uppercase font-bold tracking-widest opacity-40">System Role</p>
            <p class="font-medium capitalize">{{ $user->role_name }}</p>
        </div>
        
        <div class="space-y-1">
            <p class="text-[10px] uppercase font-bold tracking-widest opacity-40">Account Status</p>
            <p class="font-medium flex items-center gap-2">
                <span class="w-2 h-2 rounded-full {{ $user->status === 'active' ? 'bg-success' : 'bg-base-300' }}"></span>
                {{ ucfirst($user->status) }}
            </p>
        </div>

        <div class="space-y-1">
            <p class="text-[10px] uppercase font-bold tracking-widest opacity-40">Email Verified</p>
            <p class="font-medium">
                @if($user->email_verified_at)
                    <span class="text-success flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        {{ $user->email_verified_at->format('M d, Y') }}
                    </span>
                @else
                    <span class="text-base-content/40">Not Verified</span>
                @endif
            </p>
        </div>

        <div class="space-y-1">
            <p class="text-[10px] uppercase font-bold tracking-widest opacity-40">Member Since</p>
            <p class="font-medium text-base-content/70">{{ $user->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    @if($user->must_reset_password)
        <div class="alert alert-warning text-xs py-2 px-4 mt-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-4 w-4" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <span>User is required to change password on next login.</span>
        </div>
    @endif
</div>
