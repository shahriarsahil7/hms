# Simple production image: PHP 8.3 + Apache, serving Laravel's /public folder.
# Works on Render.com's free web service tier (and similar Docker-based free hosts).
FROM php:8.3-apache

# System deps + PHP extensions Laravel needs
RUN apt-get update && apt-get install -y \
        unzip git libzip-dev libpng-dev \
    && docker-php-ext-install pdo_mysql zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Point Apache's document root at Laravel's /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

# Run migrations on every deploy, then start Apache in the foreground.
CMD php artisan migrate --force; apache2-foreground
