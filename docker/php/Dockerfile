FROM php:8.2.3-alpine3.17

WORKDIR /var/www/

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN addgroup -g 1000 -S php && adduser -u 1000 -S php -G php && \
  mkdir -p /var/www && chown -R php:php /var/www

USER php
