
<dialog id="pdf-preview-modal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl h-[90vh] flex flex-col p-0 overflow-hidden">
        
        <div class="flex items-center justify-between p-4 border-b bg-base-100">
            <h3 class="font-bold text-lg">Document Preview</h3>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost">✕</button>
            </form>
        </div>
        
        
        <div class="flex-1 bg-base-200 p-4 overflow-hidden">
            <iframe name="pdf-preview-frame" class="w-full h-full rounded-lg shadow-sm bg-white border-0" title="PDF Preview"></iframe>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
    function showPdfPreviewModal() {
        document.getElementById('pdf-preview-modal').showModal();
    }
</script>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/services/partials/modal-preview.blade.php ENDPATH**/ ?>