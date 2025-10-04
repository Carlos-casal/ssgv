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

    if (addVolunteerButton && modal && closeModalButton && sendInvitationButton) {
        addVolunteerButton.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeModalButton.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });

        sendInvitationButton.addEventListener('click', () => {
            const emailInput = document.getElementById('invitation-email');
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
                if (data.message) {
                    alert('Invitación enviada correctamente a ' + email);
                } else {
                    alert('Error al enviar la invitación.');
                }
                modal.classList.add('hidden');
                emailInput.value = ''; // Clear the input
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar la invitación.');
                modal.classList.add('hidden');
            });
        });
    }
});
