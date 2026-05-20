import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['display', 'label', 'dropdown', 'searchInput', 'results'];
    static values = {
        unitId: String,
        searchUrl: String,
        saveUrl: String
    };

    connect() {
        // Close dropdown when clicking outside
        this.clickOutsideHandler = this.clickOutside.bind(this);
        document.addEventListener('click', this.clickOutsideHandler);
    }

    disconnect() {
        document.removeEventListener('click', this.clickOutsideHandler);
    }

    toggle() {
        this.dropdownTarget.classList.toggle('hidden');
        if (!this.dropdownTarget.classList.contains('hidden')) {
            this.searchInputTarget.focus();
            this.performSearch(''); // Show initial results if any or empty
        }
    }

    clickOutside(event) {
        if (!this.element.contains(event.target)) {
            this.dropdownTarget.classList.add('hidden');
        }
    }

    search(event) {
        const term = event.target.value;
        this.performSearch(term);
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
        
        // Add "Sin asignar" option
        const unassignOption = document.createElement('div');
        unassignOption.className = 'p-2 hover:bg-gray-100 cursor-pointer text-xs text-red-600 font-bold border-b';
        unassignOption.textContent = '❌ QUITAR ASIGNACIÓN (Sin asignar)';
        unassignOption.onclick = () => this.select(null);
        this.resultsTarget.appendChild(unassignOption);

        volunteers.forEach(v => {
            const item = document.createElement('div');
            item.className = 'p-2 hover:bg-blue-50 cursor-pointer text-xs border-b';
            item.innerHTML = `<span class="font-bold text-blue-700">${v.indicativo || ''}</span> ${v.name}`;
            item.onclick = () => this.select(v);
            this.resultsTarget.appendChild(item);
        });

        if (volunteers.length === 0 && this.resultsTarget.children.length === 1) {
            const noResults = document.createElement('div');
            noResults.className = 'p-2 text-xs text-gray-500 italic';
            noResults.textContent = 'No se encontraron voluntarios';
            this.resultsTarget.appendChild(noResults);
        }
    }

    async select(volunteer) {
        const displayName = volunteer ? `${volunteer.indicativo || ''} ${volunteer.name}`.trim() : 'Sin asignar';
        
        try {
            const response = await fetch(this.saveUrlValue, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ assignedTo: displayName })
            });

            if (response.ok) {
                this.labelTarget.textContent = displayName;
                this.dropdownTarget.classList.add('hidden');
                this.searchInputTarget.value = '';
                
                // Optional: Flash success
                this.labelTarget.classList.add('text-green-600');
                setTimeout(() => this.labelTarget.classList.remove('text-green-600'), 2000);
            }
        } catch (error) {
            console.error('Save error:', error);
        }
    }
}
