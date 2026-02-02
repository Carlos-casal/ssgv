import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input", "reason"];

    adjust(event) {
        const size = event.params.size;
        const delta = event.params.delta;
        const input = this.inputTargets.find(i => i.dataset.size === size);

        if (input) {
            let val = parseInt(input.value) || 0;
            input.value = val + delta;

            // Highlight change
            input.classList.add('is-modified');
        }
    }
}
