import { Controller } from '@hotwired/stimulus';
import Quill from 'quill';

export default class extends Controller {
    static targets = [
        "quillDescription",
        "quillTasks",
        "description",
        "tasks",
        "form",
        "whatsappModal",
        "whatsappMessageTextarea",
        "shareWhatsappButton",
        "closeModalButton",
    ];

    connect() {
        this.initializeQuill();
        this.setupFormSubmit();
    }

    disconnect() {
        if (this.quillDescriptionInstance) {
            this.quillDescriptionInstance = null;
        }
        if (this.quillTasksInstance) {
            this.quillTasksInstance = null;
        }
    }

    initializeQuill() {
        this.quillDescriptionInstance = this.setupQuill(this.quillDescriptionTarget, this.descriptionTarget);
        this.quillTasksInstance = this.setupQuill(this.quillTasksTarget, this.tasksTarget);
    }

    setupQuill(container, textarea) {
        if (!container || !textarea) return null;
        if (container.quill) return container.quill;

        const toolbarOptions = [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'strike', 'underline'],
            [{ 'script': 'sub'}, { 'script': 'super' }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'align': [] }],
            ['link'],
            ['clean']
        ];

        const Link = Quill.import('formats/link');
        class CustomLink extends Link {
            static sanitize(url) {
                const sanitizedUrl = super.sanitize(url);
                if (sanitizedUrl && sanitizedUrl !== 'about:blank' && !/^(https?:\/\/|mailto:|tel:)/.test(sanitizedUrl)) {
                    return 'https://' + sanitizedUrl;
                }
                return sanitizedUrl;
            }
        }
        Quill.register(CustomLink, true);

        const quill = new Quill(container, {
            modules: { toolbar: toolbarOptions },
            theme: 'snow'
        });

        quill.root.innerHTML = textarea.value;
        container.quill = quill;

        quill.on('text-change', () => {
            textarea.value = quill.root.innerHTML;
        });

        return quill;
    }

    setupFormSubmit() {
        this.formTarget.addEventListener('submit', async (event) => {
            event.preventDefault();
            this.submitForm();
        });
    }

    async submitForm() {
        // Update hidden textareas with Quill content before submitting
        this.descriptionTarget.value = this.quillDescriptionInstance.root.innerHTML;
        this.tasksTarget.value = this.quillTasksInstance.root.innerHTML;

        const formData = new FormData(this.formTarget);
        const button = this.formTarget.querySelector('button[type="submit"]');
        button.disabled = true;
        button.textContent = 'Guardando...';

        try {
            const response = await fetch(this.formTarget.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                this.whatsappMessageTextareaTarget.value = result.whatsappMessage;
                this.whatsappModalTarget.classList.remove('hidden');

                this.shareWhatsappButtonTarget.onclick = () => {
                    const message = encodeURIComponent(this.whatsappMessageTextareaTarget.value);
                    window.open(`https://wa.me/?text=${message}`, '_blank');
                };

                this.closeModalButtonTarget.onclick = () => {
                    this.whatsappModalTarget.classList.add('hidden');
                    window.location.href = this.formTarget.dataset.redirectUrl;
                };
            } else {
                let errorMessages = 'Se ha producido un error al guardar el servicio.';
                if (result.errors && result.errors.length > 0) {
                    errorMessages = result.errors.join('\n');
                }
                alert(errorMessages);
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            alert('Se ha producido un error de red. Por favor, inténtalo de nuevo.');
        } finally {
            button.disabled = false;
            button.textContent = 'Guardar Servicio';
        }
    }
}
