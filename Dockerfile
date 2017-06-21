FROM php:7.0.18-fpm

LABEL maintainer "Slimek Wu"

COPY ./config /srv/penny/config
COPY ./public /srv/penny/public
COPY ./src    /srv/penny/src
COPY ./vendor /srv/penny/vendor

RUN apt-get update \
    && cp /srv/penny/config/settings.docker.php /srv/penny/config/settings.php
