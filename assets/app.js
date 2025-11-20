import './bootstrap.js';
import './styles/app.css';
import { createIcons } from 'lucide';

// Log to confirm the file is loaded
console.log('This log comes from assets/app.js');

/**
 * Renders all Lucide icons on the page.
 * This function is designed to be called safely multiple times.
 */
const renderIcons = () => {
    try {
        console.log('app.js: Attempting to render icons...');
        createIcons();
        console.log('app.js: Successfully rendered icons.');
    } catch (error) {
        console.error('app.js: Error rendering icons:', error);
    }
};

// --- Primary Icon Rendering Logic ---

// 1. Render icons when the DOM is fully loaded for the first time.
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOMContentLoaded event fired.');
    renderIcons();
});

// 2. Render icons after every Turbo navigation.
// This is crucial for ensuring icons appear on subsequent page views.
document.addEventListener('turbo:load', () => {
    console.log('turbo:load event fired.');
    renderIcons();
});

// 3. As a fallback, render icons when the Stimulus app connects.
// This can help in scenarios where initial load timing is tricky.
document.addEventListener('stimulus:connect', () => {
    console.log('stimulus:connect event fired.');
    renderIcons();
});
