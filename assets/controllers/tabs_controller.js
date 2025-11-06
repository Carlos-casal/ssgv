import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["button", "panel"];

    connect() {
        this.change({ currentTarget: this.buttonTargets[0] });
    }

    change(event) {
        const selectedButton = event.currentTarget;

        this.buttonTargets.forEach((button, index) => {
            const panel = this.panelTargets[index];

            if (button === selectedButton) {
                button.classList.add('text-indigo-600', 'border-indigo-500');
                button.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'border-transparent');
                panel.classList.remove('hidden');
            } else {
                button.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'border-transparent');
                button.classList.remove('text-indigo-600', 'border-indigo-500');
                panel.classList.add('hidden');
            }
        });
    }
}
