import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["form", "prototype"];

    connect() {
        this.initTinyMCE();
        this.loadInitialMaterials();
    }

    initTinyMCE() {
        if (typeof tinymce !== 'undefined') {
            tinymce.remove('#service_form_description');
            tinymce.init({
                selector: '#service_form_description',
                license_key: 'gpl',
                promotion: false,
                branding: false,
                statusbar: false,
                menubar: false,
                height: 300,
                plugins: 'lists link',
                toolbar: 'bold italic strikethrough | bullist numlist | link',
                language: 'es',
                setup: (editor) => {
                    editor.on('change', () => { editor.save(); });
                }
            });
        }
    }

    // Hierarchy Logic
    async updateCategories(event) {
        const typeId = event.target.value;
        const categorySelect = document.getElementById('service_form_category_selector');
        const subcategorySelect = document.getElementById('service_form_subcategory');

        categorySelect.innerHTML = '<option value="">Cargando...</option>';
        subcategorySelect.innerHTML = '<option value="">Selecciona Categoría primero</option>';

        if (!typeId) return;

        const response = await fetch(`/api/categories/${typeId}`);
        const categories = await response.json();

        categorySelect.innerHTML = '<option value="">Selecciona Categoría</option>';
        categories.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = `${cat.codigo} - ${cat.name}`;
            categorySelect.appendChild(option);
        });
    }

    async updateSubcategories(event) {
        const categoryId = event.target.value;
        const subcategorySelect = document.getElementById('service_form_subcategory');

        subcategorySelect.innerHTML = '<option value="">Cargando...</option>';

        if (!categoryId) return;

        const response = await fetch(`/api/subcategories/${categoryId}`);
        const subs = await response.json();

        subcategorySelect.innerHTML = '<option value="">Selecciona Subcategoría</option>';
        subs.forEach(sub => {
            const option = document.createElement('option');
            option.value = sub.id;
            option.textContent = `${sub.codigo} - ${sub.name}`;
            subcategorySelect.appendChild(option);
        });
    }

    // Materials Logic
    async loadInitialMaterials() {
        ['sanitario', 'comunicaciones', 'logistica'].forEach(async (cat) => {
            const response = await fetch(`/api/materials/${cat}`);
            const materials = await response.json();
            const container = document.getElementById(`material-list-${cat}`);
            container.innerHTML = '';
            materials.forEach(m => this.renderMaterialItem(m, cat));
        });
    }

    renderMaterialItem(material, category) {
        const container = document.getElementById(`material-list-${category}`);
        const div = document.createElement('div');
        div.className = 'flex justify-between items-center bg-white p-2 rounded-lg border border-slate-100 shadow-sm';

        // This is a bit tricky with Symfony Collections.
        // We'll use a naming convention that we can handle on submit or just simple inputs if not saving to DB yet.
        // But the user wants quantities.

        div.innerHTML = `
            <span class="text-xs font-semibold text-slate-600">${material.name}</span>
            <input type="number" name="material_qty[${material.id}]" value="0" min="0" class="mini-input form-control form-control-sm" style="width: 50px;">
        `;
        container.appendChild(div);
    }

    addMaterial(event) {
        const category = event.currentTarget.dataset.category;
        document.getElementById('modal-material-category').value = category;
        document.getElementById('materialModal').classList.remove('hidden');
        document.getElementById('materialModal').classList.add('flex');
    }

    closeMaterialModal() {
        document.getElementById('materialModal').classList.add('hidden');
        document.getElementById('materialModal').classList.remove('flex');
    }

    async saveNewMaterial() {
        const name = document.getElementById('new-material-name').value;
        const category = document.getElementById('modal-material-category').value;

        if (!name) return;

        const response = await fetch('/api/materials/new', {
            method: 'POST',
            body: JSON.stringify({ name, category }),
            headers: { 'Content-Type': 'application/json' }
        });

        const newMat = await response.json();
        this.renderMaterialItem(newMat, category);
        this.closeMaterialModal();
        document.getElementById('new-material-name').value = '';
    }

    disconnect() {
        if (typeof tinymce !== 'undefined') {
            tinymce.remove('#service_form_description');
        }
    }
}
