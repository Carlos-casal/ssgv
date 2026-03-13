# Informe de Auditoría de Seguridad Técnica - /login y Autenticación

**Fecha:** 28 de Febrero de 2025
**Auditor:** Senior Application Security Engineer (Jules)
**Estado:** Finalizado (Implementado)

---

## 1. Vulnerabilidades OWASP Top 10

### 1.1 Exposición de Datos Sensibles (Tokens en Texto Plano) - [CORREGIDO]
*   **Riesgo:** Medio
*   **Descripción:** Los tokens de restablecimiento de contraseña se almacenaban en texto plano en la base de datos.
*   **Mitigación:** Se ha implementado el hasheo de tokens mediante SHA-256 antes de su almacenamiento. El usuario recibe el token original, pero en la base de datos solo reside el hash.

### 1.2 Ausencia de Limitación de Tasa (Rate Limiting) - [CORREGIDO]
*   **Riesgo:** Alto
*   **Descripción:** El formulario de login no limitaba el número de intentos fallidos, facilitando ataques de fuerza bruta.
*   **Mitigación:** Se ha configurado `login_throttling` en `security.yaml`, limitando a 5 intentos cada 15 minutos por IP/usuario.

---

## 2. Saneamiento de Entradas y Validación

### 2.1 Validación de Complejidad de Contraseña en el Backend - [CORREGIDO]
*   **Riesgo:** Medio
*   **Descripción:** No se validaba la fortaleza de la contraseña en el servidor durante el restablecimiento.
*   **Mitigación:** Se han añadido reglas de validación en `SecurityController::resetPassword`: mínimo 12 caracteres, mayúsculas, minúsculas y números.

---

## 3. Configuración de Cabeceras (Security Headers)

### 3.1 Ausencia de Políticas Globales de Seguridad - [CORREGIDO]
*   **Riesgo:** Medio
*   **Descripción:** Faltaban cabeceras críticas para mitigar ataques como XSS, Clickjacking y Sniffing de MIME.
*   **Mitigación:** Se ha creado `SecurityHeadersListener` que inyecta automáticamente:
    *   `Content-Security-Policy`: Restringe la carga de scripts y estilos a fuentes de confianza.
    *   `X-Frame-Options: DENY`: Evita ataques de Clickjacking.
    *   `X-Content-Type-Options: nosniff`: Previene el sniffing de tipos de contenido.
    *   `Strict-Transport-Security`: Fuerza el uso de HTTPS.

---

## 4. Manejo de Sesiones y Cifrado

### 4.1 Configuración de Cookies de Sesión - [CORREGIDO]
*   **Riesgo:** Bajo
*   **Descripción:** Las cookies de sesión no tenían configuraciones de seguridad estrictas por defecto.
*   **Mitigación:** Se ha actualizado `framework.yaml` para forzar:
    *   `cookie_httponly: true`: Evita acceso a la cookie vía JavaScript.
    *   `cookie_samesite: strict`: Protege contra ataques CSRF.
    *   `cookie_secure: auto`: Asegura la transmisión cifrada si el sitio usa HTTPS.

---

**Conclusión:** Se han mitigado todos los riesgos identificados en la auditoría. El sistema de autenticación ahora cumple con estándares de seguridad modernos y robustos.
