FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    libzip-dev zip unzip nginx \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Step 5: Set Workdir
WORKDIR /var/www

# Step 1: Clear directory and scaffold Laravel
RUN rm -rf /var/www/* && \
    composer create-project laravel/laravel:^10.0 . --no-interaction --prefer-dist
# Step 2: Overlay OUR custom application files on top
COPY app/        app/
COPY database/   database/
COPY resources/  resources/
COPY routes/     routes/


# Step 3: Re-install with our composer.json
COPY composer.json composer.json
RUN composer install --optimize-autoloader --no-dev

# Step 4: Generate app key
RUN php artisan key:generate --force

RUN php artisan config:clear
# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Nginx + startup
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/start.sh   /start.sh
RUN chmod +x /start.sh

EXPOSE 80
CMD ["/start.sh"]
