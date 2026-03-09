@extends('layouts.tenant', ['tenant' => $tenant])

@section('page-title', 'Staff Management')

@section('content')
<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Staff Management</h1>
            <p class="text-base-content/60 text-sm">Manage your clinic's dentists and assistants</p>
            @if($tenant->pricingPlan)
                @php
                    $userLimit = $tenant->pricingPlan->max_users;
                    $userCount = $users->count() + 1; // Include owner (who is in central db)
                    $userPercent = $userLimit ? min(100, round(($userCount / $userLimit) * 100)) : 0;
                @endphp
                <div class="flex items-center gap-3 mt-2">
                    <div class="text-[10px] uppercase font-black opacity-40 tracking-widest">Plan Usage</div>
                    <div class="w-32 h-1.5 bg-base-200 rounded-full overflow-hidden">
                        <div class="h-full bg-primary transition-all duration-500" style="width: {{ $userPercent }}%"></div>
                    </div>
                    <span class="text-[10px] font-bold {{ $userPercent >= 90 ? 'text-error' : 'text-base-content/60' }}">
                        {{ $userCount }} / {{ $userLimit ?: '∞' }} Users
                    </span>
                </div>
            @endif
        </div>
        @if($tenant->pricingPlan && $tenant->pricingPlan->max_users && ($users->count() + 1) >= $tenant->pricingPlan->max_users)
            <button onclick="showUpgradeModal()" class="btn btn-warning gap-2 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Limit Reached - Upgrade
            </button>
        @else
            <a href="{{ route('tenant.users.create', $tenant->slug) }}" class="btn btn-primary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Staff
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div class="flex flex-col">
                <span>{{ session('success') }}</span>
                @if(session('temp_password'))
                    <div class="mt-2 p-3 bg-base-100/50 rounded-lg border border-success/20">
                        <p class="text-xs font-bold uppercase opacity-60">Temporary Login Credentials:</p>
                        <p class="text-sm mt-1"><strong>Email:</strong> {{ session('staff_email') }}</p>
                        <p class="text-sm"><strong>Password:</strong> <code class="bg-base-300 px-2 py-0.5 rounded font-mono select-all">{{ session('temp_password') }}</code></p>
                        <p class="text-[10px] mt-2 italic opacity-70">Share these credentials with the staff member. They will be required to change their password on first login.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error shadow-sm mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($users as $user)
            <div class="card bg-base-100 shadow-sm border border-base-200 hover:shadow-md transition-shadow">
                <div class="card-body p-5">
                    <div class="flex items-center gap-4">
                        <div class="avatar">
                            <div class="w-12 h-12 rounded-full">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                            </div>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <h3 class="font-bold text-base truncate">{{ $user->name }}</h3>
                            <p class="text-xs text-base-content/60 truncate">{{ $user->email }}</p>
                        </div>
                        <div class="badge badge-sm {{ $user->status === 'active' ? 'badge-success' : 'badge-ghost' }} badge-outline capitalize">
                            {{ $user->status }}
                        </div>
                    </div>

                    <div class="divider my-2 opacity-50"></div>

                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-base-content/50 uppercase tracking-wider">Role</span>
                            <span class="badge badge-sm badge-outline capitalize">{{ $user->role_name }}</span>
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            @if($user->isDentist() || $user->isAssistant())
                                <button type="button" onclick="showUserModal('{{ route('tenant.users.show', ['tenant' => $tenant->slug, 'user' => $user->id]) }}')" class="btn btn-ghost btn-xs text-info" title="View Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </button>
                            @endif

                            @if(!$user->isOwner())
                                <button type="button" onclick="editUserModal('{{ route('tenant.users.edit', ['tenant' => $tenant->slug, 'user' => $user->id]) }}')" class="btn btn-ghost btn-xs text-primary">
                                    Edit
                                </button>

                                @if($user->id !== auth()->id())
                                    <form action="{{ route('tenant.users.destroy', ['tenant' => $tenant->slug, 'user' => $user->id]) }}" method="POST" data-confirm-delete="Are you sure you want to PERMANENTLY remove {{ $user->name }}? This action cannot be undone." class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs text-error">Remove</button>
                                    </form>
                                @endif
                            @else
                                <span class="text-[10px] opacity-40 italic py-1 px-2">Managed in Settings</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 flex flex-col items-center justify-center text-center">
                <div class="bg-base-200 p-6 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold">No staff members found</h3>
                <p class="text-base-content/60 max-w-xs mt-2">Start by adding your clinic's dentists and assistants to the portal.</p>
                <a href="{{ route('tenant.users.create', $tenant->slug) }}" class="btn btn-primary mt-6">Add Your First Staff</a>
            </div>
        @endforelse
    </div>
</div>

<!-- View User Modal -->
<dialog id="view_user_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-lg">Staff Details</h3>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost">✕</button>
            </form>
        </div>
        <div id="view_user_content" class="py-4">
            <div class="flex justify-center py-8">
                <span class="loading loading-spinner loading-lg text-primary"></span>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Edit User Modal -->
<dialog id="edit_user_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-lg">Edit Staff Member</h3>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost">✕</button>
            </form>
        </div>
        <div id="edit_user_content" class="py-4">
            <div class="flex justify-center py-8">
                <span class="loading loading-spinner loading-lg text-primary"></span>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    function showUserModal(url) {
        const modal = document.getElementById('view_user_modal');
        const content = document.getElementById('view_user_content');
        
        content.innerHTML = '<div class="flex justify-center py-8"><span class="loading loading-spinner loading-lg text-primary"></span></div>';
        modal.showModal();
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            content.innerHTML = html;
        })
        .catch(error => {
            content.innerHTML = '<div class="alert alert-error">Failed to load staff details.</div>';
        });
    }

    function editUserModal(url) {
        const modal = document.getElementById('edit_user_modal');
        const content = document.getElementById('edit_user_content');
        
        content.innerHTML = '<div class="flex justify-center py-8"><span class="loading loading-spinner loading-lg text-primary"></span></div>';
        modal.showModal();
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            content.innerHTML = html;
        })
        .catch(error => {
            content.innerHTML = '<div class="alert alert-error">Failed to load edit form.</div>';
        });
    }
</script>
@endsection
