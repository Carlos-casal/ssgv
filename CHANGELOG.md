# Historial de Cambios - Sistema de Gestión de Voluntarios

## [1.8.8] - 25-03-2026 (Versión Actual)
- **Logística (Excel):** Refinada la lógica de importación masiva para manejar dinámicamente columnas según la naturaleza del material.
- **Logística (Excel):** Implementada la regla de negocio que trata la columna "Lote" como "Número de Serie" para materiales de tipo `EQUIPO_TECNICO` cuando el campo de serie está vacío.
- **Logística (Excel):** Implementada la restricción de visibilidad de "Talla"; ahora este campo solo se procesa y guarda para materiales de la categoría "Uniformidad".
- **Trazabilidad (Excel):** Solucionado el problema de duplicación de registros en el historial. Ahora se genera una única entrada de "Registro Inicial" por material/ubicación/lote/talla, sumando todas las cantidades del archivo Excel.
- **Arquitectura:** Optimizado `MaterialManager` para permitir el registro manual de movimientos y la actualización de stock sin generación automática de trazabilidad, facilitando operaciones masivas atómicas.

## [1.8.7] - 25-03-2026
- **Identidad (Usuario):** Implementado el método `getName()` en la entidad `User` para centralizar la obtención del nombre amigable (Nombre y primer apellido) desde el perfil de voluntario.
- **Correcciones (UX/UI):** Solucionado el error 500 en la vista de detalle de material (`material/show.html.twig`) al intentar acceder a la propiedad `name` inexistente en la clase `User`.
- **Consistencia:** Actualizada la lógica de correos de recuperación de contraseña y el historial de trazabilidad de unidades técnicas para utilizar el nuevo método estandarizado de visualización de nombres.
- **Trazabilidad:** Mejora en la visualización de responsables en el historial de movimientos de material, permitiendo la caída automática al email si no existe perfil de voluntario asociado.

## [1.8.6] - 25-03-2026
- **Logística (Botiquines):** Implementada la separación física entre la "Identidad del Contenedor" (Alias, SN, Asociación de Plantilla) y la "Definición de Contenido" (Plantilla).
- **UX/UI (Botiquines):** Nueva vista de edición de identidad para botiquines físicos, accesible desde el listado general e inventario.
- **Correcciones (Cruce de Datos):** Solucionado el error que sobrescribía nombres de materiales maestros (ej: Pulse Oximeter) con el alias del botiquín al editar la plantilla.
- **Correcciones (Duplicidad):** Refinada la lógica de de-duplicación en el alta de materiales para evitar la creación de registros maestros duplicados por confusión con alias operativos.
- **Lógica (Refill):** Optimizada la detección de ítems técnicos en la propuesta de reposición; ahora se garantiza que elementos como pulsioxímetros siempre aparezcan como faltantes si no están físicamente en el kit.

## [1.8.5] - 25-03-2026
- **Logística (Botiquines):** Implementada la funcionalidad para eliminar botiquines físicos directamente desde el listado general en `/kits` con confirmación de seguridad.
- **Logística (Botiquines):** Corregido error de "Creación Anticipada"; los botiquines ahora solo se registran en la base de datos tras una confirmación explícita en una nueva vista previa.
- **UX/UI:** Implementada una vista previa detallada antes del registro del kit, asegurando que todos los productos de la plantilla coincidan fielmente con el inventario futuro.
- **Correcciones:** Solucionado el problema de visibilidad de productos en la carga inicial que omitía elementos de la plantilla en ciertos flujos.
- **UX/UI (FIFO):** Corregido el contraste de los desplegables de lote/unidad en modo oscuro para garantizar legibilidad.
- **Lógica (Reposición):** Los botiquines y contenedores han sido excluidos automáticamente de las propuestas de reposición y de la lista de materiales manuales para evitar transferencias de contenedores dentro de otros contenedores.

## [1.8.0] - 24-03-2026
- **Gestión de Plantillas:** Implementada la funcionalidad para eliminar plantillas de botiquines existentes con confirmación de seguridad.
- **UX/UI:** Rediseño del selector de "Tipo de Contenedor" en plantillas; ahora permite texto libre con sugerencias (datalist) en lugar de una lista cerrada.
- **UX/UI:** Optimización de los desplegables de materiales en la edición de plantillas; ahora solo muestran el nombre comercial del producto para una interfaz más limpia.
- **Inteligencia de Formulario:** Implementado filtrado dinámico en tiempo real en la edición de plantillas. Al seleccionar un material en una fila, este desaparece automáticamente de las opciones de las demás filas para evitar duplicados accidentales.

## [1.7.7] - 23-03-2026
- **Gestión:** Corrección en el cálculo de "Stock Actual" en el inventario de botiquines; ahora suma correctamente todos los lotes y tallas.
- **Logística:** Habilitada la edición manual en las propuestas de reposición. Los usuarios pueden ahora cambiar lotes, unidades técnicas y ajustar cantidades antes de confirmar traslados.
- **Optimización:** Reducción drástica de consultas a base de datos (N+1) en los listados de botiquines y plantillas mediante carga ansiosa (Eager Loading).
- **UX/UI:** Solucionado error que obligaba a pulsar dos veces para añadir productos a una plantilla.
- **Backend:** Mejora en la precisión del cálculo de faltantes al reponer kits, agregando stock por material en lugar de por entrada individual.

## [1.7.6] - 23-03-2026
- **Logística:** Sincronización completa de stock por ubicación para equipos técnicos. Ahora aparecen correctamente desglosados en el panel de almacén.
- **Trazabilidad:** Implementada lógica de de-duplicación al crear materiales. Si se intenta dar de alta un producto ya existente (mismo nombre/EAN), se reutiliza el registro maestro y se añaden las nuevas unidades al mismo.
- **UX/UI:** Mejora visual en la detección de faltas de stock durante la reposición de botiquines.
- **Corrección:** Solucionado problema de "Stock global" erróneo en el panel de almacén para materiales técnicos.

## [1.7.5] - 23-03-2026
- **Gestión:** Implementado nuevo sistema de reposición de botiquines con vista previa y confirmación.
- **Logística:** Aplicación estricta de lógica FIFO (First-In-First-Out) seleccionando automáticamente los lotes más antiguos del almacén.
- **Trazabilidad:** Los equipos técnicos ahora conservan su identidad, número de serie y alias al ser trasladados a botiquines.
- **Inteligencia:** El sistema detecta automáticamente faltas de stock en el almacén central e informa de ubicaciones alternativas (otros vehículos o botiquines) donde encontrar el material.
- **Corrección:** Robustecida la creación de botiquines para evitar fallos cuando no existe un material específico llamado "Botiquín".

## [1.7.4] - 22-03-2026
- **Corrección:** Solucionado error `MappingException` (Class App\Service\MaterialBatch does not exist) al reponer botiquines.
- **UX/UI:** Mejora en la visualización del inventario de botiquines; ahora muestra todos los productos de la plantilla (incluso sin stock) comparados con el stock real.
- **Corrección:** Corregido error en el controlador de plantillas de botiquín que impedía añadir más de un producto en la creación inicial.

## [1.7.3] - 22-03-2026
- **UX/UI:** Rediseño completo de formularios de material y vehículos con diseño unificado de tarjetas.
- **Formularios:** Implementación de "Bloque A" optimizado (Nombre y Código de Barras en la misma fila).
- **Cálculos:** Agregación en tiempo real de lotes múltiples en el bloque de "Stock y Costes".
- **Dynamic UI:** Adaptación automática del ancho de campos según el contenido y ocultación de campos irrelevantes por categoría.
- **Gestión:** Integración de modal para añadir subfamilias/prendas sobre la marcha.

## [1.7.1] - 21-03-2026
- **UX/UI:** Implementación de tooltips descriptivos en la barra lateral cuando está colapsada.
- **UX/UI:** Corrección de la alineación de iconos en el desplegable de "Añadir Material" del almacén (iconos a la izquierda del texto).

## [1.7.0] - 21-03-2026
- **Formularios:** Rediseño completo de los formularios de alta de material por categorías (Sanitario, Comunicaciones, Logística, Uniformidad, Vehículos).
- **UX/UI:** Optimización de campos dinámicos y adaptación automática del tamaño de cajas de texto al contenido.
- **Funcionalidad:** Implementación de "Añadir" en cabecera para lotes y gestión de subfamilias dinámicas con guardado permanente.
- **Naturaleza:** Incorporación de nuevas naturalezas "Otros" y "Accesorios" con lógica de campos obligatorios específicos.

## [1.6.1] - 20-03-2026
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
