import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["modal"];

    open() {
        this.modalTarget.classList.remove('hidden');
    }

    close(event) {
        // Cierra el modal si se hace clic fuera del contenido principal
        if (event.target === this.modalTarget) {
            this.modalTarget.classList.add('hidden');
        }
    }

    closeButton() {
        // Cierra el modal al hacer clic en el botón de cerrar
        this.modalTarget.classList.add('hidden');
    }
}
