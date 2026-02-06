{{-- Services Table - Pure Server-Side --}}
<div class="space-y-4">
    {{-- Search and Actions --}}
    <div class="flex items-center justify-between">
        <form method="GET" class="flex items-center gap-3">
            <input type="hidden" name="tab" value="services">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search services..." class="input input-bordered w-64">
            <select name="category" class="select select-bordered" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-ghost btn-sm">Search</button>
        </form>
        <button onclick="document.getElementById('service-modal').showModal()" class="btn btn-primary gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Service
        </button>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto" id="table-content">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Duration</th>
                    <th>Auto</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $service)
                <tr>
                    <td>{{ $items->firstItem() + $index }}</td>
                    <td>
                        <div class="font-medium">{{ $service->name }}</div>
                        <div class="text-xs text-base-content/60">{{ Str::limit($service->description, 50) ?: '-' }}</div>
                    </td>
                    <td><span class="badge badge-outline">{{ $service->category ?: '-' }}</span></td>
                    <td class="font-medium">₱{{ number_format($service->amount, 2) }}</td>
                    <td>{{ $service->duration_minutes ?? 30 }} min</td>
                    <td><input type="checkbox" {{ $service->auto_add ? 'checked' : '' }} disabled class="checkbox checkbox-sm"></td>
                    <td><span class="badge {{ $service->is_active ? 'badge-success' : 'badge-ghost' }}">{{ $service->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <div class="flex gap-1">
                            <button onclick="editService({{ json_encode($service) }})" class="btn btn-ghost btn-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('tenant.services.destroy.service', ['tenant' => request()->route('tenant'), 'service' => $service->id]) }}" data-confirm-delete="Delete this service?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-xs text-error">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-8 text-base-content/50">No services found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div id="pagination-container">
        @if($items->hasPages())
        <div class="flex justify-center">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>
