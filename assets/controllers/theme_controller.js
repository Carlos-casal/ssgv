import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["icon"];

    connect() {
        this.theme = localStorage.getItem('theme') || 'light';
        this._applyTheme();
    }

    toggle() {
        this.theme = this.theme === 'light' ? 'dark' : 'light';
        localStorage.setItem('theme', this.theme);
        this._applyTheme();
    }

    _applyTheme() {
        if (this.theme === 'dark') {
            document.documentElement.classList.add('dark');
            document.body.classList.remove('light-theme');
        } else {
            document.documentElement.classList.remove('dark');
            document.body.classList.add('light-theme');
        }

        this._updateIcon();
    }

    _updateIcon() {
        if (!this.hasIconTarget) return;

        // Moon = Dark Mode, Sun = Light Mode
        if (this.theme === 'dark') {
            this.iconTarget.setAttribute('data-lucide', 'moon');
        } else {
            this.iconTarget.setAttribute('data-lucide', 'sun');
        }

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }
}
