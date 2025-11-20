import { Controller } from '@hotwired/stimulus';
import { createIcons } from 'lucide';

/*
 * This controller is responsible for rendering Lucide icons.
 * It should be attached to the <body> element.
 */
export default class extends Controller {
    connect() {
        // On initial page load or Turbo visit, render all icons.
        this.render();

        // If the DOM changes (e.g., a Turbo stream update), re-render icons.
        // This is more robust than just listening to turbo:load.
        this.observer = new MutationObserver(() => this.render());
        this.observer.observe(this.element, {
            childList: true,
            subtree: true,
        });
    }

    disconnect() {
        // Clean up the observer when the controller is disconnected.
        if (this.observer) {
            this.observer.disconnect();
        }
    }

    render() {
        console.log('Icon controller is rendering icons...');
        // createIcons is now imported directly from the lucide package.
        createIcons({
            attrs: {
                // Add any default attributes here if needed.
                // For example: 'stroke-width': 1.5
            }
        });
        console.log('Icon rendering finished.');
    }
}
