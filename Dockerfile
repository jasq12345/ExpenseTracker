# Use the same PHP version as your service
FROM php:8.4-fpm

# Install system dependencies and build tools
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    autoconf \
    gcc \
    make \
    && docker-php-ext-install pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copy Xdebug config
COPY xdebug.ini /usr/local/etc/php/conf.d/
