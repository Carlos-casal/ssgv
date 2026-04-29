import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        updateUrl: String
    }

    async updateIdeal(event) {
        const input = event.target;
        const itemId = input.dataset.itemId;
        const quantity = input.value;

        try {
            const response = await fetch(this.updateUrlValue, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    itemId: itemId,
                    quantity: quantity
                })
            });

            if (response.ok) {
                // Success: Flash green border briefly
                input.classList.remove('border-slate-200');
                input.classList.add('border-green-500', 'bg-green-50');
                setTimeout(() => {
                    input.classList.remove('border-green-500', 'bg-green-50');
                    input.classList.add('border-slate-200');
                    // Reload page to update color coding based on new ideal
                    window.location.reload();
                }, 500);
            } else {
                alert('Error al actualizar la cantidad ideal');
                window.location.reload();
            }
        } catch (error) {
            console.error('Error updating ideal quantity:', error);
            alert('Error de conexión');
        }
    }
}
