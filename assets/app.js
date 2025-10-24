import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

import { createIcons } from 'lucide';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.addEventListener('turbo:load', () => {
    createIcons();
});

// The modal logic has been refactored into the `modal_controller.js` Stimulus controller.
// The old code has been removed to avoid conflicts.
