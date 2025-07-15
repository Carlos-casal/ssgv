document.addEventListener('DOMContentLoaded', function () {
    const addUserButton = document.getElementById('add-user-button');
    const modal = document.getElementById('add-user-modal');
    const closeModalButton = document.getElementById('close-modal');
    const volunteerList = document.getElementById('volunteer-list');

    addUserButton.addEventListener('click', () => {
        modal.classList.remove('hidden');
        fetch('/volunteers')
            .then(response => response.json())
            .then(data => {
                volunteerList.innerHTML = '';
                data.forEach(volunteer => {
                    const div = document.createElement('div');
                    div.innerHTML = `<input type="checkbox" value="${volunteer.id}"> ${volunteer.name}`;
                    volunteerList.appendChild(div);
                });
            });
    });

    closeModalButton.addEventListener('click', () => {
        modal.classList.add('hidden');
    });
});
