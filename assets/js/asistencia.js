import React from 'react';
import { createRoot } from 'react-dom/client';
import AsistenciaListado from '../../src/components/Asistencia/AsistenciaListado';

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('asistencia-react-root');
    if (container) {
        const fichajesData = JSON.parse(container.dataset.fichajes || '[]');
        const root = createRoot(container);
        root.render(
            <React.StrictMode>
                <AsistenciaListado fichajes={fichajesData} />
            </React.StrictMode>
        );
    }
});
