# Imagen base de PHP con Apache
FROM php:8.2-apache

# Instalar dependencias necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Copiar el proyecto Laravel al contenedor
COPY adn-checker/ /var/www/html
WORKDIR /var/www/html


# Dar permisos a storage y bootstrap/cache
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Generar clave de aplicación
RUN php artisan key:generate

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Copiar configuración personalizada de Apache
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

# Exponer puerto
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
