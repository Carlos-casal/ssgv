import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["container", "prototype"];

    connect() {
        this.index = this.containerTarget.querySelectorAll('.kit-item-row').length;
        this.filterOptions();
    }

    addItem(event) {
        if (event) {
            event.preventDefault();
        }

        const template = this.prototypeTarget;
        // Use .innerHTML as it works for both <template> and normal tags in Stimulus
        const html = template.innerHTML.replace(/__index__/g, this.index);
        this.containerTarget.insertAdjacentHTML('beforeend', html);
        this.index++;

        if (window.lucide) {
            window.lucide.createIcons();
        }

        this.filterOptions();
    }

    removeItem(event) {
        if (event) {
            event.preventDefault();
        }

        const row = event.target.closest('.kit-item-row');
        if (row) {
            row.remove();
            this.filterOptions();
        }
    }

    filterOptions() {
        // Find all selects with material-select class
        const selects = document.querySelectorAll('.material-select');
        const selectedValues = Array.from(selects)
            .map(select => select.value)
            .filter(value => value !== "" && value !== null);

        selects.forEach(select => {
            const currentValue = select.value;
            const options = select.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === "" || option.value === currentValue) {
                    option.style.display = "";
                    option.disabled = false;
                } else if (selectedValues.includes(option.value)) {
                    option.style.display = "none";
                    option.disabled = true;
                } else {
                    option.style.display = "";
                    option.disabled = false;
                }
            });
        });
    }
}
