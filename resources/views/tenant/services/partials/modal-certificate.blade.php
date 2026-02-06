{{-- Certificate Template Modal - HTML/JS only --}}
<dialog id="certificate-modal" class="modal">
    <div class="modal-box max-w-3xl">
        <h3 class="font-bold text-lg mb-4" id="certificate-modal-title">New Certificate Template</h3>
        <form id="certificate-form" method="POST" action="{{ route('tenant.services.store.certificate', request()->route('tenant')) }}">
            @csrf
            <input type="hidden" name="_method" id="certificate-method" value="POST">
            
            <div class="space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Template Label *</span></label>
                    <input type="text" name="label" id="certificate-label" class="input input-bordered" required placeholder="e.g., Medical Certificate">
                </div>
                
                {{-- Editor Component handles variables --}}
                <div class="form-control">
                    <x-wysiwyg-editor 
                        name="template_html" 
                        label="Certificate Template" 
                        :variables="['[[PatientName]]', '[[Date]]', '[[DoctorName]]', '[[ClinicName]]', '[[Procedure]]', '[[Findings]]', '[[Age]]', '[[Sex]]']"
                    />
                </div>

                <div class="form-control">
                    <label class="label cursor-pointer">
                        <span class="label-text">Active</span>
                        <input type="checkbox" name="is_active" id="certificate-is_active" value="1" checked class="checkbox checkbox-primary">
                    </label>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('certificate-modal').close(); resetCertificateForm();">Cancel</button>
                <button type="button" class="btn btn-secondary" onclick="previewCertificateTemplate()">Preview PDF</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
function previewCertificateTemplate() {
    // Show the modal first so the iframe exists
    if (typeof showPdfPreviewModal === 'function') {
        showPdfPreviewModal();
    } else {
        console.error('Preview modal function not found');
        return;
    }

    // Force sync content from editor
    let content = '';
    const editor = document.querySelector('#editor-container-template_html .ql-editor');
    if (editor) {
        content = editor.innerHTML;
        document.getElementById('input-template_html').value = content;
    } else {
        content = document.getElementById('input-template_html').value;
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
    inputContent.name = 'template_html';
    inputContent.value = content;
    form.appendChild(inputContent);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
function editCertificateTemplate(template) {
    document.getElementById('certificate-modal-title').textContent = 'Edit Certificate Template';
    document.getElementById('certificate-form').action = '{{ route("tenant.services.update.certificate", ["tenant" => request()->route("tenant"), "template" => "__ID__"]) }}'.replace('__ID__', template.id || template._id);
    document.getElementById('certificate-method').value = 'PUT';
    document.getElementById('certificate-label').value = template.label || '';
    
    // Set Quill content
    if (window.quill_template_html) {
        const delta = window.quill_template_html.clipboard.convert(template.template_html || '');
        window.quill_template_html.setContents(delta, 'silent');
    }

    document.getElementById('certificate-is_active').checked = template.is_active != 0;
    document.getElementById('certificate-modal').showModal();
}
function resetCertificateForm() {
    document.getElementById('certificate-modal-title').textContent = 'New Certificate Template';
    document.getElementById('certificate-form').action = '{{ route("tenant.services.store.certificate", request()->route("tenant")) }}';
    document.getElementById('certificate-method').value = 'POST';
    document.getElementById('certificate-form').reset();

    // Reset Quill content
    if (window.quill_template_html) {
        window.quill_template_html.setContents([]);
    }
}
</script>
