FROM webdevops/php-nginx:7.4

ENV WEB_DOCUMENT_ROOT /app/public
ENV WEB_DOCUMENT_INDEX index.php
ENV COMPOSER_VERSION 2
ENV PHP_DATE_TIMEZONE America/Sao_Paulo

RUN apt-get update && \
    apt-get install htop

RUN pecl install -f xdebug-2.9.8 && \
    docker-php-ext-enable xdebug 

COPY ./supervisor.conf /opt/docker/etc/supervisor.d/supervisor.conf

WORKDIR /app
