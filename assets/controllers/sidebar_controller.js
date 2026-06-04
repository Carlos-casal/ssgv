import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["sidebar", "content", "submenu", "toggleIcon", "mobileIcon"];
    static values = {
        collapsed: Boolean
    }

    connect() {
        const stored = localStorage.getItem('sidebar-collapsed');
        this.isManual = stored !== null;

        if (this.isManual) {
            this.collapsedValue = stored === 'true';
        } else {
            // Auto-collapse on small screens if no preference is stored
            this.collapsedValue = window.innerWidth < 1200;
        }

        this._updateState();

        // Store current width to detect breakpoint crossing
        this.lastWidth = window.innerWidth;

        // Listen for resize to auto-collapse/expand only when crossing breakpoint
        this.resizeObserver = new ResizeObserver(() => {
            this._handleResize();
        });
        this.resizeObserver.observe(document.documentElement);

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
        const currentWidth = window.innerWidth;
        const wasBelow = this.lastWidth < 1200;
        const isBelow = currentWidth < 1200;

        // Only auto-change if we cross the breakpoint
        if (wasBelow !== isBelow) {
            // If going to mobile, always collapse (drawer mode)
            if (isBelow) {
                this.collapsedValue = true;
            } else {
                // If going to desktop, restore manual preference or default to expanded
                const stored = localStorage.getItem('sidebar-collapsed');
                if (stored !== null) {
                    this.collapsedValue = stored === 'true';
                } else {
                    this.collapsedValue = false;
                }
            }
            this._updateState();
        }
        this.lastWidth = currentWidth;
    }

    toggleCollapse() {
        this.collapsedValue = !this.collapsedValue;
        // Only save preference if we are in desktop mode
        if (window.innerWidth >= 1200) {
            localStorage.setItem('sidebar-collapsed', this.collapsedValue);
        }
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

        // Cleanup existing tooltips to avoid ghost tooltips
        links.forEach(link => {
            const tooltip = bootstrap.Tooltip.getInstance(link);
            if (tooltip) {
                tooltip.hide();
                // We update the delay to 0 for the toggle button so it feels more responsive if needed,
                // but user asked for 2s general. However, toggle button usually shouldn't wait 2s if it's the main UI action.
                // Actually, user said: "al hacer hover debe aparecer un tooltip". Didn't specify different delay for it.
            }
        });

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
                const newTitle = 'Expandir barra lateral';
                toggleBtn.setAttribute('title', newTitle);
                toggleBtn.setAttribute('data-bs-original-title', '');

                let tooltip = bootstrap.Tooltip.getInstance(toggleBtn);
                if (tooltip) {
                    tooltip.dispose();
                }

                // Re-initialize after a short delay or just ensure attributes are clean
                toggleBtn.setAttribute('data-bs-title', newTitle);
                new bootstrap.Tooltip(toggleBtn, {
                    delay: { "show": 2000, "hide": 100 }
                });
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
                const newTitle = 'Contraer barra lateral';
                toggleBtn.setAttribute('title', newTitle);
                toggleBtn.setAttribute('data-bs-original-title', '');

                let tooltip = bootstrap.Tooltip.getInstance(toggleBtn);
                if (tooltip) {
                    tooltip.dispose();
                }

                toggleBtn.setAttribute('data-bs-title', newTitle);
                new bootstrap.Tooltip(toggleBtn, {
                    delay: { "show": 2000, "hide": 100 }
                });
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
