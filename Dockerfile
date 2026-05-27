FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install Node.js for Vite/Tailwind asset building
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN cp .env.example .env && \
    sed -i 's/APP_ENV=local/APP_ENV=production/g' .env && \
    sed -i 's/APP_DEBUG=true/APP_DEBUG=false/g' .env && \
    touch database/database.sqlite && \
    php artisan key:generate

EXPOSE 10000

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000