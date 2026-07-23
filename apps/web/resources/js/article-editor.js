import { Editor } from '@tiptap/core';
import Placeholder from '@tiptap/extension-placeholder';
import TextAlign from '@tiptap/extension-text-align';
import StarterKit from '@tiptap/starter-kit';
import { ArticleImage } from './article-image-node.js';

const form = document.querySelector('[data-article-form]');

if (form) {
    const editorElement = form.querySelector('[data-article-editor]');
    const bodyInput = form.querySelector('[data-article-body]');
    const htmlInput = form.querySelector('[data-article-html]');
    const visualPanel = form.querySelector('[data-editor-visual]');
    const htmlPanel = form.querySelector('[data-editor-html]');
    const saveState = form.querySelector('[data-editor-save-state]');
    let updateReadiness = () => {};

    const initializeEntityPickers = (root = form) => {
        root.querySelectorAll('[data-entity-picker]:not([data-picker-ready])').forEach((picker) => {
            picker.dataset.pickerReady = 'true';
            const search = picker.querySelector('[data-picker-search]');
            const results = picker.querySelector('[data-picker-results]');
            const selected = picker.querySelector('[data-picker-selected]');
            const status = picker.querySelector('[data-picker-status]');
            const multiple = picker.dataset.multiple === 'true';
            let timer;
            let controller;

            const removeChip = (button) => button.closest('[data-picker-chip]')?.remove();
            selected.querySelectorAll('[data-picker-remove]').forEach((button) => {
                button.addEventListener('click', () => removeChip(button));
            });

            const addSelection = (item) => {
                if (!item?.id || selected.querySelector(`[data-picker-chip][data-id="${CSS.escape(item.id)}"]`)) return;
                if (!multiple) selected.replaceChildren();

                const chip = document.createElement('span');
                chip.dataset.pickerChip = '';
                chip.dataset.id = item.id;
                chip.className = 'inline-flex max-w-full items-center gap-1.5 rounded-full bg-ink-100 px-3 py-1.5 text-xs font-semibold text-ink-800 dark:bg-ink-800 dark:text-ink-100';

                const text = document.createElement('span');
                text.className = 'truncate';
                text.textContent = item.label;
                const remove = document.createElement('button');
                remove.type = 'button';
                remove.dataset.pickerRemove = '';
                remove.className = 'rounded-full text-ink-500 hover:text-ember-700 dark:text-ink-300 dark:hover:text-ember-300';
                remove.setAttribute('aria-label', `Remove ${item.label}`);
                remove.textContent = '×';
                remove.addEventListener('click', () => removeChip(remove));
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = multiple ? `${picker.dataset.fieldName}[]` : picker.dataset.fieldName;
                hidden.value = item.id;
                chip.append(text, remove, hidden);
                selected.append(chip);
                picker.dispatchEvent(new CustomEvent('article:entity-selected', { bubbles: true, detail: item }));
            };

            const showResults = (items) => {
                results.replaceChildren();
                items.forEach((item) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.role = 'option';
                    button.className = 'block w-full rounded-lg px-3 py-2 text-left text-sm text-ink-900 hover:bg-ember-50 focus:bg-ember-50 focus:outline-none dark:text-ink-100 dark:hover:bg-ink-800 dark:focus:bg-ink-800';
                    button.textContent = item.label;
                    button.addEventListener('click', () => {
                        addSelection(item);
                        search.value = '';
                        results.hidden = true;
                        status.textContent = '';
                        search.focus();
                    });
                    results.append(button);
                });
                results.hidden = items.length === 0;
                status.textContent = items.length === 0 ? picker.dataset.emptyLabel : '';
            };

            search.addEventListener('input', () => {
                window.clearTimeout(timer);
                controller?.abort();
                const query = search.value.trim();
                if (query.length < 2) {
                    results.hidden = true;
                    status.textContent = query.length === 0 ? '' : form.dataset.searchMinimumLabel;
                    return;
                }

                timer = window.setTimeout(async () => {
                    controller = new AbortController();
                    status.textContent = picker.dataset.searchingLabel;
                    try {
                        const endpoint = new URL(picker.dataset.endpoint, window.location.origin);
                        endpoint.searchParams.set('q', query);
                        const response = await fetch(endpoint, {
                            headers: { Accept: 'application/json' },
                            signal: controller.signal,
                        });
                        if (!response.ok) throw new Error('Lookup failed');
                        const payload = await response.json();
                        showResults(Array.isArray(payload.results) ? payload.results : []);
                    } catch (error) {
                        if (error.name === 'AbortError') return;
                        results.hidden = true;
                        status.textContent = picker.dataset.errorLabel;
                    }
                }, 250);
            });

            picker.addEventListener('article:entity-selected', (event) => {
                if (picker.dataset.entityType !== 'user') return;
                const nameInput = picker.closest('[data-author-row]')?.querySelector('[name$="[display_name]"]');
                if (nameInput && event.detail.display_name) nameInput.value = event.detail.display_name;
            });
        });
    };

    initializeEntityPickers();

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
                ArticleImage,
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

        const imageInsertButton = form.querySelector('[data-insert-image]');
        const imageFileInput = form.querySelector('[data-image-file-input]');
        const mediaUploadUrl = form.dataset.mediaUploadUrl;

        if (imageInsertButton && imageFileInput && mediaUploadUrl) {
            imageInsertButton.addEventListener('click', () => imageFileInput.click());
            imageFileInput.addEventListener('change', async () => {
                const file = imageFileInput.files[0];
                if (!file) return;
                const altText = window.prompt(form.dataset.altTextPrompt || 'Describe this image for accessibility:');
                if (!altText) {
                    imageFileInput.value = '';
                    return;
                }
                const body = new FormData();
                body.append('image', file);
                body.append('alt_text', altText);
                try {
                    const response = await fetch(mediaUploadUrl, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content, Accept: 'application/json' },
                        body,
                    });
                    if (!response.ok) throw new Error('Upload failed');
                    const payload = await response.json();
                    editor.chain().focus().insertContent({
                        type: 'articleImage',
                        attrs: { mediaImageId: payload.id, src: `/media/image/${payload.id}`, alt: altText },
                    }).run();
                } catch (error) {
                    window.alert(form.dataset.uploadErrorLabel || 'Image upload failed. Try again.');
                } finally {
                    imageFileInput.value = '';
                }
            });
        }

        const heroFileInput = document.querySelector('[data-hero-image-file-input]');
        if (heroFileInput && mediaUploadUrl) {
            heroFileInput.addEventListener('change', async () => {
                const file = heroFileInput.files[0];
                if (!file) return;
                const altText = window.prompt(form.dataset.altTextPrompt || 'Describe this image for accessibility:');
                if (!altText) {
                    heroFileInput.value = '';
                    return;
                }
                const body = new FormData();
                body.append('image', file);
                body.append('alt_text', altText);
                try {
                    const response = await fetch(mediaUploadUrl, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content, Accept: 'application/json' },
                        body,
                    });
                    if (!response.ok) throw new Error('Upload failed');
                    const payload = await response.json();
                    const coverUrl = mediaUploadUrl.replace(/\/media$/, `/media/${payload.id}/cover`);
                    const coverForm = document.createElement('form');
                    coverForm.method = 'POST';
                    coverForm.action = coverUrl;
                    coverForm.innerHTML = `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content}">`;
                    document.body.append(coverForm);
                    coverForm.submit();
                } catch (error) {
                    window.alert(form.dataset.uploadErrorLabel || 'Image upload failed. Try again.');
                }
            });
        }

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
    const sourceList = form.querySelector('[data-source-list]');
    let slugIsAutomatic = slug?.dataset.slugAuto === 'true';

    const updateCounter = (input, target, maximum) => {
        if (!input || !target) return;
        target.textContent = `${input.value.length} / ${maximum}`;
    };

    updateReadiness = () => {
        const checks = {
            title: (title?.value.trim().length || 0) >= 4,
            description: (description?.value.trim().length || 0) >= 20,
            body: stripHtml(bodyInput?.value || '').split(/\s+/u).filter(Boolean).length >= Number(form.dataset.minimumSubmissionWords || 300),
            sources: Array.from(form.querySelectorAll('[name$="[source_title]"]')).some((input) => input.value.trim().length > 0),
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
    sourceList?.addEventListener('input', updateReadiness);

    form.addEventListener('click', (event) => {
        const addAuthor = event.target.closest('[data-add-author]');
        const addSource = event.target.closest('[data-add-source]');
        const removeAuthor = event.target.closest('[data-remove-author]');
        const removeSource = event.target.closest('[data-remove-source]');

        if (addAuthor) appendTemplate('[data-author-template]', '[data-author-list]', 'author');
        if (addSource) appendTemplate('[data-source-template]', '[data-source-list]', 'source');
        if (removeAuthor && form.querySelectorAll('[data-author-row]').length > 1) removeAuthor.closest('[data-author-row]')?.remove();
        if (removeSource) removeSource.closest('[data-source-row]')?.remove();
        if (addSource || removeSource) updateReadiness();
    });

    form.addEventListener('change', (event) => {
        const select = event.target.closest('[data-author-user]');
        if (!select) return;
        const nameInput = select.closest('[data-author-row]')?.querySelector('[name$="[display_name]"]');
        const displayName = select.selectedOptions[0]?.dataset.displayName || '';
        if (nameInput && displayName !== '') nameInput.value = displayName;
    });

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
        const characterCount = Array.from(text).length;
        const visualMinutes = wordCount === 0 ? 0 : Math.max(1, Math.ceil(wordCount / 225));
        const spokenMinutes = wordCount === 0 ? 0 : Math.max(1, Math.ceil(wordCount / 150));
        const wordTarget = form.querySelector('[data-editor-word-count]');
        const characterTarget = form.querySelector('[data-editor-character-count]');
        const visualTarget = form.querySelector('[data-editor-visual-reading-time]');
        const spokenTarget = form.querySelector('[data-editor-spoken-reading-time]');
        if (wordTarget) wordTarget.textContent = String(wordCount);
        if (characterTarget) characterTarget.textContent = String(characterCount);
        if (visualTarget) visualTarget.textContent = `${visualMinutes} min`;
        if (spokenTarget) spokenTarget.textContent = `${spokenMinutes} min`;
    }

    function appendTemplate(templateSelector, listSelector, prefix) {
        const template = form.querySelector(templateSelector);
        const list = form.querySelector(listSelector);
        if (!template || !list) return;
        const index = `${prefix}-${Date.now()}-${list.children.length}`;
        const wrapper = document.createElement('div');
        wrapper.innerHTML = template.innerHTML.replaceAll('__INDEX__', index).trim();
        if (wrapper.firstElementChild) {
            list.append(wrapper.firstElementChild);
            initializeEntityPickers(list.lastElementChild);
        }
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
