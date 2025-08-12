# Usa una imagen oficial de PHP con Apache y extensiones necesarias
FROM php:8.1-apache

# Instala extensiones y herramientas necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    zip \
    git \
    && docker-php-ext-install zip pdo_mysql mysqli

# Copia los archivos de tu proyecto al contenedor
COPY . /var/www/html

# Establece permisos adecuados
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Habilita mod_rewrite para Apache
RUN a2enmod rewrite

# Copia el archivo de configuración de Apache (opcional, para redirigir todo a public)
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

# Expone el puerto 80
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]
