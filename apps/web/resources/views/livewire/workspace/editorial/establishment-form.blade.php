<div class="space-y-6">
    @if ($isContribution)
        {{-- Hero Header Card with Three.js Interactive 3D Canvas --}}
        <div class="relative overflow-hidden rounded-3xl border border-ink-100 bg-gradient-to-br from-white via-white to-ember-50/60 p-6 shadow-sm dark:border-ink-800 dark:from-ink-900 dark:via-ink-900 dark:to-ember-950/40 sm:p-8">
            {{-- Three.js Canvas Container --}}
            <div class="absolute right-0 top-0 bottom-0 w-full sm:w-1/2 opacity-70 pointer-events-none overflow-hidden" id="three-container">
                <canvas id="spa-contribution-3d-canvas" class="w-full h-full"></canvas>
            </div>

            <div class="relative z-10 max-w-xl">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-ember-100 px-3 py-1 text-xs font-black uppercase tracking-wider text-ember-700 dark:bg-ember-950/80 dark:text-ember-300">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="size-3.5" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z" clip-rule="evenodd"/></svg>
                    <span>Community Contribution</span>
                </span>
                <h1 class="mt-3 text-3xl font-black tracking-tight text-ink-950 dark:text-ink-50 sm:text-4xl">
                    {{ __('workspace.contribution_establishment_title') }}
                </h1>
                <p class="mt-2 text-sm leading-relaxed text-ink-600 dark:text-ink-300">
                    {{ __('workspace.contribution_establishment_intro') }}
                </p>
                <p class="mt-3 text-sm">
                    <a href="{{ route('help.index') }}" class="inline-flex items-center gap-1 font-semibold text-ember-600 transition hover:text-ember-700 dark:text-ember-400 dark:hover:text-ember-300">
                        <span>{{ __('workspace.add_spa_help_link') }}</span>
                        <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h9.69L10.22 6.03a.75.75 0 1 1 1.06-1.06l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06l3.22-3.22H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                    </a>
                </p>
            </div>
        </div>

        {{-- Interactive GSAP Animated 3-Step Wizard Header --}}
        <div class="rounded-2xl border border-ink-100 bg-white p-4 shadow-2xs dark:border-ink-800 dark:bg-ink-900">
            <div class="relative flex items-center justify-between">
                {{-- Background Stepper Line --}}
                <div class="absolute left-6 right-6 top-1/2 h-1 -translate-y-1/2 bg-ink-100 dark:bg-ink-800" aria-hidden="true">
                    <div id="gsap-step-progress" class="h-full bg-gradient-to-r from-ember-500 to-amber-500 transition-all duration-500" 
                         style="width: {{ match($currentStep) { 1 => '0%', 2 => '50%', 3 => '100%', default => '0%' } }}"></div>
                </div>

                {{-- Step 1 Indicator --}}
                <div class="relative z-10 flex items-center gap-3 bg-white px-2 dark:bg-ink-900">
                    <div class="flex size-9 items-center justify-center rounded-xl font-bold text-xs shadow-2xs transition-all duration-300 {{ $currentStep >= 1 ? 'bg-ember-500 text-white ring-4 ring-ember-100 dark:ring-ember-950' : 'bg-ink-100 text-ink-500 dark:bg-ink-800 dark:text-ink-400' }}">
                        1
                    </div>
                    <span class="hidden text-xs font-bold sm:inline {{ $currentStep >= 1 ? 'text-ink-950 dark:text-white' : 'text-ink-400' }}">
                        {{ __('workspace.add_spa_step_who_you_are') }}
                    </span>
                </div>

                {{-- Step 2 Indicator --}}
                <div class="relative z-10 flex items-center gap-3 bg-white px-2 dark:bg-ink-900">
                    <div class="flex size-9 items-center justify-center rounded-xl font-bold text-xs shadow-2xs transition-all duration-300 {{ $currentStep >= 2 ? 'bg-ember-500 text-white ring-4 ring-ember-100 dark:ring-ember-950' : 'bg-ink-100 text-ink-500 dark:bg-ink-800 dark:text-ink-400' }}">
                        2
                    </div>
                    <span class="hidden text-xs font-bold sm:inline {{ $currentStep >= 2 ? 'text-ink-950 dark:text-white' : 'text-ink-400' }}">
                        {{ __('workspace.add_spa_step_spa_details') }}
                    </span>
                </div>

                {{-- Step 3 Indicator --}}
                <div class="relative z-10 flex items-center gap-3 bg-white px-2 dark:bg-ink-900">
                    <div class="flex size-9 items-center justify-center rounded-xl font-bold text-xs shadow-2xs transition-all duration-300 {{ $currentStep >= 3 ? 'bg-ember-500 text-white ring-4 ring-ember-100 dark:ring-ember-950' : 'bg-ink-100 text-ink-500 dark:bg-ink-800 dark:text-ink-400' }}">
                        3
                    </div>
                    <span class="hidden text-xs font-bold sm:inline {{ $currentStep >= 3 ? 'text-ink-950 dark:text-white' : 'text-ink-400' }}">
                        {{ __('workspace.add_spa_step_review') }}
                    </span>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-3xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:p-7">
            <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50 sm:text-3xl">{{ $establishment ? __('editorial.edit') : __('editorial.new') }} — {{ __('editorial.establishments') }}</h1>
        </div>
    @endif

    @error('form')
        <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-800 shadow-2xs dark:border-red-800 dark:bg-red-950/80 dark:text-red-200" role="alert">
            <svg viewBox="0 0 20 20" fill="currentColor" class="size-5 shrink-0" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
            <span>{{ $message }}</span>
        </div>
    @enderror

    <form wire:submit="save" class="step-card-container space-y-6">
        @if ($isContribution && $currentStep === 1)
            <div class="rounded-3xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:p-8">
                @include('livewire.workspace.establishment-form._who-you-are')
            </div>
        @elseif ($isContribution && $currentStep === 3)
            <div class="rounded-3xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900 sm:p-8">
                @include('livewire.workspace.establishment-form._review-submit')
            </div>
        @else
            <div x-data="{ tab: 'identity', collapsed: false }" wire:key="spa-details-tabs"
                 :class="collapsed ? 'sm:grid-cols-[4.25rem_1fr]' : 'sm:grid-cols-[13rem_1fr] lg:grid-cols-[15rem_1fr]'"
                 class="grid gap-0 sm:items-start transition-all duration-300">
                @php($tabIcons = [
                    'identity' => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8a7 7 0 0 1 14 0',
                    'classification' => 'M4 6h16M4 12h16M4 18h16',
                    'access' => 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z',
                    'location' => 'M12 21s-7-6.2-7-11a7 7 0 1 1 14 0c0 4.8-7 11-7 11Z',
                    'contact' => 'M4 4h16v16H4z M4 7l8 6 8-6',
                    'hours' => 'M12 7v5l3 3 M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z',
                    'facilities' => 'M3 21h18M5 21V8l7-5 7 5v13',
                    'amenities' => 'M12 3l2.5 5.3 5.5.7-4 4 1 5.7-5-2.8-5 2.8 1-5.7-4-4 5.5-.7L12 3Z',
                    'accessibility' => 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Z M12 8v8 M8 12h8',
                ])

                {{-- Side Tabs Column sitting on outer grayish background --}}
                <div class="sm:pt-2 sm:pr-0 pb-4 sm:pb-0">
                    <div class="flex items-center justify-end px-2 mb-2 hidden sm:flex">
                        <button type="button" @click="collapsed = !collapsed"
                                :title="collapsed ? 'Expand side tabs' : 'Collapse side tabs'"
                                class="inline-flex items-center justify-center size-8 rounded-lg text-ink-500 hover:bg-slate-200/70 hover:text-ink-950 dark:text-ink-400 dark:hover:bg-charcoal-800 dark:hover:text-white transition">
                            <svg x-show="!collapsed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                            <svg x-show="collapsed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    <x-editorial.tab-bar :vertical="true" :tabs="array_filter([
                        'identity' => __('editorial.tab_identity'),
                        'classification' => __('editorial.tab_classification'),
                        'access' => __('editorial.tab_access'),
                        'location' => __('editorial.tab_location'),
                        'contact' => __('editorial.tab_contact'),
                        'hours' => __('editorial.tab_hours'),
                        'facilities' => $this->hasPhysicalPremises() ? __('editorial.tab_facilities') : null,
                        'amenities' => __('editorial.tab_amenities'),
                        'accessibility' => __('editorial.tab_accessibility'),
                    ])" :icons="$tabIcons" />
                </div>

                {{-- Pure White Form Body Container --}}
                <div class="min-w-0 min-h-[34rem] space-y-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-ink-700 dark:bg-ink-900 sm:p-8">
                    @include('livewire.workspace.establishment-form._tab-identity')
                    @include('livewire.workspace.establishment-form._tab-classification')
                    @include('livewire.workspace.establishment-form._tab-access')
                    @include('livewire.workspace.establishment-form._tab-location')
                    @include('livewire.workspace.establishment-form._tab-contact')
                    @include('livewire.workspace.establishment-form._tab-hours')
                    @include('livewire.workspace.establishment-form._tab-facilities')
                    @include('livewire.workspace.establishment-form._tab-amenities')
                    @include('livewire.workspace.establishment-form._tab-accessibility')

                    <div class="flex items-center justify-between gap-3 border-t border-ink-100 pt-6 dark:border-ink-800">
                        @if ($isContribution)
                            <button type="button" wire:click="prevStep" class="inline-flex items-center gap-2 rounded-xl border border-ink-200 bg-white px-5 py-2.5 text-sm font-bold text-ink-800 shadow-2xs transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-200 dark:hover:bg-ink-800">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5 M12 19l-7-7 7-7"/></svg>
                                <span>{{ __('editorial.back') }}</span>
                            </button>
                            <button type="button" wire:click="nextStep" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-ember-500 to-ember-600 px-6 py-2.5 text-sm font-bold text-white shadow-2xs transition hover:from-ember-600 hover:to-ember-700 hover:shadow-md">
                                <span>{{ __('editorial.next') }}</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14 M12 5l7 7-7 7"/></svg>
                            </button>
                        @else
                            <a href="{{ route('workspace.editorial.establishment.index') }}" wire:navigate class="inline-flex items-center gap-2 rounded-xl border border-ink-200 bg-white px-5 py-2.5 text-sm font-bold text-ink-800 shadow-2xs transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-200 dark:hover:bg-ink-800">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5 M12 19l-7-7 7-7"/></svg>
                                <span>{{ __('editorial.cancel') }}</span>
                            </a>
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-ember-500 to-ember-600 px-6 py-2.5 text-sm font-bold text-white shadow-2xs transition hover:from-ember-600 hover:to-ember-700 hover:shadow-md">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ __('editorial.save') }}</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </form>
</div>

@push('scripts')
    @vite(['resources/js/establishment-map.js'])
    {{-- Three.js & GSAP Libraries --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. GSAP Entrance Animation for Card
            if (typeof gsap !== 'undefined') {
                gsap.from('.step-card-container', {
                    opacity: 0,
                    y: 20,
                    duration: 0.6,
                    ease: 'power2.out'
                });
            }

            // 2. Three.js 3D Interactive Scene Initialization
            const container = document.getElementById('three-container');
            const canvas = document.getElementById('spa-contribution-3d-canvas');
            
            if (container && canvas && typeof THREE !== 'undefined') {
                const width = container.clientWidth || 400;
                const height = container.clientHeight || 200;

                const scene = new THREE.Scene();
                const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
                camera.position.z = 6;

                const renderer = new THREE.WebGLRenderer({
                    canvas: canvas,
                    alpha: true,
                    antialias: true
                });
                renderer.setSize(width, height);
                renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

                // 3D Geometric Crystal Spa Mesh
                const geometry = new THREE.IcosahedronGeometry(1.8, 1);
                const material = new THREE.MeshPhongMaterial({
                    color: 0xf97316, // Ember brand tone
                    wireframe: true,
                    transparent: true,
                    opacity: 0.65
                });
                const mesh = new THREE.Mesh(geometry, material);
                scene.add(mesh);

                // Inner glowing sphere
                const innerGeo = new THREE.SphereGeometry(1.1, 16, 16);
                const innerMat = new THREE.MeshBasicMaterial({
                    color: 0xf59e0b, // Amber glow
                    wireframe: true,
                    transparent: true,
                    opacity: 0.35
                });
                const innerMesh = new THREE.Mesh(innerGeo, innerMat);
                scene.add(innerMesh);

                // Ambient Particles
                const particleCount = 40;
                const particleGeo = new THREE.BufferGeometry();
                const particlePositions = new Float32Array(particleCount * 3);
                for (let i = 0; i < particleCount * 3; i += 3) {
                    particlePositions[i] = (Math.random() - 0.5) * 8;
                    particlePositions[i + 1] = (Math.random() - 0.5) * 8;
                    particlePositions[i + 2] = (Math.random() - 0.5) * 8;
                }
                particleGeo.setAttribute('position', new THREE.BufferAttribute(particlePositions, 3));
                const particleMat = new THREE.PointsMaterial({
                    color: 0x10b981, // Leaf green accent
                    size: 0.08,
                    transparent: true,
                    opacity: 0.8
                });
                const particles = new THREE.Points(particleGeo, particleMat);
                scene.add(particles);

                // Lights
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.8);
                scene.add(ambientLight);

                const pointLight = new THREE.PointLight(0xf97316, 2, 50);
                pointLight.position.set(5, 5, 5);
                scene.add(pointLight);

                // Mouse Parallax Interaction
                let mouseX = 0;
                let mouseY = 0;
                window.addEventListener('mousemove', function(e) {
                    mouseX = (e.clientX / window.innerWidth - 0.5) * 0.5;
                    mouseY = (e.clientY / window.innerHeight - 0.5) * 0.5;
                });

                // Animation Loop
                function animate() {
                    requestAnimationFrame(animate);
                    mesh.rotation.x += 0.005;
                    mesh.rotation.y += 0.008;
                    innerMesh.rotation.y -= 0.004;
                    particles.rotation.y += 0.002;

                    // Smooth lerp camera movement
                    camera.position.x += (mouseX * 2 - camera.position.x) * 0.05;
                    camera.position.y += (-mouseY * 2 - camera.position.y) * 0.05;
                    camera.lookAt(scene.position);

                    renderer.render(scene, camera);
                }
                animate();

                // Window Resize Handler
                window.addEventListener('resize', function() {
                    if (!container) return;
                    const w = container.clientWidth;
                    const h = container.clientHeight;
                    camera.aspect = w / h;
                    camera.updateProjectionMatrix();
                    renderer.setSize(w, h);
                });
            }
        });
    </script>
@endpush
