import { Controller } from '@hotwired/stimulus';
import tinymce from 'tinymce';
import 'tinymce/themes/silver';
import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/ui/oxide/content.min.css';
import 'tinymce/skins/content/default/content.min.css';

export default class extends Controller {
    static targets = [
        "tabLink",
        "tabContent",
        "quillDescription",
        "quillTasks",
        "description",
        "tasks",
        "modal",
        "userSearchInput",
        "userList",
        "paginationContainer",
    "paginationSummary",
        "attendanceStatusSelect",
        "itemsPerPageSelect",
    "individualFichajeModal",
    "individualFichajeModalTitle",
    "lastClockOutTime",
    ];

    connect() {
        if (this.hasModalTarget) {
            this.setupAttendanceModal();
        }
        tinymce.init({
            selector: 'textarea#service_tasks, textarea#service_description',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            promotion: false,
            skin_url: '/build/skins/ui/oxide',
            content_css: '/build/skins/content/default/content.css'
        });
    }

    disconnect() {
        tinymce.remove('textarea#service_tasks');
        tinymce.remove('textarea#service_description');
    }

    switchTab(event) {
        event.preventDefault();
        const clickedLink = event.currentTarget;
        this.tabLinkTargets.forEach(link => link.classList.remove('active'));
        clickedLink.classList.add('active');

        const targetId = clickedLink.dataset.target;
        this.tabContentTargets.forEach(content => {
            if (content.id === targetId) {
                content.classList.remove('hidden');
            } else {
                content.classList.add('hidden');
            }
        });
    }

    setupAttendanceModal() {
        this.selectedVolunteers = [];
        this.searchTimeout = null;
    }

    openModal() {
        this.modalTarget.classList.remove('hidden');
        this.modalTarget.classList.add('flex');
    this.attendanceStatusSelectTarget.value = '';
        this.fetchVolunteers(1, '');
    }

    closeModal() {
        this.modalTarget.classList.add('hidden');
        this.modalTarget.classList.remove('flex');
    }

    async fetchVolunteers(page = 1, search = '') {
        const serviceId = this.element.dataset.serviceId;
        const limit = this.itemsPerPageSelectTarget.value;
        const url = `/services/${serviceId}/volunteers?page=${page}&search=${encodeURIComponent(search)}&limit=${limit}`;
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            this.renderVolunteers(data.items);
        this.renderPagination(data.pagination, data.items);
        } catch (error) {
            console.error('Error fetching volunteers:', error);
            this.userListTarget.innerHTML = '<p class="text-red-500">Error al cargar voluntarios.</p>';
        }
    }

    renderVolunteers(volunteers) {
        this.userListTarget.innerHTML = '';
        if (volunteers.length === 0) {
        this.userListTarget.innerHTML = '<p class="text-gray-500 p-4">No se encontraron voluntarios.</p>';
            return;
        }
        volunteers.forEach(volunteer => {
            const isSelected = this.selectedVolunteers.includes(volunteer.id);
            const row = document.createElement('div');
        row.className = `flex items-center p-2 rounded-lg cursor-pointer transition-colors ${isSelected ? 'bg-blue-100' : 'hover:bg-gray-100'}`;
            row.dataset.volunteerId = volunteer.id;

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300';
        checkbox.checked = isSelected;
        checkbox.addEventListener('change', (e) => {
            e.stopPropagation();
            this.toggleSelection(volunteer.id, row);
        });

        const nameSpan = document.createElement('span');
        nameSpan.className = 'ml-3 font-medium text-gray-700';
        nameSpan.textContent = `${volunteer.id} - ${volunteer.name} ${volunteer.lastName || ''}`;

        row.appendChild(checkbox);
        row.appendChild(nameSpan);

        row.addEventListener('click', (e) => {
            if (e.target.type !== 'checkbox') {
                this.toggleSelection(volunteer.id, row);
            }
        });

            this.userListTarget.appendChild(row);
        });
    }

    toggleSelection(volunteerId, rowElement) {
        const checkbox = rowElement.querySelector('input[type="checkbox"]');
        const index = this.selectedVolunteers.indexOf(volunteerId);

        if (index > -1) {
            this.selectedVolunteers.splice(index, 1);
            checkbox.checked = false;
            rowElement.classList.remove('bg-blue-100');
        } else {
            this.selectedVolunteers.push(volunteerId);
            checkbox.checked = true;
            rowElement.classList.add('bg-blue-100');
        }
    }

renderPagination(pagination, items) {
    if (this.hasPaginationSummaryTarget) {
        if (pagination.totalCount > 0) {
            const start = (pagination.currentPage - 1) * this.itemsPerPageSelectTarget.value + 1;
            const end = start + items.length - 1;
            this.paginationSummaryTarget.textContent = `Mostrando ${start}-${end} de ${pagination.totalCount}`;
        } else {
            this.paginationSummaryTarget.textContent = 'No se encontraron registros.';
        }
    }

    this.paginationContainerTarget.innerHTML = '';
    const { currentPage, totalPages } = pagination;

    const createButton = (text, page, disabled = false, active = false) => {
        const button = document.createElement('button');
        button.innerHTML = text;
        button.disabled = disabled;
        button.className = `px-3 py-1 mx-1 rounded-lg text-sm transition-colors ${active ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300'} ${disabled ? 'opacity-50 cursor-not-allowed' : ''}`;
        if (!disabled) {
            button.addEventListener('click', () => this.fetchVolunteers(page, this.userSearchInputTarget.value));
        }
        return button;
    };

    this.paginationContainerTarget.appendChild(createButton('&larr; Anterior', currentPage - 1, currentPage === 1));

    const pageNumbers = [];
    const pagesToShow = 5;
    const startPage = Math.max(1, currentPage - Math.floor(pagesToShow / 2));
    const endPage = Math.min(totalPages, startPage + pagesToShow - 1);

    if (startPage > 1) {
        pageNumbers.push(createButton(1, 1));
        if (startPage > 2) {
            const ellipsis = document.createElement('span');
            ellipsis.textContent = '...';
            ellipsis.className = 'px-3 py-1 mx-1';
            pageNumbers.push(ellipsis);
        }
    }

    for (let i = startPage; i <= endPage; i++) {
        pageNumbers.push(createButton(i, i, false, i === currentPage));
    }

    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            const ellipsis = document.createElement('span');
            ellipsis.textContent = '...';
            ellipsis.className = 'px-3 py-1 mx-1';
            pageNumbers.push(ellipsis);
        }
        pageNumbers.push(createButton(totalPages, totalPages));
    }

    pageNumbers.forEach(el => this.paginationContainerTarget.appendChild(el));

    this.paginationContainerTarget.appendChild(createButton('Siguiente &rarr;', currentPage + 1, currentPage === totalPages));
}

    search() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            this.fetchVolunteers(1, this.userSearchInputTarget.value);
        }, 300);
    }

    clearSearch() {
        this.userSearchInputTarget.value = '';
        this.search();
    }

    async saveAttendance() {
        if (this.selectedVolunteers.length === 0) {
            alert('Por favor, selecciona al menos un voluntario.');
            return;
        }

        const status = this.attendanceStatusSelectTarget.value;
    if (!status) {
        alert('Por favor, selecciona una respuesta (Asiste / No asiste).');
        return;
    }

        const serviceId = this.element.dataset.serviceId;
        const url = `/services/${serviceId}/update-attendance`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ volunteerIds: this.selectedVolunteers, status: status })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                alert('Asistencia actualizada correctamente.');
                this.closeModal();
                location.reload(); // Or update the list dynamically
            } else {
                throw new Error(result.message || 'Error al guardar la asistencia.');
            }
        } catch (error) {
            console.error('Error saving attendance:', error);
            alert(error.message);
        }
    }

    clockInAll(event) {
        event.preventDefault();
        alert('Funcionalidad "Fichar todos" pendiente de implementar.');
    }

    openIndividualFichajeModal(event) {
        const button = event.currentTarget;
        const volunteerServiceId = button.dataset.volunteerServiceId;
        const volunteerName = button.dataset.volunteerName;
        const lastClockOut = button.dataset.lastClockOut;

        this.individualFichajeModalTitleTarget.textContent = `Añadir Fichaje para ${volunteerName}`;

        if (this.hasLastClockOutTimeTarget) {
            if (lastClockOut) {
                const date = new Date(lastClockOut);
                this.lastClockOutTimeTarget.textContent = date.toLocaleString('es-ES', {
                    year: 'numeric', month: '2-digit', day: '2-digit',
                    hour: '2-digit', minute: '2-digit'
                });
            } else {
                this.lastClockOutTimeTarget.textContent = 'No hay registros previos.';
            }
        }

        const form = this.individualFichajeModalTarget.querySelector('form');
        form.action = `/volunteer_service/${volunteerServiceId}/add_fichaje`;

        // Reset form fields
        form.reset();
        document.getElementById('individual-start-date').value = '';
        document.getElementById('individual-start-time').value = '';
        document.getElementById('individual-end-date').value = '';
        document.getElementById('individual-end-time').value = '';
        document.getElementById('individual-notes').value = '';


        this.individualFichajeModalTarget.classList.remove('hidden');
    }

    closeIndividualFichajeModal() {
        this.individualFichajeModalTarget.classList.add('hidden');
    }

    openEditFichajeModal(event) {
        const button = event.currentTarget;
        const fichajeId = button.dataset.fichajeId;
        const startTime = button.dataset.startTime;
        const endTime = button.dataset.endTime;
        const notes = button.dataset.notes;

        // The modal is the same as the "add" modal, we just change its title and form action
        this.individualFichajeModalTarget.classList.remove('hidden');
        this.individualFichajeModalTitleTarget.textContent = `Editar Fichaje`;

        const form = this.individualFichajeModalTarget.querySelector('form');
        form.action = `/fichaje/${fichajeId}/edit`;

        // Pre-fill the form
        if (startTime) {
            document.getElementById('individual-start-date').value = startTime.split('T')[0];
            document.getElementById('individual-start-time').value = startTime.split('T')[1];
        }
        if (endTime) {
            document.getElementById('individual-end-date').value = endTime.split('T')[0];
            document.getElementById('individual-end-time').value = endTime.split('T')[1];
        } else {
            document.getElementById('individual-end-date').value = '';
            document.getElementById('individual-end-time').value = '';
        }
        document.getElementById('individual-notes').value = notes;
    }

    async saveAndAddAnother(event) {
        event.preventDefault();
        const form = this.individualFichajeModalTarget.querySelector('form');
        const formData = new FormData(form);
        const url = form.action;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(formData)
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Ocurrió un error.');
            }

            this.showToast(result.message);

            // Clear the form for the next entry
            document.getElementById('individual-start-date').value = '';
            document.getElementById('individual-start-time').value = '';
            document.getElementById('individual-end-date').value = '';
            document.getElementById('individual-end-time').value = '';
            document.getElementById('individual-notes').value = '';

            // Update the last clock-out time display
            if (this.hasLastClockOutTimeTarget && result.lastClockOut) {
                const date = new Date(result.lastClockOut);
                this.lastClockOutTimeTarget.textContent = date.toLocaleString('es-ES', {
                    year: 'numeric', month: '2-digit', day: '2-digit',
                    hour: '2-digit', minute: '2-digit'
                });
            }

            // Optionally, focus the first input for quick entry
            document.getElementById('individual-start-date').focus();

        } catch (error) {
            this.showToast(error.message, 'error');
        }
    }

    showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-5 right-5 p-4 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        toast.textContent = message;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
}
