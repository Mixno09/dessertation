FROM php:7.4.11-fpm-alpine3.12

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY --from=composer:2.0.13 /usr/bin/composer /usr/bin/composer

COPY --from=mlocati/php-extension-installer:1.1.30 /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions opcache
RUN install-php-extensions xdebug-2.9.8
RUN install-php-extensions apcu
RUN install-php-extensions intl
RUN install-php-extensions pdo_mysql
RUN install-php-extensions zip
