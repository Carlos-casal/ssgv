// Import our custom CSS
import './styles/app.css';

// Import the Stimulus application
import './bootstrap.js';

// Import and initialize Lucide icons
import { createIcons } from 'lucide';

document.addEventListener('turbo:load', () => {
    createIcons();
});

document.addEventListener('DOMContentLoaded', () => {
    createIcons();
});

document.addEventListener('turbo:render', () => {
    createIcons();
});
