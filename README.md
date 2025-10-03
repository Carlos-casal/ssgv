# Protección Civil de Vigo - Symfony + XAMPP

## Instalación y Configuración

### PASO 1: Preparar XAMPP
1. Descargar e instalar XAMPP desde https://www.apachefriends.org/
2. Iniciar Apache y MySQL desde el panel de control
3. Crear base de datos `gestion_voluntarios` en phpMyAdmin

### PASO 2: Crear Proyecto Symfony
```bash
cd C:\xampp\htdocs
composer create-project symfony/skeleton gestion-voluntarios
cd gestion-voluntarios
composer require webapp symfony/orm-pack symfony/form symfony/validator symfony/security-bundle
```

### PASO 3: Configurar Base de Datos
Editar `.env`:
```
DATABASE_URL="mysql://root:@127.0.0.1:3306/gestion_voluntarios?serverVersion=8.0.32&charset=utf8mb4"
```

### PASO 4: Ejecutar Migraciones
```bash
php bin/console doctrine:migrations:migrate
php bin/console app:create-user
```

### PASO 5: Iniciar Servidor
```bash
symfony server:start
```

## Credenciales de Acceso
- Email: admin@voluntarios.org
- Password: admin123