import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["modal", "invitationForm", "emailPreview", "emailBody", "emailInput"];

    connect() {
    }

    open(event) {
        event.preventDefault();
        event.stopPropagation();
        this.modalTarget.classList.remove('hidden');
    }

    close() {
        this.modalTarget.classList.add('hidden');
    }

    stopPropagation(event) {
        event.stopPropagation();
    }

    sendInvitation() {
        const email = this.emailInputTarget.value.trim();

        if (!email) {
            alert('Por favor, introduce una dirección de correo electrónico.');
            return;
        }

        fetch('/send-invitation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.is_dev) {
                this.invitationFormTarget.classList.add('hidden');
                this.emailPreviewTarget.classList.remove('hidden');
                this.emailBodyTarget.innerHTML = data.email_body;
            } else if (data.message) {
                alert('Invitación enviada correctamente a ' + email);
                this.close();
            } else {
                alert('Error al enviar la invitación.');
                this.close();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al enviar la invitación.');
            this.close();
        });
    }
}
