# -------------------------
# STAGE 1 – builder
# -------------------------
FROM php:8.2-cli-alpine AS builder

RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    icu-dev \
    bash \
    oniguruma-dev \
    libpng-dev \
    postgresql-dev \
    mysql-client \
    libxml2-dev \
    openssl-dev \
    zlib-dev \
    curl-dev \
    autoconf \
    g++ \
    make \
    pkgconfig \
    tzdata

RUN docker-php-ext-install \
    intl \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    zip \
    opcache \
    mbstring

WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
    --no-dev \
    --no-scripts \
    --no-interaction

COPY . .

RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

ENV APP_ENV=prod

RUN DATABASE_URL=sqlite:///:memory: \
    php bin/console importmap:install && \
    php bin/console asset-map:compile && \
    rm -rf var/cache/*


# -------------------------
# STAGE 2 – apache runtime
# -------------------------
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    default-mysql-client \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

# IMPORTANT: set the document root BEFORE modifying apache config
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
 && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY --from=builder --chown=www-data:www-data /app .

RUN mkdir -p var \
 && chown -R www-data:www-data var public

ENV APP_ENV=prod

EXPOSE 80
