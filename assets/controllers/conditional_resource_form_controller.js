import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["inputWrapper", "afluencia"];

    connect() {
        // Set initial state on page load
        this.inputWrapperTargets.forEach(wrapper => {
            const checkbox = this.element.querySelector(`[data-target-id="${wrapper.id}"]`);
            if (checkbox && !checkbox.checked) {
                wrapper.classList.add('hidden');
            } else if (checkbox && checkbox.checked) {
                wrapper.classList.remove('hidden');
            }
        });
        this.updateAfluenciaColor();
    }

    toggleInput(event) {
        const checkbox = event.currentTarget;
        const targetId = checkbox.dataset.targetId;
        const wrapper = this.element.querySelector(`#${targetId}`);

        if (wrapper) {
            wrapper.classList.toggle('hidden', !checkbox.checked);
            const input = wrapper.querySelector('input[type="number"]');
            if (input && !checkbox.checked) {
                input.value = ''; // Clear value when hiding
            }
        }
    }

    updateAfluenciaColor() {
        const select = this.afluenciaTarget;
        const selectedValue = select.value;

        // Reset classes
        select.classList.remove('bg-green-100', 'text-green-800', 'border-green-300');
        select.classList.remove('bg-yellow-100', 'text-yellow-800', 'border-yellow-300');
        select.classList.remove('bg-red-100', 'text-red-800', 'border-red-300');

        switch (selectedValue) {
            case 'Baja':
                select.classList.add('bg-green-100', 'text-green-800', 'border-green-300');
                break;
            case 'Media':
                select.classList.add('bg-yellow-100', 'text-yellow-800', 'border-yellow-300');
                break;
            case 'Alta':
                select.classList.add('bg-red-100', 'text-red-800', 'border-red-300');
                break;
        }
    }
}
