{{-- Applies the stored theme before first paint to avoid a flash of the wrong
     theme. Keep dependency-free and tiny: it blocks rendering. --}}
<script>
    (() => {
        const stored = localStorage.getItem('mn-theme');
        if (stored === 'dark' || (stored !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    })();
</script>
