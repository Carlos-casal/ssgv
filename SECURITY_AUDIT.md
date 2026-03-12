# Informe de Auditoría de Seguridad Técnica - Sistema de Autenticación

**Fecha:** 12 de Marzo de 2026
**Auditor:** Senior Application Security Engineer (Jules)
**Estado:** Finalizado - Mejoras Implementadas

---

## 1. Vulnerabilidades OWASP Top 10 y Hallazgos Críticos

### 1.1 Ataques de Fuerza Bruta (Brute Force) - [CORREGIDO]
*   **Riesgo:** Crítico
*   **Descripción:** El sistema carecía de mecanismos para limitar los intentos de inicio de sesión fallidos, permitiendo ataques de fuerza bruta ilimitados.
*   **Mitigación:** Se ha instalado `symfony/rate-limiter` y configurado `login_throttling` en el firewall principal.
*   **Configuración:** Máximo 5 intentos cada 15 minutos.

### 1.2 Almacenamiento Inseguro de Tokens de Recuperación - [CORREGIDO]
*   **Riesgo:** Alto
*   **Descripción:** Los `resetToken` se almacenaban en texto plano en la base de datos. Si la base de datos se veía comprometida, un atacante podía secuestrar cualquier cuenta.
*   **Mitigación:** Se ha implementado el hashing de tokens usando SHA-256 antes de guardarlos. El sistema ahora compara el hash, siguiendo las mejores prácticas de seguridad.

### 1.3 Ausencia de Cabeceras de Seguridad (Security Headers) - [CORREGIDO]
*   **Riesgo:** Alto
*   **Descripción:** Faltaban políticas de seguridad críticas que exponen al sistema a ataques de Clickjacking, XSS, y Sniffing.
*   **Mitigación:** Se ha implementado un `SecurityHeadersListener` que inyecta automáticamente:
    *   `Content-Security-Policy`: Restringe la ejecución de scripts a fuentes confiables.
    *   `Strict-Transport-Security (HSTS)`: Fuerza el uso de HTTPS.
    *   `X-Frame-Options: DENY`: Previene Clickjacking.
    *   `X-Content-Type-Options: nosniff`: Previene ataques de MIME-sniffing.
    *   `Referrer-Policy`: Protege la privacidad del usuario.

### 1.4 Debilidad en la Gestión de Sesiones - [CORREGIDO]
*   **Riesgo:** Medio
*   **Descripción:** Configuración de cookies por defecto que podían permitir ataques CSRF más fácilmente.
*   **Mitigación:** Se ha endurecido `framework.yaml` estableciendo `cookie_samesite: strict` y `cookie_httponly: true`.

---

## 2. Saneamiento de Entradas y Validación

### 2.1 Falta de Validación de Complejidad en Backend - [CORREGIDO]
*   **Riesgo:** Medio
*   **Descripción:** La complejidad de las contraseñas solo se verificaba en el cliente, permitiendo a un atacante saltarse las reglas mediante peticiones directas a la API.
*   **Mitigación:** Se ha añadido validación en el controlador `SecurityController`:
    *   Mínimo 12 caracteres.
    *   Obligatorio: Mayúsculas, Minúsculas y Números.

### 2.2 Protección Honeypot - [ACTIVADO]
*   **Riesgo:** Bajo (Prevención de Spam/Bots)
*   **Descripción:** Existía un campo honeypot en el formulario pero no se procesaba activamente por la configuración de Symfony.
*   **Mitigación:** Se ha activado `AppAuthenticator` como autenticador principal para validar el campo `_auth_username_token` antes de procesar las credenciales.

---

## 3. Resumen de Riesgos Post-Intervención

| Vulnerabilidad | Riesgo Inicial | Riesgo Actual | Estado |
| :--- | :--- | :--- | :--- |
| Inyección SQL | Bajo | Bajo | Protegido por Doctrine |
| XSS | Medio | Bajo | CSP + Twig Escaping |
| Brute Force | Crítico | Bajo | Rate Limiting activo |
| Session Hijacking | Medio | Bajo | Cookies endurecidas |
| Token Exposure | Alto | Bajo | Hashing implementado |

---

**Conclusión:** El sistema de autenticación ha sido endurecido significativamente siguiendo los estándares de la industria. Se recomienda realizar pruebas de penetración periódicas para asegurar que nuevas funcionalidades no introduzcan regresiones de seguridad.
