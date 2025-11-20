import { Controller } from '@hotwired/stimulus';
import { createIcons } from 'lucide';

export default class extends Controller {
    connect() {
        createIcons();

        // Optional: If you need to re-render icons after Turbo streams/frames updates
        document.addEventListener('turbo:render', () => {
            createIcons();
        });
    }
}
