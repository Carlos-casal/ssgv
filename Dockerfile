FROM php:8.3-fpm

# Instalar dependencias del sistema y extensiones de PHP para MySQL
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    zip \
    unzip \
    git

RUN docker-php-ext-install intl pdo pdo_mysql gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/symfony

# Instalar dependencias necesarias para archivos comprimidos (Excel)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip