{{-- Prescription Template Modal - HTML/JS only --}}
<dialog id="prescription-modal" class="modal">
    <div class="modal-box max-w-3xl">
        <h3 class="font-bold text-lg mb-4" id="prescription-modal-title">New Prescription Template</h3>
        <form id="prescription-form" method="POST" action="{{ route('tenant.services.store.prescription', request()->route('tenant')) }}">
            @csrf
            <input type="hidden" name="_method" id="prescription-method" value="POST">
            
            <div class="space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Template Label *</span></label>
                    <input type="text" name="label" id="prescription-label" class="input input-bordered" required placeholder="e.g., Common Cold Prescription">
                </div>
                
                <div class="form-control">
                    <x-wysiwyg-editor 
                        name="instructions" 
                        label="Instructions / Content" 
                        :variables="['[[PatientName]]', '[[Date]]', '[[DoctorName]]', '[[ClinicName]]', '[[Medications]]']"
                    />
                </div>

                <div class="form-control">
                    <label class="label cursor-pointer">
                        <span class="label-text">Active</span>
                        <input type="checkbox" name="is_active" id="prescription-is_active" value="1" checked class="checkbox checkbox-primary">
                    </label>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('prescription-modal').close(); resetPrescriptionForm();">Cancel</button>
                <button type="button" class="btn btn-secondary" onclick="previewPrescriptionTemplate()">Preview PDF</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
function previewPrescriptionTemplate() {
    // Show the modal first so the iframe exists
    if (typeof showPdfPreviewModal === 'function') {
        showPdfPreviewModal();
    } else {
        console.error('Preview modal function not found');
        return;
    }

    // Force sync content from editor
    let content = '';
    const editor = document.querySelector('#editor-container-instructions .ql-editor');
    if (editor) {
        content = editor.innerHTML;
        document.getElementById('input-instructions').value = content;
    } else {
        content = document.getElementById('input-instructions').value;
    }

    if (!content || content.trim() === '<p><br></p>' || content.trim() === '') {
        alert('Please enter some content to preview.');
        return;
    }

    const formAction = '{{ route("tenant.services.preview", request()->route("tenant")) }}';
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
    inputContent.name = 'instructions';
    inputContent.value = content;
    form.appendChild(inputContent);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
function editPrescriptionTemplate(template) {
    document.getElementById('prescription-modal-title').textContent = 'Edit Prescription Template';
    document.getElementById('prescription-form').action = '{{ route("tenant.services.update.prescription", ["tenant" => request()->route("tenant"), "template" => "__ID__"]) }}'.replace('__ID__', template.id || template._id);
    document.getElementById('prescription-method').value = 'PUT';
    document.getElementById('prescription-label').value = template.label || '';
    
    // Set Quill content
    if (window.quill_instructions) {
        const delta = window.quill_instructions.clipboard.convert(template.instructions || '');
        window.quill_instructions.setContents(delta, 'silent');
    }

    document.getElementById('prescription-is_active').checked = template.is_active != 0;
    document.getElementById('prescription-modal').showModal();
}
function resetPrescriptionForm() {
    document.getElementById('prescription-modal-title').textContent = 'New Prescription Template';
    document.getElementById('prescription-form').action = '{{ route("tenant.services.store.prescription", request()->route("tenant")) }}';
    document.getElementById('prescription-method').value = 'POST';
    document.getElementById('prescription-form').reset();

    // Reset Quill content
    if (window.quill_instructions) {
        window.quill_instructions.setContents([]);
    }
}
</script>
