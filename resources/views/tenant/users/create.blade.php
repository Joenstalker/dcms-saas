@extends('layouts.tenant', ['tenant' => $tenant])

@section('page-title', 'Add New Staff')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('tenant.users.index', $tenant->slug) }}" class="btn btn-ghost btn-sm gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Staff List
        </a>
        <h1 class="text-3xl font-bold text-base-content">Add New Staff Member</h1>
        <p class="text-base-content/60 mt-2">Create a new account for your clinic staff</p>
    </div>

    <div class="card bg-base-100 shadow-lg border border-base-200">
        <div class="card-body">
            <form action="{{ route('tenant.users.store', $tenant->slug) }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="alert alert-info bg-info/10 border-info/20 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="font-semibold">Important:</p>
                        <p>A random password will be generated for this staff member. After creating the account, you'll receive the temporary password that you must share with them. They will be required to change it on their first login.</p>
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
                        value="{{ old('name') }}" 
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
                        value="{{ old('email') }}" 
                        required 
                    />
                    <label class="label">
                        <span class="label-text-alt text-base-content/60">This email will be used for portal login</span>
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
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Role</option>
                            <option value="dentist" {{ old('role') === 'dentist' ? 'selected' : '' }}>Dentist</option>
                            <option value="assistant" {{ old('role') === 'assistant' ? 'selected' : '' }}>Assistant</option>
                        </select>
                        <label class="label">
                            <span class="label-text-alt text-base-content/60">Determines portal access and permissions</span>
                        </label>
                        @error('role')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Status <span class="text-error">*</span></span>
                        </label>
                        <select name="status" class="select select-bordered w-full @error('status') select-error @enderror" required>
                            <option value="active" {{ old('status') === 'active' || !old('status') ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
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

                <div class="divider"></div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('tenant.users.index', $tenant->slug) }}" class="btn btn-ghost">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Staff Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
