import './bootstrap.js';
import './styles/app.css';
import { createIcons } from 'lucide';

// Function to render icons
const renderIcons = () => {
    createIcons();
};

// Render icons on initial page load
document.addEventListener('DOMContentLoaded', renderIcons);

// Render icons after Turbo navigates to a new page
document.addEventListener('turbo:load', renderIcons);

// Render icons after a Turbo stream update
document.addEventListener('turbo:render', renderIcons);
