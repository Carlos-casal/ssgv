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
            mail: Mail,
            lock: Lock,
            eye: Eye,
            'eye-off': EyeOff,
            'alert-circle': AlertCircle,
            x: X,
            key: Key,
            'check-circle': CheckCircle
        }
    });
};

document.addEventListener('turbo:load', initializeIcons);
document.addEventListener('DOMContentLoaded', initializeIcons);
document.addEventListener('turbo:render', initializeIcons);
