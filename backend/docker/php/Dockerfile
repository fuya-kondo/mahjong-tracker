FROM php:8.4-fpm

RUN apt-get update \
    && apt-get install -y \
        git \
        zip \
        unzip \
        libzip-dev \
        libpq-dev \
        autoconf \
        gcc \
        make \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install \
        pdo_pgsql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# php.ini
COPY docker/php/php.ini /usr/local/etc/php/php.ini
