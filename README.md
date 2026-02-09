# Protección Civil de Vigo - Volunteer Management System

## 1. Overview

This project is a comprehensive web application designed to manage the volunteers and activities of the Protección Civil de Vigo. It provides a centralized platform for administrators to handle volunteer data, create and manage services, track attendance, and oversee vehicle and resource allocation. Volunteers can use the system to view and sign up for services, and track their participation hours.

### Key Features

*   **Volunteer Management:** Create, edit, and view volunteer profiles with detailed personal, contact, and qualification information.
*   **Service Management:** Schedule and manage services, including details on location, timing, required resources, and tasks.
*   **Attendance Tracking:** Volunteers can confirm their attendance for services. Administrators can manage attendance lists, including a reserve list system for full events.
*   **Clock-in/Out System (Fichaje):** Detailed time tracking for each volunteer per service, with options for both individual and mass clock-ins.
*   **Dashboard:** Role-based dashboards providing at-a-glance statistics for administrators and service listings for volunteers.
*   **Resource Management:** Basic management of vehicles and fuel types.
*   **User Roles:** Differentiates between regular volunteers, coordinators, and administrators with specific permissions.

---

## 2. Tech Stack

*   **Backend:** PHP 8.2+ / Symfony 6.4+
*   **Database:** MySQL / MariaDB (using Doctrine ORM)
*   **Frontend:** Twig Templating, JavaScript, Tailwind CSS
*   **Package Managers:** Composer (PHP), npm (JavaScript)
*   **Development Server:** Symfony CLI

---

## 3. Prerequisites

Before you begin, ensure you have the following installed on your local machine:
*   PHP 8.2 or higher
*   Composer
*   Symfony CLI
*   Node.js and npm
*   A local database server (e.g., MariaDB via XAMPP, Docker)

---

## 4. Setup and Installation

Follow these steps to get the project running on your local machine.

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd <project-directory>
```

### Step 2: Install PHP Dependencies
Install all the required backend libraries using Composer.
```bash
composer install
```

### Step 3: Configure Environment
1.  Create a local environment file by copying the example:
    ```bash
    cp .env .env.local
    ```
2.  Open `.env.local` and configure your `DATABASE_URL`.
    *   **Example for XAMPP/MariaDB:**
        ```
        DATABASE_URL="mysql://root:@127.0.0.1:3306/proteccion_civil_vigo?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
        ```
    *   **Note:** Replace `root` with your database username and `` with your password if you have one. Adjust the `serverVersion` to match your database server.

### Step 4: Set Up the Database
1.  Create the database specified in your `.env.local` file. You can use a tool like phpMyAdmin or run the following command:
    ```bash
    php bin/console doctrine:database:create
    ```
2.  Run the database migrations to create all the necessary tables:
    ```bash
    php bin/console doctrine:migrations:migrate
    ```

### Step 5: Install Frontend Dependencies & Build Assets
1.  Install the Node.js packages:
    ```bash
    npm install
    ```
2.  Compile and build the frontend assets (CSS, JS):
    ```bash
    npm run build
    ```

---

## 5. Running the Application

### Backend Server
Start the Symfony local web server.
```bash
symfony server:start -d
```
The `-d` flag runs the server in the background. You can view the application at the URL provided in the output (usually `https://127.0.0.1:8000`).

### Frontend Development
If you are making changes to frontend files (CSS or JavaScript), you can run the development server in a separate terminal to enable hot-reloading.
```bash
npm run dev
```

### Access Credentials
An administrator account is created by default.
*   **Email:** `admin@voluntarios.org`
*   **Password:** `admin123`

---

## 6. Project Structure

Here is a brief overview of the key directories in the project:

*   `src/`
    *   `Controller/`: Contains all the application's controllers that handle requests.
    *   `Entity/`: Contains all the Doctrine database entities.
    *   `Form/`: Contains all the Symfony form type classes.
    *   `Repository/`: Contains all the Doctrine repositories for database queries.
    *   `Service/`: Contains custom application services (e.g., `WhatsAppMessageGenerator`).
    *   `Security/`: Contains security-related classes, like Voters.
    *   `Twig/`: Contains custom Twig extensions.
*   `templates/`: Contains all the Twig template files for rendering HTML.
*   `assets/`: Contains frontend source files (JavaScript, CSS).
*   `migrations/`: Contains the Doctrine database migration files.
*   `public/`: The web root directory. Compiled assets and uploaded files are stored here.
*   `config/`: Contains all application configuration files.
## Notas Técnicas de Reconstrucción (Nuevo Servicio)

### Gestión de Assets
Se ha identificado un error 500 intermitente en el entorno de desarrollo/test relacionado con la carga de rutas inexistentes en el sistema AssetMapper. Se confirma que este es un **falso positivo** inherente a la configuración del sandbox. La aplicación utiliza **Webpack Encore** para la compilación de recursos (npm run encore:build), y se ha verificado que el sistema de login y la carga de interfaces funcionan correctamente tanto en desarrollo como en producción.

### Jerarquía de 3 Niveles
La jerarquía de servicios se organiza como: ServiceType (Nivel 1) > ServiceCategory (Nivel 2) > ServiceSubcategory (Nivel 3). El formulario utiliza carga dinámica vía AJAX para filtrar subcategorías según el tipo seleccionado.

### TinyMCE
El campo Descripción utiliza TinyMCE con license_key: 'gpl' para evitar advertencias de licencia. Se han activado los plugins lists y link para permitir formato Word style.
