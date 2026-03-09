
<dialog id="consent-modal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4" id="consent-modal-title">New Consent Template</h3>
        <form id="consent-form" method="POST" action="<?php echo e(route('tenant.services.store.consent', request()->route('tenant'))); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="consent-method" value="POST">
            
            <div class="space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Template Label *</span></label>
                    <input type="text" name="label" id="consent-label" class="input input-bordered" required placeholder="e.g., General Dental Consent">
                </div>
                <div class="form-control">
                    <?php if (isset($component)) { $__componentOriginald7ed76000a5d14eac13d9a7b06fc15fe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald7ed76000a5d14eac13d9a7b06fc15fe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.wysiwyg-editor','data' => ['name' => 'content','label' => 'Consent Content','variables' => ['[[PatientName]]', '[[PatientFirstName]]', '[[PatientLastName]]', '[[GuardianName]]', '[[DentistName]]', '[[ClinicName]]', '[[Date]]']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('wysiwyg-editor'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'content','label' => 'Consent Content','variables' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['[[PatientName]]', '[[PatientFirstName]]', '[[PatientLastName]]', '[[GuardianName]]', '[[DentistName]]', '[[ClinicName]]', '[[Date]]'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald7ed76000a5d14eac13d9a7b06fc15fe)): ?>
<?php $attributes = $__attributesOriginald7ed76000a5d14eac13d9a7b06fc15fe; ?>
<?php unset($__attributesOriginald7ed76000a5d14eac13d9a7b06fc15fe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald7ed76000a5d14eac13d9a7b06fc15fe)): ?>
<?php $component = $__componentOriginald7ed76000a5d14eac13d9a7b06fc15fe; ?>
<?php unset($__componentOriginald7ed76000a5d14eac13d9a7b06fc15fe); ?>
<?php endif; ?>
                </div>
                <div class="form-control">
                    <label class="label cursor-pointer">
                        <span class="label-text">Active</span>
                        <input type="checkbox" name="is_active" id="consent-is_active" value="1" checked class="checkbox checkbox-primary">
                    </label>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('consent-modal').close(); resetConsentForm();">Cancel</button>
                <button type="button" class="btn btn-secondary" onclick="previewConsentTemplate()">Preview PDF</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
function previewConsentTemplate() {
    // Show the modal first so the iframe exists
    if (typeof showPdfPreviewModal === 'function') {
        showPdfPreviewModal();
    } else {
        console.error('Preview modal function not found');
        return;
    }

    // Force sync content from editor
    let content = '';
    const editor = document.querySelector('#editor-container-content .ql-editor');
    if (editor) {
        content = editor.innerHTML;
        document.getElementById('input-content').value = content;
    } else {
        content = document.getElementById('input-content').value;
    }

    if (!content || content.trim() === '<p><br></p>' || content.trim() === '') {
        alert('Please enter some content to preview.');
        return;
    }
    
    const formAction = '<?php echo e(route("tenant.services.preview", request()->route("tenant"))); ?>';
    const csrf = document.querySelector('input[name="_token"]').value;
    
    // Create form targeting the iframe
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = formAction;
    form.target = 'pdf-preview-frame'; // Target the iframe name
    
    const inputCsrf = document.createElement('input');
    inputCsrf.type = 'hidden';
    inputCsrf.name = '_token';
    inputCsrf.value = csrf;
    form.appendChild(inputCsrf);
    
    const inputContent = document.createElement('input');
    inputContent.type = 'hidden';
    inputContent.name = 'content';
    inputContent.value = content;
    form.appendChild(inputContent);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
function editConsentTemplate(template) {
    document.getElementById('consent-modal-title').textContent = 'Edit Consent Template';
    document.getElementById('consent-form').action = '<?php echo e(route("tenant.services.update.consent", ["tenant" => request()->route("tenant"), "template" => "__ID__"])); ?>'.replace('__ID__', template.id || template._id);
    document.getElementById('consent-method').value = 'PUT';
    document.getElementById('consent-label').value = template.label || '';
    
    // Set Quill content
    if (window.quill_content) {
        // Use clipboard to handle HTML
        const delta = window.quill_content.clipboard.convert(template.content || '');
        window.quill_content.setContents(delta, 'silent');
    }
    
    document.getElementById('consent-is_active').checked = template.is_active != 0;
    document.getElementById('consent-modal').showModal();
}
function resetConsentForm() {
    document.getElementById('consent-modal-title').textContent = 'New Consent Template';
    document.getElementById('consent-form').action = '<?php echo e(route("tenant.services.store.consent", request()->route("tenant"))); ?>';
    document.getElementById('consent-method').value = 'POST';
    document.getElementById('consent-form').reset();
    
    // Reset Quill content
    if (window.quill_content) {
        window.quill_content.setContents([]);
    }
}
</script>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/services/partials/modal-consent.blade.php ENDPATH**/ ?>