// Import our custom CSS
import './styles/app.css';

// Import Bootstrap
import 'bootstrap';

// Import the Stimulus application
import './bootstrap.js';

// Import and initialize Lucide icons
import { createIcons, icons } from 'lucide';

const initializeIcons = () => {
    createIcons({ icons });
    window.lucide = { createIcons: () => createIcons({ icons }) };
};

document.addEventListener('turbo:load', initializeIcons);
document.addEventListener('DOMContentLoaded', initializeIcons);
document.addEventListener('turbo:render', initializeIcons);
