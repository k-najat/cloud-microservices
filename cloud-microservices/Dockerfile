FROM php:8.2-apache

# install-php-extensions — kayinstalli extensions b binaries jاهزة (bla compilation = rapide bzaf)
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions pdo_mysql zip

# apt: ghir unzip o nodejs
RUN apt-get update -qq \
    && apt-get install -y --no-install-recommends unzip nodejs npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Apache config
RUN a2enmod rewrite \
    && sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && printf '<Directory /var/www/html/public>\n  AllowOverride All\n  Require all granted\n</Directory>\n' \
       >> /etc/apache2/apache2.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Composer dependencies
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-scripts --no-autoloader --no-dev

# NPM build
COPY package.json vite.config.js ./
COPY resources ./resources
RUN npm install && npm run build

# Copy full app
COPY . .

# Finalize
RUN composer dump-autoload --optimize \
    && cp -n .env.example .env || true \
    && php artisan key:generate --force \
    && mkdir -p storage/framework/{sessions,views,cache/data} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]

EXPOSE 80
