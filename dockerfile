FROM php:8.4-cli AS build

# Instalar dependencias de sistema y extensiones de PHP
RUN apt-get update \
    && apt-get install -y \
        libssl-dev \
        libzip-dev \
        unzip \
        git \
        curl \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        pkg-config \
        netcat-openbsd \
    && docker-php-ext-install zip bcmath pdo pdo_mysql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Instalar Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Carpeta de trabajo
WORKDIR /app

# Copiar todo el proyecto Laravel
COPY . .

# Instalar dependencias PHP
RUN composer install --ignore-platform-reqs --no-interaction --prefer-dist --optimize-autoloader

# Instalar dependencias JS y compilar assets
RUN npm install && npm run build

# Etapa final (imagen más limpia)
FROM php:8.4-cli

RUN apt-get update \
    && apt-get install -y \
        libssl-dev \
        libzip-dev \
        unzip \
        git \
        curl \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        pkg-config \
        netcat-openbsd \
        ca-certificates \
    && update-ca-certificates \
    && docker-php-ext-install zip bcmath pdo pdo_mysql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

WORKDIR /app

COPY --from=build /app /app

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
