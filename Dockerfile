# Stage 1: Build the application
FROM composer:2 as builder

WORKDIR /app
COPY . /app

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Set up the production environment
FROM php:8.1-fpm-buster
# Install system dependencies for PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev # Add libzip-dev here

# Clear out the local repository of retrieved package files
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql gd mbstring exif pcntl bcmath zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Copy application code and built dependencies
COPY --from=builder /app /var/www/overwatch-2-companion-api
WORKDIR /var/www/overwatch-2-companion-api

# Set correct permissions
RUN chown -R www-data:www-data /var/www/overwatch-2-companion-api
RUN chown -R www-data:www-data /var/www/overwatch-2-companion-api/storage /var/www/overwatch-2-companion-api/bootstrap/cache

# Expose the default PHP-FPM port (9000)
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]