FROM php:8.3-fpm

RUN apt update -q && \
    apt install -q -y libpq-dev && \
    docker-php-ext-install pdo_pgsql pgsql

# Установка Redis через pecl
RUN pecl install redis && \
    docker-php-ext-enable redis

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
