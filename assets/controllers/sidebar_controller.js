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
            this.collapsedValue = window.innerWidth < 1024;
        }

        this._updateState();

        // Listen for resize to auto-collapse/expand
        this.resizeObserver = new ResizeObserver(() => {
            this._handleResize();
        });
        this.resizeObserver.observe(document.body);
    }

    disconnect() {
        if (this.resizeObserver) {
            this.resizeObserver.disconnect();
        }
    }

    _handleResize() {
        const shouldCollapse = window.innerWidth < 1024;
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
        if (this.collapsedValue) {
            this.sidebarTarget.classList.add('sidebar-collapsed');
            this.sidebarTarget.classList.remove('w-64');
            this.sidebarTarget.classList.add('w-20');
            if (this.hasContentTarget) {
                this.contentTarget.classList.add('sidebar-collapsed-content');
            }

            if (this.hasToggleIconTarget) {
                // Ensure the icon is chevron-right when collapsed (to expand)
                this.toggleIconTarget.setAttribute('data-lucide', 'chevron-right');
            }

            if (this.hasMobileIconTarget) {
                this.mobileIconTarget.setAttribute('data-lucide', 'menu');
            }

            this.submenuTargets.forEach(el => el.classList.remove('submenu-open'));

            const links = this.element.querySelectorAll('a[data-title]');
            links.forEach(link => {
                const title = link.getAttribute('data-title');
                if (title) {
                    link.setAttribute('title', title);
                }
            });
        } else {
            this.sidebarTarget.classList.remove('sidebar-collapsed');
            this.sidebarTarget.classList.remove('w-20');
            this.sidebarTarget.classList.add('w-64');
            if (this.hasContentTarget) {
                this.contentTarget.classList.remove('sidebar-collapsed-content');
            }

            if (this.hasToggleIconTarget) {
                // Ensure the icon is chevron-left when expanded (to collapse)
                this.toggleIconTarget.setAttribute('data-lucide', 'chevron-left');
            }

            if (this.hasMobileIconTarget) {
                this.mobileIconTarget.setAttribute('data-lucide', 'x');
            }

            const links = this.element.querySelectorAll('a[data-title]');
            links.forEach(link => {
                link.removeAttribute('title');
            });
        }

        // Refresh Lucide icons
        if (window.lucide) {
            window.lucide.createIcons();
        }

        this.dispatch('toggled', { detail: { collapsed: this.collapsedValue } });
    }
}
