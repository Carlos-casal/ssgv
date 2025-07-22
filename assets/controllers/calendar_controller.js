import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        const monthYear = document.getElementById('month-year');
        const prevMonthButton = document.getElementById('prev-month');
        const nextMonthButton = document.getElementById('next-month');
        const calendarBody = document.getElementById('calendar-body');
        const serviceList = document.getElementById('service-list');

        let currentDate = new Date();

        async function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            monthYear.textContent = new Intl.DateTimeFormat('es-ES', { year: 'numeric', month: 'long' }).format(currentDate);

            const firstDayOfMonth = new Date(year, month, 1);
            const lastDayOfMonth = new Date(year, month + 1, 0);

            const firstDayOfWeek = (firstDayOfMonth.getDay() + 6) % 7;
            const totalDays = lastDayOfMonth.getDate();

            const startDate = new Date(year, month, 1).toISOString().slice(0, 10);
            const endDate = new Date(year, month, totalDays).toISOString().slice(0, 10);

            const response = await fetch(`/api/services?start=${startDate}&end=${endDate}`);
            const services = await response.json();

            calendarBody.innerHTML = '';
            let date = 1;
            for (let i = 0; i < 6; i++) {
                const row = document.createElement('tr');
                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDayOfWeek) {
                        const cell = document.createElement('td');
                        row.appendChild(cell);
                    } else if (date > totalDays) {
                        break;
                    } else {
                        const cell = document.createElement('td');
                        const div = document.createElement('div');
                        div.classList.add('px-2', 'py-2', 'cursor-pointer', 'flex', 'w-full', 'justify-center');
                        const p = document.createElement('p');
                        p.classList.add('text-base', 'text-gray-800', 'font-medium');
                        p.textContent = date;

                        const today = new Date();
                        if (date === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                            p.classList.add('text-blue-500');
                        }

                        const servicesForDay = services.filter(service => new Date(service.start).getDate() === date);
                        if (servicesForDay.length > 0) {
                            div.classList.add('service-day');
                        }

                        div.addEventListener('click', () => {
                            serviceList.innerHTML = '';
                            if (servicesForDay.length > 0) {
                                servicesForDay.forEach(service => {
                                    const div = document.createElement('div');
                                    div.classList.add('border-b', 'pb-4', 'border-gray-400', 'border-dashed', 'pt-5');
                                    const p1 = document.createElement('p');
                                    p1.classList.add('text-xs', 'font-light', 'leading-3', 'text-gray-500', 'dark:text-gray-300');
                                    p1.textContent = new Date(service.start).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
                                    const a = document.createElement('a');
                                    a.tabIndex = 0;
                                    a.classList.add('focus:outline-none', 'text-lg', 'font-medium', 'leading-5', 'text-gray-800', 'dark:text-gray-100', 'mt-2');
                                    a.textContent = service.title;
                                    a.href = service.url;
                                    div.appendChild(p1);
                                    div.appendChild(a);
                                    serviceList.appendChild(div);
                                });
                            } else {
                                const p = document.createElement('p');
                                p.textContent = 'No hay servicios para este día.';
                                serviceList.appendChild(p);
                            }
                        });

                        div.appendChild(p);
                        cell.appendChild(div);
                        row.appendChild(cell);
                        date++;
                    }
                }
                calendarBody.appendChild(row);
            }
        }

        prevMonthButton.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });

        nextMonthButton.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });

        renderCalendar();
    }
}
