FROM php:8.4-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install \
        pdo_pgsql \
        mbstring \
        bcmath \
        intl \
        xml \
        zip \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY package.json package-lock.json ./

RUN apt-get update && apt-get install -y nodejs npm \
    && npm ci \
    && npm run build \
    && rm -rf node_modules \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY . .

RUN composer dump-autoload --optimize

RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint

RUN chmod +x /usr/local/bin/entrypoint

EXPOSE 10000

ENTRYPOINT ["entrypoint"]