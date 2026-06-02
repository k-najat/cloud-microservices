FROM php:8.2-cli

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions mongodb sockets

RUN apt-get update -qq \
    && apt-get install -y --no-install-recommends unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app

COPY composer.json ./
RUN composer update --no-interaction --prefer-dist --no-scripts --no-autoloader

COPY . .

RUN mkdir -p bootstrap/cache storage/logs storage/framework/cache \
    && composer dump-autoload --optimize --no-scripts \
    && cp .env.example .env \
    && php artisan key:generate --force

EXPOSE 8002
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8002"]
