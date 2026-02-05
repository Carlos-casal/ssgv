import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    connect() {
        console.log("Material Comms Form controller connected");
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Example: validate ISSI/IMEI length for communications
        const networkIdInput = document.querySelector('[id$="_networkId"]');
        if (networkIdInput) {
            networkIdInput.addEventListener('input', (e) => {
                const val = e.target.value;
                if (val && val.length < 5) {
                    e.target.classList.add('is-invalid');
                } else {
                    e.target.classList.remove('is-invalid');
                }
            });
        }
    }
}
