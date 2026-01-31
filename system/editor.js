/**
 * Stanza Markdown Editor Bridge (Milkdown Crepe) - PRO Level UI/UX
 * Custom floating tooltip toolbar implementation
 */

let crepeInstance = null;
let currentFilename = null;
let currentCollection = null;
let tooltipElement = null;
let selectionCheckInterval = null;

// Inject CSS for the refined, minimalist editor UI
const style = document.createElement('style');
style.textContent = `
    #stanza-editor-overlay {
        position: fixed;
        inset: 0;
        background: var(--bg-color);
        z-index: 9998;
        display: none;
        flex-direction: column;
        animation: fadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: var(--font-main);
    }
    #stanza-editor-container {
        flex: 1;
        overflow-y: auto;
        padding: 10vh 2rem;
        display: flex;
        justify-content: center;
    }
    #milkdown-root {
        width: 100%;
        max-width: 800px;
        position: relative;
    }

    /* ============================================
       AGGRESSIVE HIDE: ALL Crepe/Milkdown UI
       ============================================ */
    .crepe-toolbar,
    .milkdown-toolbar,
    [class*="crepe-toolbar"],
    [class*="crepe-feature"],
    .milkdown-menu,
    .milkdown-link-preview,
    .milkdown-link-edit,
    .milkdown-latex-inline-edit,
    .milkdown-slash-menu,
    .milkdown-block-handle,
    .milkdown-check-item,
    .crepe-drop-cursor,
    .milkdown-floating-menu,
    milkdown-toolbar,
    milkdown-slash-menu,
    milkdown-block-handle {
        display: none !important;
        visibility: hidden !important;
        pointer-events: none !important;
        opacity: 0 !important;
    }
    
    /* Fix virtual cursor causing layout shift */
    .prosemirror-virtual-cursor,
    .prosemirror-virtual-cursor-animation,
    .ProseMirror-widget {
        position: absolute !important;
        height: 0 !important;
        width: 0 !important;
        overflow: hidden !important;
        pointer-events: none !important;
    }

    /* ============================================
       CUSTOM STANZA FLOATING TOOLBAR (Pill Style)
       ============================================ */
    #stanza-floating-toolbar {
        position: fixed;
        background: var(--accent-color);
        color: #fff;
        padding: 8px 16px;
        border-radius: 50px;
        display: none;
        gap: 4px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
        z-index: 10001;
        align-items: center;
        white-space: nowrap;
        pointer-events: auto;
    }
    
    #stanza-floating-toolbar.visible {
        display: flex !important;
    }
    
    #stanza-floating-toolbar button {
        background: none;
        border: none;
        color: white;
        padding: 6px 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
    }
    
    #stanza-floating-toolbar button:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    
    #stanza-floating-toolbar button.active {
        background: rgba(255, 255, 255, 0.3);
    }

    #stanza-floating-toolbar button svg {
        width: 16px;
        height: 16px;
        stroke-width: 2px;
    }

    #stanza-floating-toolbar .divider {
        width: 1px;
        height: 16px;
        background: rgba(255, 255, 255, 0.3);
        margin: 0 4px;
    }

    /* Editor Bottom-Right Action Toolbar (Identical to Admin) */
    #stanza-editor-actions {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: var(--accent-color);
        color: #fff;
        padding: 12px 24px;
        border-radius: 50px;
        display: flex;
        gap: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        z-index: 10000;
        backdrop-filter: blur(5px);
    }
    #stanza-editor-actions button {
        color: #fff;
        text-decoration: none;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: opacity 0.3s ease;
        background: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }
    #stanza-editor-actions button:hover {
        opacity: 0.8;
    }

    /* Hide regular admin toolbar when editor is active */
    body.editor-active .admin-toolbar {
        display: none !important;
    }

    /* Typography Match - Stanza PRO Level */
    .milkdown .editor,
    .milkdown-crepe,
    [data-milkdown-root] {
        background: transparent !important;
        color: var(--text-color) !important;
        font-family: var(--font-main) !important;
    }
    .milkdown .editor .ProseMirror,
    .milkdown-crepe .ProseMirror,
    [data-milkdown-root] .ProseMirror {
        font-size: 24px !important;
        line-height: 1.7 !important;
        outline: none !important;
        border: none !important;
        box-shadow: none !important;
        min-height: 60vh;
        padding: 0 !important;
        padding-bottom: 20vh !important;
        margin: 0 !important;
    }
    
    /* Remove ALL focus outlines/borders from editor elements */
    .milkdown *:focus,
    .milkdown-crepe *:focus,
    [data-milkdown-root] *:focus,
    #milkdown-root *:focus,
    .ProseMirror:focus,
    .ProseMirror *:focus,
    .milkdown .editor:focus,
    .milkdown-crepe:focus {
        outline: none !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    /* Prevent any focus-visible styling */
    .milkdown *:focus-visible,
    .milkdown-crepe *:focus-visible,
    [data-milkdown-root] *:focus-visible,
    #milkdown-root *:focus-visible,
    .ProseMirror:focus-visible {
        outline: none !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    /* Reset any Milkdown wrapper focus styles */
    .milkdown,
    .milkdown-crepe,
    [data-milkdown-root],
    #milkdown-root > div {
        outline: none !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
    .milkdown h1, .milkdown-crepe h1, [data-milkdown-root] h1,
    .milkdown h2, .milkdown-crepe h2, [data-milkdown-root] h2,
    .milkdown h3, .milkdown-crepe h3, [data-milkdown-root] h3 {
        font-weight: 400 !important;
        color: var(--accent-color) !important;
        margin-top: 3rem !important;
        margin-bottom: 1.5rem !important;
        font-family: var(--font-main) !important;
    }

    /* Link Styling - Stanza PRO Level */
    .ProseMirror a {
        color: var(--accent-color) !important;
        text-decoration: underline !important;
        cursor: pointer;
    }
    
    /* Animation for the overlay */
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
`;
document.head.appendChild(style);

// Load essential styles
const link = document.createElement('link');
link.rel = 'stylesheet';
link.href = 'https://esm.sh/@milkdown/crepe@7/theme/common.css';
document.head.appendChild(link);

// SVG Icons for toolbar buttons
const icons = {
    bold: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/></svg>',
    italic: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>',
    strikethrough: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 4H9a3 3 0 0 0-3 3v0a3 3 0 0 0 3 3h6a3 3 0 0 1 3 3v0a3 3 0 0 1-3 3H8"/><line x1="4" y1="12" x2="20" y2="12"/></svg>',
    link: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>',
    quote: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/></svg>',
};

function createFloatingToolbar() {
    if (tooltipElement) return tooltipElement;
    
    tooltipElement = document.createElement('div');
    tooltipElement.id = 'stanza-floating-toolbar';
    tooltipElement.innerHTML = `
        <button data-command="paragraph" title="Body Text">P</button>
        <button data-command="h1" title="Heading 1">H1</button>
        <button data-command="h2" title="Heading 2">H2</button>
        <button data-command="h3" title="Heading 3">H3</button>
        <span class="divider"></span>
        <button data-command="bold" title="Bold">${icons.bold}</button>
        <button data-command="italic" title="Italic">${icons.italic}</button>
        <button data-command="strikethrough" title="Strikethrough">${icons.strikethrough}</button>
        <button data-command="link" title="Link">${icons.link}</button>
        <button data-command="quote" title="Quote">${icons.quote}</button>
    `;
    
    // Prevent mousedown from stealing focus/selection
    tooltipElement.addEventListener('mousedown', (e) => {
        e.preventDefault();
        e.stopPropagation();
    });
    
    document.body.appendChild(tooltipElement);
    return tooltipElement;
}

function updateToolbarPosition() {
    const selection = window.getSelection();
    
    // Hide if no selection or collapsed
    if (!selection || selection.isCollapsed || !selection.toString().trim()) {
        tooltipElement?.classList.remove('visible');
        return;
    }
    
    // Check if selection is within the editor
    const editor = document.querySelector('#milkdown-root .ProseMirror');
    if (!editor) {
        tooltipElement?.classList.remove('visible');
        return;
    }
    
    // Ensure selection is inside editor
    const range = selection.getRangeAt(0);
    if (!editor.contains(range.commonAncestorContainer)) {
        tooltipElement?.classList.remove('visible');
        return;
    }
    
    // Get selection bounds
    const rect = range.getBoundingClientRect();
    if (rect.width === 0 && rect.height === 0) {
        tooltipElement?.classList.remove('visible');
        return;
    }
    
    // Position toolbar above selection, centered
    const toolbarRect = tooltipElement.getBoundingClientRect();
    const left = rect.left + (rect.width / 2) - (toolbarRect.width / 2);
    const top = rect.top - toolbarRect.height - 10; // 10px gap above selection
    
    // Clamp to viewport
    const clampedLeft = Math.max(10, Math.min(left, window.innerWidth - toolbarRect.width - 10));
    const clampedTop = Math.max(10, top);
    
    tooltipElement.style.left = `${clampedLeft}px`;
    tooltipElement.style.top = `${clampedTop}px`;
    tooltipElement.classList.add('visible');
    
    // Update active states
    updateToolbarActiveStates();
}

function updateToolbarActiveStates() {
    const view = getProseMirrorView();
    if (!view || !view.state) return;
    
    const { state } = view;
    const toolbar = document.getElementById('stanza-floating-toolbar');
    if (!toolbar) return;
    
    // Clear all active states first
    toolbar.querySelectorAll('button').forEach(btn => btn.classList.remove('active'));
    
    // Check for active marks
    const marks = state.storedMarks || state.selection.$from.marks();
    const activeMarks = new Set();
    marks.forEach(mark => activeMarks.add(mark.type.name));
    
    // Check for active block type
    const $from = state.selection.$from;
    const blockType = $from.parent.type.name;
    const blockAttrs = $from.parent.attrs;
    
    // Apply active class to corresponding buttons
    toolbar.querySelectorAll('button[data-command]').forEach(btn => {
        const command = btn.dataset.command;
        
        // Mark-based commands
        if (command === 'bold' && activeMarks.has('strong')) {
            btn.classList.add('active');
        }
        if (command === 'italic' && activeMarks.has('em')) {
            btn.classList.add('active');
        }
        if (command === 'strikethrough' && (activeMarks.has('strike_through') || activeMarks.has('strikethrough'))) {
            btn.classList.add('active');
        }
        if (command === 'link' && activeMarks.has('link')) {
            btn.classList.add('active');
        }
        
        // Block-based commands
        if (command === 'h1' && blockType === 'heading' && blockAttrs.level === 1) {
            btn.classList.add('active');
        }
        if (command === 'h2' && blockType === 'heading' && blockAttrs.level === 2) {
            btn.classList.add('active');
        }
        if (command === 'h3' && blockType === 'heading' && blockAttrs.level === 3) {
            btn.classList.add('active');
        }
        if (command === 'paragraph' && blockType === 'paragraph') {
            btn.classList.add('active');
        }
        if (command === 'quote' && blockType === 'blockquote') {
            btn.classList.add('active');
        }
    });
}

function initEditorOverlay() {
    if (document.getElementById('stanza-editor-overlay')) return;

    const overlay = document.createElement('div');
    overlay.id = 'stanza-editor-overlay';
    overlay.innerHTML = `
        <div id="stanza-editor-container">
            <div id="milkdown-root"></div>
        </div>
        <div id="stanza-editor-actions">
            <button onclick="closeEditor()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <span>Cancel</span>
            </button>
            <button onclick="savePoem()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Save</span>
            </button>
        </div>
    `;
    document.body.appendChild(overlay);
    
    // Create floating toolbar (appended to body for fixed positioning)
    createFloatingToolbar();
}

async function openEditor(filename = null) {
    initEditorOverlay();
    currentFilename = filename;
    
    const urlParams = new URLSearchParams(window.location.search);
    currentCollection = urlParams.get('c');

    if (!currentCollection) {
        console.error('Collection not found in URL');
        return;
    }

    document.getElementById('stanza-editor-overlay').style.display = 'flex';
    document.body.classList.add('editor-active');
    document.body.style.overflow = 'hidden';

    let content = '';
    if (filename) {
        try {
            const response = await fetch(`system/editor-handler.php?collection=${currentCollection}&file=${filename}`);
            content = await response.text();
        } catch (e) {
            console.error('Failed to load poem', e);
        }
    } else {
        content = '# New Poem\n\nStart writing here...';
    }

    setupMilkdown(content);
}

function closeEditor() {
    document.getElementById('stanza-editor-overlay').style.display = 'none';
    document.body.classList.remove('editor-active');
    document.body.style.overflow = '';
    tooltipElement?.classList.remove('visible');
    
    // Stop selection checking
    if (selectionCheckInterval) {
        clearInterval(selectionCheckInterval);
        selectionCheckInterval = null;
    }
}

async function setupMilkdown(content) {
    const { Crepe } = await import('https://esm.sh/@milkdown/crepe@7?bundle');
    
    // Destroy previous instance if it exists
    if (crepeInstance) {
        try {
            crepeInstance.destroy();
        } catch (e) {
            console.warn('Failed to destroy previous crepe instance', e);
        }
    }

    const root = document.getElementById('milkdown-root');
    root.innerHTML = '';

    crepeInstance = new Crepe({
        root,
        defaultValue: content,
        features: {
            [Crepe.Feature.Toolbar]: false,
            [Crepe.Feature.BlockEdit]: false,
            [Crepe.Feature.LinkTooltip]: false,
            [Crepe.Feature.ImageBlock]: false,
        }
    });

    await crepeInstance.create();
    
    // Wait for editor to be fully ready
    await new Promise(resolve => setTimeout(resolve, 150));
    
    // Verify editor DOM is ready
    const editorElement = document.querySelector('#milkdown-root .ProseMirror');
    if (!editorElement) {
        console.error('Editor DOM not ready after initialization');
        return;
    }
    
    // Selection change monitoring using interval (most reliable for contenteditable)
    let lastSelectionText = '';
    selectionCheckInterval = setInterval(() => {
        const currentText = window.getSelection()?.toString() || '';
        if (currentText !== lastSelectionText) {
            lastSelectionText = currentText;
            updateToolbarPosition();
        }
    }, 50);
    
    // Also update on mouse/keyboard events for responsiveness
    const editorContainer = document.getElementById('stanza-editor-container');
    editorContainer.addEventListener('mouseup', () => {
        setTimeout(updateToolbarPosition, 10);
    });
    editorContainer.addEventListener('keyup', () => {
        setTimeout(updateToolbarPosition, 10);
    });
    
    // Setup toolbar button handlers
    setupToolbarCommands();
}

function setupToolbarCommands() {
    const toolbar = document.getElementById('stanza-floating-toolbar');
    if (!toolbar) return;
    
    toolbar.querySelectorAll('button').forEach(btn => {
        // Remove old listeners by cloning
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        newBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            const command = newBtn.dataset.command;
            
            try {
                // Get the ProseMirror view - traverse pmViewDesc chain
                const view = getProseMirrorView();
                if (!view) {
                    console.error('Could not access ProseMirror view');
                    return;
                }
                
                const { state, dispatch } = view;
                const { from, to } = state.selection;
                const schema = state.schema;
                
                switch(command) {
                    case 'bold':
                        toggleMark(view, schema.marks.strong);
                        break;
                    case 'italic':
                        toggleMark(view, schema.marks.em || schema.marks.emphasis);
                        break;
                    case 'strikethrough':
                        // Logged as strike_through in Milkdown 7
                        toggleMark(view, schema.marks.strike_through || schema.marks.strikethrough);
                        break;
                    case 'link':
                        showLinkModal(view, from, to);
                        break;
                    case 'paragraph':
                        setBlockType(view, schema.nodes.paragraph);
                        break;
                    case 'h1':
                        setBlockType(view, schema.nodes.heading, { level: 1 });
                        break;
                    case 'h2':
                        setBlockType(view, schema.nodes.heading, { level: 2 });
                        break;
                    case 'h3':
                        setBlockType(view, schema.nodes.heading, { level: 3 });
                        break;
                    case 'quote':
                        // Check if blockquote exists in schema
                        if (schema.nodes.blockquote) {
                            toggleWrap(view, schema.nodes.blockquote);
                        }
                        break;
                }
                
                view.focus();
            } catch (err) {
                console.error('Command error:', err);
            }
            
            // Keep toolbar visible briefly after action
            setTimeout(updateToolbarPosition, 50);
        });
    });
}

// Get ProseMirror view by traversing the pmViewDesc chain
function getProseMirrorView() {
    const pmElement = document.querySelector('.ProseMirror');
    if (!pmElement) return null;
    
    // Method 1: Check pmViewDesc chain
    let desc = pmElement.pmViewDesc;
    while (desc) {
        if (desc.view) return desc.view;
        desc = desc.parent;
    }
    
    // Method 2: Check for internal properties
    for (const key of Object.keys(pmElement)) {
        if (key.startsWith('__pm') && pmElement[key]?.view) {
            return pmElement[key].view;
        }
    }
    
    // Method 3: Try symbols
    for (const sym of Object.getOwnPropertySymbols(pmElement)) {
        const val = pmElement[sym];
        if (val && typeof val === 'object' && val.state && val.dispatch) {
            return val;
        }
    }
    
    // Method 4: Use crepeInstance if available
    if (crepeInstance?.editor) {
        try {
            let foundView = null;
            crepeInstance.editor.action((ctx) => {
                foundView = ctx.get('editorView');
            });
            if (foundView) return foundView;
        } catch (e) {}
    }
    
    return null;
}

// Show custom link modal
function showLinkModal(view, from, to) {
    const { state } = view;
    const schema = state.schema;
    
    if (!schema.marks.link) return;
    
    // Create modal overlay
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10005;
        font-family: var(--font-main, serif);
    `;
    
    const modalContent = document.createElement('div');
    modalContent.style.cssText = `
        background: var(--bg-color, #e4e2d7);
        padding: 30px;
        border-radius: 12px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        border: 1px solid var(--accent-color, #8c3b3b);
    `;
    
    modalContent.innerHTML = `
        <h3 style="margin-top: 0; color: var(--accent-color, #8c3b3b); font-weight: 400; margin-bottom: 20px;">Insert Link</h3>
        <input 
            type="text" 
            id="link-url-input" 
            placeholder="https://example.com" 
            style="
                width: 100%; 
                padding: 12px; 
                margin-bottom: 20px; 
                border: 1px solid var(--accent-color, #8c3b3b);
                border-radius: 4px;
                background: rgba(255, 255, 255, 0.5);
                font-family: inherit;
                font-size: 1rem;
                outline: none;
            "
        >
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button 
                id="link-cancel-btn"
                style="
                    padding: 10px 20px;
                    background: transparent;
                    border: 1px solid var(--accent-color, #8c3b3b);
                    color: var(--accent-color, #8c3b3b);
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 1rem;
                "
            >Cancel</button>
            <button 
                id="link-ok-btn"
                style="
                    padding: 10px 20px;
                    background: var(--accent-color, #8c3b3b);
                    border: none;
                    color: #fff;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 1rem;
                "
            >Add Link</button>
        </div>
    `;
    
    modal.appendChild(modalContent);
    document.body.appendChild(modal);
    
    const input = document.getElementById('link-url-input');
    const okBtn = document.getElementById('link-ok-btn');
    const cancelBtn = document.getElementById('link-cancel-btn');
    
    // Focus input
    setTimeout(() => input.focus(), 100);
    
    // Handle OK
    const handleOk = () => {
        const url = input.value.trim();
        if (url) {
            const linkMark = schema.marks.link.create({ href: url });
            view.dispatch(state.tr.addMark(from, to, linkMark));
        }
        document.body.removeChild(modal);
        view.focus();
    };
    
    // Handle Cancel
    const handleCancel = () => {
        document.body.removeChild(modal);
        view.focus();
    };
    
    okBtn.addEventListener('click', handleOk);
    cancelBtn.addEventListener('click', handleCancel);
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleOk();
        } else if (e.key === 'Escape') {
            e.preventDefault();
            handleCancel();
        }
    });
    
    // Prevent modal from closing when clicking inside
    modalContent.addEventListener('click', (e) => {
        e.stopPropagation();
    });
    
    // Close on overlay click
    modal.addEventListener('click', handleCancel);
}

// Helper: Toggle a mark on selection
function toggleMark(view, markType) {
    if (!markType) return;
    const { state, dispatch } = view;
    const { from, to, empty } = state.selection;
    
    if (empty) return;
    
    const hasMark = state.doc.rangeHasMark(from, to, markType);
    if (hasMark) {
        dispatch(state.tr.removeMark(from, to, markType));
    } else {
        dispatch(state.tr.addMark(from, to, markType.create()));
    }
}

// Helper: Set block type (for headings)
function setBlockType(view, nodeType, attrs = {}) {
    if (!nodeType) return;
    const { state, dispatch } = view;
    const { $from, $to } = state.selection;
    
    // Check if we can change the block type
    const range = $from.blockRange($to);
    if (!range) return;
    
    const tr = state.tr;
    tr.setBlockType(range.start, range.end, nodeType, attrs);
    dispatch(tr);
}

// Helper: Toggle Wrap (Blockquote)
function toggleWrap(view, nodeType) {
    if (!nodeType) return;
    const { state, dispatch } = view;
    const { $from, $to } = state.selection;
    const range = $from.blockRange($to);
    if (!range) return;
    
    // Check if we are already in this node type
    const wrapping = range.parent.type === nodeType;
    if (wrapping) {
        // Lift out
        const tr = state.tr.lift(range, range.depth - 1);
        dispatch(tr);
    } else {
        // Wrap
        const tr = state.tr.wrap(range, [{type: nodeType}]);
        dispatch(tr);
    }
}

// Helper: Toggle List (UL, OL)
function toggleList(view, listType, itemType) {
    if (!listType || !itemType) return;
    const { state, dispatch } = view;
    const { $from, $to } = state.selection;
    const range = $from.blockRange($to);
    if (!range) return;

    // Check if we're in a list of this type
    let listDepth = -1;
    for (let d = $from.depth; d >= 0; d--) {
        const node = $from.node(d);
        if (node.type === listType) {
            listDepth = d;
            break;
        }
    }
    
    if (listDepth >= 0) {
        // Lift out of the list
        try {
            dispatch(state.tr.lift(range, listDepth - 1));
            return;
        } catch (e) {
            console.error('Failed to lift from list:', e);
        }
    }

    // Create a new list
    try {
        const tr = state.tr;
        const listItems = [];
        const schema = state.schema;
        
        // Wrap each selected block in a list item
        for (let i = range.startIndex; i < range.endIndex; i++) {
            let child = range.parent.child(i);
            
            // Schema requirement: list_item must start with a paragraph
            if (child.type.name !== 'paragraph') {
                // Convert non-paragraphs (like headings) to paragraphs
                child = schema.nodes.paragraph.create(null, child.content, child.marks);
            }
            
            const item = itemType.createAndFill(null, child);
            if (item) listItems.push(item);
        }
        
        if (listItems.length > 0) {
            const listNode = listType.createAndFill(null, listItems);
            if (listNode) {
                tr.replaceRangeWith(range.start, range.end, listNode);
                dispatch(tr);
            }
        }
    } catch (e) {
        console.error('Failed to create list:', e);
    }
}

// Helper: Toggle Task List
function toggleTaskList(view) {
    const { state, dispatch } = view;
    const { $from, $to } = state.selection;
    const schema = state.schema;
    const listType = schema.nodes.bullet_list;
    const itemType = schema.nodes.list_item;
    
    if (!listType || !itemType) return;

    const range = $from.blockRange($to);
    if (!range) return;
    
    // Check if we're already in a task list (bullet_list where items have checked attr)
    let listDepth = -1;
    let isTaskList = false;
    
    for (let d = $from.depth; d >= 0; d--) {
        const node = $from.node(d);
        if (node.type === listType) {
            listDepth = d;
            // Check first item to see if it's a task item
            const firstItem = node.firstChild;
            if (firstItem && firstItem.attrs && firstItem.attrs.checked !== undefined && firstItem.attrs.checked !== null) {
                isTaskList = true;
            }
            break;
        }
    }
    
    if (listDepth >= 0 && isTaskList) {
        // Lift out
        try {
            dispatch(state.tr.lift(range, listDepth - 1));
            return;
        } catch (e) {
            console.error('Failed to lift from task list:', e);
        }
    }

    // Create a new task list
    try {
        const tr = state.tr;
        const listItems = [];
        for (let i = range.startIndex; i < range.endIndex; i++) {
            let child = range.parent.child(i);
            
            if (child.type.name !== 'paragraph') {
                child = schema.nodes.paragraph.create(null, child.content, child.marks);
            }
            
            // In Milkdown 7, task list items are list_items with checked: false
            const item = itemType.createAndFill({ checked: false }, child);
            if (item) listItems.push(item);
        }
        
        if (listItems.length > 0) {
            const listNode = listType.createAndFill(null, listItems);
            if (listNode) {
                tr.replaceRangeWith(range.start, range.end, listNode);
                dispatch(tr);
            }
        }
    } catch (e) {
        console.error('Failed to lift from blockquote:', e);
    }
}

async function savePoem() {
    if (!crepeInstance) return;

    let content = '';
    try {
        content = crepeInstance.getMarkdown();
    } catch (e) {
        console.warn('Crepe getMarkdown failed, trying fallback', e);
        const view = getProseMirrorView();
        if (view) {
            alert('Editor state error. Try selecting text or typing something first.');
            return;
        }
    }

    if (!content) return;
    
    // Clean up markdown: convert <br /> tags to actual newlines
    content = content.replace(/<br\s*\/?>/gi, '\n');
    // Remove 4-space indentation that Milkdown sometimes adds (causes code blocks)
    content = content.replace(/^    /gm, '');
    
    const lines = content.split('\n');
    const firstLine = lines.find(l => l.trim().length > 0) || '';
    const title = firstLine.startsWith('# ') ? firstLine.replace('# ', '').trim() : '';

    const payload = {
        collection: currentCollection,
        filename: currentFilename,
        content: content,
        title: title
    };

    try {
        const response = await fetch('system/editor-handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const result = await response.json();
        if (result.success) {
            window.location.href = `?c=${encodeURIComponent(currentCollection)}&p=${encodeURIComponent(result.filename)}`;
        } else {
            alert('Error saving poem: ' + result.error);
        }
    } catch (e) {
        console.error('Save failed', e);
        alert('Failed to save. Check console.');
    }
}

window.openEditor = openEditor;
window.closeEditor = closeEditor;
window.savePoem = savePoem;
