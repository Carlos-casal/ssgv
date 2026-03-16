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
        } else {
            document.documentElement.classList.remove('dark');
        }

        this._updateIcon();
    }

    _updateIcon() {
        if (!this.hasIconTarget) return;

        if (this.theme === 'dark') {
            this.iconTarget.setAttribute('data-lucide', 'sun');
        } else {
            this.iconTarget.setAttribute('data-lucide', 'moon');
        }

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }
}
