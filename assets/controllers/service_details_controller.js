import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["details", "icon"];

    toggle() {
        this.detailsTarget.classList.toggle('hidden');
        this.iconTarget.classList.toggle('rotate-180');
    }
}