import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        const hasDarkClass = document.documentElement.classList.contains('dark');
        this.theme = localStorage.getItem('theme') || (hasDarkClass ? 'dark' : 'light');
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
        
        // No need to manually update icons anymore as we use CSS dark: classes
        // but we trigger lucide just in case for new elements
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }
}
