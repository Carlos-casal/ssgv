import { Controller } from '@hotwired/stimulus';

/**
 * Controlador para gestionar el encabezado de la página.
 * - Actualiza el título del documento para que coincida con el primer H1.
 * - Elimina todos los elementos H2 de la página.
 */
export default class extends Controller {
  connect() {
    this.updateTitle();
    this.removeSubheadings();
  }

  /**
   * Encuentra el primer elemento H1 y establece el título del documento con su contenido.
   * Si no se encuentra ningún H1, no se realiza ninguna acción.
   */
  updateTitle() {
    const mainHeading = this.element.querySelector('h1');
    if (mainHeading && mainHeading.textContent) {
      document.title = `${mainHeading.textContent.trim()} - PC Vigo`;
    }
  }

  /**
   * Encuentra y elimina todos los elementos H2 del documento.
   */
  removeSubheadings() {
    const subheadings = this.element.querySelectorAll('h2');
    subheadings.forEach(h2 => h2.remove());
  }
}
