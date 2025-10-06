import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.addEventListener('DOMContentLoaded', () => {
    const addVolunteerButton = document.getElementById('add-volunteer-button');
    const modal = document.getElementById('add-volunteer-modal');
    const closeModalButton = document.getElementById('close-modal-button');
    const sendInvitationButton = document.getElementById('send-invitation-button');
    const invitationForm = document.getElementById('invitation-form');
    const emailPreview = document.getElementById('email-preview');
    const emailBodyContent = document.getElementById('email-body-content');
    const closePreviewButton = document.getElementById('close-preview-button');
    const emailInput = document.getElementById('invitation-email');

    const resetModal = () => {
        modal.classList.add('hidden');
        invitationForm.classList.remove('hidden');
        emailPreview.classList.add('hidden');
        emailInput.value = '';
    };

    if (addVolunteerButton && modal && closeModalButton && sendInvitationButton && closePreviewButton) {
        addVolunteerButton.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeModalButton.addEventListener('click', resetModal);
        closePreviewButton.addEventListener('click', resetModal);

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                resetModal();
            }
        });

        sendInvitationButton.addEventListener('click', () => {
            const email = emailInput.value.trim();

            if (!email) {
                alert('Por favor, introduce una dirección de correo electrónico.');
                return;
            }

            fetch('/send-invitation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.is_dev) {
                    invitationForm.classList.add('hidden');
                    emailPreview.classList.remove('hidden');
                    emailBodyContent.innerHTML = data.email_body;
                } else if (data.message) {
                    alert('Invitación enviada correctamente a ' + email);
                    resetModal();
                } else {
                    alert('Error al enviar la invitación.');
                    resetModal();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar la invitación.');
                resetModal();
            });
        });
    }
});
