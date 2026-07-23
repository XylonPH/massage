<div class="mx-auto max-w-6xl">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <a href="{{ route('workspace.editorial.home') }}" wire:navigate class="text-sm font-bold text-ember-600 hover:underline">&larr; {{ __('editorial.title') }}</a>
            <h1 class="mt-2 text-2xl font-black text-ink-950 dark:text-ink-50">Article Management & Review</h1>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">Manage all articles across the platform, review pending submissions, and delete obsolete content.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="rounded-full bg-leaf-100 px-3 py-1 text-xs font-bold text-leaf-800 dark:bg-leaf-950 dark:text-leaf-300">
                {{ $pendingCount }} Pending Review
            </span>
            <a href="{{ route('workspace.article.create') }}" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-ember-600">
                + Write Article
            </a>
        </div>
    </div>

    @if (session('editorial_status'))
        <p class="mt-4 rounded-lg border border-leaf-200 bg-leaf-50 px-4 py-2.5 text-sm font-semibold text-leaf-700 dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-300">{{ session('editorial_status') }}</p>
    @endif

    {{-- Filter & Search Controls --}}
    <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap gap-2">
            @foreach ([
                'all' => 'All Articles',
                'pending' => 'Pending Review (' . $pendingCount . ')',
                'published' => 'Published',
                'draft' => 'Drafts',
                'unpublished' => 'Unpublished',
            ] as $key => $label)
                <button type="button"
                        wire:click="$set('statusFilter', '{{ $key }}')"
                        class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $statusFilter === $key ? 'bg-ink-950 text-white dark:bg-ember-500 shadow-sm' : 'bg-ink-100 text-ink-700 hover:bg-ink-200 dark:bg-ink-800 dark:text-ink-300' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="w-full sm:w-72">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by title or keyword..."
                   class="w-full rounded-xl border border-ink-200 bg-white px-3.5 py-2 text-xs text-ink-950 shadow-sm focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-900 dark:text-white">
        </div>
    </div>

    {{-- Articles Table --}}
    <div class="mt-6 overflow-x-auto rounded-2xl border border-ink-100 bg-white shadow-sm dark:border-ink-800 dark:bg-ink-900">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-ink-100 bg-ink-50/50 text-xs font-bold uppercase tracking-wider text-ink-500 dark:border-ink-800 dark:bg-ink-950 dark:text-ink-400">
                <tr>
                    <th class="px-5 py-3">Article Title & Details</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Updated</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100 dark:divide-ink-800">
                @forelse ($articles as $item)
                    @php
                        $articleId = (string) $item->getKey();
                        $isPending = isset($pendingArticles[$articleId]);
                        $pubStatus = $item->status_publication;
                    @endphp
                    <tr class="hover:bg-ink-50/50 dark:hover:bg-ink-950/50 transition" wire:key="{{ $articleId }}">
                        <td class="px-5 py-4">
                            <p class="font-bold text-ink-950 dark:text-ink-50">{{ $item->localized('article_title') }}</p>
                            <p class="mt-1 line-clamp-1 text-xs text-ink-500 dark:text-ink-400">{{ $item->localized('short_description') ?: 'No description provided.' }}</p>
                        </td>

                        <td class="px-5 py-4 whitespace-nowrap">
                            @if ($isPending)
                                <span class="inline-flex items-center gap-1 rounded-md bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                                    ⏳ Pending Review
                                </span>
                            @elseif ($pubStatus === 'P')
                                <span class="inline-flex items-center gap-1 rounded-md bg-leaf-100 px-2.5 py-0.5 text-xs font-bold text-leaf-800 dark:bg-leaf-950 dark:text-leaf-300">
                                    ✓ Published
                                </span>
                            @elseif ($pubStatus === 'U')
                                <span class="inline-flex items-center gap-1 rounded-md bg-ember-100 px-2.5 py-0.5 text-xs font-bold text-ember-800 dark:bg-ember-950 dark:text-ember-300">
                                    Unpublished
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-md bg-ink-100 px-2.5 py-0.5 text-xs font-bold text-ink-700 dark:bg-ink-800 dark:text-ink-300">
                                    Draft
                                </span>
                            @endif
                        </td>

                        <td class="whitespace-nowrap px-5 py-4 text-xs text-ink-600 dark:text-ink-300">
                            {{ $item->updated_at?->format('M j, Y g:i A') ?? 'N/A' }}
                        </td>

                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-3 text-xs">
                                @if ($isPending)
                                    <a href="{{ route('workspace.editorial.article.review', $item) }}" wire:navigate class="font-bold text-amber-600 hover:underline dark:text-amber-400">
                                        Review
                                    </a>
                                @endif

                                @php
                                    $slugVal = is_array($item->article_slug) ? $item->localized('article_slug') : $item->article_slug;
                                @endphp
                                @if ($pubStatus === 'P' && ! empty($slugVal))
                                    <a href="{{ route('article.show', $slugVal) }}" target="_blank" class="font-bold text-leaf-600 hover:underline dark:text-leaf-400">
                                        View Public
                                    </a>
                                @endif

                                <a href="{{ route('workspace.article.edit', $item) }}" class="font-bold text-ink-700 hover:underline dark:text-ink-300">
                                    Edit
                                </a>

                                <button type="button"
                                        wire:click="deleteArticle('{{ $articleId }}')"
                                        wire:confirm="Are you sure you want to delete this article? This will permanently delete its content and revisions."
                                        class="font-bold text-ember-600 hover:underline dark:text-ember-400">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center">
                            <p class="font-bold text-ink-700 dark:text-ink-200">No articles found</p>
                            <p class="mt-1 text-xs text-ink-500 dark:text-ink-400">Try adjusting your status filter or search keywords.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $articles->links() }}
    </div>
</div>
