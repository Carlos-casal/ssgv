// Import our custom CSS
import './styles/app.css';
import './styles/material_form.css';

// Import Bootstrap JS
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

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
        return new bootstrap.Tooltip(tooltipTriggerEl);
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
