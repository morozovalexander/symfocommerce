FROM php:8.3-fpm-alpine

RUN apk add --no-cache libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo_mysql zip opcache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /app

USER app