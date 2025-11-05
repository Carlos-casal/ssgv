import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["afluencia"];

    connect() {
        this.updateAfluenciaColor();
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
