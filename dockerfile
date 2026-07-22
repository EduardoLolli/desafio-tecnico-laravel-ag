FROM php:8.4-fpm

# Instala dependências de sistema e extensões do PHP necessárias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite mbstring exif pcntl bcmath gd

RUN git config --global --add safe.directory /var/www

# Instala o Composer dinamicamente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

USER www-data