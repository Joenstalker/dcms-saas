@props(['name', 'value' => '', 'label' => '', 'variables' => []])

<div class="form-control w-full" id="quill-wrapper-{{ $name }}">
    @if($label)
        <label class="label">
            <span class="label-text">{{ $label }}</span>
        </label>
    @endif

    {{-- Toolbar --}}
    <div id="toolbar-container-{{ $name }}">
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

    {{-- Editor Container --}}
    <div id="editor-container-{{ $name }}" class="h-64 bg-base-100 rounded-b-lg">
        {!! $value !!}
    </div>

    {{-- Hidden Input for Form Submission --}}
    <input type="hidden" name="{{ $name }}" id="input-{{ $name }}" value="{{ $value }}">

    {{-- Variable Buttons --}}
    @if(count($variables) > 0)
        <div class="mt-2 flex flex-wrap gap-2">
            @foreach($variables as $var)
                <button type="button" 
                        onclick="insertVariable('{{ $name }}', '{{ $var }}')" 
                        class="btn btn-xs btn-outline btn-neutral">
                    {{ $var }}
                </button>
            @endforeach
        </div>
    @endif
</div>

<script>
    (function() {
        // Ensure Quill is loaded or wait for it
        const initQuill{{ str_replace('-', '_', $name) }} = function() {
            if (typeof Quill === 'undefined') {
                setTimeout(initQuill{{ str_replace('-', '_', $name) }}, 100);
                return;
            }

            const quill = new Quill('#editor-container-{{ $name }}', {
                modules: {
                    toolbar: '#toolbar-container-{{ $name }}'
                },
                theme: 'snow'
            });

            // Sync with hidden input
            quill.on('text-change', function() {
                const html = document.querySelector('#editor-container-{{ $name }} .ql-editor').innerHTML;
                document.getElementById('input-{{ $name }}').value = html;
            });
            
            // Allow programmatic update
            window.quill_{{ str_replace('-', '_', $name) }} = quill;
        };

        // Initialize on load or when modal opens
        // We add a slight delay to ensure DOM is ready if in a modal
        document.addEventListener('DOMContentLoaded', () => {
             initQuill{{ str_replace('-', '_', $name) }}();
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
