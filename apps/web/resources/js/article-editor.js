import { Editor } from '@tiptap/core';
import Placeholder from '@tiptap/extension-placeholder';
import TextAlign from '@tiptap/extension-text-align';
import StarterKit from '@tiptap/starter-kit';

const form = document.querySelector('[data-article-form]');

if (form) {
    const editorElement = form.querySelector('[data-article-editor]');
    const bodyInput = form.querySelector('[data-article-body]');
    const htmlInput = form.querySelector('[data-article-html]');
    const visualPanel = form.querySelector('[data-editor-visual]');
    const htmlPanel = form.querySelector('[data-editor-html]');
    const saveState = form.querySelector('[data-editor-save-state]');
    let updateReadiness = () => {};

    const readOnly = editorElement?.dataset.readOnly === 'true';

    if (editorElement && bodyInput && htmlInput && visualPanel && htmlPanel) {
        let mode = 'visual';
        let dirty = false;

        const markDirty = () => {
            dirty = true;
            if (saveState) {
                saveState.textContent = form.dataset.unsavedLabel;
                saveState.className = 'font-semibold text-ember-700';
            }
        };

        const editor = new Editor({
            element: editorElement,
            editable: !readOnly,
            content: bodyInput.value || '<p></p>',
            extensions: [
                StarterKit.configure({
                    heading: { levels: [2, 3, 4] },
                    code: false,
                    codeBlock: false,
                    link: {
                        openOnClick: false,
                        autolink: true,
                        defaultProtocol: 'https',
                        HTMLAttributes: { rel: 'noopener noreferrer' },
                    },
                }),
                TextAlign.configure({ types: ['heading', 'paragraph'], alignments: ['left', 'center', 'right', 'justify'] }),
                Placeholder.configure({ placeholder: editorElement.dataset.placeholder || '' }),
            ],
            editorProps: {
                attributes: {
                    'aria-label': editorElement.dataset.ariaLabel || '',
                    class: 'min-h-[34rem] px-6 py-5 outline-none',
                },
            },
            onCreate: ({ editor }) => {
                htmlInput.value = editor.getHTML();
                updateEditorDetails(editor);
                updateActiveButtons(editor);
            },
            onUpdate: ({ editor }) => {
                bodyInput.value = editor.getHTML();
                htmlInput.value = editor.getHTML();
                updateEditorDetails(editor);
                updateActiveButtons(editor);
                markDirty();
            },
            onSelectionUpdate: ({ editor }) => updateActiveButtons(editor),
        });

        const actions = {
            paragraph: () => editor.chain().focus().setParagraph().run(),
            heading2: () => editor.chain().focus().toggleHeading({ level: 2 }).run(),
            heading3: () => editor.chain().focus().toggleHeading({ level: 3 }).run(),
            heading4: () => editor.chain().focus().toggleHeading({ level: 4 }).run(),
            bold: () => editor.chain().focus().toggleBold().run(),
            italic: () => editor.chain().focus().toggleItalic().run(),
            underline: () => editor.chain().focus().toggleUnderline().run(),
            strike: () => editor.chain().focus().toggleStrike().run(),
            bulletList: () => editor.chain().focus().toggleBulletList().run(),
            orderedList: () => editor.chain().focus().toggleOrderedList().run(),
            blockquote: () => editor.chain().focus().toggleBlockquote().run(),
            horizontalRule: () => editor.chain().focus().setHorizontalRule().run(),
            alignLeft: () => editor.chain().focus().setTextAlign('left').run(),
            alignCenter: () => editor.chain().focus().setTextAlign('center').run(),
            alignRight: () => editor.chain().focus().setTextAlign('right').run(),
            unlink: () => editor.chain().focus().unsetLink().run(),
            clear: () => editor.chain().focus().unsetAllMarks().clearNodes().run(),
            undo: () => editor.chain().focus().undo().run(),
            redo: () => editor.chain().focus().redo().run(),
            link: () => {
                const existing = editor.getAttributes('link').href || '';
                const href = window.prompt(form.dataset.linkPrompt, existing);
                if (href === null) return;
                if (href.trim() === '') {
                    editor.chain().focus().extendMarkRange('link').unsetLink().run();
                    return;
                }
                if (!/^(https?:\/\/|mailto:|\/|#)/i.test(href.trim())) return;
                editor.chain().focus().extendMarkRange('link').setLink({ href: href.trim() }).run();
            },
        };

        form.querySelectorAll('[data-editor-action]').forEach((button) => {
            if (readOnly) button.disabled = true;
            button.addEventListener('click', () => actions[button.dataset.editorAction]?.());
        });

        form.querySelectorAll('[data-editor-mode]').forEach((button) => {
            button.addEventListener('click', () => {
                const nextMode = button.dataset.editorMode;
                if (nextMode === mode) return;

                if (nextMode === 'html') {
                    htmlInput.value = editor.getHTML();
                } else {
                    editor.commands.setContent(htmlInput.value || '<p></p>');
                    bodyInput.value = editor.getHTML();
                }

                mode = nextMode;
                visualPanel.toggleAttribute('hidden', mode !== 'visual');
                htmlPanel.toggleAttribute('hidden', mode !== 'html');
                form.querySelectorAll('[data-editor-mode]').forEach((modeButton) => {
                    const active = modeButton.dataset.editorMode === mode;
                    modeButton.setAttribute('aria-selected', String(active));
                    modeButton.classList.toggle('bg-ink-900', active);
                    modeButton.classList.toggle('text-white', active);
                    modeButton.classList.toggle('text-ink-600', !active);
                });
            });
        });

        htmlInput.addEventListener('input', () => {
            bodyInput.value = htmlInput.value;
            updateTextMetrics(stripHtml(htmlInput.value));
            markDirty();
        });

        form.addEventListener('submit', () => {
            if (mode === 'html') {
                editor.commands.setContent(htmlInput.value || '<p></p>');
            }
            bodyInput.value = editor.getHTML();
            dirty = false;
        });

        window.addEventListener('beforeunload', (event) => {
            if (!dirty) return;
            event.preventDefault();
        });
    }

    const title = form.querySelector('[data-article-title]');
    const slug = form.querySelector('[data-article-slug]');
    const description = form.querySelector('[data-article-description]');
    const sourceInput = form.querySelector('#source_references');
    let slugIsAutomatic = slug?.dataset.slugAuto === 'true';

    const updateCounter = (input, target, maximum) => {
        if (!input || !target) return;
        target.textContent = `${input.value.length} / ${maximum}`;
    };

    updateReadiness = () => {
        const checks = {
            title: (title?.value.trim().length || 0) >= 4,
            description: (description?.value.trim().length || 0) >= 20,
            body: stripHtml(bodyInput?.value || '').split(/\s+/u).filter(Boolean).length >= 20,
            sources: (sourceInput?.value.trim().length || 0) > 0,
        };
        Object.entries(checks).forEach(([key, complete]) => {
            const item = form.querySelector(`[data-readiness="${key}"]`);
            if (!item) return;
            item.classList.toggle('text-leaf-700', complete);
            item.classList.toggle('font-semibold', complete);
            item.classList.toggle('text-ink-500', !complete);
            item.dataset.complete = String(complete);
        });
    };

    title?.addEventListener('input', () => {
        updateCounter(title, form.querySelector('[data-title-count]'), 75);
        if (slugIsAutomatic && slug) slug.value = slugify(title.value);
        updateReadiness();
    });
    slug?.addEventListener('input', () => { slugIsAutomatic = false; });
    description?.addEventListener('input', () => {
        updateCounter(description, form.querySelector('[data-description-count]'), 255);
        updateReadiness();
    });
    sourceInput?.addEventListener('input', updateReadiness);

    updateCounter(title, form.querySelector('[data-title-count]'), 75);
    updateCounter(description, form.querySelector('[data-description-count]'), 255);
    if (slugIsAutomatic && slug && title?.value) slug.value = slugify(title.value);
    updateReadiness();

    function updateEditorDetails(editor) {
        bodyInput.value = editor.getHTML();
        updateTextMetrics(editor.getText());
        updateReadiness();
    }

    function updateTextMetrics(text) {
        const words = text.trim() === '' ? [] : text.trim().split(/\s+/u);
        const wordCount = words.length;
        const minutes = wordCount === 0 ? 0 : Math.max(1, Math.ceil(wordCount / 225));
        const wordTarget = form.querySelector('[data-editor-word-count]');
        const timeTarget = form.querySelector('[data-editor-reading-time]');
        if (wordTarget) wordTarget.textContent = String(wordCount);
        if (timeTarget) timeTarget.textContent = `${minutes} min`;
    }

    function updateActiveButtons(editor) {
        const states = {
            paragraph: editor.isActive('paragraph'),
            heading2: editor.isActive('heading', { level: 2 }),
            heading3: editor.isActive('heading', { level: 3 }),
            heading4: editor.isActive('heading', { level: 4 }),
            bold: editor.isActive('bold'),
            italic: editor.isActive('italic'),
            underline: editor.isActive('underline'),
            strike: editor.isActive('strike'),
            bulletList: editor.isActive('bulletList'),
            orderedList: editor.isActive('orderedList'),
            blockquote: editor.isActive('blockquote'),
            link: editor.isActive('link'),
            alignLeft: editor.isActive({ textAlign: 'left' }),
            alignCenter: editor.isActive({ textAlign: 'center' }),
            alignRight: editor.isActive({ textAlign: 'right' }),
        };
        form.querySelectorAll('[data-editor-action]').forEach((button) => {
            const active = Boolean(states[button.dataset.editorAction]);
            button.classList.toggle('is-active', active);
            button.setAttribute('aria-pressed', String(active));
        });
    }
}

function slugify(value) {
    return value
        .normalize('NFKD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '')
        .slice(0, 100)
        .replace(/-+$/g, '');
}

function stripHtml(html) {
    const document = new DOMParser().parseFromString(html, 'text/html');
    return document.body.textContent || '';
}
