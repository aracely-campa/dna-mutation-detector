
FROM php:8.3-cli AS build
 
RUN apt-get update \
    && apt-get install -y libssl-dev pkg-config unzip git curl netcat-openbsd \
    && pecl install mongodb-1.21.0 \
    && docker-php-ext-enable mongodb

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
 

ENV COMPOSER_ALLOW_SUPERUSER=1
 
WORKDIR /app

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
 
COPY --from=build /app /app
 
EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]