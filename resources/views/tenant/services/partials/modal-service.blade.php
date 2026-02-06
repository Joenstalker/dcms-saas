{{-- Service Modal - HTML/JS only --}}
<dialog id="service-modal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4" id="service-modal-title">New Service</h3>
        <form id="service-form" method="POST" action="{{ route('tenant.services.store.service', request()->route('tenant')) }}">
            @csrf
            <input type="hidden" name="_method" id="service-method" value="POST">
            <input type="hidden" id="service-id" value="">
            
            <div class="grid grid-cols-2 gap-4">
                <div class="form-control col-span-2">
                    <label class="label"><span class="label-text">Service Name *</span></label>
                    <input type="text" name="name" id="service-name" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Category</span></label>
                    <select name="category" id="service-category" class="select select-bordered">
                        <option value="">Select Category</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Amount (₱)</span></label>
                    <input type="number" name="amount" id="service-amount" step="0.01" class="input input-bordered" value="0">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Duration (minutes)</span></label>
                    <input type="number" name="duration_minutes" id="service-duration" class="input input-bordered" value="30">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Color</span></label>
                    <input type="color" name="color" id="service-color" class="h-10 w-full" value="#3b82f6">
                </div>
                <div class="form-control col-span-2">
                    <label class="label"><span class="label-text">Description</span></label>
                    <textarea name="description" id="service-description" class="textarea textarea-bordered h-24"></textarea>
                </div>
                <div class="form-control">
                    <label class="label cursor-pointer">
                        <span class="label-text">Auto Add to Invoice</span>
                        <input type="checkbox" name="auto_add" id="service-auto_add" value="1" class="checkbox checkbox-primary">
                    </label>
                </div>
                <div class="form-control">
                    <label class="label cursor-pointer">
                        <span class="label-text">Active</span>
                        <input type="checkbox" name="is_active" id="service-is_active" value="1" checked class="checkbox checkbox-primary">
                    </label>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('service-modal').close(); resetServiceForm();">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
function editService(service) {
    document.getElementById('service-modal-title').textContent = 'Edit Service';
    document.getElementById('service-form').action = '{{ route("tenant.services.update.service", ["tenant" => request()->route("tenant"), "service" => "__ID__"]) }}'.replace('__ID__', service.id || service._id);
    document.getElementById('service-method').value = 'PUT';
    document.getElementById('service-id').value = service.id || service._id;
    document.getElementById('service-name').value = service.name || '';
    document.getElementById('service-category').value = service.category || '';
    document.getElementById('service-amount').value = service.amount || 0;
    document.getElementById('service-duration').value = service.duration_minutes || 30;
    document.getElementById('service-color').value = service.color || '#3b82f6';
    document.getElementById('service-description').value = service.description || '';
    document.getElementById('service-auto_add').checked = service.auto_add == 1;
    document.getElementById('service-is_active').checked = service.is_active != 0;
    document.getElementById('service-modal').showModal();
}
function resetServiceForm() {
    document.getElementById('service-modal-title').textContent = 'New Service';
    document.getElementById('service-form').action = '{{ route("tenant.services.store.service", request()->route("tenant")) }}';
    document.getElementById('service-method').value = 'POST';
    document.getElementById('service-form').reset();
}
</script>
