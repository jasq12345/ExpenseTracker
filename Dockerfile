FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    autoconf \
    gcc \
    make \
    libsodium-dev \
    && docker-php-ext-install \
        pdo_mysql \
        sodium \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copy Xdebug config
COPY xdebug.ini /usr/local/etc/php/conf.d/
