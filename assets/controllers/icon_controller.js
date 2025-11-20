import { Controller } from '@hotwired/stimulus';
import { createIcons } from 'lucide';

/**
 * Controller to render Lucide icons.
 * Attach to the <body> tag.
 */
export default class extends Controller {
    connect() {
        this.render();
    }

    render() {
        try {
            console.log('Attempting to render icons...');
            // Find all elements with the data-lucide attribute and render them.
            createIcons({
                // You can add default attributes here if needed
            });
            console.log('Successfully rendered icons.');
        } catch (error) {
            console.error('Error rendering icons:', error);
        }
    }
}
