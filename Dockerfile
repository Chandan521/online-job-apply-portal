FROM php:8.2-apache

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
    zip unzip curl git libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 🔥 Fix here — disable artisan auto-scripts
RUN composer install --no-dev --optimize-autoloader --no-scripts

EXPOSE 80

CMD ["apache2-foreground"]
