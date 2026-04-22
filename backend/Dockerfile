FROM php:8.3-fpm AS base

RUN docker-php-ext-install pdo_mysql opcache bcmath

RUN apt-get update -y \
    && apt-get install -y --no-install-recommends libzip-dev libicu-dev \
    && docker-php-ext-install zip intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY docker/php/php.ini /usr/local/etc/php/conf.d/aquaviva.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

EXPOSE 9000

CMD ["php-fpm"]


FROM base AS development

RUN apt-get update -y \
    && apt-get install -y --no-install-recommends git vim \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
