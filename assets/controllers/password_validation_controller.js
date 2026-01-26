import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["password", "confirm", "length", "uppercase", "special"];

    validate() {
        const password = this.passwordTarget.value;

        // 8+ characters
        const isLongEnough = password.length >= 8;
        this.updateFeedback(this.lengthTarget, isLongEnough);

        // 1 uppercase
        const hasUppercase = /[A-Z]/.test(password);
        this.updateFeedback(this.uppercaseTarget, hasUppercase);

        // 1 number or symbol
        const hasSpecial = /[0-9!@#$%^&*(),.?":{}|<>]/.test(password);
        this.updateFeedback(this.specialTarget, hasSpecial);

        this.validateConfirmation();
    }

    validateConfirmation() {
        const password = this.passwordTarget.value;
        const confirm = this.confirmTarget.value;

        if (confirm.length > 0) {
            if (password === confirm) {
                this.confirmTarget.classList.remove('border-red-500');
                this.confirmTarget.classList.add('border-green-500');
            } else {
                this.confirmTarget.classList.remove('border-green-500');
                this.confirmTarget.classList.add('border-red-500');
            }
        } else {
            this.confirmTarget.classList.remove('border-green-500', 'border-red-500');
        }
    }

    updateFeedback(target, isValid) {
        if (isValid) {
            target.classList.remove('text-slate-400');
            target.classList.add('text-green-600');
            const icon = target.querySelector('[data-lucide]');
            if (icon) icon.setAttribute('data-lucide', 'check-circle');
        } else {
            target.classList.remove('text-green-600');
            target.classList.add('text-slate-400');
            const icon = target.querySelector('[data-lucide]');
            if (icon) icon.setAttribute('data-lucide', 'circle');
        }
        // Trigger lucide icon replacement if needed
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }
}
