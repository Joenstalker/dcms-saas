@extends('layouts.admin')

@section('title', 'Platform Updates')

@section('styles')
<style>
    .version-card {
        transition: all 0.2s ease;
    }
    .version-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .status-badge {
        @apply px-2 py-1 rounded-full text-xs font-medium;
    }
    .status-draft {
        @apply bg-gray-100 text-gray-800;
    }
    .status-testing {
        @apply bg-yellow-100 text-yellow-800;
    }
    .status-stable {
        @apply bg-green-100 text-green-800;
    }
    .status-deprecated {
        @apply bg-red-100 text-red-800;
    }
    .release-type-major {
        @apply text-red-600 font-bold;
    }
    .release-type-minor {
        @apply text-blue-600 font-bold;
    }
    .release-type-patch {
        @apply text-green-600 font-bold;
    }
    .release-type-hotfix {
        @apply text-orange-600 font-bold;
    }
    .maintenance-banner {
        @apply bg-red-50 border-l-4 border-red-500 p-4 mb-4;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Platform Updates</h1>
        <div class="flex gap-2">
            <button onclick="checkForUpdates()" class="btn btn-primary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Check for Updates
            </button>
        </div>
    </div>

    <!-- Maintenance Mode Banner -->
    @if($settings['maintenance_mode'])
    <div class="maintenance-banner">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="font-medium text-red-800">Maintenance Mode Active</span>
            </div>
            <button onclick="disableMaintenanceMode()" class="btn btn-sm btn-outline btn-error">
                Disable Maintenance Mode
            </button>
        </div>
    </div>
    @endif

    <!-- Update Available Banner -->
    @if($updateAvailable)
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span class="font-medium text-green-800">Update Available!</span>
                    <p class="text-sm text-green-700">Version {{ $updateAvailable['latest_version'] }} is available</p>
                </div>
            </div>
            <button onclick="deployVersion('{{ $updateAvailable['latest_version'] }}')" class="btn btn-sm btn-success">
                Deploy Now
            </button>
        </div>
    </div>
    @endif

    <!-- Current Version & Settings -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Current Version Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">Current Version</h3>
            <div class="flex items-center">
                <span class="text-4xl font-bold text-primary">{{ $settings['current_version'] }}</span>
            </div>
            <p class="text-sm text-gray-500 mt-2">Update Channel: {{ ucfirst($settings['update_channel']) }}</p>
        </div>

        <!-- Auto Update Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">Auto Update</h3>
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-600">Auto Update Enabled</span>
                <label class="cursor-pointer relative">
                    <input type="checkbox" 
                           id="autoUpdateToggle" 
                           class="toggle toggle-primary" 
                           {{ $settings['auto_update_enabled'] ? 'checked' : '' }}
                           onchange="toggleAutoUpdate(this.checked)">
                </label>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Update Channel</span>
                <select id="channelSelect" class="select select-bordered select-sm w-32" onchange="changeChannel(this.value)">
                    <option value="stable" {{ $settings['update_channel'] === 'stable' ? 'selected' : '' }}>Stable</option>
                    <option value="beta" {{ $settings['update_channel'] === 'beta' ? 'selected' : '' }}>Beta</option>
                    <option value="alpha" {{ $settings['update_channel'] === 'alpha' ? 'selected' : '' }}>Alpha</option>
                </select>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">Quick Actions</h3>
            <div class="space-y-2">
                <button onclick="enableMaintenanceMode()" class="btn btn-outline btn-sm w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Enable Maintenance Mode
                </button>
                <button onclick="rollback()" class="btn btn-outline btn-sm w-full btn-warning" {{ !$settings['maintenance_mode'] ? 'disabled' : '' }}>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                    Rollback
                </button>
            </div>
        </div>
    </div>

    <!-- Available Versions -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold">Available Versions</h2>
        </div>
        <div class="p-4">
            @if(count($availableVersions) > 0)
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Version</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Channel</th>
                            <th>Release Notes</th>
                            <th>Deployed At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availableVersions as $version)
                        <tr class="version-card">
                            <td>
                                <span class="font-mono font-bold">{{ $version['version'] }}</span>
                            </td>
                            <td>
                                <span class="release-type-{{ $version['release_type'] }}">
                                    {{ ucfirst($version['release_type']) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $version['status'] }}">
                                    {{ ucfirst($version['status']) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($version['update_channel'] ?? 'stable') }}</td>
                            <td>
                                <button class="btn btn-ghost btn-xs" onclick="showReleaseNotes('{{ $version['version'] }}', `{{ addslashes($version['release_notes'] ?? '') }}`)">
                                    View Notes
                                </button>
                            </td>
                            <td>
                                {{ $version['deployed_at'] ? \Carbon\Carbon::parse($version['deployed_at'])->format('M d, Y H:i') : 'Not deployed' }}
                            </td>
                            <td>
                                @if($version['status'] === 'stable' && $version['version'] !== $settings['current_version'])
                                <button onclick="deployVersion('{{ $version['version'] }}')" class="btn btn-xs btn-primary">
                                    Deploy
                                </button>
                                @elseif($version['version'] === $settings['current_version'])
                                <span class="text-sm text-gray-500">Current</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p>No versions available</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Deployment History -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold">Deployment History</h2>
        </div>
        <div class="p-4">
            @if(count($deploymentHistory) > 0)
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Version</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Deployed At</th>
                            <th>Deployed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deploymentHistory as $history)
                        <tr>
                            <td class="font-mono font-bold">{{ $history['version'] }}</td>
                            <td>{{ ucfirst($history['release_type']) }}</td>
                            <td>
                                <span class="status-badge status-{{ $history['status'] }}">
                                    {{ ucfirst($history['status']) }}
                                </span>
                            </td>
                            <td>{{ $history['deployed_at'] ? \Carbon\Carbon::parse($history['deployed_at'])->format('M d, Y H:i') : '-' }}</td>
                            <td>{{ $history['created_by'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <p>No deployment history</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Release Notes Modal -->
<dialog id="releaseNotesModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Release Notes - <span id="modalVersion"></span></h3>
        <p id="modalNotes" class="whitespace-pre-wrap"></p>
        <div class="modal-action">
            <button class="btn" onclick="document.getElementById('releaseNotesModal').close()">Close</button>
        </div>
    </div>
</dialog>

@endsection

@push('scripts')
<script>
    // Check for updates
    async function checkForUpdates() {
        try {
            const response = await fetch('{{ route("admin.platform-updates.check") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            const data = await response.json();
            
            if (data.success) {
                if (data.update_available) {
                    Swal.fire({
                        title: 'Update Available!',
                        html: `Version ${data.update.latest_version} is available`,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'No Updates',
                        text: data.message,
                        icon: 'info'
                    });
                }
            }
        } catch (error) {
            console.error('Error checking for updates:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to check for updates',
                icon: 'error'
            });
        }
    }

    // Deploy version
    async function deployVersion(version) {
        const result = await Swal.fire({
            title: 'Deploy Version',
            text: `Are you sure you want to deploy version ${version}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Deploy',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch('{{ route("admin.platform-updates.deploy") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ version })
                });
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error'
                    });
                }
            } catch (error) {
                console.error('Error deploying version:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to deploy version',
                    icon: 'error'
                });
            }
        }
    }

    // Rollback
    async function rollback() {
        const result = await Swal.fire({
            title: 'Rollback',
            text: 'Are you sure you want to rollback to the previous version?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Rollback',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch('{{ route("admin.platform-updates.rollback") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error'
                    });
                }
            } catch (error) {
                console.error('Error rolling back:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to rollback',
                    icon: 'error'
                });
            }
        }
    }

    // Enable maintenance mode
    async function enableMaintenanceMode() {
        try {
            const response = await fetch('{{ route("admin.platform-updates.maintenance.enable") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    title: 'Success',
                    text: 'Maintenance mode enabled',
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            }
        } catch (error) {
            console.error('Error enabling maintenance mode:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to enable maintenance mode',
                icon: 'error'
            });
        }
    }

    // Disable maintenance mode
    async function disableMaintenanceMode() {
        try {
            const response = await fetch('{{ route("admin.platform-updates.maintenance.disable") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    title: 'Success',
                    text: 'Maintenance mode disabled',
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            }
        } catch (error) {
            console.error('Error disabling maintenance mode:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to disable maintenance mode',
                icon: 'error'
            });
        }
    }

    // Toggle auto update
    async function toggleAutoUpdate(enabled) {
        try {
            const response = await fetch('{{ route("admin.platform-updates.settings.update") }}', {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ auto_update_enabled: enabled })
            });
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    title: 'Success',
                    text: 'Settings updated',
                    icon: 'success',
                    timer: 1500
                });
            }
        } catch (error) {
            console.error('Error updating settings:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to update settings',
                icon: 'error'
            });
        }
    }

    // Change channel
    async function changeChannel(channel) {
        try {
            const response = await fetch('{{ route("admin.platform-updates.settings.update") }}', {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ update_channel: channel })
            });
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    title: 'Success',
                    text: 'Channel changed to ' + channel,
                    icon: 'success',
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            }
        } catch (error) {
            console.error('Error changing channel:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to change channel',
                icon: 'error'
            });
        }
    }

    // Show release notes
    function showReleaseNotes(version, notes) {
        document.getElementById('modalVersion').textContent = version;
        document.getElementById('modalNotes').textContent = notes || 'No release notes available';
        document.getElementById('releaseNotesModal').showModal();
    }
</script>
@endpush
