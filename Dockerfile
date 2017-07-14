FROM php:7.1.5-fpm

LABEL maintainer "Slimek Wu"

COPY ./config /srv/penny/config
COPY ./data   /srv/penny/data
COPY ./public /srv/penny/public
COPY ./src    /srv/penny/src
COPY ./vendor /srv/penny/vendor

RUN apt-get update \
    && docker-php-ext-install -j$(nproc) bcmath pdo_mysql \
    && chown www-data:www-data /srv/penny/data \
    && cp /srv/penny/config/settings.docker.php /srv/penny/config/settings.php
