import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.sanitize = this.sanitize.bind(this);
        this.element.querySelector('input').addEventListener('input', this.sanitize);
    }

    disconnect() {
        this.element.querySelector('input').removeEventListener('input', this.sanitize);
    }

    sanitize(event) {
        const regex = /[^a-zA-Z\sñÑáéíóúÁÉÍÓÚüÜ]/g;
        event.target.value = event.target.value.replace(regex, '');
    }
}