// Import our custom CSS
import './styles/app.css';
import './styles/material_form.css';

// Import Bootstrap JS
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Import SweetAlert2
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
window.Swal = Swal;

// Global window.alert override
window.alert = function(message) {
    if (window.Swal) {
        window.Swal.fire({
            title: 'Notificación del sistema',
            text: message,
            icon: 'info',
            confirmButtonText: 'Aceptar',
            customClass: {
                confirmButton: 'ui-btn btn-cyan px-4',
                popup: 'rounded-2xl border-none shadow-2xl dark:bg-slate-800 dark:text-white'
            },
            buttonsStyling: false
        });
    } else {
        console.warn("Alert: ", message);
    }
};

// Import the Stimulus application
import './bootstrap.js';

// Import and initialize Lucide icons
import { createIcons, icons } from 'lucide';

const initializeIcons = () => {
    createIcons({
        icons,
        attrs: {
            class: 'lucide-icon',
            'stroke-width': 2,
        }
    });
};


const initializeTooltips = () => {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        if (!bootstrap.Tooltip.getInstance(tooltipTriggerEl)) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                delay: { "show": 0, "hide": 100 }
            });
        }
    });
};

document.addEventListener('turbo:load', () => {
    initializeIcons();
    initializeTooltips();
});
document.addEventListener('DOMContentLoaded', () => {
    initializeIcons();
    initializeTooltips();
});
document.addEventListener('turbo:render', () => {
    initializeIcons();
    initializeTooltips();
});

