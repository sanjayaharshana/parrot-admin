import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

// CKEditor configuration
const ckeditorConfig = {
    toolbar: {
        items: [
            'heading',
            '|',
            'bold',
            'italic',
            'link',
            'bulletedList',
            'numberedList',
            '|',
            'outdent',
            'indent',
            '|',
            'imageUpload',
            'blockQuote',
            'insertTable',
            'mediaEmbed',
            'undo',
            'redo'
        ]
    },
    language: 'en',
    image: {
        toolbar: [
            'imageTextAlternative',
            'imageStyle:full',
            'imageStyle:side'
        ]
    },
    table: {
        contentToolbar: [
            'tableColumn',
            'tableRow',
            'mergeTableCells'
        ]
    },
    licenseKey: '',
};

// Initialize CKEditor on all textarea fields with data-ckeditor attribute
function initializeCKEditor() {
    const textareas = document.querySelectorAll('textarea[data-ckeditor]');
    
    textareas.forEach(textarea => {
        const editorId = textarea.id || `ckeditor-${Math.random().toString(36).substr(2, 9)}`;
        textarea.id = editorId;
        
        // Create a container for the editor
        const editorContainer = document.createElement('div');
        editorContainer.className = 'ckeditor-container mb-4';
        textarea.parentNode.insertBefore(editorContainer, textarea);
        
        // Hide the original textarea
        textarea.style.display = 'none';
        
        // Initialize CKEditor
        ClassicEditor
            .create(editorContainer, ckeditorConfig)
            .then(editor => {
                // Set initial content
                if (textarea.value) {
                    editor.setData(textarea.value);
                }
                
                // Update textarea value when editor content changes
                editor.model.document.on('change:data', () => {
                    textarea.value = editor.getData();
                });
                
                // Store editor instance for later use
                window.ckeditorInstances = window.ckeditorInstances || {};
                window.ckeditorInstances[editorId] = editor;
                
                // Add destroy method for cleanup
                editor.destroy = () => {
                    if (window.ckeditorInstances[editorId]) {
                        window.ckeditorInstances[editorId].destroy();
                        delete window.ckeditorInstances[editorId];
                    }
                };
            })
            .catch(error => {
                console.error('CKEditor initialization failed:', error);
                // Fallback: show original textarea
                textarea.style.display = 'block';
            });
    });
}

// Initialize CKEditor when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeCKEditor);
} else {
    initializeCKEditor();
}

// Export for use in other modules
export { initializeCKEditor, ckeditorConfig };
