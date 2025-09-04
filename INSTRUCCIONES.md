Hola,

Parece que tu entorno local está atascado en una versión antigua del código, lo que está causando el error `EntrypointNotFoundException`.

Para solucionarlo, por favor sigue estos pasos en tu terminal.

**¡ADVERTENCIA! El paso 3 descartará cualquier cambio local que tengas en esta rama. Asegúrate de guardar tu trabajo en otro lugar si es necesario.**

1.  **Asegúrate de estar en la rama correcta:**
    ```bash
    git checkout feat/JULES-81-implement-quilljs-editor
    ```

2.  **Descarga los últimos cambios del repositorio:**
    ```bash
    git fetch origin
    ```

3.  **Fuerza la actualización de tu rama local a la última versión:**
    ```bash
    git reset --hard origin/feat/JULES-81-implement-quilljs-editor
    ```

Después de ejecutar estos tres comandos, tu código local será idéntico al mío y el error debería desaparecer.

Lamento los inconvenientes.
