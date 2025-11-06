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
        select.classList.remove('bg-green-500', 'text-white', 'border-green-700');
        select.classList.remove('bg-yellow-500', 'text-white', 'border-yellow-700');
        select.classList.remove('bg-red-500', 'text-white', 'border-red-700');

        switch (selectedValue) {
            case 'Baja':
                select.classList.add('bg-green-500', 'text-white', 'border-green-700');
                break;
            case 'Media':
                select.classList.add('bg-yellow-500', 'text-white', 'border-yellow-700');
                break;
            case 'Alta':
                select.classList.add('bg-red-500', 'text-white', 'border-red-700');
                break;
        }
    }
}
