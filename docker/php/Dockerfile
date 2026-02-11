FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
