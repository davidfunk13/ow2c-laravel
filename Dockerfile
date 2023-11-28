# Stage 1: Build the application
FROM composer:2 as builder

WORKDIR /app
COPY . /app

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Set up the production environment
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y nginx

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy PHP-FPM configuration
COPY .docker/php/fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Copy Nginx configuration
COPY .docker/nginx/default.conf /etc/nginx/sites-available/default

# Copy application code and built dependencies from the builder stage
COPY --from=builder /app /var/www/overwatch-2-companion-api
WORKDIR /var/www/overwatch-2-companion-api

# Set correct permissions
RUN chown -R www-data:www-data /var/www/overwatch-2-companion-api

# Expose port 80 for Nginx
EXPOSE 80

# Start Nginx and PHP-FPM
CMD service nginx start && php-fpm
