FROM php:8.3-cli AS build

RUN apt-get update \
    && apt-get install -y libssl-dev pkg-config unzip git zip libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql \
    && pecl install mongodb-1.21.0 \
    && docker-php-ext-enable mongodb

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app


RUN composer install --ignore-platform-reqs --no-interaction --prefer-dist --optimize-autoloader

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install && npm run build
