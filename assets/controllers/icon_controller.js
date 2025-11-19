import { Controller } from '@hotwired/stimulus';

/*
 * This controller is responsible for rendering Lucide icons.
 * It should be attached to the <body> element.
 */
export default class extends Controller {
    connect() {
        this.renderIcons();
    }

    renderIcons() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        } else {
            // If lucide is not yet available, wait a bit and try again.
            // This handles race conditions with the CDN script loading,
            // especially with Turbo navigations.
            setTimeout(() => this.renderIcons(), 50);
        }
    }
}
