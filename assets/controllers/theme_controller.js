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

        // User requested: "si esta luna tien que ser modo oscuro ... si esta modo claro el icoo tiene que ser en sol"
        // This is a bit unusual (usually icon represents action, not state), but I will follow:
        // Moon icon = Dark Mode active
        // Sun icon = Light Mode active
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
