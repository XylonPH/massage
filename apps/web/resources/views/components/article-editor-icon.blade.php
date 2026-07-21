@props(['name'])

@if (in_array($name, ['paragraph', 'heading2', 'heading3', 'heading4'], true))
    <svg aria-hidden="true" viewBox="0 0 24 24" class="h-4 w-4">
        <text x="12" y="16" text-anchor="middle" fill="currentColor" font-family="sans-serif" font-size="12" font-weight="800">
            {{ ['paragraph' => 'P', 'heading2' => 'H2', 'heading3' => 'H3', 'heading4' => 'H4'][$name] }}
        </text>
    </svg>
@else
    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
        @switch($name)
            @case('bold') <path d="M7 5h6a4 4 0 0 1 0 8H7zm0 8h7a4 4 0 0 1 0 8H7z" /> @break
            @case('italic') <path d="M10 5h8M6 19h8M14 5 10 19" /> @break
            @case('underline') <path d="M7 4v7a5 5 0 0 0 10 0V4M5 21h14" /> @break
            @case('strike') <path d="M17 6.5C16 5.5 14.7 5 13 5c-2.8 0-5 1.3-5 3.5 0 1.6 1.1 2.5 3 3M4 12h16M8 17.5c1.1 1 2.5 1.5 4.5 1.5 2.8 0 5-1.3 5-3.5 0-1.5-1-2.4-2.7-3" /> @break
            @case('bulletList') <path d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01" /> @break
            @case('orderedList') <path d="M10 6h10M10 12h10M10 18h10M4 5h1v3M4 11h2l-2 3h2M4 17h2v3H4" /> @break
            @case('blockquote') <path d="M8 11H4a4 4 0 0 1 4-4v8H4M20 11h-4a4 4 0 0 1 4-4v8h-4" /> @break
            @case('horizontalRule') <path d="M4 12h16" /> @break
            @case('alignLeft') <path d="M4 6h16M4 10h10M4 14h16M4 18h10" /> @break
            @case('alignCenter') <path d="M4 6h16M7 10h10M4 14h16M7 18h10" /> @break
            @case('alignRight') <path d="M4 6h16M10 10h10M4 14h16M10 18h10" /> @break
            @case('link') <path d="M10 13a5 5 0 0 0 7.1.1l2-2a5 5 0 0 0-7.1-7.1l-1.1 1.1M14 11a5 5 0 0 0-7.1-.1l-2 2A5 5 0 0 0 12 20l1.1-1.1" /> @break
            @case('unlink') <path d="m8 8-3-3m14 14-3-3M9 15l-2 2a5 5 0 0 1-7-7l2-2M15 9l2-2a5 5 0 0 1 7 7l-2 2M8 12h8" /> @break
            @case('clear') <path d="m4 20 5-5M14 4l6 6-9 9H5v-6zM10 8l6 6" /> @break
            @case('undo') <path d="m9 7-5 5 5 5M4 12h9a6 6 0 0 1 6 6" /> @break
            @case('redo') <path d="m15 7 5 5-5 5M20 12h-9a6 6 0 0 0-6 6" /> @break
        @endswitch
    </svg>
@endif
