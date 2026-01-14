import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["modal"];

    connect() {
        console.log("Hello Modal Controller CONECTADO. Si ves esto, el JS se ha cargado.");
    }

    open() {
        console.log("Botón de abrir modal pulsado. Intentando mostrar el modal...");
        if (this.hasModalTarget) {
            this.modalTarget.classList.remove('hidden');
            console.log("Modal mostrado. Si no lo ves, es un problema de CSS.");
        } else {
            console.error("ERROR: No se ha encontrado el target 'modal'. Revisa el HTML.");
        }
    }

    close(event) {
        // Cierra el modal solo si se hace clic en el fondo (el propio div del modal)
        if (event.target === this.modalTarget) {
            console.log("Fondo del modal pulsado. Ocultando modal...");
            this.modalTarget.classList.add('hidden');
        }
    }

    closeButton() {
        // Cierra el modal al hacer clic en el botón 'x'
        console.log("Botón de cerrar pulsado. Ocultando modal...");
        this.modalTarget.classList.add('hidden');
    }
}
