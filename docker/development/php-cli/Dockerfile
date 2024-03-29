FROM php:8.3-cli-alpine

RUN apk add --no-cache libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo_mysql zip opcache

WORKDIR /app