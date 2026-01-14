// Import our custom CSS
import './styles/app.css';

// Import the Stimulus application
import './bootstrap.js';

// Import and initialize Lucide icons
import { createIcons, icons } from 'lucide';

const initializeIcons = () => {
    // Pass the icons object as required by the lucide library
    createIcons({ icons });
};

document.addEventListener('turbo:load', initializeIcons);
document.addEventListener('DOMContentLoaded', initializeIcons);
document.addEventListener('turbo:render', initializeIcons);
