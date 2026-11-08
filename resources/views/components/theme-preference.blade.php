<script>
    (function () {
        const preferenceVersion = 'dark-default-v1';
        const storageKey = 'theme';

        if (localStorage.getItem('themePreferenceVersion') !== preferenceVersion) {
            localStorage.setItem(storageKey, 'dark');
            localStorage.setItem('themePreferenceVersion', preferenceVersion);
        }

        const applyTheme = (theme) => {
            document.documentElement.classList.toggle('light', theme === 'light');
            document.documentElement.classList.toggle('dark', theme !== 'light');
        };

        applyTheme(localStorage.getItem(storageKey) || 'dark');

        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                mode: localStorage.getItem(storageKey) || 'dark',

                toggle() {
                    this.mode = this.mode === 'dark' ? 'light' : 'dark';
                    localStorage.setItem(storageKey, this.mode);
                    applyTheme(this.mode);
                },
            });
        });
    })();
</script>
