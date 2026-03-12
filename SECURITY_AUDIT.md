# Informe de Auditoría de Seguridad Técnica - /login y Autenticación

**Fecha:** 24 de Mayo de 2024
**Auditor:** Senior Application Security Engineer (Jules)
**Estado:** Finalizado

---

## 1. Vulnerabilidades OWASP Top 10

### 1.1 Funcionalidad de Desarrollo Insegura (autoLogin) - [MITIGADO]
*   **Riesgo Inicial:** Crítico
*   **Descripción:** El método `SecurityController::autoLogin` permitía el acceso directo a cuentas de administrador sin contraseña.
*   **Estado:** **Mitigado**. Se ha eliminado el código de `autoLogin` y su ruta asociada. Se ha sustituido por la funcionalidad nativa de Symfony `switch_user`, que es más segura, requiere permisos específicos (`ROLE_ALLOWED_TO_SWITCH`) y deja rastro en los logs.

### 1.2 Ausencia de Limitación de Tasa (Rate Limiting) - [PARCIALMENTE MITIGADO]
*   **Riesgo:** Alto
*   **Descripción:** Falta una política global de bloqueo de cuenta por intentos fallidos. Sin embargo, se ha implementado un **Honeypot (Honey Token)** en el formulario de login para detectar y bloquear bots automáticamente.
*   **Mitigación Realizada:** Se añadió el campo `_auth_username_token` (oculto). Si se rellena, la autenticación falla inmediatamente con un error de "Actividad sospechosa".
*   **Mitigación Recomendada:** Complementar con `symfony/rate-limiter` en la configuración de seguridad para proteger contra ataques de fuerza bruta realizados por humanos o bots avanzados.
    ```yaml
    # config/packages/security.yaml
    main:
        # ...
        login_throttling:
            max_attempts: 5
            interval: '15 minutes'
    ```

### 1.3 Almacenamiento de Tokens de Recuperación en Texto Plano
*   **Riesgo:** Medio
*   **Descripción:** El `resetToken` se guarda directamente en la base de datos sin cifrar ni hashear. Si la base de datos se viera comprometida, un atacante podría obtener tokens activos y restablecer contraseñas de usuarios.
*   **Mitigación Recomendada:** Almacenar un hash del token (SHA-256) en la base de datos en lugar del token original. El usuario recibe el token original, pero el sistema lo compara contra el hash.

---

## 2. Saneamiento de Entradas y Validación

### 2.1 Falta de Validación de Complejidad en el Backend
*   **Riesgo:** Medio
*   **Descripción:** La validación de contraseñas solo se realiza en el frontend (`password_validation_controller.js`). El controlador `SecurityController::resetPassword` permite establecer contraseñas débiles si se omite el navegador.
*   **Mitigación Recomendada:** Añadir restricciones de validación en la entidad `User` o en el controlador.

---

## 3. Configuración de Cabeceras (Security Headers)

### 3.1 Ausencia de Políticas Globales de Seguridad
*   **Riesgo:** Medio
*   **Descripción:** Faltan cabeceras críticas como `Content-Security-Policy`, `Strict-Transport-Security`, `X-Frame-Options` y `X-Content-Type-Options`.
*   **Mitigación Recomendada:** Implementar un EventListener para añadir estas cabeceras a todas las respuestas o usar un bundle como `nelmio/security-bundle`.

---

## 4. Manejo de Sesiones y Cifrado

### 4.1 Configuración de Cookies de Sesión
*   **Riesgo:** Bajo
*   **Descripción:** Se recomienda forzar `Samesite: Strict` para mayor protección contra CSRF.
*   **Mitigación Recomendada:**
    ```yaml
    # config/packages/framework.yaml
    session:
        cookie_samesite: strict
    ```

---

## 5. Resumen de Inyecciones
*   **SQL Injection:** Riesgo **Bajo**. Uso correcto de Doctrine QueryBuilder.
*   **XSS (Cross-Site Scripting):** Riesgo **Bajo**. Protección automática de Twig.

---

**Conclusión:** Se ha eliminado la vulnerabilidad más crítica (`autoLogin`). El sistema aún requiere mejoras en la limitación de tasa (brute force) y el endurecimiento de cabeceras HTTP para alcanzar un nivel de seguridad óptimo para producción.
