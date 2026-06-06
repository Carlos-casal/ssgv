import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        title: String,
        text: String,
        icon: String,
        confirmButtonText: String,
        cancelButtonText: String,
        confirmButtonClass: String
    }

    confirm(event) {
        event.preventDefault();
        const element = event.currentTarget;
        const form = element.closest('form');

        if (!window.Swal) {
            console.error('SweetAlert2 not found');
            return;
        }

        Swal.fire({
            title: this.titleValue || '¿Estás seguro?',
            text: this.textValue || 'Esta acción no se puede deshacer.',
            icon: this.iconValue || 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22d3ee',
            cancelButtonColor: '#1e293b',
            confirmButtonText: this.confirmButtonTextValue || 'Sí, continuar',
            cancelButtonText: this.cancelButtonTextValue || 'Cancelar',
            customClass: {
                confirmButton: this.confirmButtonClassValue || 'ui-btn btn-cyan px-4',
                cancelButton: 'ui-btn btn-dark px-4',
                popup: 'rounded-2xl border-none shadow-2xl dark:bg-slate-800 dark:text-white'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                if (form) {
                    form.submit();
                } else if (element.tagName === 'A') {
                    window.location.href = element.href;
                }
            }
        });
    }
}
