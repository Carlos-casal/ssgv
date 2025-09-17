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
    const showModalBtn = document.getElementById('show-volunteers-btn');

    if (showModalBtn) {
        showModalBtn.addEventListener('click', async () => {
            const url = showModalBtn.dataset.url;
            if (!url) {
                console.error('The URL for fetching volunteers is not defined in data-url attribute.');
                alert('No se pudo cargar la lista de voluntarios: URL no especificada.');
                return;
            }
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const volunteers = await response.json();
                createModal(volunteers);
            } catch (error) {
                console.error('Error fetching volunteers:', error);
                alert('No se pudo cargar la lista de voluntarios.');
            }
        });
    }

    function createModal(volunteers) {
        const existingModal = document.getElementById('volunteer-list-modal');
        if (existingModal) {
            existingModal.remove();
        }

        const modal = document.createElement('div');
        modal.id = 'volunteer-list-modal';
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center';
        modal.style.zIndex = '1050';

        const modalContent = document.createElement('div');
        modalContent.className = 'relative mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white';

        const modalHeader = document.createElement('div');
        modalHeader.className = 'flex justify-between items-center pb-3 border-b';
        modalHeader.innerHTML = '<h3 class="text-lg font-medium text-gray-900">Lista de Voluntarios</h3>';

        const closeButton = document.createElement('button');
        closeButton.innerHTML = '&times;';
        closeButton.className = 'text-black close-modal text-2xl leading-none hover:text-gray-700';

        modalHeader.appendChild(closeButton);
        modalContent.appendChild(modalHeader);

        const list = document.createElement('ul');
        list.className = 'space-y-2 max-h-80 overflow-y-auto py-3';

        if (volunteers.length > 0) {
            volunteers.forEach(v => {
                const listItem = document.createElement('li');
                listItem.className = 'p-2 border-b border-gray-200';
                listItem.textContent = `ID: ${v.id} - ${v.name}`;
                list.appendChild(listItem);
            });
        } else {
            const noVolunteers = document.createElement('p');
            noVolunteers.textContent = 'No hay voluntarios para mostrar.';
            list.appendChild(noVolunteers);
        }

        modalContent.appendChild(list);
        modal.appendChild(modalContent);
        document.body.appendChild(modal);

        const closeModal = () => modal.remove();
        closeButton.onclick = closeModal;
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
    }
});
