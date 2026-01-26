@extends('layouts.tenant', ['tenant' => $tenant])

@section('page-title', 'Staff Management')

@section('content')
<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Staff Users</h1>
            <p class="text-base-content/60 text-sm">Manage your clinic's dentists and assistants</p>
        </div>
        <button class="btn btn-primary gap-2" onclick="add_staff_modal.showModal()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Staff
        </button>
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
                        <div class="flex gap-2">
                            <button class="btn btn-ghost btn-xs text-primary" onclick="window.location.href='{{ route('tenant.users.edit', ['tenant' => $tenant->slug, 'user' => $user->id]) }}'">
                                Edit
                            </button>
                            @if(!$user->isOwner() && $user->id !== auth()->id())
                                <form action="{{ route('tenant.users.destroy', ['tenant' => $tenant->slug, 'user' => $user->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this staff member?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-xs text-error">Remove</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 flex flex-col items-center justify-center text-center">
                <div class="bg-base-200 p-6 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 15.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold">No staff members found</h3>
                <p class="text-base-content/60 max-w-xs mt-2">Start by adding your clinic's dentists and assistants to the portal.</p>
                <button class="btn btn-primary mt-6" onclick="add_staff_modal.showModal()">Add Your First Staff</button>
            </div>
        @endforelse
    </div>
</div>

<!-- Add Staff Modal -->
<dialog id="add_staff_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-md p-0 overflow-hidden">
        <div class="bg-primary p-6 text-primary-content">
            <h3 class="font-bold text-xl">Add New Staff</h3>
            <p class="text-primary-content/80 text-sm">Create a new account for your clinic staff.</p>
        </div>
        
        <form action="{{ route('tenant.users.store', ['tenant' => $tenant->slug]) }}" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text font-semibold">Full Name</span>
                </label>
                <input type="text" name="name" placeholder="Dr. John Doe" class="input input-bordered w-full @error('name') input-error @enderror" value="{{ old('name') }}" required />
                @error('name') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text font-semibold">Email Address</span>
                </label>
                <input type="email" name="email" placeholder="john@example.com" class="input input-bordered w-full @error('email') input-error @enderror" value="{{ old('email') }}" required />
                <label class="label">
                    <span class="label-text-alt text-base-content/60 italic">Used for portal login</span>
                </label>
                @error('email') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Role</span>
                    </label>
                    <select name="role" class="select select-bordered w-full @error('role') select-error @enderror" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="dentist" {{ old('role') === 'dentist' ? 'selected' : '' }}>Dentist</option>
                        <option value="assistant" {{ old('role') === 'assistant' ? 'selected' : '' }}>Assistant</option>
                    </select>
                    @error('role') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Status</span>
                    </label>
                    <select name="status" class="select select-bordered w-full @error('status') select-error @enderror" required>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="alert alert-info bg-info/10 border-info/20 text-xs py-3 mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>A random password will be generated and shown after you save. Staff can change it in their settings.</span>
            </div>

            <div class="modal-action mt-6 flex gap-2">
                <button type="button" class="btn btn-ghost flex-1" onclick="add_staff_modal.close()">Cancel</button>
                <button type="submit" class="btn btn-primary flex-1">Save Staff Member</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        add_staff_modal.showModal();
    });
</script>
@endif

@endsection
