<div class="bg-ink-50 dark:bg-ink-950 min-h-screen pb-12">
    {{-- Top Header / Nav Area --}}
    <div class="bg-white dark:bg-ink-900 border-b border-ink-200 dark:border-ink-800 py-4 px-6 mb-8 shadow-sm">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <h1 class="text-2xl font-black tracking-tight text-ink-950 dark:text-white flex items-center gap-2">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-6 text-ember-600 dark:text-ember-500" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                </svg>
                The Resting Leaf
            </h1>
            <div class="flex items-center gap-4 text-sm font-medium text-ink-600 dark:text-ink-400">
                <a href="#" class="hover:text-ember-600 dark:hover:text-ember-400 transition">Archive</a>
                <a href="#" class="hover:text-ember-600 dark:hover:text-ember-400 transition">About Cast</a>
            </div>
        </div>
    </div>

    {{-- Main Content Area --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Comic Header / Meta --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-ink-900 dark:text-white">{{ $comicTitle }}</h2>
                <p class="text-sm text-ink-500 dark:text-ink-400 mt-1">Published {{ \Carbon\Carbon::parse($comicDate)->format('F j, Y') }}</p>
            </div>
            
            {{-- Navigation --}}
            <div class="flex items-center gap-2">
                <button type="button" class="inline-flex items-center gap-1 px-4 py-2 rounded-lg bg-white dark:bg-ink-800 border border-ink-200 dark:border-ink-700 text-ink-700 dark:text-ink-300 hover:bg-ink-50 dark:hover:bg-ink-700 transition shadow-sm font-medium text-sm">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-4"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"/></svg>
                    Previous
                </button>
                <button type="button" class="inline-flex items-center gap-1 px-4 py-2 rounded-lg bg-white dark:bg-ink-800 border border-ink-200 dark:border-ink-700 text-ink-400 dark:text-ink-500 cursor-not-allowed shadow-sm font-medium text-sm" disabled>
                    Next
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-4"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        </div>

        {{-- Comic Viewer Container --}}
        <div class="bg-white dark:bg-ink-900 rounded-2xl shadow-sm border border-ink-200 dark:border-ink-800 overflow-hidden p-2 sm:p-4 md:p-6 mb-8">
            {{-- Single Image representing the 6-panel comic --}}
            <img src="{{ asset('img/placeholders/comic.jpg') }}" alt="The Resting Leaf - {{ $comicTitle }}" class="w-full h-auto rounded-xl shadow-2xs border border-ink-100 dark:border-ink-800 object-contain">
        </div>

        {{-- Footer area / Author Notes --}}
        <div class="bg-white dark:bg-ink-900 rounded-2xl p-6 shadow-sm border border-ink-200 dark:border-ink-800">
            <h3 class="font-bold text-ink-900 dark:text-white mb-2">Creator's Note</h3>
            <p class="text-sm text-ink-600 dark:text-ink-400 leading-relaxed">
                Welcome to The Resting Leaf! This is a placeholder space for our upcoming 6-panel slice-of-life comic featuring the Nexus Cast. Check back soon when our media management system goes live.
            </p>
        </div>

    </div>
</div>
