{{-- Operating hours --}}
<div x-show="tab === 'hours'" x-cloak wire:key="tab-content-hours" class="mt-5 space-y-4">
    <div class="flex items-center justify-between">
        <p class="text-sm font-black text-ink-950 dark:text-white">{{ __('editorial.operating_hours') }}</p>
        <span class="text-xs font-semibold text-ink-500 dark:text-ink-400">Configure weekly schedules and status</span>
    </div>

    @foreach ($state['operating_hours'] as $i => $row)
        <div class="grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-2xs sm:grid-cols-[auto_1fr_1fr_1fr_auto] dark:border-ink-800 dark:bg-ink-950/60" wire:key="hours-{{ $i }}">
            {{-- Interactive OPEN / CLOSED Status Switcher --}}
            <div class="self-end pb-1">
                <label class="group relative inline-flex cursor-pointer select-none items-center gap-2">
                    <input type="checkbox" wire:model.live="state.operating_hours.{{ $i }}.is_closed" class="peer sr-only">
                    {{-- Open State Badge (Green) --}}
                    <span class="inline-flex items-center gap-1.5 rounded-xl border border-leaf-300 bg-leaf-50 px-3 py-2.5 text-xs font-black text-leaf-800 shadow-2xs transition group-hover:scale-105 peer-checked:hidden dark:border-leaf-800 dark:bg-leaf-950/80 dark:text-leaf-300">
                        <span class="size-2 rounded-full bg-leaf-500 animate-pulse"></span>
                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/></svg>
                        <span>OPEN</span>
                    </span>
                    {{-- Closed State Badge (Red) --}}
                    <span class="hidden items-center gap-1.5 rounded-xl border border-ember-300 bg-ember-50 px-3 py-2.5 text-xs font-black text-ember-800 shadow-2xs transition group-hover:scale-105 peer-checked:inline-flex dark:border-ember-800 dark:bg-ember-950/80 dark:text-ember-300">
                        <span class="size-2 rounded-full bg-ember-500"></span>
                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd"/></svg>
                        <span>CLOSED</span>
                    </span>
                </label>
            </div>

            <x-form.field :label="__('editorial.day_of_week')" :error="$errors->first('state.operating_hours.'.$i.'.day_of_week')">
                <x-form.select wire:model="state.operating_hours.{{ $i }}.day_of_week" :options="$dayOfWeekOptions" :placeholder="__('editorial.select_placeholder')" />
            </x-form.field>
            <x-form.field :label="__('editorial.open_time')" :error="$errors->first('state.operating_hours.'.$i.'.open_time')">
                <x-form.input wire:model="state.operating_hours.{{ $i }}.open_time" type="time" />
            </x-form.field>
            <x-form.field :label="__('editorial.close_time')" :error="$errors->first('state.operating_hours.'.$i.'.close_time')">
                <x-form.input wire:model="state.operating_hours.{{ $i }}.close_time" type="time" />
            </x-form.field>

            {{-- Icon Remove Button --}}
            <div class="self-end pb-1">
                <button type="button" wire:click="removeRow('operating_hours', {{ $i }})" 
                        title="{{ __('editorial.remove') }}"
                        aria-label="{{ __('editorial.remove') }}"
                        class="inline-flex size-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-ink-500 shadow-2xs transition hover:border-ember-300 hover:bg-ember-50 hover:text-ember-600 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-400 dark:hover:border-ember-800 dark:hover:bg-ember-950 dark:hover:text-ember-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4.5" aria-hidden="true">
                        <path d="M3 6h18"/>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                        <line x1="10" y1="11" x2="10" y2="17"/>
                        <line x1="14" y1="11" x2="14" y2="17"/>
                    </svg>
                </button>
            </div>
        </div>
    @endforeach

    <button type="button" wire:click="addRow('operating_hours')" 
            class="inline-flex items-center gap-2 rounded-xl border border-dashed border-slate-300 bg-slate-50/50 px-4 py-2.5 text-xs font-bold text-ink-700 transition hover:border-ember-400 hover:bg-ember-50/50 hover:text-ember-600 dark:border-ink-700 dark:bg-ink-950/50 dark:text-ink-300 dark:hover:border-ember-600 dark:hover:text-ember-400">
        <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/></svg>
        <span>{{ __('editorial.add_row') }}</span>
    </button>
</div>
