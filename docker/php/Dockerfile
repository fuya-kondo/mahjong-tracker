FROM php:8.4-fpm

RUN apt-get update \
    && apt-get install -y git zip unzip libzip-dev libpq-dev \
    && docker-php-ext-install pdo_pgsql zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

# composer（公式イメージからコピー）
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/php.ini
