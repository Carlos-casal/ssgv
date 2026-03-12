# Informe de Auditoría de Seguridad Técnica - /login y Autenticación

**Fecha:** 24 de Mayo de 2024
**Auditor:** Senior Application Security Engineer (Jules)
**Estado:** Finalizado

---

## 1. Vulnerabilidades OWASP Top 10 Detectadas

### 1.1 Funcionalidad de Desarrollo Insegura (autoLogin)
*   **Riesgo:** Crítico
*   **Descripción:** El método `SecurityController::autoLogin` permite el acceso directo a cuentas de administrador sin contraseña si el entorno está configurado como `dev`. Aunque existe una comprobación de entorno, la presencia de este código en producción (por error de configuración o despliegue) representa un riesgo catastrófico de "Broken Access Control" (A01:2021).
*   **Mitigación:** Eliminar completamente este método y su ruta asociada en `config/routes/web_profiler.yaml` antes del despliegue en producción.

### 1.2 Ausencia de Limitación de Tasa (Rate Limiting)
*   **Riesgo:** Alto
*   **Descripción:** No se ha detectado ninguna política de bloqueo de cuenta o limitación de intentos fallidos en `/login` o `/forgot-password`. Esto expone al sistema a ataques de fuerza bruta (A07:2021 - Identification and Authentication Failures).
*   **Mitigación:** Implementar `symfony/rate-limiter` en la configuración de seguridad.
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
*   **Mitigación:** Almacenar un hash del token (SHA-256) en la base de datos en lugar del token original. El usuario recibe el token original, pero el sistema lo compara contra el hash.

---

## 2. Saneamiento de Entradas y Validación

### 2.1 Falta de Validación de Complejidad en el Backend
*   **Riesgo:** Medio
*   **Descripción:** La validación de contraseñas (8 caracteres, mayúsculas, símbolos) solo se realiza en el frontend (`password_validation_controller.js`). El controlador `SecurityController::resetPassword` solo comprueba que las contraseñas coincidan, permitiendo establecer contraseñas débiles mediante herramientas que omitan el navegador.
*   **Mitigación:** Añadir restricciones de validación en la entidad `User` o en el controlador:
    ```php
    // Ejemplo de validación en controlador
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        $this->addFlash('error', 'La contraseña no cumple los requisitos de seguridad.');
        return $this->render(...);
    }
    ```

---

## 3. Configuración de Cabeceras (Security Headers)

### 3.1 Ausencia de Políticas Globales de Seguridad
*   **Riesgo:** Medio
*   **Descripción:** Faltan cabeceras críticas para mitigar ataques como XSS, Clickjacking y Sniffing:
    *   `Content-Security-Policy` (CSP)
    *   `Strict-Transport-Security` (HSTS)
    *   `X-Frame-Options: DENY`
    *   `X-Content-Type-Options: nosniff`
*   **Mitigación:** Implementar un EventListener para añadir estas cabeceras a todas las respuestas:
    ```php
    public function onKernelResponse(ResponseEvent $event) {
        $response = $event->getResponse();
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Content-Security-Policy', "default-src 'self'...");
    }
    ```

---

## 4. Manejo de Sesiones y Cifrado

### 4.1 Configuración de Cookies de Sesión
*   **Riesgo:** Bajo
*   **Descripción:** La configuración de `framework.yaml` usa valores por defecto. Para máxima seguridad, se recomienda forzar `Samesite: Strict` para evitar ataques CSRF en navegadores antiguos que no lo soporten por defecto.
*   **Mitigación:**
    ```yaml
    # config/packages/framework.yaml
    session:
        cookie_samesite: strict
        cookie_secure: auto
        cookie_httponly: true
    ```

---

## 5. Resumen de Inyecciones
*   **SQL Injection:** Riesgo **Bajo**. Se utiliza Doctrine ORM y QueryBuilder de forma consistente en los repositorios, lo que sanitiza automáticamente los parámetros. No se encontraron consultas nativas concatenadas manualmente.
*   **XSS (Cross-Site Scripting):** Riesgo **Bajo**. Twig escapa automáticamente el contenido. Se han revisado los filtros `|raw` en `templates/service/view.html.twig` y `location/index.html.twig`, los cuales deben ser monitorizados pero no afectan directamente al flujo de login.

---

**Conclusión:** El sistema de autenticación es funcional pero carece de protecciones modernas contra ataques de fuerza bruta y endurecimiento (hardening) de cabeceras. La presencia de `autoLogin` es el punto de mayor riesgo operacional.
