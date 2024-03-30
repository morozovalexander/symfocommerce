FROM php:8.3-cli-alpine

RUN apk add --no-cache  \
    bash  \
    libzip-dev \
    icu-dev \
    git \
    && docker-php-ext-configure zip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql zip opcache intl \
    && curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash \
    && apk add symfony-cli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

WORKDIR /app