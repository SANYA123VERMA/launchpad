FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN cp .env.example .env && \
    touch database/database.sqlite && \
    php artisan key:generate

EXPOSE 10000

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000