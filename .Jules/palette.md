## 2026-02-05 - [Traceability & Specialized UI]
- Implementación de un sistema de trazabilidad basado en ubicaciones (Almacén, Vehículos, Kits).
- Rediseño de formularios de alta de material mediante layouts especializados:
    - **Sanitario**: Rejilla 2-columnas (66/33) priorizando datos clínicos y seguridad (Lote/Caducidad).
    - **Comunicaciones**: Layout técnico de 3 paneles (Equipo, Red, Mantenimiento) para activos individuales.
- Uso de semáforos visuales (rojo/naranja/amarillo/verde) para la gestión proactiva de caducidades en el inventario fungible.
- Asociación estricta de IDs de red (ISSI/IMEI) y Números de Serie como identificadores únicos de activos técnicos.
- Mejora de la robustez en la sumisión de formularios Twig usando `document.forms['material'].submit()` para evitar conflictos de sintaxis con selectores de comillas.
