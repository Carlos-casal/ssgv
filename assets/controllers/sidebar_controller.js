import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["sidebar", "content", "submenu", "toggleIcon", "mobileIcon"];
    static values = {
        collapsed: Boolean
    }

    connect() {
        const stored = localStorage.getItem('sidebar-collapsed');
        if (stored !== null) {
            this.collapsedValue = stored === 'true';
        } else {
            // Auto-collapse on small screens if no preference is stored
            this.collapsedValue = window.innerWidth < 1200;
        }

        this._updateState();

        // Listen for resize to auto-collapse/expand
        this.resizeObserver = new ResizeObserver(() => {
            this._handleResize();
        });
        this.resizeObserver.observe(document.body);

        // Close sidebar when clicking outside on mobile
        this._handleClickOutside = (event) => {
            if (window.innerWidth < 1200 && !this.collapsedValue) {
                const sidebar = this.sidebarTarget;
                const toggleBtn = this.element.querySelector('button[data-action="click->sidebar#toggleCollapse"]');

                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    this.collapsedValue = true;
                    this._updateState();
                }
            }
        };
        document.addEventListener('mousedown', this._handleClickOutside);
    }

    disconnect() {
        if (this.resizeObserver) {
            this.resizeObserver.disconnect();
        }
        document.removeEventListener('mousedown', this._handleClickOutside);
    }

    _handleResize() {
        const shouldCollapse = window.innerWidth < 1200;

        if (shouldCollapse !== this.collapsedValue) {
            this.collapsedValue = shouldCollapse;
            this._updateState();
        }
    }

    toggleCollapse() {
        this.collapsedValue = !this.collapsedValue;
        localStorage.setItem('sidebar-collapsed', this.collapsedValue);
        this._updateState();
    }

    toggleSubmenu(event) {
        const item = event.currentTarget.closest('.has-submenu');
        if (!item) return;

        const isAlreadyOpen = item.classList.contains('submenu-open');

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
        const toggleBtn = document.getElementById('sidebar-toggle-btn');
        const links = this.element.querySelectorAll('a[data-bs-toggle="tooltip"], button[data-bs-toggle="tooltip"]');

        if (this.collapsedValue) {
            this.sidebarTarget.classList.add('sidebar-collapsed');
            this.sidebarTarget.classList.remove('w-64');
            this.sidebarTarget.classList.add('w-20');
            if (this.hasContentTarget) {
                this.contentTarget.classList.add('sidebar-collapsed-content');
            }

            if (this.hasToggleIconTarget) {
                this.toggleIconTarget.setAttribute('data-lucide', 'chevron-right');
            }

            if (toggleBtn) {
                toggleBtn.setAttribute('title', 'Expandir barra lateral');
                const tooltip = bootstrap.Tooltip.getInstance(toggleBtn);
                if (tooltip) tooltip.setContent({ '.tooltip-inner': 'Expandir barra lateral' });
            }

            if (this.hasMobileIconTarget) {
                this.mobileIconTarget.setAttribute('data-lucide', 'menu');
            }

            this.submenuTargets.forEach(el => el.classList.remove('submenu-open'));

            links.forEach(link => {
                if (link.id === 'sidebar-toggle-btn') return;
                const tooltip = bootstrap.Tooltip.getInstance(link);
                if (tooltip) tooltip.enable();
            });
        } else {
            this.sidebarTarget.classList.remove('sidebar-collapsed');
            this.sidebarTarget.classList.remove('w-20');
            this.sidebarTarget.classList.add('w-64');
            if (this.hasContentTarget) {
                this.contentTarget.classList.remove('sidebar-collapsed-content');
            }

            if (this.hasToggleIconTarget) {
                this.toggleIconTarget.setAttribute('data-lucide', 'chevron-left');
            }

            if (toggleBtn) {
                toggleBtn.setAttribute('title', 'Contraer barra lateral');
                const tooltip = bootstrap.Tooltip.getInstance(toggleBtn);
                if (tooltip) tooltip.setContent({ '.tooltip-inner': 'Contraer barra lateral' });
            }

            if (this.hasMobileIconTarget) {
                this.mobileIconTarget.setAttribute('data-lucide', 'x');
            }

            links.forEach(link => {
                if (link.id === 'sidebar-toggle-btn') return;
                const tooltip = bootstrap.Tooltip.getInstance(link);
                if (tooltip) tooltip.disable();
            });
        }

        // Refresh Lucide icons
        if (window.lucide) {
            window.lucide.createIcons();
        }

        this.dispatch('toggled', { detail: { collapsed: this.collapsedValue } });
    }
}
