FROM php:8.1-apache

# Instalar extensiones
RUN apt-get update && apt-get install -y \
    libzip-dev unzip zip git \
    && docker-php-ext-install zip pdo_mysql mysqli

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Copiar el proyecto
COPY . /var/www/html

# Cambiar permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configuración de Apache para que apunte a public
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
