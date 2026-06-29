FROM php:8.3-fpm-alpine

# Instalar dependencias del sistema y herramientas de compilación
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    openssl-dev \
    autoconf \
    build-base

# Instalar extensiones de PHP necesarias y la extensión de MongoDB
RUN docker-php-ext-install pdo_mysql bcmath xml
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar el proyecto al contenedor
COPY . .

# Instalar dependencias de Composer para producción
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Configurar permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer el puerto que Render asigna dinámicamente
EXPOSE 10000

# Comando para arrancar la aplicación usando el servidor nativo optimizado
CMD php artisan serve --host=0.0.0.0 --port=10000