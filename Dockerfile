FROM php:7.0-fpm

RUN apt-get update \
    && docker-php-ext-install -j$(nproc) pdo_mysql
