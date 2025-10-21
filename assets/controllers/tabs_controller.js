import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['tab', 'panel'];

    connect() {
        this.change({ currentTarget: this.tabTargets[0] });
    }

    change(event) {
        event.preventDefault();
        const selectedTab = event.currentTarget;

        this.tabTargets.forEach((tab, index) => {
            const panel = this.panelTargets[index];
            if (tab === selectedTab) {
                tab.classList.add('border-blue-500', 'text-blue-600');
                tab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                panel.classList.remove('hidden');
            } else {
                tab.classList.remove('border-blue-500', 'text-blue-600');
                tab.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                panel.classList.add('hidden');
            }
        });
    }
}
