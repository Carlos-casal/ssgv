import { Controller } from '@hotwired/stimulus';
import tinymce from 'tinymce';
import 'tinymce/themes/silver';
import 'tinymce/icons/default';
import 'tinymce/models/dom';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/autoresize';
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
        "form",
        "typeModal",
        "subcategoryModal"
    ];

    connect() {
        if (this.hasModalTarget) {
            this.setupAttendanceModal();
        }

        // Initialize Hierarchy Selector
        const typeSelect = document.getElementById('service_form_type');
        const subcategorySelect = document.getElementById('service_form_subcategory');
        if (typeSelect && subcategorySelect) {
            if (!typeSelect.value) {
                subcategorySelect.disabled = true;
                subcategorySelect.innerHTML = '<option value="">Selecciona primero un Tipo...</option>';
            } else {
                subcategorySelect.disabled = false;
            }
        }

        // Date Listeners for Availability Check
        const startDateInput = document.getElementById('service_form_startDate');
        const endDateInput = document.getElementById('service_form_endDate');
        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', () => this.updateAllMaterialAvailability());
            endDateInput.addEventListener('change', () => this.updateAllMaterialAvailability());
        }

        // TinyMCE configuration for Description
        tinymce.init({
            selector: '#service_form_description',
            plugins: 'lists link autoresize',
            toolbar: 'bold italic strikethrough | bullist numlist | link | removeformat',
            menubar: false,
            statusbar: false,
            branding: false,
            resize: false,
            min_height: 450,
            autoresize_bottom_margin: 0,
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

        // Bind main form submission if present
        if (this.hasFormTarget) {
            this.formTarget.addEventListener('submit', this.handleMainFormSubmit.bind(this));
        }

        this.filterExistingMaterials();
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
        const subcategorySelect = document.getElementById('service_form_subcategory');

        // Reset state
        subcategorySelect.innerHTML = '';

        if (!typeId) {
            subcategorySelect.disabled = true;
            subcategorySelect.innerHTML = '<option value="">Selecciona primero un Tipo...</option>';
            return;
        }

        subcategorySelect.disabled = false;
        subcategorySelect.innerHTML = '<option value="">Cargando...</option>';

        try {
            const response = await fetch(`/api/subcategories?type_id=${typeId}`);
            if (!response.ok) throw new Error('API Error');
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

        // Filter the material dropdown by category
        const select = wrapper.querySelector('select');
        if (select) {
            Array.from(select.options).forEach(option => {
                // If the option has a category and it doesn't match, remove it
                if (option.value && option.dataset.category && option.dataset.category !== category) {
                    option.remove();
                }
            });
        }

        const column = container.querySelector(`[data-material-category="${category}"]`);
        if (column) {
            column.appendChild(wrapper.firstChild);
        }

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    removeMaterial(event) {
        event.currentTarget.closest('.material-item').remove();
        this.updateAllMaterialAvailability();
    }

    filterExistingMaterials() {
        if (!this.hasMaterialsContainerTarget) return;

        const container = this.materialsContainerTarget;
        const columns = container.querySelectorAll('[data-material-category]');

        columns.forEach(column => {
            const category = column.dataset.materialCategory;
            const selects = column.querySelectorAll('select.material-selector');
            selects.forEach(select => {
                Array.from(select.options).forEach(option => {
                    if (option.value && option.dataset.category && option.dataset.category !== category) {
                        option.remove();
                    }
                });

                // Initial check for unit container visibility
                const nature = select.options[select.selectedIndex]?.dataset.nature;
                if (nature === 'EQUIPO_TECNICO') {
                    select.closest('.material-item')?.querySelector('.unit-selection-container')?.classList.remove('hidden');
                }
            });
        });

        this.updateAllMaterialAvailability();
    }

    onMaterialRowChange(event) {
        const row = event.currentTarget.closest('.material-item');
        this.checkMaterialAvailability(row);
    }

    onQuantityInput(event) {
        const input = event.currentTarget;
        const row = input.closest('.material-item');
        const qty = parseInt(input.value);
        const materialSelect = row.querySelector('.material-selector');
        const nature = materialSelect.options[materialSelect.selectedIndex]?.dataset.nature;

        // Special behavior for Technical Assets: split into multiple rows
        if (nature === 'EQUIPO_TECNICO' && qty > 1) {
            const category = materialSelect.options[materialSelect.selectedIndex]?.dataset.category;
            const materialId = materialSelect.value;

            // Set current row quantity to 1
            input.value = 1;

            // Add (qty - 1) more rows with same material
            for (let i = 0; i < qty - 1; i++) {
                this.addMaterialWithData(category, materialId, 1);
            }

            this.updateAllMaterialAvailability();
            return;
        }

        const max = parseInt(input.getAttribute('max'));
        if (max !== undefined && !isNaN(max) && parseInt(input.value) > max) {
            input.value = max;
        }

        this.updateAllMaterialAvailability();
    }

    addMaterialWithData(category, materialId, quantity) {
        const container = this.materialsContainerTarget;
        const index = container.dataset.index;
        const prototype = container.dataset.prototype;

        const newForm = prototype.replace(/__name__/g, index);
        container.dataset.index = parseInt(index) + 1;

        const wrapper = document.createElement('div');
        wrapper.innerHTML = newForm;

        const select = wrapper.querySelector('select.material-selector');
        if (select) {
            Array.from(select.options).forEach(option => {
                if (option.value && option.dataset.category && option.dataset.category !== category) {
                    option.remove();
                }
            });
            select.value = materialId;
        }

        const qtyInput = wrapper.querySelector('.quantity-input');
        if (qtyInput) {
            qtyInput.value = quantity;
        }

        const column = container.querySelector(`[data-material-category="${category}"]`);
        if (column) {
            column.appendChild(wrapper.firstChild);
        }

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    onMaterialSelectChange(event) {
        const select = event.currentTarget;
        const nature = select.options[select.selectedIndex]?.dataset.nature;
        const unitContainer = select.closest('.material-item')?.querySelector('.unit-selection-container');
        const qtyInput = select.closest('.material-item')?.querySelector('.quantity-input');
        const qty = parseInt(qtyInput?.value || 0);

        if (nature === 'EQUIPO_TECNICO') {
            unitContainer?.classList.remove('hidden');

            // If selecting technical material and quantity is > 1, trigger split
            if (qty > 1) {
                const category = select.options[select.selectedIndex]?.dataset.category;
                const materialId = select.value;
                qtyInput.value = 1;
                for (let i = 0; i < qty - 1; i++) {
                    this.addMaterialWithData(category, materialId, 1);
                }
            }
        } else {
            unitContainer?.classList.add('hidden');
        }

        this.updateAllMaterialAvailability();
    }

    async updateAllMaterialAvailability() {
        if (!this.hasMaterialsContainerTarget) return;
        const rows = this.materialsContainerTarget.querySelectorAll('.material-item');

        // Group rows by material to calculate usage
        const materialUsage = {};
        rows.forEach(row => {
            const materialId = row.querySelector('.material-selector')?.value;
            const qty = parseInt(row.querySelector('.quantity-input')?.value || 0);
            if (materialId) {
                materialUsage[materialId] = (materialUsage[materialId] || 0) + qty;
            }
        });

        for (const row of rows) {
            await this.checkMaterialAvailability(row, materialUsage);
        }
    }

    async checkMaterialAvailability(row, globalUsage = null) {
        const materialSelect = row.querySelector('.material-selector');
        const quantityInput = row.querySelector('.quantity-input');
        const startDateInput = document.getElementById('service_form_startDate');
        const endDateInput = document.getElementById('service_form_endDate');

        if (!materialSelect?.value || !startDateInput?.value || !endDateInput?.value) return;

        const materialId = materialSelect.value;
        const quantity = parseInt(quantityInput?.value || 1);
        const start = startDateInput.value;
        const end = endDateInput.value;
        const serviceId = this.element.dataset.serviceId || '';

        try {
            // We fetch availability based on TOTAL requested in form to see if the global pool is exceeded
            let totalRequestedInForm = quantity;
            if (globalUsage && globalUsage[materialId]) {
                totalRequestedInForm = globalUsage[materialId];
            }

            const response = await fetch(`/api/material/check-availability?id=${materialId}&start=${start}&end=${end}&quantity=${totalRequestedInForm}&excludeServiceId=${serviceId}`);
            const data = await response.json();

            const statusLabel = row.querySelector('.availability-status');
            const unitSelector = row.querySelector('.unit-selector');

            // Calculate "remaining" for this specific input limit
            const othersUsage = (globalUsage?.[materialId] || quantity) - quantity;
            const remainingForThisRow = Math.max(0, data.totalAvailable - othersUsage);

            // Set dynamic max attribute
            quantityInput.setAttribute('max', remainingForThisRow);

            if (data.available) {
                materialSelect.classList.remove('border-red-500');
                if (statusLabel) {
                    statusLabel.innerHTML = `<i data-lucide="check-circle" class="w-3 h-3 text-green-500 inline mr-1"></i> <span class="text-slate-600 font-bold">${data.totalAvailable} disponibles</span>`;
                    statusLabel.className = 'availability-status text-[10px] mt-1 text-green-600';
                }

                if (data.nature === 'EQUIPO_TECNICO' && data.suggestedUnits && unitSelector) {
                    this.updateUnitSelector(unitSelector, data.suggestedUnits);
                }
            } else {
                materialSelect.classList.add('border-red-500');
                if (statusLabel) {
                    statusLabel.innerHTML = `<i data-lucide="alert-triangle" class="w-3 h-3 text-red-500 inline mr-1"></i> <span class="text-red-600 font-black">Stock insuficiente (Soli: ${quantity} / Disp: ${data.totalAvailable})</span>`;
                    statusLabel.className = 'availability-status text-[10px] mt-1';
                }
            }

            if (window.lucide) {
                window.lucide.createIcons();
            }
        } catch (error) {
            console.error('Error checking availability:', error);
        }
    }

    updateUnitSelector(selector, units) {
        const currentValue = selector.value;

        selector.innerHTML = '<option value="">Selección automática (Rotación)</option>';
        units.forEach(unit => {
            const label = (unit.collectiveNumber ? `[${unit.collectiveNumber}] ` : '') + (unit.serialNumber || `ID: ${unit.id}`);
            const option = new Option(label, unit.id);
            selector.appendChild(option);
        });

        if (currentValue && Array.from(selector.options).some(o => o.value == currentValue)) {
            selector.value = currentValue;
        } else if (units.length > 0) {
            // Auto-select the first suggested unit (the oldest one)
            selector.value = units[0].id;
        }
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

    // Hierarchy Creation Logic
    openTypeModal() {
        this.typeModalTarget.classList.remove('hidden');
    }

    closeTypeModal() {
        this.typeModalTarget.classList.add('hidden');
        this.clearAllModalErrors(this.typeModalTarget);
    }

    async handleTypeSubmit(event) {
        event.preventDefault();
        const form = event.currentTarget;

        // Custom validation
        if (!form.checkValidity()) {
            this.validateForm(form);
            return;
        }

        const formData = new FormData(form);
        const payload = {
            service_type: {
                name: formData.get('name')
            }
        };

        try {
            const response = await fetch('/api/service-type/new', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok) {
                this.closeTypeModal();

                // Add to main selector and select it
                const typeSelect = document.getElementById('service_form_type');
                const option = new Option(data.name, data.id, true, true);
                typeSelect.appendChild(option);

                // Trigger updateCategories
                this.updateCategories({ target: typeSelect });
            } else {
                this.showError('type_name_input', this.humanizeError(data.errors || 'Nombre inválido o duplicado'));
            }
        } catch (error) {
            console.error('Error adding type:', error);
        }
    }

    async openSubcategoryModal() {
        const typeId = document.getElementById('service_form_type').value;
        if (!typeId) {
            this.showToast('Por favor, selecciona primero un Tipo de Servicio.');
            return;
        }

        const modal = this.subcategoryModalTarget;
        modal.querySelector('#modal_type_id').value = typeId;

        // Fetch categories for this type
        const categorySelect = modal.querySelector('#modal_category_select');
        categorySelect.innerHTML = '<option value="">Cargando...</option>';

        try {
            const response = await fetch(`/api/service-category/list?type=${typeId}`);
            const categories = await response.json();

            categorySelect.innerHTML = '<option value="">Selecciona...</option>';
            categories.forEach(cat => {
                const option = new Option(cat.name, cat.id);
                categorySelect.appendChild(option);
            });

            modal.classList.remove('hidden');
        } catch (error) {
            console.error('Error fetching categories:', error);
        }
    }

    closeSubcategoryModal() {
        this.subcategoryModalTarget.classList.add('hidden');
        this.subcategoryModalTarget.querySelector('#new_category_input_container').classList.add('hidden');
        this.subcategoryModalTarget.querySelector('#modal_new_category_name').value = '';
        this.clearAllModalErrors(this.subcategoryModalTarget);
    }

    showNewCategoryInput() {
        const container = this.subcategoryModalTarget.querySelector('#new_category_input_container');
        container.classList.toggle('hidden');
        if (!container.classList.contains('hidden')) {
            this.subcategoryModalTarget.querySelector('#modal_category_select').removeAttribute('required');
            this.subcategoryModalTarget.querySelector('#modal_new_category_name').setAttribute('required', 'required');
        } else {
            this.subcategoryModalTarget.querySelector('#modal_category_select').setAttribute('required', 'required');
            this.subcategoryModalTarget.querySelector('#modal_new_category_name').removeAttribute('required');
        }
    }

    async handleSubcategorySubmit(event) {
        event.preventDefault();
        const form = event.currentTarget;

        // Custom validation
        if (!form.checkValidity()) {
            this.validateForm(form);
            return;
        }

        const formData = new FormData(form);
        const typeId = formData.get('type_id');
        let categoryId = formData.get('category');
        const newCategoryName = formData.get('new_category_name');
        const subcategoryName = formData.get('name');

        try {
            // 1. If new category requested, create it first
            if (newCategoryName) {
                const catResponse = await fetch('/api/service-category/new', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        service_category: {
                            name: newCategoryName,
                            type: typeId
                        }
                    })
                });
                const catData = await catResponse.json();
                if (catResponse.ok) {
                    categoryId = catData.id;
                } else {
                    this.showError('modal_new_category_name', this.humanizeError(catData.errors || 'Error al crear categoría'));
                    return;
                }
            }

            if (!categoryId && !newCategoryName) {
                this.showError('modal_category_select', 'Debes seleccionar o crear una categoría');
                return;
            }

            // 2. Create subcategory
            const subResponse = await fetch('/api/subcategories/new', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    service_subcategory: {
                        name: subcategoryName,
                        category: categoryId
                    }
                })
            });

            const subData = await subResponse.json();

            if (subResponse.ok) {
                this.closeSubcategoryModal();

                // Refresh main hierarchy selector
                const typeSelect = document.getElementById('service_form_type');
                await this.updateCategories({ target: typeSelect });

                // Select the new subcategory
                document.getElementById('service_form_subcategory').value = subData.id;
            } else {
                this.showError('subcategory_name_input', this.humanizeError(subData.errors || 'Error al crear subcategoría'));
            }
        } catch (error) {
            console.error('Error adding subcategory:', error);
        }
    }

    async handleMaterialSubmit(event) {
        event.preventDefault();
        const form = event.currentTarget;

        // Custom validation
        if (!form.checkValidity()) {
            this.validateForm(form);
            return;
        }

        const formData = new FormData(form);
        const name = formData.get('name');
        const category = formData.get('category');
        const payload = { name, category };

        try {
            const response = await fetch('/api/material/new', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (response.ok) {
                this.closeMaterialModal();
                this.showToast(`Material "${data.name}" añadido.`);
            } else {
                this.showError('material_name', this.humanizeError(data.errors || 'Error al añadir material'));
            }
        } catch (error) {
            console.error('Error adding material:', error);
        }
    }

    // Visual Validation Helpers
    showError(inputId, message) {
        const input = document.getElementById(inputId);
        if (!input) return;

        input.classList.add('is-invalid');

        // Find or create invalid-feedback
        let errorDiv = document.getElementById(`error-${inputId}`);
        if (!errorDiv) {
            errorDiv = input.closest('.col-md-8, .col-md-4, .col-md-6, .mb-4')?.querySelector('.invalid-feedback');
        }

        if (errorDiv) {
            errorDiv.textContent = this.humanizeError(message);
            errorDiv.classList.remove('hidden');
            errorDiv.classList.add('d-block'); // Bootstrap 5 visibility
            errorDiv.style.fontSize = "0.85rem";
        }
    }

    validateForm(form) {
        form.querySelectorAll('[required]').forEach(input => {
            if (!input.value) {
                this.showError(input.id, 'Este campo es obligatorio');
            } else {
                this.clearError({ currentTarget: input });
            }
        });
    }

    humanizeError(message) {
        if (typeof message !== 'string') return message;

        // Remove technical prefixes like "name: ERROR:"
        let clean = message.replace(/^[a-zA-Z0-9_]+:\s*ERROR:\s*/i, '');
        clean = clean.replace(/^[a-zA-Z0-9_]+:\s*/i, '');

        // Common translations
        const translations = {
            'This value should not be blank.': 'Este campo es obligatorio.',
            'This value is already used.': 'Este valor ya está en uso.',
            'Invalid credentials.': 'El correo electrónico o la contraseña no son correctos.',
            'This registration number is already in use.': 'Este número de registro ya está en uso.'
        };

        return translations[clean] || clean;
    }

    clearError(event) {
        const input = event.currentTarget;
        const inputId = input.id;
        const errorDiv = document.getElementById(`error-${inputId}`);
        input.classList.remove('is-invalid');
        input.classList.remove('border-red-500');
        if (errorDiv) {
            errorDiv.textContent = '';
            errorDiv.classList.add('hidden');
        }
    }

    clearAllModalErrors(modal) {
        modal.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
            el.classList.remove('border-red-500');
        });
        modal.querySelectorAll('.invalid-feedback').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
    }

    showToast(message) {
        // Fallback to alert if no toast system, but user wanted to remove blocking alerts.
        // For now, let's use a non-blocking way if possible, or just skip it if it's just a success message.
        console.log('Success:', message);
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
        const limit = this.hasItemsPerPageSelectTarget ? this.itemsPerPageSelectTarget.value : 10;
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
                const limit = this.hasItemsPerPageSelectTarget ? this.itemsPerPageSelectTarget.value : 10;
                const start = (pagination.currentPage - 1) * limit + 1;
                const end = start + items.length - 1;
                this.paginationSummaryTarget.textContent = `Mostrando ${start}-${end} de ${pagination.totalCount}`;
            } else {
                this.paginationSummaryTarget.textContent = 'No se encontraron registros.';
            }
        }

        if (this.hasPaginationContainerTarget) {
            this.paginationContainerTarget.innerHTML = '';
        }
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

        if (this.hasPaginationContainerTarget) {
            this.paginationContainerTarget.appendChild(createButton('&larr; Anterior', currentPage - 1, currentPage === 1));
        }
    }

    search() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            this.fetchVolunteers(1, this.userSearchInputTarget.value);
        }, 300);
    }

    handleMainFormSubmit(event) {
        if (!event.currentTarget.checkValidity()) {
            event.preventDefault();
            this.validateForm(event.currentTarget);

            // Scroll to the first error
            const firstError = event.currentTarget.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    async saveAttendance() {
        // Clear previous errors in modal
        this.clearAllModalErrors(this.modalTarget);

        let hasError = false;
        if (this.selectedVolunteers.length === 0) {
            // We don't have a specific input for volunteers, but we can show it near the search
            this.showError(this.userSearchInputTarget.id, 'Selecciona al menos un voluntario');
            hasError = true;
        }

        const status = this.attendanceStatusSelectTarget.value;
        if (!status) {
            this.showError(this.attendanceStatusSelectTarget.id, 'Selecciona una respuesta');
            hasError = true;
        }

        if (hasError) return;

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
