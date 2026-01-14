import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["modal"];

    open() {
        if (this.hasModalTarget) {
            this.modalTarget.classList.remove('hidden');
        }
    }

    close(event) {
        // Cierra el modal solo si se hace clic en el fondo (el propio div del modal)
        if (event.target === this.modalTarget) {
            this.modalTarget.classList.add('hidden');
        }
    }

    closeButton() {
        // Cierra el modal al hacer clic en el botón 'x'
        this.modalTarget.classList.add('hidden');
    }
}
