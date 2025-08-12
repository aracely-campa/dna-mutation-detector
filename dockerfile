# Imagen base PHP con Apache
FROM php:8.1-apache

# Instalar extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev unzip zip git libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mysqli zip

# Habilitar mod_rewrite para rutas bonitas
RUN a2enmod rewrite

# Copiar configuración personalizada de Apache
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

# Copiar todo el proyecto al contenedor
COPY . /var/www/html

# Crear carpetas necesarias para Laravel y dar permisos
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Instalar Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Generar clave de aplicación
RUN php artisan key:generate

# Exponer el puerto 80
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
