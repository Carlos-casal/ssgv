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
        "materialsContainer",
        "materialModal",
        "form"
    ];

    connect() {
        if (this.hasModalTarget) {
            this.setupAttendanceModal();
        }

        // Initialize Hierarchy Selector
        const typeSelect = document.getElementById('service_type');
        const subcategorySelect = document.getElementById('service_subcategory');
        if (typeSelect && subcategorySelect && !typeSelect.value) {
            subcategorySelect.disabled = true;
            subcategorySelect.innerHTML = '<option value="">Selecciona primero un Tipo...</option>';
        }

        // Explicitly remove TinyMCE from tasks field to ensure it remains plain text
        tinymce.remove('textarea#service_tasks');

        // TinyMCE configuration for Description
        tinymce.init({
            selector: '#service_form_description',
            plugins: 'lists link',
            toolbar: 'bold italic underline strikethrough | bullist numlist | link | removeformat',
            menubar: false,
            statusbar: false,
            branding: false,
            resize: false,
            height: 250,
            toolbar_mode: 'floating',
            promotion: false,
            base_url: '/build/tinymce',
            suffix: '.min',
            license_key: 'gpl',
            'api-key': 'no-api-key',
            setup: function(editor) {
                editor.on('init', function() {
                    editor.getContainer().style.borderRadius = "1rem";
                });
            }
        });

        this.updateAllAfluenciaColors();
    }

    disconnect() {
        tinymce.remove('#service_form_description');
    }

    switchTab(event) {
        event.preventDefault();
        const clickedLink = event.currentTarget;
        const targetId = clickedLink.dataset.target;

        // Update link styles
        this.tabLinkTargets.forEach(link => {
            if (link === clickedLink || link.dataset.target === targetId) {
                link.classList.add('bg-white', 'text-blue-600', 'shadow-sm');
                link.classList.remove('text-slate-500');
            } else {
                link.classList.remove('bg-white', 'text-blue-600', 'shadow-sm');
                link.classList.add('text-slate-500');
            }
        });

        // Show/Hide content
        this.tabContentTargets.forEach(content => {
            if (content.id === targetId) {
                content.classList.remove('hidden');
            } else {
                content.classList.add('hidden');
            }
        });
    }

    // Unified Hierarchy Logic
    async updateCategories(event) {
        const typeId = event.target.value;
        const subcategorySelect = document.getElementById('service_subcategory');

        if (!typeId) {
            subcategorySelect.disabled = true;
            subcategorySelect.innerHTML = '<option value="">Selecciona primero un Tipo...</option>';
            return;
        }

        subcategorySelect.disabled = false;
        subcategorySelect.innerHTML = '<option value="">Cargando...</option>';

        try {
            const response = await fetch(`/api/subcategories?type_id=${typeId}`);
            const subcategories = await response.json();

            subcategorySelect.innerHTML = '<option value="">Selecciona una opción...</option>';

            // Group subcategories by categoryName
            const grouped = subcategories.reduce((acc, sub) => {
                if (!acc[sub.categoryName]) acc[sub.categoryName] = [];
                acc[sub.categoryName].push(sub);
                return acc;
            }, {});

            for (const [categoryName, subs] of Object.entries(grouped)) {
                const optgroup = document.createElement('optgroup');
                optgroup.label = categoryName;
                subs.forEach(sub => {
                    const option = new Option('\u00A0\u00A0\u00A0' + sub.name, sub.id); // Add indentation for subcategories
                    optgroup.appendChild(option);
                });
                subcategorySelect.appendChild(optgroup);
            }
        } catch (error) {
            console.error('Error fetching hierarchy:', error);
            subcategorySelect.innerHTML = '<option value="">Error al cargar datos</option>';
        }
    }

    // Materials Logic
    addMaterial(event) {
        const category = event.currentTarget.dataset.category;
        const container = this.materialsContainerTarget;
        const index = container.dataset.index;
        const prototype = container.dataset.prototype;

        const newForm = prototype.replace(/__name__/g, index);
        container.dataset.index = parseInt(index) + 1;

        const wrapper = document.createElement('div');
        wrapper.innerHTML = newForm;

        const column = container.querySelector(`[data-material-category="${category}"]`);
        column.appendChild(wrapper.firstChild);

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    removeMaterial(event) {
        event.currentTarget.closest('.material-item').remove();
    }

    openMaterialModal(event) {
        const category = event.currentTarget.dataset.category;
        const modal = this.materialModalTarget;
        modal.querySelector('#material_category').value = category;
        modal.classList.remove('hidden');
    }

    closeMaterialModal() {
        this.materialModalTarget.classList.add('hidden');
    }

    async handleMaterialSubmit(event) {
        event.preventDefault();
        const form = event.currentTarget;
        const formData = new FormData(form);
        const payload = {
            name: formData.get('name'),
            category: formData.get('category')
        };

        try {
            const response = await fetch('/api/material/new', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            if (response.ok) {
                const data = await response.json();
                this.closeMaterialModal();
                alert(`Material "${data.name}" añadido a la base de datos.`);
            }
        } catch (error) {
            console.error('Error adding material:', error);
        }
    }

    // Afluencia
    updateAfluenciaColor(event) {
        this.applyAfluenciaClass(event.currentTarget);
    }

    updateAllAfluenciaColors() {
        this.element.querySelectorAll('select[id$="_afluencia"]').forEach(select => {
            this.applyAfluenciaClass(select);
        });
    }

    applyAfluenciaClass(select) {
        select.classList.remove('afluencia-baja', 'afluencia-media', 'afluencia-alta');
        const val = select.value;
        if (val === 'baja') select.classList.add('afluencia-baja');
        else if (val === 'media') select.classList.add('afluencia-media');
        else if (val === 'alta') select.classList.add('afluencia-alta');
    }

    // Attendance Logic
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
        if (!status) {
            alert('Por favor, selecciona una respuesta.');
            return;
        }

        const serviceId = this.element.dataset.serviceId;
        try {
            const response = await fetch(`/services/${serviceId}/update-attendance`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ volunteerIds: this.selectedVolunteers, status: status })
            });
            const result = await response.json();
            if (result.success) {
                location.reload();
            }
        } catch (error) {
            console.error('Error saving attendance:', error);
        }
    }
}
