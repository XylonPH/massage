{{-- Applies the stored theme before first paint to avoid a flash of the wrong
     theme. Keep dependency-free and tiny: it blocks rendering. --}}
<script>
    (() => {
        const accountColorMode = @json(data_get(auth()->user(), 'appearance_preference.color_mode'));
        const accountScale = @json(data_get(auth()->user(), 'appearance_preference.text_scale_percent', 100));
        const accountContrast = @json(data_get(auth()->user(), 'appearance_preference.is_high_contrast', false));
        const accountReducedMotion = @json(data_get(auth()->user(), 'appearance_preference.is_reduced_motion', false));
        const preferred = accountColorMode === 'DRK' ? 'dark' : (accountColorMode === 'LGT' ? 'light' : null);
        if (preferred) localStorage.setItem('mn-theme', preferred);
        const stored = preferred || localStorage.getItem('mn-theme');
        if (stored === 'dark' || (stored !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
        document.documentElement.style.fontSize = `${Math.max(90, Math.min(125, accountScale))}%`;
        document.documentElement.classList.toggle('high-contrast', accountContrast);
        document.documentElement.classList.toggle('reduce-motion', accountReducedMotion);
    })();
</script>
