import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["sidebar", "content", "submenu", "toggleIcon"];
    static values = {
        collapsed: Boolean
    }

    connect() {
        this.collapsedValue = localStorage.getItem('sidebar-collapsed') === 'true';
        this.theme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', this.theme);

        // Auto-collapse on small screens
        this._checkResponsive();
        window.addEventListener('resize', this._checkResponsive.bind(this));

        this._updateState();
    }

    disconnect() {
        window.removeEventListener('resize', this._checkResponsive.bind(this));
    }

    _checkResponsive() {
        if (window.innerWidth < 1024) { // lg breakpoint in Tailwind
            this.collapsedValue = true;
        }
    }

    toggleCollapse() {
        this.collapsedValue = !this.collapsedValue;
        localStorage.setItem('sidebar-collapsed', this.collapsedValue);
        this._updateState();
    }

    toggleTheme() {
        this.theme = this.theme === 'light' ? 'dark' : 'light';
        localStorage.setItem('theme', this.theme);
        document.documentElement.setAttribute('data-theme', this.theme);

        // Update Lucide icons after theme change if they contain theme-specific classes
        if (window.lucide) window.lucide.createIcons();
    }

    toggleSubmenu(event) {
        const item = event.currentTarget.closest('.has-submenu');
        if (!item) return;

        // In collapsed mode, we might want different behavior,
        // but for now let's just toggle.

        const isAlreadyOpen = item.classList.contains('submenu-open');

        // Close other submenus at the same level
        const parentUl = item.parentElement;
        parentUl.querySelectorAll(':scope > .has-submenu').forEach(sibling => {
            if (sibling !== item) {
                sibling.classList.remove('submenu-open');
                const link = sibling.querySelector('a[aria-expanded]');
                if (link) link.setAttribute('aria-expanded', 'false');
            }
        });

        if (isAlreadyOpen) {
            item.classList.remove('submenu-open');
            event.currentTarget.setAttribute('aria-expanded', 'false');
        } else {
            item.classList.add('submenu-open');
            event.currentTarget.setAttribute('aria-expanded', 'true');
        }
    }

    _updateState() {
        if (this.collapsedValue) {
            this.sidebarTarget.classList.add('sidebar-collapsed');
            this.sidebarTarget.classList.remove('w-64');
            this.sidebarTarget.classList.add('w-20');
            if (this.hasContentTarget) {
                this.contentTarget.classList.add('sidebar-collapsed-content');
            }

            // Update icon
            if (this.hasToggleIconTarget) {
                this.toggleIconTarget.setAttribute('data-lucide', 'panel-left-open');
            }

            // Close all submenus when collapsing
            this.submenuTargets.forEach(el => el.classList.remove('submenu-open'));
        } else {
            this.sidebarTarget.classList.remove('sidebar-collapsed');
            this.sidebarTarget.classList.remove('w-20');
            this.sidebarTarget.classList.add('w-64');
            if (this.hasContentTarget) {
                this.contentTarget.classList.remove('sidebar-collapsed-content');
            }

            // Update icon
            if (this.hasToggleIconTarget) {
                this.toggleIconTarget.setAttribute('data-lucide', 'panel-left-close');
            }
        }

        // Re-initialize Lucide icons
        if (window.lucide) {
            window.lucide.createIcons();
        }

        // Dispatch event for other components if needed
        this.dispatch('toggled', { detail: { collapsed: this.collapsedValue } });
    }
}
