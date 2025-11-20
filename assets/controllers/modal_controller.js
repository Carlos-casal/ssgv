import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["modal", "invitationForm", "emailPreview", "emailBody", "emailInput"];

    open(event) {
        // Detener la propagación para evitar que el mismo clic active el listener de cierre en la ventana
        event.stopPropagation();
        this.modalTarget.classList.remove('hidden');
    }

    close(event) {
        // Esta lógica cierra el modal solo si el clic es en el fondo (this.modalTarget),
        // no en el contenido del modal.
        if (event.target !== this.modalTarget) {
            return;
        }
        this.resetModal();
    }

    closeButton() {
        this.resetModal();
    }

    resetModal() {
        this.modalTarget.classList.add('hidden');
        this.invitationFormTarget.classList.remove('hidden');
        this.emailPreviewTarget.classList.add('hidden');
        if (this.hasEmailInputTarget) {
            this.emailInputTarget.value = '';
        }
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
                this.resetModal();
            } else {
                alert('Error al enviar la invitación.');
                this.resetModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al enviar la invitación.');
            this.resetModal();
        });
    }
}
