FROM php:8.4-cli AS build

# Instalar dependencias del sistema y extensiones de PHP necesarias para Laravel
RUN apt-get update \
    && apt-get install -y \
        libssl-dev \
        pkg-config \
        unzip \
        git \
        curl \
        libzip-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        && docker-php-ext-install zip mbstring pdo pdo_mysql \
        && pecl install mongodb \
        && docker-php-ext-enable mongodb

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copiar archivos de Laravel
COPY . .

# Instalar dependencias de PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Instalar dependencias de Node (si usas Vite o npm en Laravel)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install \
    && npm run build

# Etapa final
FROM php:8.4-cli

RUN apt-get update \
    && apt-get install -y \
        libssl-dev \
        pkg-config \
        unzip \
        git \
        libzip-dev \
        libpng-dev \
        && docker-php-ext-install zip mbstring pdo pdo_mysql \
        && pecl install mongodb \
        && docker-php-ext-enable mongodb

WORKDIR /app

COPY --from=build /app /app

EXPOSE 8000

# Comando para iniciar Laravel en Render
CMD php artisan serve --host=0.0.0.0 --port=8000




FROM php:8.4.11-cli

RUN apt-get update \
    && apt-get install -y libssl-dev pkg-config unzip git curl netcat-openbsd ca-certificates \
    && update-ca-certificates \
    && pecl install mongodb-1.21.0 \
    && docker-php-ext-enable mongodb


WORKDIR /app

COPY --from=build /app /app

COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8000

