import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['display', 'label', 'dropdown', 'searchInput', 'results', 'profileLabel', 'profileDropdown'];
    static values = {
        unitId: String,
        searchUrl: String,
        saveUrl: String
    };

    connect() {
        console.log("Unit Quick Edit controller connected for unit:", this.unitIdValue);
        this.clickOutsideHandler = this.clickOutside.bind(this);
        document.addEventListener('click', this.clickOutsideHandler);
    }

    disconnect() {
        document.removeEventListener('click', this.clickOutsideHandler);
    }

    // --- ASSIGNMENT ---
    toggleAssignment(event) {
        if (event) event.stopPropagation();
        console.log("Toggling assignment dropdown");
        this.dropdownTarget.classList.toggle('hidden');
        if (!this.dropdownTarget.classList.contains('hidden')) {
            this.searchInputTarget.focus();
            this.performSearch('');
        }
    }

    search(event) {
        this.performSearch(event.target.value);
    }

    async performSearch(term) {
        try {
            const response = await fetch(`${this.searchUrlValue}?q=${encodeURIComponent(term)}`);
            const data = await response.json();
            this.renderResults(data);
        } catch (error) {
            console.error('Search error:', error);
        }
    }

    renderResults(volunteers) {
        this.resultsTarget.innerHTML = '';
        const unassignOption = document.createElement('div');
        unassignOption.className = 'p-2 hover:bg-gray-100 cursor-pointer text-xs text-red-600 font-bold border-b';
        unassignOption.textContent = '❌ QUITAR ASIGNACIÓN';
        unassignOption.onclick = () => this.updateField({ assignedTo: 'Sin asignar' });
        this.resultsTarget.appendChild(unassignOption);

        volunteers.forEach(v => {
            const item = document.createElement('div');
            item.className = 'p-2 hover:bg-blue-50 cursor-pointer text-xs border-b';
            item.innerHTML = `<span class="font-bold text-blue-700">${v.indicativo || ''}</span> ${v.name}`;
            item.onclick = () => this.updateField({ assignedTo: v.displayName });
            this.resultsTarget.appendChild(item);
        });
    }

    // --- PROFILE ---
    toggleProfile(event) {
        if (event) event.stopPropagation();
        console.log("Toggling profile dropdown");
        this.profileDropdownTarget.classList.toggle('hidden');
    }

    selectProfile(event) {
        const profile = event.currentTarget.dataset.value;
        this.updateField({ profile: profile });
    }

    // --- ACCESSORIES ---
    async toggleAccessory(event) {
        const field = event.currentTarget.dataset.field;
        const currentValue = event.currentTarget.dataset.value === 'true';
        const newValue = !currentValue;
        
        // Optimistic UI
        const icon = event.currentTarget;
        if (newValue) {
            icon.classList.remove('text-red-500', 'opacity-30');
            icon.classList.add('text-green-500');
        } else {
            icon.classList.remove('text-green-500');
            icon.classList.add('text-red-500', 'opacity-30');
        }
        icon.dataset.value = newValue;

        await this.updateField({ [field]: newValue });
    }

    // --- CORE ---
    async updateField(data) {
        try {
            const response = await fetch(this.saveUrlValue, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                const result = await response.json();
                this.updateUI(result.unit);
            }
        } catch (error) {
            console.error('Update error:', error);
        }
    }

    updateUI(unit) {
        if (this.hasLabelTarget) this.labelTarget.textContent = unit.assignedTo || 'Sin asignar';
        if (this.hasProfileLabelTarget) this.profileLabelTarget.textContent = unit.profile || 'Sin perfil';
        
        if (this.hasDropdownTarget) this.dropdownTarget.classList.add('hidden');
        if (this.hasProfileDropdownTarget) this.profileDropdownTarget.classList.add('hidden');

        // Accessories are updated optimistically but we could sync here if needed
    }

    clickOutside(event) {
        if (!this.element.contains(event.target)) {
            if (this.hasDropdownTarget) this.dropdownTarget.classList.add('hidden');
            if (this.hasProfileDropdownTarget) this.profileDropdownTarget.classList.add('hidden');
        }
    }
}
