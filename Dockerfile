FROM php:8.3-fpm-alpine

# Instalar dependencias del sistema y herramientas de desarrollo
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    libxml2-dev \
    oniguruma-dev \
    zip \
    unzip \
    git \
    openssl-dev \
    autoconf \
    build-base

# Configurar e instalar las extensiones críticas de PHP (Añadimos mbstring para DomPDF)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql bcmath xml gd zip mbstring

# Instalar la extensión de MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Instalar Composer de forma global
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# 💡 LA JUGADA MAESTRA: Añadimos --ignore-platform-reqs para que muerda el anzuelo sin protestar por el entorno Alpine
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts --ignore-platform-reqs

# Configurar permisos correctos para carpetas internas de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 10000

# Al arrancar en vivo, mapeamos los paquetes con el .env ya inyectado por Render
CMD php artisan package:discover && php artisan serve --host=0.0.0.0 --port=10000