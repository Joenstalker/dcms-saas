@extends('layouts.admin')

@section('page-title', 'Account Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Profile Information -->
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">
            <h2 class="card-title text-xl mb-4">Profile Information</h2>
            
            <form method="post" action="{{ route('admin.profile.update') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <div class="flex items-start gap-6">
                    <div class="avatar placeholder">
                        <div class="w-32 rounded-full bg-neutral text-neutral-content ring ring-primary ring-offset-base-100 ring-offset-2">
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" id="photo-preview" />
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-2 mt-4">
                        <h3 class="font-bold text-lg">Profile Photo</h3>
                        <p class="text-sm text-base-content/70">Update your profile picture.</p>
                        <button type="button" class="btn btn-outline btn-sm gap-2 mt-2 w-fit" onclick="openCropModal()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Change Photo
                        </button>
                        <input type="file" name="photo" id="photo" class="hidden" accept="image/*" />
                        @error('photo')
                            <span class="text-error text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label" for="name">
                            <span class="label-text font-medium">Name</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="input input-bordered w-full" required autofocus autocomplete="name" />
                        @error('name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label" for="email">
                            <span class="label-text font-medium">Email</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="input input-bordered w-full" required autocomplete="username" />
                        @error('email')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>

                <div class="card-actions justify-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Password -->
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">
            <h2 class="card-title text-xl mb-4">Update Password</h2>
            
            <form method="post" action="{{ route('admin.password.update') }}" class="space-y-4">
                @csrf
                @method('put')

                <div class="form-control">
                    <label class="label" for="current_password">
                        <span class="label-text font-medium">Current Password</span>
                    </label>
                    <input type="password" name="current_password" id="current_password" class="input input-bordered w-full max-w-md" autocomplete="current-password" />
                    @error('current_password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label" for="password">
                        <span class="label-text font-medium">New Password</span>
                    </label>
                    <input type="password" name="password" id="password" class="input input-bordered w-full max-w-md" autocomplete="new-password" />
                    @error('password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label" for="password_confirmation">
                        <span class="label-text font-medium">Confirm New Password</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="input input-bordered w-full max-w-md" autocomplete="new-password" />
                    @error('password_confirmation')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="card-actions justify-end mt-4">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Custom Styles for Croppie to match design -->
<style>
    .cr-slider-wrap {
        width: 100%;
        padding: 0;
        margin: 0;
    }
    .cr-slider {
        width: 100%;
        background-color: transparent;
        -webkit-appearance: none;
    }
    input[type=range]::-webkit-slider-runnable-track {
        background: #D1D5DB; /* gray-300 */
        height: 6px;
        border-radius: 3px;
    }
    input[type=range]::-webkit-slider-thumb {
        -webkit-appearance: none;
        height: 18px;
        width: 18px;
        border-radius: 50%;
        background: #84cc16; /* lime-500/success-ish */
        margin-top: -6px;
        cursor: pointer;
    }
</style>

<!-- Crop Modal -->
<dialog id="cropModal" class="modal">
    <div class="modal-box w-[500px] p-0 overflow-hidden bg-base-100 rounded-lg">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-base-200">
            <h3 class="font-bold text-xl text-base-content">Profile Photo</h3>
        </div>

        <!-- Body -->
        <div class="p-6">
            <!-- Croppie Container -->
            <div id="croppie-container" class="h-[300px] w-full bg-base-200 rounded-lg mb-6 border-2 border-dashed border-base-300 flex items-center justify-center">
                <!-- Placeholder when no image -->
                <div id="croppie-placeholder" class="text-base-content/30 flex flex-col items-center">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Select an image</span>
                </div>
            </div>

            <!-- Controls -->
            <div class="flex items-center gap-4">
                <div class="flex-1" id="slider-container">
                    <!-- Croppie moves the slider here dynamically or we hide theirs and bind ours -->
                </div>
                <button type="button" class="btn btn-primary btn-sm px-6" onclick="document.getElementById('photo').click()">
                    Browse
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-base-100 border-t border-base-200 flex justify-center gap-3">
            <button type="button" class="btn btn-primary w-24" onclick="cropImage()">Save</button>
            <button type="button" class="btn btn-ghost bg-base-200 w-24 hover:bg-base-300" onclick="closeCropModal()">Cancel</button>
        </div>
    </div>
</dialog>

<script>
    let croppieInstance;
    const photoInput = document.getElementById('photo');
    const cropModal = document.getElementById('cropModal');
    const photoPreview = document.getElementById('photo-preview');
    const croppieContainer = document.getElementById('croppie-container');
    const croppiePlaceholder = document.getElementById('croppie-placeholder');

    function openCropModal() {
        cropModal.showModal();
        initCroppie();
    }

    function initCroppie(url = null) {
        if (croppieInstance) {
            croppieInstance.destroy();
            croppieInstance = null;
        }
        
        // Recreate the target element to prevent "already initialized" errors
        croppieContainer.innerHTML = '<div id="croppie-target"></div>';
        const target = document.getElementById('croppie-target');
        
        croppiePlaceholder.style.display = url ? 'none' : 'flex';
        
        croppieInstance = new Croppie(target, {
            viewport: { width: 200, height: 200, type: 'square' },
            boundary: { width: '100%', height: 300 },
            showZoomer: true,
            enableOrientation: true
        });

        if (url) {
            croppieInstance.bind({ url });
        }
    }

    photoInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Open modal if not already open
                if(!cropModal.open) {
                    cropModal.showModal();
                }

                initCroppie(e.target.result);
            };
            
            reader.readAsDataURL(file);
        }
    });

    function closeCropModal() {
        cropModal.close();
        // Don't destroy immediately if you want to keep state, but usually good to reset
        if (croppieInstance) {
            croppieInstance.destroy();
            croppieInstance = null;
        }
        // If no file cropped, clear input
        if (!photoPreview.dataset.cropped) {
            photoInput.value = '';
        }
    }

    function cropImage() {
        if (!croppieInstance) return;

        croppieInstance.result({
            type: 'blob',
            size: 'viewport',
            format: 'jpeg',
            circle: false
        }).then(function(blob) {
            const fileName = 'cropped-profile.jpg';
            const file = new File([blob], fileName, { type: 'image/jpeg' });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            photoInput.files = dataTransfer.files;

            photoPreview.src = URL.createObjectURL(blob);
            photoPreview.dataset.cropped = "true";

            closeCropModal();
        });
    }
</script>
@endsection
