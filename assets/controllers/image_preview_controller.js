import { Controller } from '@hotwired/stimulus';

/**
 * Controller to handle live preview of an image upload.
 */
export default class extends Controller {
    static targets = ['input', 'preview'];

    connect() {
        // Ensure the preview element exists
        if (!this.hasPreviewTarget) {
            console.error('Image preview target is missing.');
            return;
        }

        // Set a default state or show a placeholder
        this.previewTarget.innerHTML = '<div class="w-32 h-32 rounded-full mx-auto bg-gray-200 flex items-center justify-center text-gray-500 text-sm border-2 border-blue-200 shadow-inner">Sin Foto</div>';
    }

    /**
     * Triggered when the file input changes.
     */
    displayPreview(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                // Replace the placeholder div with an img tag
                const newImg = document.createElement('img');
                newImg.src = e.target.result;
                newImg.alt = 'Foto de perfil';
                newImg.className = 'w-32 h-32 rounded-full object-cover mx-auto border-2 border-blue-300 shadow';

                // Keep the same target for future updates
                newImg.setAttribute('data-image-preview-target', 'preview');

                this.previewTarget.replaceWith(newImg);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
}