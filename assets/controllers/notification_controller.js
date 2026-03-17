import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["item", "badge", "dropdown"];

    markAsRead(event) {
        const id = event.currentTarget.dataset.id;
        const url = event.currentTarget.dataset.url;
        const targetUrl = event.currentTarget.getAttribute('href');
        const item = event.currentTarget.closest('[data-notification-target="item"]');

        // Prevent default if it's just a button or we want to handle navigation manually
        // But in our case, it's an overlay link <a>, we want it to navigate.
        // We fire the fetch and then let the browser navigate, or navigate in then().

        event.preventDefault();

        fetch(url, { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (item) {
                        item.classList.add('opacity-50');
                        item.classList.remove('border-cyan-500', 'bg-cyan-500/5');
                        item.classList.add('border-transparent');
                    }
                    this.updateBadge();
                }
                // Navigate to the target URL after marking as read
                if (targetUrl && targetUrl !== '#') {
                    window.location.href = targetUrl;
                }
            });
    }

    markAllAsRead(event) {
        event.preventDefault();
        const url = event.currentTarget.dataset.url;

        fetch(url, { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.itemTargets.forEach(item => {
                        item.classList.add('opacity-50');
                        item.classList.remove('border-cyan-500', 'bg-cyan-500/5');
                        item.classList.add('border-transparent');
                    });
                    this.updateBadge();
                }
            });
    }

    delete(event) {
        event.preventDefault();
        event.stopPropagation();
        const id = event.currentTarget.dataset.id;
        const url = event.currentTarget.dataset.url;
        const item = event.currentTarget.closest('[data-notification-target="item"]');

        fetch(url, { method: 'DELETE' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (item) item.remove();
                    this.updateBadge();
                }
            });
    }

    deleteAll(event) {
        event.preventDefault();
        const url = event.currentTarget.dataset.url;

        fetch(url, { method: 'DELETE' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.itemTargets.forEach(item => item.remove());
                    this.updateBadge();
                }
            });
    }

    updateBadge() {
        const unreadCount = this.itemTargets.filter(item => !item.classList.contains('opacity-50')).length;
        if (unreadCount > 0) {
            this.badgeTarget.textContent = unreadCount;
            this.badgeTarget.classList.remove('d-none');
        } else {
            this.badgeTarget.classList.add('d-none');
        }
    }
}
