// Import our custom CSS
import './styles/app.css';

// Import the Stimulus application
import './bootstrap.js';

// Import and initialize Lucide icons
import { createIcons } from 'lucide';

const initializeIcons = () => {
    createIcons();
};

document.addEventListener('turbo:load', initializeIcons);
document.addEventListener('DOMContentLoaded', initializeIcons);
document.addEventListener('turbo:render', initializeIcons);
