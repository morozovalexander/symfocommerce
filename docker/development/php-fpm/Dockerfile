FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    libzip-dev \
    icu-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql zip opcache intl

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /app

USER app