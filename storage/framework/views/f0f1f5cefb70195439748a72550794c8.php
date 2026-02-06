
<dialog id="medicine-modal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4" id="medicine-modal-title">New Medicine</h3>
        <form id="medicine-form" method="POST" action="<?php echo e(route('tenant.services.store.medicine', request()->route('tenant'))); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="medicine-method" value="POST">
            
            <div class="grid grid-cols-2 gap-4">
                <div class="form-control col-span-2">
                    <label class="label"><span class="label-text">Medicine Name *</span></label>
                    <input type="text" name="name" id="medicine-name" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Generic Name</span></label>
                    <input type="text" name="generic_name" id="medicine-generic_name" class="input input-bordered">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Dosage</span></label>
                    <input type="text" name="dosage" id="medicine-dosage" class="input input-bordered" placeholder="e.g., 500">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Unit</span></label>
                    <select name="unit" id="medicine-unit" class="select select-bordered">
                        <option value="">Select Unit</option>
                        <option value="mg">mg</option>
                        <option value="g">g</option>
                        <option value="ml">ml</option>
                        <option value="mcg">mcg</option>
                        <option value="IU">IU</option>
                        <option value="tablet">tablet(s)</option>
                        <option value="capsule">capsule(s)</option>
                        <option value="drop">drop(s)</option>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label cursor-pointer">
                        <span class="label-text">Active</span>
                        <input type="checkbox" name="is_active" id="medicine-is_active" value="1" checked class="checkbox checkbox-primary">
                    </label>
                </div>
                <div class="form-control col-span-2">
                    <label class="label"><span class="label-text">Description</span></label>
                    <textarea name="description" id="medicine-description" class="textarea textarea-bordered h-24"></textarea>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('medicine-modal').close(); resetMedicineForm();">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
function editMedicine(med) {
    document.getElementById('medicine-modal-title').textContent = 'Edit Medicine';
    document.getElementById('medicine-form').action = '<?php echo e(route("tenant.services.update.medicine", ["tenant" => request()->route("tenant"), "medicine" => "__ID__"])); ?>'.replace('__ID__', med.id || med._id);
    document.getElementById('medicine-method').value = 'PUT';
    document.getElementById('medicine-name').value = med.name || '';
    document.getElementById('medicine-generic_name').value = med.generic_name || '';
    document.getElementById('medicine-dosage').value = med.dosage || '';
    document.getElementById('medicine-unit').value = med.unit || '';
    document.getElementById('medicine-description').value = med.description || '';
    document.getElementById('medicine-is_active').checked = med.is_active != 0;
    document.getElementById('medicine-modal').showModal();
}
function resetMedicineForm() {
    document.getElementById('medicine-modal-title').textContent = 'New Medicine';
    document.getElementById('medicine-form').action = '<?php echo e(route("tenant.services.store.medicine", request()->route("tenant"))); ?>';
    document.getElementById('medicine-method').value = 'POST';
    document.getElementById('medicine-form').reset();
}
</script>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/services/partials/modal-medicine.blade.php ENDPATH**/ ?>