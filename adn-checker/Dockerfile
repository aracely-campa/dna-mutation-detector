FROM php:8.4-fpm

# Instala dependencias y la extensión de MongoDB
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip && \
    docker-php-ext-install pdo pdo_mysql && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www