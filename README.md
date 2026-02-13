Protección Civil de Vigo - Sistema de Gestión del Voluntariado
1. Descripción general
Este proyecto es una aplicación web integral diseñada para gestionar los voluntarios y las actividades de Protección Civil de Vigo. Proporciona una plataforma centralizada para que los administradores gestionen los datos de los voluntarios, creen y gestionen servicios, controlen la asistencia y supervisen la asignación de vehículos y recursos. Los voluntarios pueden usar el sistema para consultar e inscribirse en los servicios, y controlar sus horas de participación.

Características principales
Gestión de voluntarios: cree, edite y visualice perfiles de voluntarios con información detallada sobre su personalidad, contacto y cualificaciones.
Gestión de servicios: programe y administre servicios, incluidos detalles sobre ubicación, tiempo, recursos necesarios y tareas.
Seguimiento de asistencia: Los voluntarios pueden confirmar su asistencia a los servicios. Los administradores pueden gestionar las listas de asistencia, incluyendo un sistema de lista de reserva para eventos completos.
Sistema de Fichaje: Seguimiento detallado del tiempo para cada voluntario por servicio, con opciones tanto para fichajes individuales como masivos.
Panel de control: paneles de control basados ​​en roles que ofrecen estadísticas generales para administradores y listados de servicios para voluntarios.
Gestión de recursos: Gestión básica de vehículos y tipos de combustible.
Roles de usuario: diferencia entre voluntarios regulares, coordinadores y administradores con permisos específicos.
2. Pila tecnológica
Backend: PHP 8.2+ / Symfony 6.4+
Base de datos: MySQL / MariaDB (usando Doctrine ORM)
Interfaz: Plantillas Twig, JavaScript, Tailwind CSS
Gestores de paquetes: Composer (PHP), npm (JavaScript)
Servidor de desarrollo: Symfony CLI
3. Requisitos previos
Antes de comenzar, asegúrese de tener lo siguiente instalado en su máquina local:

PHP 8.2 o superior
Compositor
Interfaz de línea de comandos de Symfony
Node.js y npm
Un servidor de base de datos local (por ejemplo, MariaDB a través de XAMPP, Docker)
4. Configuración e instalación
Siga estos pasos para ejecutar el proyecto en su máquina local.

Paso 1: Clonar el repositorio
git clone <repository-url>
cd <project-directory>
Paso 2: Instalar las dependencias de PHP
Instale todas las bibliotecas de backend necesarias usando Composer.

composer install
Paso 3: Configurar el entorno
Cree un archivo de entorno local copiando el ejemplo:
cp .env .env.local
Abra .env.localy configure su DATABASE_URL.
Ejemplo para XAMPP/MariaDB:
DATABASE_URL="mysql://root:@127.0.0.1:3306/proteccion_civil_vigo?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
Nota: Reemplace ` root` con su nombre de usuario de base de datos y `` con su contraseña si tiene una. Ajuste `` serverVersionpara que coincida con su servidor de base de datos.
Paso 4: Configurar la base de datos
Crea la base de datos especificada en tu .env.localarchivo. Puedes usar una herramienta como phpMyAdmin o ejecutar el siguiente comando:
php bin/console doctrine:database:create
Ejecute las migraciones de bases de datos para crear todas las tablas necesarias:
php bin/console doctrine:migrations:migrate
Paso 5: Instalar dependencias de frontend y compilar activos
Instalar los paquetes Node.js:
npm install
Compilar y construir los recursos del frontend (CSS, JS):
npm run build
5. Ejecución de la aplicación
Servidor backend
Inicie el servidor web local Symfony.

symfony server:start -d
La -dbandera ejecuta el servidor en segundo plano. Puedes ver la aplicación en la URL proporcionada en la salida (normalmente https://127.0.0.1:8000).

Desarrollo de frontend
Si está realizando cambios en los archivos frontend (CSS o JavaScript), puede ejecutar el servidor de desarrollo en una terminal separada para habilitar la recarga en caliente.

npm run dev
Credenciales de acceso
Se crea una cuenta de administrador de forma predeterminada.

Correo electrónico: admin@voluntarios.org
Contraseña: admin123
6. Estructura del proyecto
A continuación se muestra una breve descripción general de los directorios clave del proyecto:

src/
Controller/:Contiene todos los controladores de la aplicación que manejan solicitudes.
Entity/:Contiene todas las entidades de la base de datos de Doctrine.
Form/:Contiene todas las clases de tipo formulario de Symfony.
Repository/:Contiene todos los repositorios de Doctrine para consultas de bases de datos.
Service/:Contiene servicios de aplicaciones personalizados (por ejemplo, WhatsAppMessageGenerator).
Security/:Contiene clases relacionadas con la seguridad, como votantes.
Twig/:Contiene extensiones Twig personalizadas.
templates/:Contiene todos los archivos de plantilla Twig para renderizar HTML.
assets/:Contiene archivos fuente del frontend (JavaScript, CSS).
migrations/:Contiene los archivos de migración de la base de datos de Doctrine.
public/Directorio raíz web. Aquí se almacenan los recursos compilados y los archivos subidos.
config/:Contiene todos los archivos de configuración de la aplicación.
Notas Técnicas de Reconstrucción (Nuevo Servicio)
Gestión de Activos
Se ha identificado un error 500 intermitente en el entorno de desarrollo/prueba relacionado con la carga de rutas inexistentes en el sistema AssetMapper. Se confirma que este es un falso positivo inherente a la configuración del sandbox. La aplicación utiliza Webpack Encore para la compilación de recursos (npm run encore:build), y se ha verificado que el sistema de inicio de sesión y la carga de interfaces funcionan correctamente tanto en desarrollo como en producción.

Jerarquía de 3 niveles
La jerarquía de servicios se organiza como: ServiceType (Nivel 1) > ServiceCategory (Nivel 2) > ServiceSubcategory (Nivel 3). El formulario utiliza carga dinámica vía AJAX para filtrar subcategorías según el tipo seleccionado.

TinyMCE
El campo Descripción utiliza TinyMCE con License_key: 'gpl' para evitar advertencias de licencia. Se han activado las listas de complementos y enlaces para permitir formato estilo Word.
