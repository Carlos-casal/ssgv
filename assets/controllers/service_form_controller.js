import { Controller } from '@hotwired/stimulus';

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
        "clockInModal",
        "clockInStartDate",
        "clockInStartTime",
        "clockInEndDate",
        "clockInEndTime",
        "clockInVolunteerList",
    ];

    connect() {
        this.initializeQuill();
        if (this.hasModalTarget) {
            this.setupAttendanceModal();
        }
    }

    disconnect() {
        if (this.quillDescriptionInstance) {
            this.quillDescriptionInstance = null;
        }
        if (this.quillTasksInstance) {
            this.quillTasksInstance = null;
        }
    }

    initializeQuill() {
        this.quillDescriptionInstance = this.setupQuill(this.quillDescriptionTarget, this.descriptionTarget);
        this.quillTasksInstance = this.setupQuill(this.quillTasksTarget, this.tasksTarget);
    }

    setupQuill(container, textarea) {
        if (!container || !textarea) return null;
        if (container.quill) return container.quill;

        const toolbarOptions = [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'strike', 'underline'],
            [{ 'script': 'sub'}, { 'script': 'super' }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'align': [] }],
            ['link'],
            ['clean']
        ];

        const Link = window.Quill.import('formats/link');
        class CustomLink extends Link {
            static sanitize(url) {
                const sanitizedUrl = super.sanitize(url);
                if (sanitizedUrl && sanitizedUrl !== 'about:blank' && !/^(https?:\/\/|mailto:|tel:)/.test(sanitizedUrl)) {
                    return 'https://' + sanitizedUrl;
                }
                return sanitizedUrl;
            }
        }
        window.Quill.register(CustomLink, true);

        const quill = new window.Quill(container, {
            modules: { toolbar: toolbarOptions },
            theme: 'snow'
        });

        quill.root.innerHTML = textarea.value;
        container.quill = quill;

        quill.on('text-change', () => {
            textarea.value = quill.root.innerHTML;
        });

        return quill;
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
        nameSpan.textContent = volunteer.name;

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
            this.paginationSummaryTarget.textContent = `Mostrando registros del ${start} al ${end} de un total de ${pagination.totalCount} registros`;
        } else {
            this.paginationSummaryTarget.textContent = '';
        }
    }

        this.paginationContainerTarget.innerHTML = '';
        if (pagination.totalPages <= 1) return;

    const createButton = (text, page, disabled = false, active = false) => {
        const button = document.createElement('button');
        button.innerHTML = text;
        button.disabled = disabled;
        button.className = `px-3 py-1 mx-1 rounded-lg text-sm ${active ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300 disabled:opacity-50'}`;
        if (!active) {
            button.addEventListener('click', () => this.fetchVolunteers(page, this.userSearchInputTarget.value));
        }
        return button;
    };

    this.paginationContainerTarget.appendChild(createButton('Anterior', pagination.currentPage - 1, pagination.currentPage === 1));

    // Simplified pagination links
    this.paginationContainerTarget.appendChild(createButton(pagination.currentPage, pagination.currentPage, false, true));

    this.paginationContainerTarget.appendChild(createButton('Siguiente', pagination.currentPage + 1, pagination.currentPage === pagination.totalPages));
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

    openClockInModal() {
        this.clockInModalTarget.classList.remove('hidden');
        this.clockInModalTarget.classList.add('flex');
        this.fetchAttendingVolunteers();
    }

    closeClockInModal() {
        this.clockInModalTarget.classList.add('hidden');
        this.clockInModalTarget.classList.remove('flex');
    }

    async fetchAttendingVolunteers() {
        const serviceId = this.element.dataset.serviceId;
        const url = `/services/${serviceId}/attending-volunteers`;
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('Network response was not ok');
            const volunteers = await response.json();
            this.renderClockInVolunteers(volunteers);
        } catch (error) {
            console.error('Error fetching attending volunteers:', error);
            this.clockInVolunteerListTarget.innerHTML = '<p class="text-red-500">Error al cargar voluntarios.</p>';
        }
    }

    renderClockInVolunteers(volunteers) {
        this.clockInVolunteerListTarget.innerHTML = '';
        if (volunteers.length === 0) {
            this.clockInVolunteerListTarget.innerHTML = '<p class="text-gray-500 p-4">No hay voluntarios que asistan.</p>';
            return;
        }
        volunteers.forEach(volunteer => {
            const row = document.createElement('div');
            row.className = 'flex items-center p-2 rounded-lg';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 clock-in-volunteer-checkbox';
            checkbox.value = volunteer.id;
            checkbox.checked = true;

            const nameSpan = document.createElement('span');
            nameSpan.className = 'ml-3 font-medium text-gray-700';
            nameSpan.textContent = `${volunteer.name} ${volunteer.lastname}`;

            row.appendChild(checkbox);
            row.appendChild(nameSpan);
            this.clockInVolunteerListTarget.appendChild(row);
        });
    }

    toggleAllVolunteers(event) {
        const isChecked = event.currentTarget.checked;
        this.clockInVolunteerListTarget.querySelectorAll('.clock-in-volunteer-checkbox').forEach(checkbox => {
            checkbox.checked = isChecked;
        });
    }

    async saveClockIn() {
        const selectedVolunteers = Array.from(this.clockInVolunteerListTarget.querySelectorAll('.clock-in-volunteer-checkbox:checked')).map(cb => cb.value);

        if (selectedVolunteers.length === 0) {
            alert('Por favor, selecciona al menos un voluntario.');
            return;
        }

        const startDate = this.clockInStartDateTarget.value;
        const startTime = this.clockInStartTimeTarget.value;
        const endDate = this.clockInEndDateTarget.value;
        const endTime = this.clockInEndTimeTarget.value;

        if (!startDate || !startTime || !endDate || !endTime) {
            alert('Por favor, completa todos los campos de fecha y hora.');
            return;
        }

        const serviceId = this.element.dataset.serviceId;
        const url = `/services/${serviceId}/clock-in-all`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    volunteerIds: selectedVolunteers,
                    startTime: `${startDate} ${startTime}`,
                    endTime: `${endDate} ${endTime}`,
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                alert('Fichaje guardado correctamente.');
                this.closeClockInModal();
                location.reload();
            } else {
                throw new Error(result.message || 'Error al guardar el fichaje.');
            }
        } catch (error) {
            console.error('Error saving clock-in:', error);
            alert(error.message);
        }
    }
}
