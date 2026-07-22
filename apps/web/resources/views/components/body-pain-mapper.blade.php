<div x-data="{
        selectedRegion: null,
        selectedName: 'Entire Body (Click any muscle region)',
        mapperInstance: null,

        initMapper() {
            const tryInit = () => {
                if (typeof window.initThreeBodyMapper === 'function' && this.$refs.canvasContainer) {
                    this.mapperInstance = window.initThreeBodyMapper(this.$refs.canvasContainer, (data) => {
                        this.selectedRegion = data.id;
                        this.selectedName = data.name;
                    });
                } else {
                    setTimeout(tryInit, 100);
                }
            };
            this.$nextTick(tryInit);
        },
        selectRegion(id, name) {
            this.selectedRegion = id;
            this.selectedName = name;
            if (this.mapperInstance) {
                this.mapperInstance.selectRegion(id);
            }
        },
        resetMapper() {
            this.selectedRegion = null;
            this.selectedName = 'Entire Body (Click any muscle region)';
            if (this.mapperInstance) {
                this.mapperInstance.reset();
            }
        }
     }"
     x-init="initMapper()"
     class="relative overflow-hidden rounded-3xl border border-ink-100 bg-gradient-to-br from-ink-950 via-charcoal-950 to-leaf-950 p-6 text-white shadow-xl lg:p-8">

    <div class="grid gap-8 lg:grid-cols-[1fr_22rem] lg:items-center">
        {{-- Left: Description & Accessible Target Buttons --}}
        <div>
            <div class="inline-flex items-center gap-2 rounded-full bg-leaf-500/20 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-leaf-300 border border-leaf-400/30">
                <span class="inline-block size-2 rounded-full bg-leaf-400 animate-pulse"></span>
                <span>Interactive 3D WebGL Search</span>
            </div>

            <h2 class="mt-4 text-2xl font-black tracking-tight sm:text-3xl">3D Muscle & Body Pain Mapper</h2>
            <p class="mt-2 text-sm leading-relaxed text-ink-200">Rotate and click any muscle region on the 3D model below to pinpoint your body tension. Filter verified spas and therapists by specialized anatomical focus.</p>

            {{-- Active Region Pill Indicator --}}
            <div class="mt-5 inline-flex items-center gap-3 rounded-2xl border border-white/20 bg-white/10 px-4 py-2.5 backdrop-blur">
                <span class="text-xs font-bold text-ink-300 uppercase tracking-wider">Target Region:</span>
                <span class="text-sm font-black text-ember-300" x-text="selectedName"></span>
                <template x-if="selectedRegion">
                    <button type="button" @click="resetMapper()" class="ml-2 text-xs text-ink-400 hover:text-white underline">Reset</button>
                </template>
            </div>

            {{-- Target Region Buttons --}}
            <div class="mt-6">
                <p class="text-xs font-bold uppercase tracking-wider text-ink-300">Quick Select Muscle Group:</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ([
                        ['id' => 'neck', 'name' => 'Head, Neck & Trapezius', 'label' => '💆‍♂️ Neck & Trapezius'],
                        ['id' => 'shoulders', 'name' => 'Shoulders & Upper Back', 'label' => '💪 Shoulders & Upper Back'],
                        ['id' => 'lower_back', 'name' => 'Lower Back & Lumbar', 'label' => '🩺 Lower Back & Lumbar'],
                        ['id' => 'arms', 'name' => 'Arms & Hands', 'label' => '🤲 Arms & Hands'],
                        ['id' => 'legs', 'name' => 'Thighs & Calves', 'label' => '🦵 Thighs & Calves'],
                        ['id' => 'feet', 'name' => 'Feet & Reflexology Points', 'label' => '🦶 Feet & Reflexology'],
                    ] as $item)
                        <button type="button"
                                @click="selectRegion('{{ $item['id'] }}', '{{ $item['name'] }}')"
                                :class="selectedRegion === '{{ $item['id'] }}' ? 'bg-ember-500 text-white font-bold shadow-lg ring-2 ring-ember-300' : 'bg-white/10 text-white/90 hover:bg-white/20'"
                                class="rounded-xl px-3.5 py-2 text-xs transition">
                            {{ $item['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Directory Filter Submit Action --}}
            <form action="{{ route('directory.index') }}" method="get" class="mt-6 flex gap-3">
                <input type="hidden" name="service" :value="selectedName">
                <button type="submit" class="rounded-xl bg-ember-500 px-6 py-3 text-xs font-bold text-white shadow-lg transition hover:bg-ember-600">
                    Find Spas & Therapists for Selected Region &rarr;
                </button>
            </form>
        </div>

        {{-- Right: Three.js 3D Model Viewport Container --}}
        <div class="relative flex flex-col items-center justify-center rounded-2xl border border-white/15 bg-ink-900/60 p-4 backdrop-blur shadow-inner">
            <div x-ref="canvasContainer" class="h-[420px] w-full max-w-[340px] cursor-grab">
                {{-- Three.js WebGL canvas mounts dynamically here --}}
            </div>
            <div class="mt-2 flex items-center justify-between w-full px-2 text-[11px] text-ink-400">
                <span>🖱️ Drag to rotate 360°</span>
                <span>✨ Click to select region</span>
            </div>
        </div>
    </div>
</div>
