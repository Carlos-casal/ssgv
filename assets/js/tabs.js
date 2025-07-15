document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('[data-tab]');
    const tabContents = document.querySelectorAll('[data-tab-content]');

    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();

            const target = tab.dataset.tab;

            tabs.forEach(t => {
                t.classList.remove('border-indigo-500', 'text-indigo-600');
                t.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });

            tab.classList.add('border-indigo-500', 'text-indigo-600');
            tab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');

            tabContents.forEach(content => {
                if (content.id === target) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            });
        });
    });
});
