# Historial de Cambios - Sistema de Gestión de Voluntarios

## [1.6.1] - 20-03-2026 (Versión Actual)
- **Corrección:** Mejora en la lógica de conteo de materiales "actualizados" vs "creados" durante la importación, funcionando correctamente incluso para registros nuevos no guardados aún.
- **Corrección:** Refinamiento en la detección de materiales duplicados por nombre; ahora solo se agrupan si no se proporcionan identificadores únicos (EAN/Serie), evitando fusiones accidentales de productos distintos.
- **Corrección:** Soporte para códigos de barras largos y en notación científica provenientes de Excel, asegurando que se capturen como texto exacto.
- **Lógica:** Protección de la naturaleza del material (Consumible/Técnico) durante la importación para evitar sobrescrituras automáticas por categoría.

## [1.6.0] - 19-03-2026
- **Corrección:** Arreglo del error `getCellByColumnAndRow` en la importación de Excel causado por incompatibilidad con versiones recientes de PhpSpreadsheet.
- **Corrección:** Solución al fallo de duplicados (`IntegrityConstraintViolation`) en la importación masiva al validar campos únicos como `network_id` y `serial_number`.
- **Corrección:** Solución al error `LogicException` al descargar plantillas en servidores sin la extensión PHP `fileinfo` habilitada.
- **Importación:** Rediseño del proceso de importación Excel con agrupación de filas por material, mejorando la transparencia en la vista previa.
- **Importación:** Implementación de caché de sesión en `ExcelImportService` para agrupar filas del mismo material y evitar errores de duplicado previos al guardado.
- **Sincronización:** Mejora en la importación de Excel para permitir la actualización de metadatos en materiales y unidades técnicas existentes.
- **Sincronización:** Unificación de la versión del Changelog con el archivo VERSION del sistema.

## [1.5.1] - 17-03-2026
- **Control Visual:** Implementación del número de versión manual en formato X.Y.Z.
- **Interfaz:** Visualización de la versión v1.5.1 en la barra lateral del sistema.

## [1.5.0] - 16-03-2026
- **Multi-lote:** Soporte para múltiples lotes (MaterialBatch) por producto.
- **FIFO:** Implementación de lógica de consumo First-In-First-Out para consumibles.
- **Alertas Stock:** Notificaciones automáticas de stock bajo y caducidad inminente.

## [1.4.1] - 08-03-2026
- **Optimización:** Mejora de velocidad en la carga de estadísticas del panel.

## [1.4.0] - 05-03-2026
- **Dashboard:** Rediseño completo de la interfaz de administración (Command Center).
- **UX:** Implementación de barra lateral colapsable y optimización móvil.
- **Modo Oscuro:** Sistema de temas claro/oscuro con persistencia en localStorage.

## [1.3.1] - 25-02-2026
- **Excel:** Mejora en la importación masiva con soporte para 22 columnas de datos.

## [1.3.0] - 20-02-2026
- **Seguridad:** Hardening de la autenticación, limitación de tasa (Rate Limiting) y Honeypot.
- **Cifrado:** Hasheo SHA-256 para tokens de recuperación de contraseña.

## [1.2.0] - 05-02-2026
- **Trazabilidad:** Sistema completo de movimientos de almacén y transferencias entre ubicaciones.
- **Imágenes:** Soporte para fotografías en las fichas de materiales y vehículos.

## [1.1.0] - 27-01-2026
- **Botiquines:** Módulo de gestión de mochilas sanitarias, plantillas de dotación y revisiones.
- **Radios:** Control de accesorios de comunicaciones (PTTs y baterías).

## [1.0.1] - 15-08-2025
- **Inventario:** Campos técnicos añadidos (Alias, ID de Red, Teléfono).

## [1.0.0] - 15-07-2025
- **Lanzamiento:** Sistema inicial de voluntarios y gestión de inventario básico.
