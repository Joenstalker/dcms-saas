@extends('layouts.tenant', ['tenant' => $tenant])

@section('page-title', 'Edit Staff Member')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('tenant.users.index', $tenant->slug) }}" class="btn btn-ghost btn-sm gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg"  class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Staff List
        </a>
        <h1 class="text-3xl font-bold text-base-content">Edit Staff Member</h1>
        <p class="text-base-content/60 mt-2">Update information for {{ $user->name }}</p>
    </div>

    <div class="card bg-base-100 shadow-lg border border-base-200">
        <div class="card-body">
            <form action="{{ route('tenant.users.update', ['tenant' => $tenant->slug, 'user' => $user->id]) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')
                
                <div class="flex items-center gap-4 p-4 bg-base-200 rounded-lg">
                    <div class="avatar">
                        <div class="w-16 h-16 rounded-full">
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold">{{ $user->name }}</p>
                        <p class="text-sm text-base-content/60">{{ $user->email }}</p>
                        <p class="text-xs text-base-content/50 mt-1">Member since {{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Full Name <span class="text-error">*</span></span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        placeholder="Dr. John Doe" 
                        class="input input-bordered w-full @error('name') input-error @enderror" 
                        value="{{ old('name', $user->name) }}" 
                        required 
                    />
                    @error('name')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Email Address <span class="text-error">*</span></span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        placeholder="john.doe@example.com" 
                        class="input input-bordered w-full @error('email') input-error @enderror" 
                        value="{{ old('email', $user->email) }}" 
                        required 
                    />
                    <label class="label">
                        <span class="label-text-alt text-base-content/60">Used for portal login</span>
                    </label>
                    @error('email')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Role <span class="text-error">*</span></span>
                        </label>
                        <select name="role" class="select select-bordered w-full @error('role') select-error @enderror" required>
                            <option value="dentist" {{ old('role', $user->current_role ?? $user->role) === 'dentist' ? 'selected' : '' }}>Dentist</option>
                            <option value="assistant" {{ old('role', $user->current_role ?? $user->role) === 'assistant' ? 'selected' : '' }}>Assistant</option>
                        </select>
                        @error('role')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Status</span>
                        </label>
                        <select name="status" class="select select-bordered w-full @error('status') select-error @enderror">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">Inactive users cannot login</span>
                        </label>
                        @error('status')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>

                <div class="divider">Update Password (Optional)</div>

                <div class="alert alert-warning bg-warning/10 border-warning/20 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Leave password fields blank to keep the current password</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">New Password</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="••••••••" 
                            class="input input-bordered w-full @error('password') input-error @enderror"
                        />
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">Minimum 8 characters</span>
                        </label>
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Confirm New Password</span>
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            placeholder="••••••••" 
                            class="input input-bordered w-full"
                        />
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">Re-enter new password</span>
                        </label>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('tenant.users.index', $tenant->slug) }}" class="btn btn-ghost">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Staff Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
