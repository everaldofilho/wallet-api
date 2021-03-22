FROM webdevops/php-nginx:7.3

ENV WEB_DOCUMENT_ROOT /app/public
ENV WEB_DOCUMENT_INDEX index.php

ENV PHP_DATE_TIMEZONE America/Sao_Paulo

WORKDIR /app
