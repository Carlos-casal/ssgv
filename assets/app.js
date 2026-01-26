// Import our custom CSS
import './styles/app.css';

// Import the Stimulus application
import './bootstrap.js';

// Import and initialize Lucide icons
import {
    createIcons,
    Mail,
    Lock,
    Eye,
    EyeOff,
    AlertCircle,
    X,
    Key,
    CheckCircle
} from 'lucide';

const initializeIcons = () => {
    createIcons({
        icons: {
            Mail,
            Lock,
            Eye,
            EyeOff,
            AlertCircle,
            X,
            Key,
            CheckCircle
        }
    });
};

document.addEventListener('turbo:load', initializeIcons);
document.addEventListener('DOMContentLoaded', initializeIcons);
document.addEventListener('turbo:render', initializeIcons);
