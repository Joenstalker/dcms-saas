<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'value' => '', 'label' => '', 'variables' => []]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['name', 'value' => '', 'label' => '', 'variables' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="form-control w-full" id="quill-wrapper-<?php echo e($name); ?>">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label): ?>
        <label class="label">
            <span class="label-text"><?php echo e($label); ?></span>
        </label>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div id="toolbar-container-<?php echo e($name); ?>">
        <span class="ql-formats">
            <button class="ql-bold"></button>
            <button class="ql-italic"></button>
            <button class="ql-underline"></button>
            <button class="ql-strike"></button>
        </span>
        <span class="ql-formats">
            <button class="ql-list" value="ordered"></button>
            <button class="ql-list" value="bullet"></button>
        </span>
        <span class="ql-formats">
            <button class="ql-script" value="sub"></button>
            <button class="ql-script" value="super"></button>
        </span>
        <span class="ql-formats">
            <button class="ql-indent" value="-1"></button>
            <button class="ql-indent" value="+1"></button>
        </span>
        <span class="ql-formats">
            <select class="ql-size">
                <option value="small"></option>
                <option selected></option>
                <option value="large"></option>
                <option value="huge"></option>
            </select>
        </span>
        <span class="ql-formats">
            <select class="ql-font"></select>
        </span>
        <span class="ql-formats">
            <select class="ql-align"></select>
        </span>
        <span class="ql-formats">
            <button class="ql-clean"></button>
        </span>
    </div>

    
    <div id="editor-container-<?php echo e($name); ?>" class="h-64 bg-base-100 rounded-b-lg">
        <?php echo $value; ?>

    </div>

    
    <input type="hidden" name="<?php echo e($name); ?>" id="input-<?php echo e($name); ?>" value="<?php echo e($value); ?>">

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($variables) > 0): ?>
        <div class="mt-2 flex flex-wrap gap-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" 
                        onclick="insertVariable('<?php echo e($name); ?>', '<?php echo e($var); ?>')" 
                        class="btn btn-xs btn-outline btn-neutral">
                    <?php echo e($var); ?>

                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<script>
    (function() {
        // Ensure Quill is loaded or wait for it
        const initQuill<?php echo e(str_replace('-', '_', $name)); ?> = function() {
            if (typeof Quill === 'undefined') {
                setTimeout(initQuill<?php echo e(str_replace('-', '_', $name)); ?>, 100);
                return;
            }

            const quill = new Quill('#editor-container-<?php echo e($name); ?>', {
                modules: {
                    toolbar: '#toolbar-container-<?php echo e($name); ?>'
                },
                theme: 'snow'
            });

            // Sync with hidden input
            quill.on('text-change', function() {
                const html = document.querySelector('#editor-container-<?php echo e($name); ?> .ql-editor').innerHTML;
                document.getElementById('input-<?php echo e($name); ?>').value = html;
            });
            
            // Allow programmatic update
            window.quill_<?php echo e(str_replace('-', '_', $name)); ?> = quill;
        };

        // Initialize on load or when modal opens
        // We add a slight delay to ensure DOM is ready if in a modal
        document.addEventListener('DOMContentLoaded', () => {
             initQuill<?php echo e(str_replace('-', '_', $name)); ?>();
        });
        
        // Expose global insert function if not exists
        if (!window.insertVariable) {
            window.insertVariable = function(name, variable) {
                // Find the quill instance
                const quillInstance = window['quill_' + name.replace(/-/g, '_')];
                if (quillInstance) {
                    const range = quillInstance.getSelection(true);
                    quillInstance.insertText(range.index, variable);
                    quillInstance.setSelection(range.index + variable.length);
                }
            };
        }
    })();
</script>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/components/wysiwyg-editor.blade.php ENDPATH**/ ?>