<div x-data="{
        currentIndex: 0,
        voted: {},
        selectedOption: null,

        polls: [
            {
                id: 'poll-1',
                question: 'How often do you get a professional massage?',
                options: [
                    { id: 'weekly', label: 'Weekly', percent: 32, votes: 454 },
                    { id: 'monthly', label: 'Monthly', percent: 45, votes: 639 },
                    { id: 'occasionally', label: 'Occasionally', percent: 18, votes: 256 },
                    { id: 'first_time', label: 'First Time', percent: 5, votes: 71 },
                ],
                totalVotes: 1420
            },
            {
                id: 'poll-2',
                question: 'Which massage modality brings you the most stress relief?',
                options: [
                    { id: 'hilot', label: 'Traditional Hilot', percent: 41, votes: 582 },
                    { id: 'swedish', label: 'Swedish Relaxation', percent: 33, votes: 468 },
                    { id: 'deep_tissue', label: 'Deep Tissue Recovery', percent: 18, votes: 256 },
                    { id: 'shiatsu', label: 'Shiatsu Acupressure', percent: 8, votes: 114 },
                ],
                totalVotes: 1420
            },
            {
                id: 'poll-3',
                question: 'What is your top priority when choosing a wellness spa?',
                options: [
                    { id: 'cleanliness', label: 'Sanitation & Hygiene', percent: 48, votes: 682 },
                    { id: 'therapists', label: 'Certified Therapists', percent: 31, votes: 440 },
                    { id: 'price', label: 'Affordable Packages', percent: 14, votes: 199 },
                    { id: 'ambiance', label: 'Ambiance & Amenities', percent: 7, votes: 99 },
                ],
                totalVotes: 1420
            }
        ],

        init() {
            try {
                const saved = localStorage.getItem('mn_community_pulse_votes');
                if (saved) this.voted = JSON.parse(saved);
            } catch (e) {}
        },

        currentPoll() {
            return this.polls[this.currentIndex];
        },

        hasVoted() {
            return !!this.voted[this.currentPoll().id];
        },

        submitVote() {
            if (!this.selectedOption) return;
            const pollId = this.currentPoll().id;
            this.voted[pollId] = this.selectedOption;
            try {
                localStorage.setItem('mn_community_pulse_votes', JSON.stringify(this.voted));
            } catch (e) {}
            this.selectedOption = null;
        },

        nextPoll() {
            this.currentIndex = (this.currentIndex + 1) % this.polls.length;
            this.selectedOption = null;
        }
     }"
     x-init="init()"
     class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">

    {{-- Pulse Widget Header --}}
    <div class="flex items-center justify-between border-b border-ink-100 pb-3 dark:border-ink-800">
        <div class="flex items-center gap-2">
            <span class="relative flex size-3">
              <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-leaf-400 opacity-75"></span>
              <span class="relative inline-flex size-3 rounded-full bg-leaf-500"></span>
            </span>
            <h2 class="text-sm font-black tracking-tight text-ink-950 dark:text-ink-50">Community Pulse</h2>
        </div>
        <button type="button" @click="nextPoll()" class="inline-flex items-center gap-1 text-xs font-bold text-ember-600 transition hover:text-ember-700 dark:text-ember-400 dark:hover:text-ember-300">
            <span>Rotate Topic ↻</span>
        </button>
    </div>

    {{-- Active Poll Question --}}
    <div class="mt-4">
        <p class="text-xs font-bold uppercase tracking-wider text-leaf-700 dark:text-leaf-400" x-text="'Topic ' + (currentIndex + 1) + ' of ' + polls.length"></p>
        <p class="mt-1 text-sm font-bold text-ink-900 dark:text-ink-100" x-text="currentPoll().question"></p>

        {{-- Voting Form View --}}
        <template x-if="!hasVoted()">
            <form @submit.prevent="submitVote()" class="mt-4 space-y-2">
                <template x-for="option in currentPoll().options" :key="option.id">
                    <label class="flex cursor-pointer items-center justify-between rounded-xl border border-ink-100 p-3 text-xs text-ink-800 transition hover:border-ember-300 hover:bg-ember-50/50 dark:border-ink-800 dark:text-ink-200 dark:hover:border-ember-700 dark:hover:bg-ember-950/40">
                        <div class="flex items-center gap-2.5">
                            <input type="radio" :name="'pulse_' + currentPoll().id" :value="option.id" x-model="selectedOption" class="size-4 accent-ember-500">
                            <span class="font-semibold" x-text="option.label"></span>
                        </div>
                    </label>
                </template>

                <button type="submit" :disabled="!selectedOption"
                        class="mt-3 w-full rounded-xl bg-ember-500 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-ember-600 disabled:opacity-50">
                    Submit Community Vote
                </button>
            </form>
        </template>

        {{-- Live Percentage Results View (After Voting) --}}
        <template x-if="hasVoted()">
            <div class="mt-4 space-y-3">
                <div class="flex items-center justify-between text-[11px] font-bold text-leaf-700 dark:text-leaf-400">
                    <span>✓ Vote Recorded</span>
                    <span x-text="currentPoll().totalVotes + ' Total Community Votes'"></span>
                </div>

                <template x-for="option in currentPoll().options" :key="option.id">
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs font-semibold text-ink-800 dark:text-ink-200">
                            <span x-text="option.label"></span>
                            <span class="font-bold text-ember-600 dark:text-ember-400" x-text="option.percent + '%'"></span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-ink-100 dark:bg-ink-800">
                            <div class="h-full bg-gradient-to-r from-ember-500 to-leaf-500 transition-all duration-700" :style="'width: ' + option.percent + '%'"></div>
                        </div>
                    </div>
                </template>

                <div class="mt-4 pt-2 text-center">
                    <button type="button" @click="nextPoll()" class="text-xs font-bold text-ink-500 hover:text-ink-950 dark:text-ink-400 dark:hover:text-white underline">
                        View Next Poll Topic &rarr;
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>
