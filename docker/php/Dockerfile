FROM php:8.2-fpm

RUN apt-get update && apt-get install -y curl git zip \
    libzip-dev \
    libpq-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

RUN docker-php-ext-install zip \
    pdo \
    pdo_pgsql \
    pgsql

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY --from=composer /usr/bin/composer /usr/bin/composer