# Etapa de build
FROM php:8.3-cli AS build

RUN apt-get update \
    && apt-get install -y libssl-dev pkg-config unzip git curl netcat-openbsd \
    && pecl install mongodb-1.21.0 \
    && docker-php-ext-enable mongodb

# Instalar Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copiar proyecto y dependencias
COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm install
RUN npm run build

# Etapa final
FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y libssl-dev pkg-config unzip git curl netcat-openbsd ca-certificates \
    && update-ca-certificates \
    && pecl install mongodb-1.21.0 \
    && docker-php-ext-enable mongodb

WORKDIR /app

# Copiar archivos generados en la etapa de build
COPY --from=build /app /app

EXPOSE 8000

# Iniciar Laravel directamente
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
