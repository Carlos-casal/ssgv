// Enable tabs for volunteer edit page - Pure JavaScript, no jQuery
document.addEventListener('DOMContentLoaded', function () {
    console.log('Initializing volunteer tabs...');

    var tabTriggers = document.querySelectorAll('a[data-toggle="tab"]');

    if (tabTriggers.length === 0) {
        console.warn('No tab triggers found');
        return;
    }

    tabTriggers.forEach(function (trigger) {
        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var targetId = this.getAttribute('href');
            console.log('Tab clicked:', targetId);

            document.querySelectorAll('.nav-link').forEach(function (link) {
                link.classList.remove('active');
            });

            document.querySelectorAll('.tab-pane').forEach(function (pane) {
                pane.classList.remove('show', 'active');
            });

            this.classList.add('active');

            var targetPane = document.querySelector(targetId);

            if (targetPane) {
                targetPane.classList.add('show', 'active');
                console.log('Activated pane:', targetId);
            } else {
                console.error('Target pane not found:', targetId);
            }
        });
    });

    console.log('Tabs initialized successfully. Found', tabTriggers.length, 'tab triggers');
});
