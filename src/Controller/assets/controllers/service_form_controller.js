import { Controller } from '@hotwired/stimulus';
import Quill from 'quill';

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
        "attendanceStatusSelect",
    ];

    connect() {
        this.initializeQuill();
        if (this.hasTabLinkTarget) {
            this.setupTabs();
        }
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

        const Link = Quill.import('formats/link');
        class CustomLink extends Link {
            static sanitize(url) {
                const sanitizedUrl = super.sanitize(url);
                if (sanitizedUrl && sanitizedUrl !== 'about:blank' && !/^(https?:\/\/|mailto:|tel:)/.test(sanitizedUrl)) {
                    return 'https://' + sanitizedUrl;
                }
                return sanitizedUrl;
            }
        }
        Quill.register(CustomLink, true);

        const quill = new Quill(container, {
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

    setupTabs() {
        this.tabLinkTargets.forEach(link => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                this.switchTab(event.currentTarget);
            });
        });
    }

    switchTab(clickedLink) {
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
        this.fetchVolunteers(1, '');
    }

    closeModal() {
        this.modalTarget.classList.add('hidden');
    }

    async fetchVolunteers(page = 1, search = '') {
        const serviceId = this.element.dataset.serviceId;
        const url = `/services/${serviceId}/volunteers?page=${page}&search=${encodeURIComponent(search)}`;
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            this.renderVolunteers(data.items);
            this.renderPagination(data.pagination);
        } catch (error) {
            console.error('Error fetching volunteers:', error);
            this.userListTarget.innerHTML = '<p class="text-red-500">Error al cargar voluntarios.</p>';
        }
    }

    renderVolunteers(volunteers) {
        this.userListTarget.innerHTML = '';
        if (volunteers.length === 0) {
            this.userListTarget.innerHTML = '<p class="text-gray-500">No se encontraron voluntarios.</p>';
            return;
        }
        volunteers.forEach(volunteer => {
            const isSelected = this.selectedVolunteers.includes(volunteer.id);
            const row = document.createElement('div');
            row.className = `flex items-center justify-between p-3 rounded-lg cursor-pointer transition-colors ${isSelected ? 'bg-blue-100' : 'hover:bg-gray-100'}`;
            row.dataset.volunteerId = volunteer.id;
            row.innerHTML = `
                <span class="font-medium">${volunteer.name}</span>
                <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded" ${isSelected ? 'checked' : ''}>
            `;
            row.addEventListener('click', () => this.toggleSelection(volunteer.id, row));
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

    renderPagination(pagination) {
        this.paginationContainerTarget.innerHTML = '';
        if (pagination.totalPages <= 1) return;

        const prevButton = document.createElement('button');
        prevButton.innerHTML = '&laquo;';
        prevButton.disabled = pagination.currentPage === 1;
        prevButton.className = 'px-4 py-2 mx-1 rounded-lg bg-gray-200 hover:bg-gray-300 disabled:opacity-50';
        prevButton.addEventListener('click', () => this.fetchVolunteers(pagination.currentPage - 1, this.userSearchInputTarget.value));
        this.paginationContainerTarget.appendChild(prevButton);

        for (let i = 1; i <= pagination.totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.innerText = i;
            pageButton.className = `px-4 py-2 mx-1 rounded-lg ${i === pagination.currentPage ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300'}`;
            pageButton.addEventListener('click', () => this.fetchVolunteers(i, this.userSearchInputTarget.value));
            this.paginationContainerTarget.appendChild(pageButton);
        }

        const nextButton = document.createElement('button');
        nextButton.innerHTML = '&raquo;';
        nextButton.disabled = pagination.currentPage === pagination.totalPages;
        nextButton.className = 'px-4 py-2 mx-1 rounded-lg bg-gray-200 hover:bg-gray-300 disabled:opacity-50';
        nextButton.addEventListener('click', () => this.fetchVolunteers(pagination.currentPage + 1, this.userSearchInputTarget.value));
        this.paginationContainerTarget.appendChild(nextButton);
    }

    search() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            this.fetchVolunteers(1, this.userSearchInputTarget.value);
        }, 300);
    }

    async saveAttendance() {
        if (this.selectedVolunteers.length === 0) {
            alert('Por favor, selecciona al menos un voluntario.');
            return;
        }

        const status = this.attendanceStatusSelectTarget.value;
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
}
