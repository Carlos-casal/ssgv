import './bootstrap.js';

// Import our custom CSS
import './styles/app.css';

// Import and initialize Lucide icons
import { createIcons, icons } from 'lucide';

// Function to create icons
const createLucideIcons = () => {
  createIcons({ icons });
};

// Create icons on initial page load
document.addEventListener('DOMContentLoaded', () => {
  createLucideIcons();
});

// Re-create icons when Turbo navigates
document.addEventListener('turbo:load', () => {
  createLucideIcons();
});

// Re-create icons after a Turbo stream render
document.addEventListener('turbo:render', () => {
    createLucideIcons();
});
