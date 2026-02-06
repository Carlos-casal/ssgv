## 2026-02-05 - [Traceability & Specialized UI]
- Implementación de un sistema de trazabilidad basado en ubicaciones (Almacén, Vehículos, Kits).
- Rediseño de formularios de alta de material mediante layouts especializados:
    - **Sanitario**: Rejilla 2-columnas (66/33) priorizando datos clínicos y seguridad (Lote/Caducidad).
    - **Comunicaciones**: Layout técnico de 3 paneles (Equipo, Red, Mantenimiento) para activos individuales.
- Uso de semáforos visuales (rojo/naranja/amarillo/verde) para la gestión proactiva de caducidades en el inventario fungible.
- Asociación estricta de IDs de red (ISSI/IMEI) y Números de Serie como identificadores únicos de activos técnicos.
- Mejora de la robustez en la sumisión de formularios Twig usando `document.forms['material'].submit()` para evitar conflictos de sintaxis con selectores de comillas.

## 2026-02-05 - [Service Form Compact Redesign]
- **Structure:** Replaced the multi-tab interface with a single-page "Command Center" dashboard layout.
- **Header:** Implemented a fixed top header (bg-white/90 with backdrop-blur) containing primary actions (Guardar/Cancelar) to ensure they are always accessible regardless of scroll position.
- **Grid Layout:** Used a 4:8 split for the top row (Identity vs. Chronology) and a full-width bottom section for Resources.
- **Fields:** Condensed date and time fields into horizontal rows using 'form-control-sm' and grouped related items (Start/End, Base/Departure) to maximize "above the fold" visibility.
- **Accessibility:** Added 'aria-label' and 'title' to all icon-only buttons (e.g., adding subcategories or new materials).

## 2026-02-05 - [Warehouse Traceability & Specialized UI]
- **Category Specificity:** Implemented specialized show/edit layouts for Sanitary and Communications items. Sanitary focuses on Batch/Expiration/Clinical families, while Communications focuses on S/N, Network IDs (ISSI/IMEI), and operational status.
- **Movements:** Added a "Log de Movimientos" section in the material detail view to track every transfer between locations.
- **Visual Feedback:** Integrated a traffic light semaphore for expiration dates (Red: Expired, Orange: <30 days, Yellow: <6 months) and low-stock alerts.

## 2024-05-23 - Compact 2-Tab Service Management Dashboard
- **Layout:** Replaced long scrolling forms with a 2-tab Bootstrap structure ('Datos Generales' and 'Recursos').
- **Header:** Implemented a fixed top header with primary action buttons (Guardar/Cancelar) to maintain context while scrolling.
- **Tab 1 (Identity & Context):** Uses a 4:8 split grid. Left panel for Identity (Title, ID, Category); Right panel for Chronology (Dates, Times, Location) with horizontal row layouts for time inputs.
- **Tab 2 (Resource Allocation):** Centralizes all resource management (Materials, Vehicles, Personnel) in a single unified view.
- **Micro-UX:** Applied a bottom 'Definición Operativa' section for multi-line textareas (Tasks, Description) with fixed-height rows to prevent layout shifts.
- **Traceability Feedback:** Added 'expiration status' indicators (traffic light dots) in material lists to provide immediate visual warnings for expiring inventory.

## 2025-05-15 - Rediseño de Gestión de Servicios y Trazabilidad
- **Micro-UX de Pestañas:** Implementado un sistema de navegación por pestañas de alta densidad (4:8 split) para centralizar Datos, Recursos y Asistencia sin perder el foco.
- **Header Pegajoso (Sticky):** El botón de "Guardar" y "Cancelar" se mantiene siempre visible en la parte superior, reduciendo la carga cognitiva y el scroll innecesario.
- **Consistencia Visual:** Unificado el diseño entre la creación de servicios y la edición, extendiendo el sistema de pestañas para incluir la gestión de asistencia en el modo edición.
- **Trazabilidad de Almacén:** Evolución del sistema hacia una arquitectura de movimientos entre ubicaciones (Almacén, Vehículos, Kits) con desgloses automáticos por ubicación y valoración económica en tiempo real.
