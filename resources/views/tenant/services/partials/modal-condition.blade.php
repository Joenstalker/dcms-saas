{{-- Condition Modal - HTML/JS only --}}
<dialog id="condition-modal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4" id="condition-modal-title">New Condition</h3>
        <form id="condition-form" method="POST" action="{{ route('tenant.services.store.condition', request()->route('tenant')) }}">
            @csrf
            <input type="hidden" name="_method" id="condition-method" value="POST">
            
            <div class="grid grid-cols-2 gap-4">
                <div class="form-control col-span-2">
                    <label class="label"><span class="label-text">Condition Name *</span></label>
                    <input type="text" name="name" id="condition-name" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">ICD-10 Code</span></label>
                    <input type="text" name="icd_code" id="condition-icd_code" class="input input-bordered" placeholder="e.g., E11.9">
                </div>
                <div class="form-control">
                    <label class="label cursor-pointer">
                        <span class="label-text">Active</span>
                        <input type="checkbox" name="is_active" id="condition-is_active" value="1" checked class="checkbox checkbox-primary">
                    </label>
                </div>
                <div class="form-control col-span-2">
                    <label class="label"><span class="label-text">Remarks / Notes</span></label>
                    <textarea name="remarks" id="condition-remarks" class="textarea textarea-bordered h-24"></textarea>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('condition-modal').close(); resetConditionForm();">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
function editCondition(condition) {
    document.getElementById('condition-modal-title').textContent = 'Edit Condition';
    document.getElementById('condition-form').action = '{{ route("tenant.services.update.condition", ["tenant" => request()->route("tenant"), "condition" => "__ID__"]) }}'.replace('__ID__', condition.id || condition._id);
    document.getElementById('condition-method').value = 'PUT';
    document.getElementById('condition-name').value = condition.name || '';
    document.getElementById('condition-icd_code').value = condition.icd_code || '';
    document.getElementById('condition-remarks').value = condition.remarks || '';
    document.getElementById('condition-is_active').checked = condition.is_active != 0;
    document.getElementById('condition-modal').showModal();
}
function resetConditionForm() {
    document.getElementById('condition-modal-title').textContent = 'New Condition';
    document.getElementById('condition-form').action = '{{ route("tenant.services.store.condition", request()->route("tenant")) }}';
    document.getElementById('condition-method').value = 'POST';
    document.getElementById('condition-form').reset();
}
</script>
