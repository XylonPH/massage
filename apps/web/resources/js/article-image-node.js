import { Node, mergeAttributes } from '@tiptap/core';

export const ArticleImage = Node.create({
    name: 'articleImage',
    group: 'block',
    atom: true,

    addAttributes() {
        return {
            mediaImageId: {
                default: null,
                parseHTML: (element) => element.getAttribute('data-media-image-id'),
                renderHTML: (attributes) => ({ 'data-media-image-id': attributes.mediaImageId }),
            },
            src: { default: null },
            alt: { default: '' },
        };
    },

    parseHTML() {
        return [{ tag: 'figure[data-media-image-id]' }];
    },

    renderHTML({ node }) {
        return [
            'figure',
            mergeAttributes({ 'data-media-image-id': node.attrs.mediaImageId }),
            ['img', { src: node.attrs.src, alt: node.attrs.alt, loading: 'lazy' }],
        ];
    },
});
